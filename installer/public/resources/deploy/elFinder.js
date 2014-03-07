"use strict";
/**
 * @class elFinder - file manager for web
 *
 * @author Dmitry (dio) Levashov
 **/
window.elFinder = function(node, opts) {
	this.time('load');

	var self = this,
		
		/**
		 * Node on which elfinder creating
		 *
		 * @type jQuery
		 **/
		node = $(node),
		
		/**
		 * Store node contents.
		 *
		 * @see this.destroy
		 * @type jQuery
		 **/
		prevContent = $('<div/>').append(node.contents()),
		
		/**
		 * Store node inline styles
		 *
		 * @see this.destroy
		 * @type String
		 **/
		prevStyle = node.attr('style'),
		
		/**
		 * Instance ID. Required to get/set cookie
		 *
		 * @type String
		 **/
		id = node.attr('id') || '',
		
		/**
		 * Events namespace
		 *
		 * @type String
		 **/
		namespace = 'elfinder-'+(id || Math.random().toString().substr(2, 7)),
		
		/**
		 * Mousedown event
		 *
		 * @type String
		 **/
		mousedown = 'mousedown.'+namespace,
		
		/**
		 * Keydown event
		 *
		 * @type String
		 **/
		keydown = 'keydown.'+namespace,
		
		/**
		 * Keypress event
		 *
		 * @type String
		 **/
		keypress = 'keypress.'+namespace,
		
		/**
		 * Is shortcuts/commands enabled
		 *
		 * @type Boolean
		 **/
		enabled = true,
		
		/**
		 * Store enabled value before ajax requiest
		 *
		 * @type Boolean
		 **/
		prevEnabled = true,
		
		/**
		 * List of build-in events which mapped into methods with same names
		 *
		 * @type Array
		 **/
		events = ['enable', 'disable', 'load', 'open', 'reload', 'select',  'add', 'remove', 'change', 'dblclick', 'getfile', 'lockfiles', 'unlockfiles', 'dragstart', 'dragstop'],
		
		/**
		 * Rules to validate data from backend
		 *
		 * @type Object
		 **/
		rules = {},
		
		/**
		 * Current working directory hash
		 *
		 * @type String
		 **/
		cwd = '',
		
		/**
		 * Current working directory options
		 *
		 * @type Object
		 **/
		cwdOptions = {
			path          : '',
			url           : '',
			tmbUrl        : '',
			disabled      : [],
			separator     : '/',
			archives      : [],
			extract       : [],
			copyOverwrite : true,
			uploadMaxSize : 0,
			tmb           : false // old API
		},
		
		/**
		 * Files/dirs cache
		 *
		 * @type Object
		 **/
		files = {},
		
		/**
		 * Selected files hashes
		 *
		 * @type Array
		 **/
		selected = [],
		
		/**
		 * Events listeners
		 *
		 * @type Object
		 **/
		listeners = {},
		
		/**
		 * Shortcuts
		 *
		 * @type Object
		 **/
		shortcuts = {},
		
		/**
		 * Buffer for copied files
		 *
		 * @type Array
		 **/
		clipboard = [],
		
		/**
		 * Copied/cuted files hashes
		 * Prevent from remove its from cache.
		 * Required for dispaly correct files names in error messages
		 *
		 * @type Array
		 **/
		remember = [],
		
		/**
		 * Queue for 'open' requests
		 *
		 * @type Array
		 **/
		queue = [],
		
		/**
		 * Net drivers names
		 *
		 * @type Array
		 **/
		netDrivers = [],
		
		/**
		 * Commands prototype
		 *
		 * @type Object
		 **/
		base = new self.command(self),
		
		/**
		 * elFinder node width
		 *
		 * @type String
		 * @default "auto"
		 **/
		width  = 'auto',
		
		/**
		 * elFinder node height
		 *
		 * @type Number
		 * @default 400
		 **/
		height = 400,
				
		beeper = $(document.createElement('audio')).hide().appendTo('body')[0],
			
		syncInterval,
		
		open = function(data) {
			if (data.init) {
				// init - reset cache
				files = {};
			} else {
				// remove only files from prev cwd
				for (var i in files) {
					if (files.hasOwnProperty(i) 
					&& files[i].mime != 'directory' 
					&& files[i].phash == cwd
					&& $.inArray(i, remember) === -1) {
						delete files[i];
					}
				}
			}

			cwd = data.cwd.hash;
			cache(data.files);
			if (!files[cwd]) {
				cache([data.cwd]);
			}
			self.lastDir(cwd);
		},
		
		/**
		 * Store info about files/dirs in "files" object.
		 *
		 * @param  Array  files
		 * @return void
		 **/
		cache = function(data) {
			var l = data.length, f;

			while (l--) {
				f = data[l];
				if (f.name && f.hash && f.mime) {
					if (!f.phash) {
						var name = 'volume_'+f.name,
							i18 = self.i18n(name);

						if (name != i18) {
							f.i18 = i18;
						}
					}
					files[f.hash] = f;
				} 
			}
		},
		
		/**
		 * Exec shortcut
		 *
		 * @param  jQuery.Event  keydown/keypress event
		 * @return void
		 */
		execShortcut = function(e) {
			var code    = e.keyCode,
				ctrlKey = !!(e.ctrlKey || e.metaKey);

			if (enabled) {

				$.each(shortcuts, function(i, shortcut) {
					if (shortcut.type    == e.type 
					&& shortcut.keyCode  == code 
					&& shortcut.shiftKey == e.shiftKey 
					&& shortcut.ctrlKey  == ctrlKey 
					&& shortcut.altKey   == e.altKey) {
						e.preventDefault()
						e.stopPropagation();
						shortcut.callback(e, self);
						self.debug('shortcut-exec', i+' : '+shortcut.description);
					}
				});
				
				// prevent tab out of elfinder
				if (code == 9 && !$(e.target).is(':input')) {
					e.preventDefault();
				}

			}
		},
		date = new Date(),
		utc,
		i18n
		;


	/**
	 * Protocol version
	 *
	 * @type String
	 **/
	this.api = null;
	
	/**
	 * elFinder use new api
	 *
	 * @type Boolean
	 **/
	this.newAPI = false;
	
	/**
	 * elFinder use old api
	 *
	 * @type Boolean
	 **/
	this.oldAPI = false;
	
	/**
	 * User os. Required to bind native shortcuts for open/rename
	 *
	 * @type String
	 **/
	this.OS = navigator.userAgent.indexOf('Mac') !== -1 ? 'mac' : navigator.userAgent.indexOf('Win') !== -1  ? 'win' : 'other';
	
	/**
	 * User browser UA.
	 * jQuery.browser: version deprecated: 1.3, removed: 1.9
	 *
	 * @type Object
	 **/
	this.UA = (function(){
		var webkit = !document.uniqueID && !window.opera && !window.sidebar && window.localStorage && typeof window.orientation == "undefined";
		return {
			// Browser IE <= IE 6
			ltIE6:typeof window.addEventListener == "undefined" && typeof document.documentElement.style.maxHeight == "undefined",
			// Browser IE <= IE 7
			ltIE7:typeof window.addEventListener == "undefined" && typeof document.querySelectorAll == "undefined",
			// Browser IE <= IE 8
			ltIE8:typeof window.addEventListener == "undefined" && typeof document.getElementsByClassName == "undefined",
			IE:document.uniqueID,
			Firefox:window.sidebar,
			Opera:window.opera,
			Webkit:webkit,
			Chrome:webkit && window.chrome,
			Safari:webkit && !window.chrome,
			Mobile:typeof window.orientation != "undefined"
		}
	})();
	
	/**
	 * Configuration options
	 *
	 * @type Object
	 **/
	this.options = $.extend(true, {}, this._options, opts||{});
	
	if (opts.ui) {
		this.options.ui = opts.ui;
	}
	
	if (opts.commands) {
		this.options.commands = opts.commands;
	}
	
	if (opts.uiOptions && opts.uiOptions.toolbar) {
		this.options.uiOptions.toolbar = opts.uiOptions.toolbar;
	}

	$.extend(this.options.contextmenu, opts.contextmenu);

	
	/**
	 * Ajax request type
	 *
	 * @type String
	 * @default "get"
	 **/
	this.requestType = /^(get|post)$/i.test(this.options.requestType) ? this.options.requestType.toLowerCase() : 'get',
	
	/**
	 * Any data to send across every ajax request
	 *
	 * @type Object
	 * @default {}
	 **/
	this.customData = $.isPlainObject(this.options.customData) ? this.options.customData : {};

	/**
	 * Any custom headers to send across every ajax request
	 *
	 * @type Object
	 * @default {}
	*/
	this.customHeaders = $.isPlainObject(this.options.customHeaders) ? this.options.customHeaders : {};

	/**
	 * Any custom xhrFields to send across every ajax request
	 *
	 * @type Object
	 * @default {}
	 */
	this.xhrFields = $.isPlainObject(this.options.xhrFields) ? this.options.xhrFields : {};

	/**
	 * ID. Required to create unique cookie name
	 *
	 * @type String
	 **/
	this.id = id;
	
	/**
	 * URL to upload files
	 *
	 * @type String
	 **/
	this.uploadURL = opts.urlUpload || opts.url;
	
	/**
	 * Events namespace
	 *
	 * @type String
	 **/
	this.namespace = namespace;

	/**
	 * Interface language
	 *
	 * @type String
	 * @default "en"
	 **/
	this.lang = this.i18[this.options.lang] && this.i18[this.options.lang].messages ? this.options.lang : 'en';
	
	i18n = this.lang == 'en' 
		? this.i18['en'] 
		: $.extend(true, {}, this.i18['en'], this.i18[this.lang]);
	
	/**
	 * Interface direction
	 *
	 * @type String
	 * @default "ltr"
	 **/
	this.direction = i18n.direction;
	
	/**
	 * i18 messages
	 *
	 * @type Object
	 **/
	this.messages = i18n.messages;
	
	/**
	 * Date/time format
	 *
	 * @type String
	 * @default "m.d.Y"
	 **/
	this.dateFormat = this.options.dateFormat || i18n.dateFormat;
	
	/**
	 * Date format like "Yesterday 10:20:12"
	 *
	 * @type String
	 * @default "{day} {time}"
	 **/
	this.fancyFormat = this.options.fancyDateFormat || i18n.fancyDateFormat;

	/**
	 * Today timestamp
	 *
	 * @type Number
	 **/
	this.today = (new Date(date.getFullYear(), date.getMonth(), date.getDate())).getTime()/1000;
	
	/**
	 * Yesterday timestamp
	 *
	 * @type Number
	 **/
	this.yesterday = this.today - 86400;
	
	utc = this.options.UTCDate ? 'UTC' : '';
	
	this.getHours    = 'get'+utc+'Hours';
	this.getMinutes  = 'get'+utc+'Minutes';
	this.getSeconds  = 'get'+utc+'Seconds';
	this.getDate     = 'get'+utc+'Date';
	this.getDay      = 'get'+utc+'Day';
	this.getMonth    = 'get'+utc+'Month';
	this.getFullYear = 'get'+utc+'FullYear';
	
	/**
	 * Css classes 
	 *
	 * @type String
	 **/
	this.cssClass = 'ui-helper-reset ui-helper-clearfix ui-widget ui-widget-content ui-corner-all elfinder elfinder-'+(this.direction == 'rtl' ? 'rtl' : 'ltr')+' '+this.options.cssClass;

	/**
	 * Method to store/fetch data
	 *
	 * @type Function
	 **/
	this.storage = (function() {
		try {
			return 'localStorage' in window && window['localStorage'] !== null ? self.localStorage : self.cookie;
		} catch (e) {
			return self.cookie;
		}
	})();

	this.viewType = this.storage('view') || this.options.defaultView || 'icons';

	this.sortType = this.storage('sortType') || this.options.sortType || 'name';
	
	this.sortOrder = this.storage('sortOrder') || this.options.sortOrder || 'asc';

	this.sortStickFolders = this.storage('sortStickFolders');

	if (this.sortStickFolders === null) {
		this.sortStickFolders = !!this.options.sortStickFolders;
	} else {
		this.sortStickFolders = !!this.sortStickFolders
	}

	this.sortRules = $.extend(true, {}, this._sortRules, this.options.sortsRules);
	
	$.each(this.sortRules, function(name, method) {
		if (typeof method != 'function') {
			delete self.sortRules[name];
		} 
	});
	
	this.compare = $.proxy(this.compare, this);
	
	/**
	 * Delay in ms before open notification dialog
	 *
	 * @type Number
	 * @default 500
	 **/
	this.notifyDelay = this.options.notifyDelay > 0 ? parseInt(this.options.notifyDelay) : 500;
	
	/**
	 * Base draggable options
	 *
	 * @type Object
	 **/
	this.draggable = {
		appendTo   : 'body',
		addClasses : true,
		delay      : 30,
		distance   : 8,
		revert     : true,
		refreshPositions : true,
		cursor     : 'move',
		cursorAt   : {left : 50, top : 47},
		drag       : function(e, ui) {
			if (! ui.helper.data('locked')) {
				ui.helper.toggleClass('elfinder-drag-helper-plus', e.shiftKey||e.ctrlKey||e.metaKey);
			}
		},
		start      : function(e, ui) {
			var targets = $.map(ui.helper.data('files')||[], function(h) { return h || null ;}),
			cnt, h;
			cnt = targets.length;
			while (cnt--) {
				h = targets[cnt];
				if (files[h].locked) {
					ui.helper.addClass('elfinder-drag-helper-plus').data('locked', true);
					break;
				}
			}
		},
		stop       : function() { self.trigger('focus').trigger('dragstop'); },
		helper     : function(e, ui) {
			var element = this.id ? $(this) : $(this).parents('[id]:first'),
				helper  = $('<div class="elfinder-drag-helper"><span class="elfinder-drag-helper-icon-plus"/></div>'),
				icon    = function(mime) { return '<div class="elfinder-cwd-icon '+self.mime2class(mime)+' ui-corner-all"/>'; },
				hashes, l;
			
			self.trigger('dragstart', {target : element[0], originalEvent : e});
			
			hashes = element.is('.'+self.res('class', 'cwdfile')) 
				? self.selected() 
				: [self.navId2Hash(element.attr('id'))];
			
			helper.append(icon(files[hashes[0]].mime)).data('files', hashes).data('locked', false);

			if ((l = hashes.length) > 1) {
				helper.append(icon(files[hashes[l-1]].mime) + '<span class="elfinder-drag-num">'+l+'</span>');
			}
			
			return helper;
		}
	};
	
	/**
	 * Base droppable options
	 *
	 * @type Object
	 **/
	this.droppable = {
			// greedy     : true,
			tolerance  : 'pointer',
			accept     : '.elfinder-cwd-file-wrapper,.elfinder-navbar-dir,.elfinder-cwd-file',
			hoverClass : this.res('class', 'adroppable'),
			drop : function(e, ui) {
				var dst     = $(this),
					targets = $.map(ui.helper.data('files')||[], function(h) { return h || null }),
					result  = [],
					c       = 'class',
					cnt, hash, i, h;
				
				if (dst.is('.'+self.res(c, 'cwd'))) {
					hash = cwd;
				} else if (dst.is('.'+self.res(c, 'cwdfile'))) {
					hash = dst.attr('id');
				} else if (dst.is('.'+self.res(c, 'navdir'))) {
					hash = self.navId2Hash(dst.attr('id'));
				}

				cnt = targets.length;
				
				while (cnt--) {
					h = targets[cnt];
					// ignore drop into itself or in own location
					h != hash && files[h].phash != hash && result.push(h);
				}
				
				if (result.length) {
					ui.helper.hide();
					self.clipboard(result, !(e.ctrlKey||e.shiftKey||e.metaKey||ui.helper.data('locked')));
					self.exec('paste', hash);
					self.trigger('drop', {files : targets});

				}
			}
		};
	
	/**
	 * Return true if filemanager is active
	 *
	 * @return Boolean
	 **/
	this.enabled = function() {
		return node.is(':visible') && enabled;
	}
	
	/**
	 * Return true if filemanager is visible
	 *
	 * @return Boolean
	 **/
	this.visible = function() {
		return node.is(':visible');
	}
	
	/**
	 * Return root dir hash for current working directory
	 * 
	 * @return String
	 */
	this.root = function(hash) {
		var dir = files[hash || cwd], i;
		
		while (dir && dir.phash) {
			dir = files[dir.phash]
		}
		if (dir) {
			return dir.hash;
		}
		
		while (i in files && files.hasOwnProperty(i)) {
			dir = files[i]
			if (!dir.phash && !dir.mime == 'directory' && dir.read) {
				return dir.hash
			}
		}
		
		return '';
	}
	
	/**
	 * Return current working directory info
	 * 
	 * @return Object
	 */
	this.cwd = function() {
		return files[cwd] || {};
	}
	
	/**
	 * Return required cwd option
	 * 
	 * @param  String  option name
	 * @return mixed
	 */
	this.option = function(name) {
		return cwdOptions[name]||'';
	}
	
	/**
	 * Return file data from current dir or tree by it's hash
	 * 
	 * @param  String  file hash
	 * @return Object
	 */
	this.file = function(hash) { 
		return files[hash]; 
	};
	
	/**
	 * Return all cached files
	 * 
	 * @return Array
	 */
	this.files = function() {
		return $.extend(true, {}, files);
	}
	
	/**
	 * Return list of file parents hashes include file hash
	 * 
	 * @param  String  file hash
	 * @return Array
	 */
	this.parents = function(hash) {
		var parents = [],
			dir;
		
		while ((dir = this.file(hash))) {
			parents.unshift(dir.hash);
			hash = dir.phash;
		}
		return parents;
	}
	
	this.path2array = function(hash, i18) {
		var file, 
			path = [];
			
		while (hash && (file = files[hash]) && file.hash) {
			path.unshift(i18 && file.i18 ? file.i18 : file.name);
			hash = file.phash;
		}
			
		return path;
	}
	
	/**
	 * Return file path
	 * 
	 * @param  Object  file
	 * @return String
	 */
	this.path = function(hash, i18) { 
		return files[hash] && files[hash].path
			? files[hash].path
			: this.path2array(hash, i18).join(cwdOptions.separator);
	}
	
	/**
	 * Return file url if set
	 * 
	 * @param  Object  file
	 * @return String
	 */
	this.url = function(hash) {
		var file = files[hash];
		
		if (!file || !file.read) {
			return '';
		}
		
		if (file.url) {
			return file.url;
		}
		
		if (cwdOptions.url) {
			return cwdOptions.url + $.map(this.path2array(hash), function(n) { return encodeURIComponent(n); }).slice(1).join('/')
		}

		var params = $.extend({}, this.customData, {
			cmd: 'file',
			target: file.hash
		});
		if (this.oldAPI) {
			params.cmd = 'open';
			params.current = file.phash;
		}
		return this.options.url + (this.options.url.indexOf('?') === -1 ? '?' : '&') + $.param(params, true);
	}
	
	/**
	 * Return thumbnail url
	 * 
	 * @param  String  file hash
	 * @return String
	 */
	this.tmb = function(hash) {
		var file = files[hash],
			url = file && file.tmb && file.tmb != 1 ? cwdOptions['tmbUrl'] + file.tmb : '';
		
		if (url && (this.UA.Opera || this.UA.IE)) {
			url += '?_=' + new Date().getTime();
		}
		return url;
	}
	
	/**
	 * Return selected files hashes
	 *
	 * @return Array
	 **/
	this.selected = function() {
		return selected.slice(0);
	}
	
	/**
	 * Return selected files info
	 * 
	 * @return Array
	 */
	this.selectedFiles = function() {
		return $.map(selected, function(hash) { return files[hash] ? $.extend({}, files[hash]) : null });
	};
	
	/**
	 * Return true if file with required name existsin required folder
	 * 
	 * @param  String  file name
	 * @param  String  parent folder hash
	 * @return Boolean
	 */
	this.fileByName = function(name, phash) {
		var hash;
	
		for (hash in files) {
			if (files.hasOwnProperty(hash) && files[hash].phash == phash && files[hash].name == name) {
				return files[hash];
			}
		}
	};
	
	/**
	 * Valid data for required command based on rules
	 * 
	 * @param  String  command name
	 * @param  Object  cammand's data
	 * @return Boolean
	 */
	this.validResponse = function(cmd, data) {
		return data.error || this.rules[this.rules[cmd] ? cmd : 'defaults'](data);
	}
	
	/**
	 * Return bytes from ini formated size
	 * 
	 * @param  String  ini formated size
	 * @return Integer
	 */
	this.returnBytes = function(val) {
		if (val == '-1') val = 0;
		if (val) {
			// for ex. 1mb, 1KB
			val = val.replace(/b$/i, '');
			var last = val.charAt(val.length - 1).toLowerCase();
			val = val.replace(/[gmk]$/i, '');
			if (last == 'g') {
				val = val * 1024 * 1024 * 1024;
			} else if (last == 'm') {
				val = val * 1024 * 1024;
			} else if (last == 'k') {
				val = val * 1024;
			}
		}
		return val;
	};
	
	/**
	 * Proccess ajax request.
	 * Fired events :
	 * @todo
	 * @example
	 * @todo
	 * @return $.Deferred
	 */
	this.request = function(options) { 
		var self     = this,
			o        = this.options,
			dfrd     = $.Deferred(),
			// request data
			data     = $.extend({}, o.customData, {mimes : o.onlyMimes}, options.data || options),
			// command name
			cmd      = data.cmd,
			// call default fail callback (display error dialog) ?
			deffail  = !(options.preventDefault || options.preventFail),
			// call default success callback ?
			defdone  = !(options.preventDefault || options.preventDone),
			// options for notify dialog
			notify   = $.extend({}, options.notify),
			// do not normalize data - return as is
			raw      = !!options.raw,
			// sync files on request fail
			syncOnFail = options.syncOnFail,
			// open notify dialog timeout		
			timeout, 
			// request options
			options = $.extend({
				url      : o.url,
				async    : true,
				type     : this.requestType,
				dataType : 'json',
				cache    : false,
				// timeout  : 100,
				data     : data,
				headers  : this.customHeaders,
				xhrFields: this.xhrFields
			}, options.options || {}),
			/**
			 * Default success handler. 
			 * Call default data handlers and fire event with command name.
			 *
			 * @param Object  normalized response data
			 * @return void
			 **/
			done = function(data) {
				data.warning && self.error(data.warning);
				
				cmd == 'open' && open($.extend(true, {}, data));

				// fire some event to update cache/ui
				data.removed && data.removed.length && self.remove(data);
				data.added   && data.added.length   && self.add(data);
				data.changed && data.changed.length && self.change(data);
				
				// fire event with command name
				self.trigger(cmd, data);
				
				// force update content
				data.sync && self.sync();
			},
			/**
			 * Request error handler. Reject dfrd with correct error message.
			 *
			 * @param jqxhr  request object
			 * @param String request status
			 * @return void
			 **/
			error = function(xhr, status) {
				var error;
				
				switch (status) {
					case 'abort':
						error = xhr.quiet ? '' : ['errConnect', 'errAbort'];
						break;
					case 'timeout':	    
						error = ['errConnect', 'errTimeout'];
						break;
					case 'parsererror': 
						error = ['errResponse', 'errDataNotJSON'];
						break;
					default:
						if (xhr.status == 403) {
							error = ['errConnect', 'errAccess'];
						} else if (xhr.status == 404) {
							error = ['errConnect', 'errNotFound'];
						} else {
							error = 'errConnect';
						} 
				}
				
				dfrd.reject(error, xhr, status);
			},
			/**
			 * Request success handler. Valid response data and reject/resolve dfrd.
			 *
			 * @param Object  response data
			 * @param String request status
			 * @return void
			 **/
			success = function(response) {
				if (raw) {
					return dfrd.resolve(response);
				}
				
				if (!response) {
					return dfrd.reject(['errResponse', 'errDataEmpty'], xhr);
				} else if (!$.isPlainObject(response)) {
					return dfrd.reject(['errResponse', 'errDataNotJSON'], xhr);
				} else if (response.error) {
					return dfrd.reject(response.error, xhr);
				} else if (!self.validResponse(cmd, response)) {
					return dfrd.reject('errResponse', xhr);
				}

				response = self.normalize(response);

				if (!self.api) {
					self.api    = response.api || 1;
					self.newAPI = self.api >= 2;
					self.oldAPI = !self.newAPI;
				}
				
				if (response.options) {
					cwdOptions = $.extend({}, cwdOptions, response.options);
				}

				if (response.netDrivers) {
					self.netDrivers = response.netDrivers;
				}

				if (cmd == 'open' && !!data.init) {
					self.uplMaxSize = self.returnBytes(response.uplMaxSize);
					self.uplMaxFile = !!response.uplMaxFile? parseInt(response.uplMaxFile) : 20;
				}

				dfrd.resolve(response);
				response.debug && self.debug('backend-debug', response.debug);
			},
			xhr, _xhr
			;

		defdone && dfrd.done(done);
		dfrd.fail(function(error) {
			if (error) {
				deffail ? self.error(error) : self.debug('error', self.i18n(error));
			}
		})
		
		if (!cmd) {
			return dfrd.reject('errCmdReq');
		}	

		if (syncOnFail) {
			dfrd.fail(function(error) {
				error && self.sync();
			});
		}

		if (notify.type && notify.cnt) {
			timeout = setTimeout(function() {
				self.notify(notify);
				dfrd.always(function() {
					notify.cnt = -(parseInt(notify.cnt)||0);
					self.notify(notify);
				})
			}, self.notifyDelay)
			
			dfrd.always(function() {
				clearTimeout(timeout);
			});
		}
		
		// quiet abort not completed "open" requests
		if (cmd == 'open') {
			while ((_xhr = queue.pop())) {
				if (_xhr.state() == 'pending') {
					_xhr.quiet = true;
					_xhr.abort();
				}
			}
		}

		delete options.preventFail

		xhr = this.transport.send(options).fail(error).done(success);
		
		// this.transport.send(options)
		
		// add "open" xhr into queue
		if (cmd == 'open') {
			queue.unshift(xhr);
			dfrd.always(function() {
				var ndx = $.inArray(xhr, queue);
				
				ndx !== -1 && queue.splice(ndx, 1);
			});
		}
		
		return dfrd;
	};
	
	/**
	 * Compare current files cache with new files and return diff
	 * 
	 * @param  Array  new files
	 * @return Object
	 */
	this.diff = function(incoming) {
		var raw       = {},
			added     = [],
			removed   = [],
			changed   = [],
			isChanged = function(hash) {
				var l = changed.length;

				while (l--) {
					if (changed[l].hash == hash) {
						return true;
					}
				}
			};
			
		$.each(incoming, function(i, f) {
			raw[f.hash] = f;
		});
			
		// find removed
		$.each(files, function(hash, f) {
			!raw[hash] && removed.push(hash);
		});
		
		// compare files
		$.each(raw, function(hash, file) {
			var origin = files[hash];

			if (!origin) {
				added.push(file);
			} else {
				$.each(file, function(prop) {
					if (file[prop] != origin[prop]) {
						changed.push(file)
						return false;
					}
				});
			}
		});
		
		// parents of removed dirs mark as changed (required for tree correct work)
		$.each(removed, function(i, hash) {
			var file  = files[hash], 
				phash = file.phash;

			if (phash 
			&& file.mime == 'directory' 
			&& $.inArray(phash, removed) === -1 
			&& raw[phash] 
			&& !isChanged(phash)) {
				changed.push(raw[phash]);
			}
		});
		
		return {
			added   : added,
			removed : removed,
			changed : changed
		};
	}
	
	/**
	 * Sync content
	 * 
	 * @return jQuery.Deferred
	 */
	this.sync = function() {
		var self  = this,
			dfrd  = $.Deferred().done(function() { self.trigger('sync'); }),
			opts1 = {
				data           : {cmd : 'open', init : 1, target : cwd, tree : this.ui.tree ? 1 : 0},
				preventDefault : true
			},
			opts2 = {
				data           : {cmd : 'tree', target : (cwd == this.root())? cwd : this.file(cwd).phash},
				preventDefault : true
			};
		
		$.when(
			this.request(opts1),
			this.request(opts2)
		)
		.fail(function(error) {
			dfrd.reject(error);
			error && self.request({
				data   : {cmd : 'open', target : self.lastDir(''), tree : 1, init : 1},
				notify : {type : 'open', cnt : 1, hideCnt : true},
				preventDefault : true
			});
		})
		.done(function(odata, pdata) {
			var diff = self.diff(odata.files.concat(pdata && pdata.tree ? pdata.tree : []));

			diff.added.push(odata.cwd)
			diff.removed.length && self.remove(diff);
			diff.added.length   && self.add(diff);
			diff.changed.length && self.change(diff);
			return dfrd.resolve(diff);
		});
		
		return dfrd;
	}
	
	this.upload = function(files) {
		return this.transport.upload(files, this);
	}
	
	/**
	 * Attach listener to events
	 * To bind to multiply events at once, separate events names by space
	 * 
	 * @param  String  event(s) name(s)
	 * @param  Object  event handler
	 * @return elFinder
	 */
	this.bind = function(event, callback) {
		var i;
		
		if (typeof(callback) == 'function') {
			event = ('' + event).toLowerCase().split(/\s+/);
			
			for (i = 0; i < event.length; i++) {
				if (listeners[event[i]] === void(0)) {
					listeners[event[i]] = [];
				}
				listeners[event[i]].push(callback);
			}
		}
		return this;
	};
	
	/**
	 * Remove event listener if exists
	 *
	 * @param  String    event name
	 * @param  Function  callback
	 * @return elFinder
	 */
	this.unbind = function(event, callback) {
		var l = listeners[('' + event).toLowerCase()] || [],
			i = l.indexOf(callback);

		i > -1 && l.splice(i, 1);
		//delete callback; // need this?
		callback = null
		return this;
	};
	
	/**
	 * Fire event - send notification to all event listeners
	 *
	 * @param  String   event type
	 * @param  Object   data to send across event
	 * @return elFinder
	 */
	this.trigger = function(event, data) {
		var event    = event.toLowerCase(),
			handlers = listeners[event] || [], i, j;
		
		this.debug('event-'+event, data)
		
		if (handlers.length) {
			event = $.Event(event);

			for (i = 0; i < handlers.length; i++) {
				// to avoid data modifications. remember about "sharing" passing arguments in js :) 
				event.data = $.extend(true, {}, data);

				try {
					if (handlers[i](event, this) === false 
					|| event.isDefaultPrevented()) {
						this.debug('event-stoped', event.type);
						break;
					}
				} catch (ex) {
					window.console && window.console.log && window.console.log(ex);
				}
				
			}
		}
		return this;
	}
	
	/**
	 * Bind keybord shortcut to keydown event
	 *
	 * @example
	 *    elfinder.shortcut({ 
	 *       pattern : 'ctrl+a', 
	 *       description : 'Select all files', 
	 *       callback : function(e) { ... }, 
	 *       keypress : true|false (bind to keypress instead of keydown) 
	 *    })
	 *
	 * @param  Object  shortcut config
	 * @return elFinder
	 */
	this.shortcut = function(s) {
		var patterns, pattern, code, i, parts;
		
		if (this.options.allowShortcuts && s.pattern && $.isFunction(s.callback)) {
			patterns = s.pattern.toUpperCase().split(/\s+/);
			
			for (i= 0; i < patterns.length; i++) {
				pattern = patterns[i]
				parts   = pattern.split('+');
				code    = (code = parts.pop()).length == 1 
					? code > 0 ? code : code.charCodeAt(0) 
					: $.ui.keyCode[code];

				if (code && !shortcuts[pattern]) {
					shortcuts[pattern] = {
						keyCode     : code,
						altKey      : $.inArray('ALT', parts)   != -1,
						ctrlKey     : $.inArray('CTRL', parts)  != -1,
						shiftKey    : $.inArray('SHIFT', parts) != -1,
						type        : s.type || 'keydown',
						callback    : s.callback,
						description : s.description,
						pattern     : pattern
					};
				}
			}
		}
		return this;
	}
	
	/**
	 * Registered shortcuts
	 *
	 * @type Object
	 **/
	this.shortcuts = function() {
		var ret = [];
		
		$.each(shortcuts, function(i, s) {
			ret.push([s.pattern, self.i18n(s.description)]);
		});
		return ret;
	};
	
	/**
	 * Get/set clipboard content.
	 * Return new clipboard content.
	 *
	 * @example
	 *   this.clipboard([]) - clean clipboard
	 *   this.clipboard([{...}, {...}], true) - put 2 files in clipboard and mark it as cutted
	 * 
	 * @param  Array    new files hashes
	 * @param  Boolean  cut files?
	 * @return Array
	 */
	this.clipboard = function(hashes, cut) {
		var map = function() { return $.map(clipboard, function(f) { return f.hash }); }

		if (hashes !== void(0)) {
			clipboard.length && this.trigger('unlockfiles', {files : map()});
			remember = [];
			
			clipboard = $.map(hashes||[], function(hash) {
				var file = files[hash];
				if (file) {
					
					remember.push(hash);
					
					return {
						hash   : hash,
						phash  : file.phash,
						name   : file.name,
						mime   : file.mime,
						read   : file.read,
						locked : file.locked,
						cut    : !!cut
					}
				}
				return null;
			});
			this.trigger('changeclipboard', {clipboard : clipboard.slice(0, clipboard.length)});
			cut && this.trigger('lockfiles', {files : map()});
		}

		// return copy of clipboard instead of refrence
		return clipboard.slice(0, clipboard.length);
	}
	
	/**
	 * Return true if command enabled
	 * 
	 * @param  String  command name
	 * @return Boolean
	 */
	this.isCommandEnabled = function(name) {
		return this._commands[name] ? $.inArray(name, cwdOptions.disabled) === -1 : false;
	}
	
	/**
	 * Exec command and return result;
	 *
	 * @param  String         command name
	 * @param  String|Array   usualy files hashes
	 * @param  String|Array   command options
	 * @return $.Deferred
	 */		
	this.exec = function(cmd, files, opts) {
		return this._commands[cmd] && this.isCommandEnabled(cmd) 
			? this._commands[cmd].exec(files, opts) 
			: $.Deferred().reject('No such command');
	}
	
	/**
	 * Create and return dialog.
	 *
	 * @param  String|DOMElement  dialog content
	 * @param  Object             dialog options
	 * @return jQuery
	 */
	this.dialog = function(content, options) {
		return $('<div/>').append(content).appendTo(node).elfinderdialog(options);
	}
	
	/**
	 * Return UI widget or node
	 *
	 * @param  String  ui name
	 * @return jQuery
	 */
	this.getUI = function(ui) {
		return this.ui[ui] || node;
	}
	
	this.command = function(name) {
		return name === void(0) ? this._commands : this._commands[name];
	}
	
	/**
	 * Resize elfinder node
	 * 
	 * @param  String|Number  width
	 * @param  Number         height
	 * @return void
	 */
	this.resize = function(w, h) {
		node.css('width', w).height(h).trigger('resize');
		this.trigger('resize', {width : node.width(), height : node.height()});
	}
	
	/**
	 * Restore elfinder node size
	 * 
	 * @return elFinder
	 */
	this.restoreSize = function() {
		this.resize(width, height);
	}
	
	this.show = function() {
		node.show();
		this.enable().trigger('show');
	}
	
	this.hide = function() {
		this.disable().trigger('hide');
		node.hide();
	}
	
	/**
	 * Destroy this elFinder instance
	 *
	 * @return void
	 **/
	this.destroy = function() {
		if (node && node[0].elfinder) {
			this.trigger('destroy').disable();
			listeners = {};
			shortcuts = {};
			$(document).add(node).unbind('.'+this.namespace);
			self.trigger = function() { }
			node.children().remove();
			node.append(prevContent.contents()).removeClass(this.cssClass).attr('style', prevStyle);
			node[0].elfinder = null;
			if (syncInterval) {
				clearInterval(syncInterval);
			}
		}
	}
	
	/*************  init stuffs  ****************/
	
	// check jquery ui
	if (!($.fn.selectable && $.fn.draggable && $.fn.droppable)) {
		return alert(this.i18n('errJqui'));
	}

	// check node
	if (!node.length) {
		return alert(this.i18n('errNode'));
	}
	// check connector url
	if (!this.options.url) {
		return alert(this.i18n('errURL'));
	}

	$.extend($.ui.keyCode, {
		'F1' : 112,
		'F2' : 113,
		'F3' : 114,
		'F4' : 115,
		'F5' : 116,
		'F6' : 117,
		'F7' : 118,
		'F8' : 119,
		'F9' : 120
	});
	
	this.dragUpload = false;
	this.xhrUpload  = (typeof XMLHttpRequestUpload != 'undefined' || typeof XMLHttpRequestEventTarget != 'undefined') && typeof File != 'undefined' && typeof FormData != 'undefined';
	
	// configure transport object
	this.transport = {}

	if (typeof(this.options.transport) == 'object') {
		this.transport = this.options.transport;
		if (typeof(this.transport.init) == 'function') {
			this.transport.init(this)
		}
	}
	
	if (typeof(this.transport.send) != 'function') {
		this.transport.send = function(opts) { return $.ajax(opts); }
	}
	
	if (this.transport.upload == 'iframe') {
		this.transport.upload = $.proxy(this.uploads.iframe, this);
	} else if (typeof(this.transport.upload) == 'function') {
		this.dragUpload = !!this.options.dragUploadAllow;
	} else if (this.xhrUpload && !!this.options.dragUploadAllow) {
		this.transport.upload = $.proxy(this.uploads.xhr, this);
		this.dragUpload = true;
	} else {
		this.transport.upload = $.proxy(this.uploads.iframe, this);
	}

	/**
	 * Alias for this.trigger('error', {error : 'message'})
	 *
	 * @param  String  error message
	 * @return elFinder
	 **/
	this.error = function() {
		var arg = arguments[0];
		return arguments.length == 1 && typeof(arg) == 'function'
			? self.bind('error', arg)
			: self.trigger('error', {error : arg});
	}
	
	// create bind/trigger aliases for build-in events
	$.each(['enable', 'disable', 'load', 'open', 'reload', 'select',  'add', 'remove', 'change', 'dblclick', 'getfile', 'lockfiles', 'unlockfiles', 'dragstart', 'dragstop', 'search', 'searchend', 'viewchange'], function(i, name) {
		self[name] = function() {
			var arg = arguments[0];
			return arguments.length == 1 && typeof(arg) == 'function'
				? self.bind(name, arg)
				: self.trigger(name, $.isPlainObject(arg) ? arg : {});
		}
	});
	
	// bind core event handlers
	this
		.enable(function() {
			if (!enabled && self.visible() && self.ui.overlay.is(':hidden')) {
				enabled = true;
				$('texarea:focus,input:focus,button').blur();
				node.removeClass('elfinder-disabled');
			}
		})
		.disable(function() {
			prevEnabled = enabled;
			enabled = false;
			node.addClass('elfinder-disabled');
		})
		.open(function() {
			selected = [];
		})
		.select(function(e) {
			selected = $.map(e.data.selected || e.data.value|| [], function(hash) { return files[hash] ? hash : null; });
		})
		.error(function(e) { 
			var opts  = {
					cssClass  : 'elfinder-dialog-error',
					title     : self.i18n(self.i18n('error')),
					resizable : false,
					destroyOnClose : true,
					buttons   : {}
			};

			opts.buttons[self.i18n(self.i18n('btnClose'))] = function() { $(this).elfinderdialog('close'); };

			self.dialog('<span class="elfinder-dialog-icon elfinder-dialog-icon-error"/>'+self.i18n(e.data.error), opts);
		})
		.bind('tree parents', function(e) {
			cache(e.data.tree || []);
		})
		.bind('tmb', function(e) {
			$.each(e.data.images||[], function(hash, tmb) {
				if (files[hash]) {
					files[hash].tmb = tmb;
				}
			})
		})
		.add(function(e) {
			cache(e.data.added||[]);
		})
		.change(function(e) {
			$.each(e.data.changed||[], function(i, file) {
				var hash = file.hash;
				if ((files[hash].width && !file.width) || (files[hash].height && !file.height)) {
					files[hash].width = undefined;
					files[hash].height = undefined;
				}
				files[hash] = files[hash] ? $.extend(files[hash], file) : file;
			});
		})
		.remove(function(e) {
			var removed = e.data.removed||[],
				l       = removed.length, 
				rm      = function(hash) {
					var file = files[hash];
					if (file) {
						if (file.mime == 'directory' && file.dirs) {
							$.each(files, function(h, f) {
								f.phash == hash && rm(h);
							});
						}
						delete files[hash];
					}
				};
		
			while (l--) {
				rm(removed[l]);
			}
			
		})
		.bind('search', function(e) {
			cache(e.data.files);
		})
		.bind('rm', function(e) {
			var play  = beeper.canPlayType && beeper.canPlayType('audio/wav; codecs="1"');
		
			play && play != '' && play != 'no' && $(beeper).html('<source src="./sounds/rm.wav" type="audio/wav">')[0].play()
		})
		
		;

	// bind external event handlers
	$.each(this.options.handlers, function(event, callback) {
		self.bind(event, callback);
	});

	/**
	 * History object. Store visited folders
	 *
	 * @type Object
	 **/
	this.history = new this.history(this);
	
	// in getFileCallback set - change default actions on double click/enter/ctrl+enter
	if (typeof(this.options.getFileCallback) == 'function' && this.commands.getfile) {
		this.bind('dblclick', function(e) {
			e.preventDefault();
			self.exec('getfile').fail(function() {
				self.exec('open');
			});
		});
		this.shortcut({
			pattern     : 'enter',
			description : this.i18n('cmdgetfile'),
			callback    : function() { self.exec('getfile').fail(function() { self.exec(self.OS == 'mac' ? 'rename' : 'open') }) }
		})
		.shortcut({
			pattern     : 'ctrl+enter',
			description : this.i18n(this.OS == 'mac' ? 'cmdrename' : 'cmdopen'),
			callback    : function() { self.exec(self.OS == 'mac' ? 'rename' : 'open') }
		});
		
	} 

	/**
	 * Loaded commands
	 *
	 * @type Object
	 **/
	this._commands = {};
	
	if (!$.isArray(this.options.commands)) {
		this.options.commands = [];
	}
	// check required commands
	$.each(['open', 'reload', 'back', 'forward', 'up', 'home', 'info', 'quicklook', 'getfile', 'help'], function(i, cmd) {
		$.inArray(cmd, self.options.commands) === -1 && self.options.commands.push(cmd);
	});

	// load commands
	$.each(this.options.commands, function(i, name) {
		var cmd = self.commands[name];
		if ($.isFunction(cmd) && !self._commands[name]) {
			cmd.prototype = base;
			self._commands[name] = new cmd();
			self._commands[name].setup(name, self.options.commandsOptions[name]||{});
		}
	});
	
	// prepare node
	node.addClass(this.cssClass)
		.bind(mousedown, function() {
			!enabled && self.enable();
		});
	
	/**
	 * UI nodes
	 *
	 * @type Object
	 **/
	this.ui = {
		// container for nav panel and current folder container
		workzone : $('<div/>').appendTo(node).elfinderworkzone(this),
		// container for folders tree / places
		navbar : $('<div/>').appendTo(node).elfindernavbar(this, this.options.uiOptions.navbar || {}),
		// contextmenu
		contextmenu : $('<div/>').appendTo(node).elfindercontextmenu(this),
		// overlay
		overlay : $('<div/>').appendTo(node).elfinderoverlay({
			show : function() { self.disable(); },
			hide : function() { prevEnabled && self.enable(); }
		}),
		// current folder container
		cwd : $('<div/>').appendTo(node).elfindercwd(this, this.options.uiOptions.cwd || {}),
		// notification dialog window
		notify : this.dialog('', {
			cssClass  : 'elfinder-dialog-notify',
			position  : {top : '12px', right : '12px'},
			resizable : false,
			autoOpen  : false,
			title     : '&nbsp;',
			width     : 280
		}),
		statusbar : $('<div class="ui-widget-header ui-helper-clearfix ui-corner-bottom elfinder-statusbar"/>').hide().appendTo(node)
	}
	
	// load required ui
	$.each(this.options.ui || [], function(i, ui) {
		var name = 'elfinder'+ui,
			opts = self.options.uiOptions[ui] || {};

		if (!self.ui[ui] && $.fn[name]) {
			self.ui[ui] = $('<'+(opts.tag || 'div')+'/>').appendTo(node)[name](self, opts);
		}
	});
	


	// store instance in node
	node[0].elfinder = this;
	
	// make node resizable
	this.options.resizable 
	&& $.fn.resizable 
	&& node.resizable({
		handles   : 'se',
		minWidth  : 300,
		minHeight : 200
	});

	if (this.options.width) {
		width = this.options.width;
	}
	
	if (this.options.height) {
		height = parseInt(this.options.height);
	}
	
	// update size	
	self.resize(width, height);
	
	// attach events to document
	$(document)
		// disable elfinder on click outside elfinder
		.bind('click.'+this.namespace, function(e) { enabled && !$(e.target).closest(node).length && self.disable(); })
		// exec shortcuts
		.bind(keydown+' '+keypress, execShortcut);
	
	// attach events to window
	self.options.useBrowserHistory && $(window)
		.on('popstate', function(ev) {
			var target = ev.originalEvent.state && ev.originalEvent.state.thash;
			target && !$.isEmptyObject(self.files()) && self.request({
				data   : {cmd  : 'open', target : target, onhistory : 1},
				notify : {type : 'open', cnt : 1, hideCnt : true},
				syncOnFail : true
			});
		});
	
	// send initial request and start to pray >_<
	this.trigger('init')
		.request({
			data        : {cmd : 'open', target : self.startDir(), init : 1, tree : this.ui.tree ? 1 : 0}, 
			preventDone : true,
			notify      : {type : 'open', cnt : 1, hideCnt : true},
			freeze      : true
		})
		.fail(function() {
			self.trigger('fail').disable().lastDir('');
			listeners = {};
			shortcuts = {};
			$(document).add(node).unbind('.'+this.namespace);
			self.trigger = function() { };
		})
		.done(function(data) {
			self.load().debug('api', self.api);
			data = $.extend(true, {}, data);
			open(data);
			self.trigger('open', data);
		});
	
	// update ui's size after init
	this.one('load', function() {
		node.trigger('resize');
		if (self.options.sync > 1000) {
			syncInterval = setInterval(function() {
				self.sync();
			}, self.options.sync)
			
		}

	});

	// self.timeEnd('load'); 

}

/**
 * Prototype
 * 
 * @type  Object
 */
elFinder.prototype = {
	
	res : function(type, id) {
		return this.resources[type] && this.resources[type][id];
	}, 
	
	/**
	 * Internationalization object
	 * 
	 * @type  Object
	 */
	i18 : {
		en : {
			translator      : '',
			language        : 'English',
			direction       : 'ltr',
			dateFormat      : 'd.m.Y H:i',
			fancyDateFormat : '$1 H:i',
			messages        : {}
		},
		months : ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
		monthsShort : ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],

		days : ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'],
		daysShort : ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat']
	},
	
	/**
	 * File mimetype to kind mapping
	 * 
	 * @type  Object
	 */
	kinds : 	{
			'unknown'                       : 'Unknown',
			'directory'                     : 'Folder',
			'symlink'                       : 'Alias',
			'symlink-broken'                : 'AliasBroken',
			'application/x-empty'           : 'TextPlain',
			'application/postscript'        : 'Postscript',
			'application/vnd.ms-office'     : 'MsOffice',
			'application/vnd.ms-word'       : 'MsWord',
			'application/vnd.openxmlformats-officedocument.wordprocessingml.document' : 'MsWord',
			'application/vnd.ms-word.document.macroEnabled.12'                        : 'MsWord',
			'application/vnd.openxmlformats-officedocument.wordprocessingml.template' : 'MsWord',
			'application/vnd.ms-word.template.macroEnabled.12'                        : 'MsWord',
			'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'       : 'MsWord',
			'application/vnd.ms-excel'      : 'MsExcel',
			'application/vnd.ms-excel.sheet.macroEnabled.12'                          : 'MsExcel',
			'application/vnd.openxmlformats-officedocument.spreadsheetml.template'    : 'MsExcel',
			'application/vnd.ms-excel.template.macroEnabled.12'                       : 'MsExcel',
			'application/vnd.ms-excel.sheet.binary.macroEnabled.12'                   : 'MsExcel',
			'application/vnd.ms-excel.addin.macroEnabled.12'                          : 'MsExcel',
			'application/vnd.ms-powerpoint' : 'MsPP',
			'application/vnd.openxmlformats-officedocument.presentationml.presentation' : 'MsPP',
			'application/vnd.ms-powerpoint.presentation.macroEnabled.12'              : 'MsPP',
			'application/vnd.openxmlformats-officedocument.presentationml.slideshow'  : 'MsPP',
			'application/vnd.ms-powerpoint.slideshow.macroEnabled.12'                 : 'MsPP',
			'application/vnd.openxmlformats-officedocument.presentationml.template'   : 'MsPP',
			'application/vnd.ms-powerpoint.template.macroEnabled.12'                  : 'MsPP',
			'application/vnd.ms-powerpoint.addin.macroEnabled.12'                     : 'MsPP',
			'application/vnd.openxmlformats-officedocument.presentationml.slide'      : 'MsPP',
			'application/vnd.ms-powerpoint.slide.macroEnabled.12'                     : 'MsPP',
			'application/pdf'               : 'PDF',
			'application/xml'               : 'XML',
			'application/vnd.oasis.opendocument.text' : 'OO',
			'application/vnd.oasis.opendocument.text-template'         : 'OO',
			'application/vnd.oasis.opendocument.text-web'              : 'OO',
			'application/vnd.oasis.opendocument.text-master'           : 'OO',
			'application/vnd.oasis.opendocument.graphics'              : 'OO',
			'application/vnd.oasis.opendocument.graphics-template'     : 'OO',
			'application/vnd.oasis.opendocument.presentation'          : 'OO',
			'application/vnd.oasis.opendocument.presentation-template' : 'OO',
			'application/vnd.oasis.opendocument.spreadsheet'           : 'OO',
			'application/vnd.oasis.opendocument.spreadsheet-template'  : 'OO',
			'application/vnd.oasis.opendocument.chart'                 : 'OO',
			'application/vnd.oasis.opendocument.formula'               : 'OO',
			'application/vnd.oasis.opendocument.database'              : 'OO',
			'application/vnd.oasis.opendocument.image'                 : 'OO',
			'application/vnd.openofficeorg.extension'                  : 'OO',
			'application/x-shockwave-flash' : 'AppFlash',
			'application/flash-video'       : 'Flash video',
			'application/x-bittorrent'      : 'Torrent',
			'application/javascript'        : 'JS',
			'application/rtf'               : 'RTF',
			'application/rtfd'              : 'RTF',
			'application/x-font-ttf'        : 'TTF',
			'application/x-font-otf'        : 'OTF',
			'application/x-rpm'             : 'RPM',
			'application/x-web-config'      : 'TextPlain',
			'application/xhtml+xml'         : 'HTML',
			'application/docbook+xml'       : 'DOCBOOK',
			'application/x-awk'             : 'AWK',
			'application/x-gzip'            : 'GZIP',
			'application/x-bzip2'           : 'BZIP',
			'application/zip'               : 'ZIP',
			'application/x-zip'               : 'ZIP',
			'application/x-rar'             : 'RAR',
			'application/x-tar'             : 'TAR',
			'application/x-7z-compressed'   : '7z',
			'application/x-jar'             : 'JAR',
			'text/plain'                    : 'TextPlain',
			'text/x-php'                    : 'PHP',
			'text/html'                     : 'HTML',
			'text/javascript'               : 'JS',
			'text/css'                      : 'CSS',
			'text/rtf'                      : 'RTF',
			'text/rtfd'                     : 'RTF',
			'text/x-c'                      : 'C',
			'text/x-csrc'                   : 'C',
			'text/x-chdr'                   : 'CHeader',
			'text/x-c++'                    : 'CPP',
			'text/x-c++src'                 : 'CPP',
			'text/x-c++hdr'                 : 'CPPHeader',
			'text/x-shellscript'            : 'Shell',
			'application/x-csh'             : 'Shell',
			'text/x-python'                 : 'Python',
			'text/x-java'                   : 'Java',
			'text/x-java-source'            : 'Java',
			'text/x-ruby'                   : 'Ruby',
			'text/x-perl'                   : 'Perl',
			'text/x-sql'                    : 'SQL',
			'text/xml'                      : 'XML',
			'text/x-comma-separated-values' : 'CSV',
			'image/x-ms-bmp'                : 'BMP',
			'image/jpeg'                    : 'JPEG',
			'image/gif'                     : 'GIF',
			'image/png'                     : 'PNG',
			'image/tiff'                    : 'TIFF',
			'image/x-targa'                 : 'TGA',
			'image/vnd.adobe.photoshop'     : 'PSD',
			'image/xbm'                     : 'XBITMAP',
			'image/pxm'                     : 'PXM',
			'audio/mpeg'                    : 'AudioMPEG',
			'audio/midi'                    : 'AudioMIDI',
			'audio/ogg'                     : 'AudioOGG',
			'audio/mp4'                     : 'AudioMPEG4',
			'audio/x-m4a'                   : 'AudioMPEG4',
			'audio/wav'                     : 'AudioWAV',
			'audio/x-mp3-playlist'          : 'AudioPlaylist',
			'video/x-dv'                    : 'VideoDV',
			'video/mp4'                     : 'VideoMPEG4',
			'video/mpeg'                    : 'VideoMPEG',
			'video/x-msvideo'               : 'VideoAVI',
			'video/quicktime'               : 'VideoMOV',
			'video/x-ms-wmv'                : 'VideoWM',
			'video/x-flv'                   : 'VideoFlash',
			'video/x-matroska'              : 'VideoMKV',
			'video/ogg'                     : 'VideoOGG'
		},
	
	/**
	 * Ajax request data validation rules
	 * 
	 * @type  Object
	 */
	rules : {
		defaults : function(data) {
			if (!data
			|| (data.added && !$.isArray(data.added))
			||  (data.removed && !$.isArray(data.removed))
			||  (data.changed && !$.isArray(data.changed))) {
				return false;
			}
			return true;
		},
		open    : function(data) { return data && data.cwd && data.files && $.isPlainObject(data.cwd) && $.isArray(data.files); },
		tree    : function(data) { return data && data.tree && $.isArray(data.tree); },
		parents : function(data) { return data && data.tree && $.isArray(data.tree); },
		tmb     : function(data) { return data && data.images && ($.isPlainObject(data.images) || $.isArray(data.images)); },
		upload  : function(data) { return data && ($.isPlainObject(data.added) || $.isArray(data.added));},
		search  : function(data) { return data && data.files && $.isArray(data.files)}
	},

	

	
	/**
	 * Commands costructors
	 *
	 * @type Object
	 */
	commands : {},
	
	parseUploadData : function(text) {
		var data;
		
		if (!$.trim(text)) {
			return {error : ['errResponse', 'errDataEmpty']};
		}
		
		try {
			data = $.parseJSON(text);
		} catch (e) {
			return {error : ['errResponse', 'errDataNotJSON']}
		}
		
		if (!this.validResponse('upload', data)) {
			return {error : ['errResponse']};
		}
		data = this.normalize(data);
		data.removed = $.map(data.added||[], function(f) { return f.hash; })
		return data;
		
	},
	
	iframeCnt : 0,
	
	uploads : {
		// check droped contents
		checkFile : function(data, fm) {
			if (!!data.checked || data.type == 'files') {
				return data.files;
			} else if (data.type == 'data') {
				var dfrd = $.Deferred(),
				files = [],
				paths = [],
				dirctorys = [],
				entries = [],
				processing = 0,
				
				readEntries = function(dirReader) {
					var toArray = function(list) {
						return Array.prototype.slice.call(list || []);
					};
					var readFile = function(fileEntry, callback) {
						var dfrd = $.Deferred();
						if (typeof fileEntry == 'undefined') {
							dfrd.reject('empty');
						} else if (fileEntry.isFile) {
							fileEntry.file(function (file) {
								dfrd.resolve(file);
							}, function(e){
								dfrd.reject();
							});
						} else {
							dfrd.reject('dirctory');
						}
						return dfrd.promise();
					};
			
					dirReader.readEntries(function (results) {
						if (!results.length) {
							var len = entries.length - 1;
							var read = function(i) {
								readFile(entries[i]).done(function(file){
									if (! (fm.OS == 'win' && file.name.match(/^(?:desktop\.ini|thumbs\.db)$/i))
											&&
										! (fm.OS == 'mac' && file.name.match(/^\.ds_store$/i))) {
										paths.push(entries[i].fullPath);
										files.push(file);
									}
								}).fail(function(e){
									if (e == 'dirctory') {
										// dirctory
										dirctorys.push(entries[i]);
									} else if (e == 'empty') {
										// dirctory is empty
									} else {
										// why fail?
									}
								}).always(function(){
									processing--;
									if (i < len) {
										processing++;
										read(++i);
									}
								});
							};
							processing++;
							read(0);
							processing--;
						} else {
							entries = entries.concat(toArray(results));
							readEntries(dirReader);
						}
					});
				},
				
				doScan = function(items, isEntry) {
					var dirReader, entry;
					entries = [];
					var length = items.length;
					for (var i = 0; i < length; i++) {
						if (! isEntry) {
							entry = !!items[i].getAsEntry? items[i].getAsEntry() : items[i].webkitGetAsEntry();
						} else {
							entry = items[i];
						}
						if (entry) {
							if (entry.isFile) {
								paths.push('');
								files.push(data.files.items[i].getAsFile());
							} else if (entry.isDirectory) {
								if (processing > 0) {
									dirctorys.push(entry);
								} else {
									processing = 0;
									dirReader = entry.createReader();
									processing++;
									readEntries(dirReader);
								}
							}
						}
					}
				};
				
				doScan(data.files.items);
				
				setTimeout(function wait() {
					if (processing > 0) {
						setTimeout(wait, 10);
					} else {
						if (dirctorys.length > 0) {
							doScan([dirctorys.shift()], true);
							setTimeout(wait, 10);
						} else {
							dfrd.resolve([files, paths]);
						}
					}
				}, 10);
				
				return dfrd.promise();
			} else {
				var ret = [];
				var regex;
				var str = data.files[0];
				if (data.type == 'html') {
					regex = /<img[^>]+src=["']?([^"'> ]+)/ig;
					var m = [];
					var url = '';
					var links;
					while (m = regex.exec(str)) {
						url = m[1].replace(/&amp;/g, '&');
						if (url.match(/^http|data:/) && $.inArray(url, ret) == -1) ret.push(url);
					}
					links = str.match(/<\/a>/i);
					if (links && links.length == 1) {
						regex = /<a[^>]+href=["']?([^"'> ]+)((?:.|\s)+)<\/a>/i;
						if (m = regex.exec(str)) {
							if (! m[2].match(/<img/i)) {
								url = m[1].replace(/&amp;/g, '&');
								if (url.match(/^http/) && $.inArray(url, ret) == -1) ret.push(url);
							}
						}
					}
				} else {
					regex = /(http[^<>"{}|\\^\[\]`\s]+)/ig;
					while (m = regex.exec(str)) {
						url = m[1].replace(/&amp;/g, '&');
						if ($.inArray(url, ret) == -1) ret.push(url);
					}
				}
				return ret;
			}
		},
		// upload transport using iframe
		iframe : function(data, fm) { 
			var self   = fm ? fm : this,
				input  = data.input? data.input : false,
				files  = !input ? self.uploads.checkFile(data, self) : false,
				dfrd   = $.Deferred()
					.fail(function(error) {
						error && self.error(error);
					})
					.done(function(data) {
						data.warning && self.error(data.warning);
						data.removed && self.remove(data);
						data.added   && self.add(data);
						data.changed && self.change(data);
						self.trigger('upload', data);
						data.sync && self.sync();
					}),
				name = 'iframe-'+self.namespace+(++self.iframeCnt),
				form = $('<form action="'+self.uploadURL+'" method="post" enctype="multipart/form-data" encoding="multipart/form-data" target="'+name+'" style="display:none"><input type="hidden" name="cmd" value="upload" /></form>'),
				msie = this.UA.IE,
				// clear timeouts, close notification dialog, remove form/iframe
				onload = function() {
					abortto  && clearTimeout(abortto);
					notifyto && clearTimeout(notifyto);
					notify   && self.notify({type : 'upload', cnt : -cnt});
					
					setTimeout(function() {
						msie && $('<iframe src="javascript:false;"/>').appendTo(form);
						form.remove();
						iframe.remove();
					}, 100);
				},
				iframe = $('<iframe src="'+(msie ? 'javascript:false;' : 'about:blank')+'" name="'+name+'" style="position:absolute;left:-1000px;top:-1000px" />')
					.bind('load', function() {
						iframe.unbind('load')
							.bind('load', function() {
								var data = self.parseUploadData(iframe.contents().text());
								
								onload();
								data.error ? dfrd.reject(data.error) : dfrd.resolve(data);
							});
							
							// notify dialog
							notifyto = setTimeout(function() {
								notify = true;
								self.notify({type : 'upload', cnt : cnt});
							}, self.options.notifyDelay);
							
							// emulate abort on timeout
							if (self.options.iframeTimeout > 0) {
								abortto = setTimeout(function() {
									onload();
									dfrd.reject([errors.connect, errors.timeout]);
								}, self.options.iframeTimeout);
							}
							
							form.submit();
					}),
				cnt, notify, notifyto, abortto
				
				;

			if (files && files.length) {
				$.each(files, function(i, val) {
					form.append('<input type="hidden" name="upload[]" value="'+val+'"/>');
				});
				cnt = 1;
			} else if (input && $(input).is(':file') && $(input).val()) {
				form.append(input);
				cnt = input.files ? input.files.length : 1;
			} else {
				return dfrd.reject();
			}
			
			form.append('<input type="hidden" name="'+(self.newAPI ? 'target' : 'current')+'" value="'+self.cwd().hash+'"/>')
				.append('<input type="hidden" name="html" value="1"/>')
				.append($(input).attr('name', 'upload[]'));
			
			$.each(self.options.onlyMimes||[], function(i, mime) {
				form.append('<input type="hidden" name="mimes[]" value="'+mime+'"/>');
			});
			
			$.each(self.options.customData, function(key, val) {
				form.append('<input type="hidden" name="'+key+'" value="'+val+'"/>');
			});
			
			form.appendTo('body');
			iframe.appendTo('body');
			
			return dfrd;
		},
		// upload transport using XMLHttpRequest
		xhr : function(data, fm) { 
			var self   = fm ? fm : this,
				dfrd   = $.Deferred()
					.fail(function(error) {
						error && self.error(error);
					})
					.done(function(data) {
						data.warning && self.error(data.warning);
						data.removed && self.remove(data);
						data.added   && self.add(data);
						data.changed && self.change(data);
	 					self.trigger('upload', data);
						data.sync && self.sync();
					})
					.always(function() {
						notifyto && clearTimeout(notifyto);
						! data.checked && notify && self.notify({type : 'upload', cnt : -cnt, progress : 100*cnt});
					}),
				xhr         = new XMLHttpRequest(),
				formData    = new FormData(),
				isDataType  = (data.type == 'data'),
				files       = data.input ? data.input.files : self.uploads.checkFile(data, self), 
				cnt         = data.checked? (isDataType? files[0].length : files.length) : files.length,
				loaded      = 5,
				notify      = false,
				startNotify = function() {
					return setTimeout(function() {
						notify = true;
						self.notify({type : 'upload', cnt : cnt, progress : loaded*cnt});
					}, self.options.notifyDelay);
				},
				notifyto, notifyto2;
			
			if (!isDataType && !cnt) {
				return dfrd.reject();
			}
			
			xhr.addEventListener('error', function() {
				dfrd.reject('errConnect');
			}, false);
			
			xhr.addEventListener('abort', function() {
				dfrd.reject(['errConnect', 'errAbort']);
			}, false);
			
			xhr.addEventListener('load', function() {
				var status = xhr.status, data;
				
				if (status > 500) {
					return dfrd.reject('errResponse');
				}
				if (status != 200) {
					return dfrd.reject('errConnect');
				}
				if (xhr.readyState != 4) {
					return dfrd.reject(['errConnect', 'errTimeout']); // am i right?
				}
				if (!xhr.responseText) {
					return dfrd.reject(['errResponse', 'errDataEmpty']);
				}

				data = self.parseUploadData(xhr.responseText);
				data.error ? dfrd.reject(data.error) : dfrd.resolve(data);
			}, false);
			
			xhr.upload.addEventListener('progress', function(e) {
				var prev = loaded, curr;

				if (e.lengthComputable) {
					
					curr = parseInt(e.loaded*100 / e.total);

					// to avoid strange bug in safari (not in chrome) with drag&drop.
					// bug: macos finder opened in any folder,
					// reset safari cache (option+command+e), reload elfinder page,
					// drop file from finder
					// on first attempt request starts (progress callback called ones) but never ends.
					// any next drop - successfull.
					if (!data.checked && curr > 0 && !notifyto) {
						notifyto = startNotify();
					}
					
					if (curr - prev > 4) {
						loaded = curr;
						(data.checked || notify) && self.notify({type : 'upload', cnt : 0, progress : (loaded - prev)*cnt});
					}
				}
			}, false);
			
			var send = function(files, paths){
				var size = 0, fcnt = 1, sfiles = [], c = 0, total = cnt, maxFileSize;
				if (! data.checked) {
					maxFileSize = fm.option('uploadMaxSize')? fm.option('uploadMaxSize') : fm.uplMaxSize;
					for (var i=0; i < files.length; i++) {
						if (maxFileSize && files[i].size >= maxFileSize) {
							self.error(self.i18n('errUploadFile', files[i].name) + ' ' + self.i18n('errUploadFileSize'));
							continue;
						}
						if ((fm.uplMaxSize && size + files[i].size >= fm.uplMaxSize) || fcnt > fm.uplMaxFile) {
							size = 0;
							fcnt = 1;
							c++;
						}
						if (typeof sfiles[c] == 'undefined') {
							sfiles[c] = [];
							if (isDataType) {
								sfiles[c][0] = [];
								sfiles[c][1] = [];
							}
						}
						if (isDataType) {
							sfiles[c][0].push(files[i]);
							sfiles[c][1].push(paths[i]);
						} else {
							sfiles[c].push(files[i]);
						}
						size += files[i].size;
						fcnt++;
					}
					
					if (sfiles.length == 0) {
						data.checked = true;
						return false;
					}
					
					if (sfiles.length > 1) {
						notifyto = startNotify();
						for (var i=0; i < sfiles.length; i++) {
							fm.exec('upload', {type: data.type, files: sfiles[i], checked: true}).always(function() {
								if (notify) {
									var _cnt = (isDataType? this[0] : this).length;
									total -= _cnt;
									if (total < 1) {
										notifyto && clearTimeout(notifyto);
										self.notify({type : 'upload', cnt : -cnt, progress : 100 * cnt});
									}
								}
							}.bind(sfiles[i]));
						}
						return false;
					}
					
					if (isDataType) {
						files = sfiles[0][0];
						paths = sfiles[0][1];
					} else {
						files = sfiles[0];
					}
				}
				
				xhr.open('POST', self.uploadURL, true);
				formData.append('cmd', 'upload');
				formData.append(self.newAPI ? 'target' : 'current', self.cwd().hash);
				$.each(self.options.customData, function(key, val) {
					formData.append(key, val);
				});
				$.each(self.options.onlyMimes, function(i, mime) {
					formData.append('mimes['+i+']', mime);
				});
				
				$.each(files, function(i, file) {
					formData.append('upload[]', file);
				});
				
				if (isDataType) {
					$.each(paths, function(i, path) {
						formData.append('upload_path[]', path);
					});
				}
				
				xhr.onreadystatechange = function() {
					if (xhr.readyState == 4 && xhr.status == 0) {
						// ff bug while send zero sized file
						// for safari - send directory
						dfrd.reject(['errConnect', 'errAbort']);
					}
				}
				
				xhr.send(formData);
				
				return true;
			};
			
			if (! isDataType) {
				if (! send(files)) {
					dfrd.reject();
				}
			} else {
				if (!! data.checked) {
					send(files[0], files[1]);
				} else {
					notifyto2 = setTimeout(function() {
						self.notify({type : 'readdir', cnt : 1, hideCnt: true});
					}, self.options.notifyDelay);
					files.done(function(result){
						notifyto2 && clearTimeout(notifyto2);
						self.notify({type : 'readdir', cnt : -1});
						cnt = result[0].length;
						send(result[0], result[1]);
					}).fail(function(){
						dfrd.reject();
					});
				}
			}

			if (!isDataType && !data.checked && (!this.UA.Safari || !data.files)) {
				notifyto = startNotify();
			}
			
			return dfrd;
		}
	},
	
	
	/**
	 * Bind callback to event(s) The callback is executed at most once per event.
	 * To bind to multiply events at once, separate events names by space
	 *
	 * @param  String    event name
	 * @param  Function  callback
	 * @return elFinder
	 */
	one : function(event, callback) {
		var self = this,
			h    = $.proxy(callback, function(event) {
				setTimeout(function() {self.unbind(event.type, h);}, 3);
				return callback.apply(this, arguments);
			});
		return this.bind(event, h);
	},
	
	/**
	 * Set/get data into/from localStorage
	 *
	 * @param  String       key
	 * @param  String|void  value
	 * @return String
	 */
	localStorage : function(key, val) {
		var s = window.localStorage;

		key = 'elfinder-'+key+this.id;
		
		if (val === null) {
			console.log('remove', key)
			return s.removeItem(key);
		}
		
		if (val !== void(0)) {
			try {
				s.setItem(key, val);
			} catch (e) {
				s.clear();
				s.setItem(key, val);
			}
		}

		return s.getItem(key);
	},
	
	/**
	 * Get/set cookie
	 *
	 * @param  String       cookie name
	 * @param  String|void  cookie value
	 * @return String
	 */
	cookie : function(name, value) {
		var d, o, c, i;

		name = 'elfinder-'+name+this.id;

		if (value === void(0)) {
			if (document.cookie && document.cookie != '') {
				c = document.cookie.split(';');
				name += '=';
				for (i=0; i<c.length; i++) {
					c[i] = $.trim(c[i]);
					if (c[i].substring(0, name.length) == name) {
						return decodeURIComponent(c[i].substring(name.length));
					}
				}
			}
			return '';
		}

		o = $.extend({}, this.options.cookie);
		if (value === null) {
			value = '';
			o.expires = -1;
		}
		if (typeof(o.expires) == 'number') {
			d = new Date();
			d.setTime(d.getTime()+(o.expires * 86400000));
			o.expires = d;
		}
		document.cookie = name+'='+encodeURIComponent(value)+'; expires='+o.expires.toUTCString()+(o.path ? '; path='+o.path : '')+(o.domain ? '; domain='+o.domain : '')+(o.secure ? '; secure' : '');
		return value;
	},
	
	/**
	 * Get start directory (by location.hash or last opened directory)
	 * 
	 * @return String
	 */
	startDir : function() {
		var locHash = window.location.hash;
		if (locHash && locHash.match(/^#elf_/)) {
			return locHash.replace(/^#elf_/, '');
		} else {
			return this.lastDir();
		}
	},
	
	/**
	 * Get/set last opened directory
	 * 
	 * @param  String|undefined  dir hash
	 * @return String
	 */
	lastDir : function(hash) { 
		return this.options.rememberLastDir ? this.storage('lastdir', hash) : '';
	},
	
	/**
	 * Node for escape html entities in texts
	 * 
	 * @type jQuery
	 */
	_node : $('<span/>'),
	
	/**
	 * Replace not html-safe symbols to html entities
	 * 
	 * @param  String  text to escape
	 * @return String
	 */
	escape : function(name) {
		return this._node.text(name).html();
	},
	
	/**
	 * Cleanup ajax data.
	 * For old api convert data into new api format
	 * 
	 * @param  String  command name
	 * @param  Object  data from backend
	 * @return Object
	 */
	normalize : function(data) {
		var filter = function(file) { 
		
			if (file && file.hash && file.name && file.mime) {
				if (file.mime == 'application/x-empty') {
					file.mime = 'text/plain';
				}
				return file;
			}
			return null;
			return file && file.hash && file.name && file.mime ? file : null; 
		};
		

		if (data.files) {
			data.files = $.map(data.files, filter);
		} 
		if (data.tree) {
			data.tree = $.map(data.tree, filter);
		}
		if (data.added) {
			data.added = $.map(data.added, filter);
		}
		if (data.changed) {
			data.changed = $.map(data.changed, filter);
		}
		if (data.api) {
			data.init = true;
		}
		return data;
	},
	
	/**
	 * Update sort options
	 *
	 * @param {String} sort type
	 * @param {String} sort order
	 * @param {Boolean} show folder first
	 */
	setSort : function(type, order, stickFolders) {
		this.storage('sortType', (this.sortType = this.sortRules[type] ? type : 'name'));
		this.storage('sortOrder', (this.sortOrder = /asc|desc/.test(order) ? order : 'asc'));
		this.storage('sortStickFolders', (this.sortStickFolders = !!stickFolders) ? 1 : '');
		this.trigger('sortchange');
	},
	
	_sortRules : {
		name : function(file1, file2) { return file1.name.toLowerCase().localeCompare(file2.name.toLowerCase()); },
		size : function(file1, file2) { 
			var size1 = parseInt(file1.size) || 0,
				size2 = parseInt(file2.size) || 0;
				
			return size1 == size2 ? 0 : size1 > size2 ? 1 : -1;
			return (parseInt(file1.size) || 0) > (parseInt(file2.size) || 0) ? 1 : -1; },
		kind : function(file1, file2) { return file1.mime.localeCompare(file2.mime); },
		date : function(file1, file2) { 
			var date1 = file1.ts || file1.date,
				date2 = file2.ts || file2.date;

			return date1 == date2 ? 0 : date1 > date2 ? 1 : -1
		}
	},
	
	/**
	 * Compare files based on elFinder.sort
	 *
	 * @param  Object  file
	 * @param  Object  file
	 * @return Number
	 */
	compare : function(file1, file2) {
		var self  = this,
			type  = self.sortType,
			asc   = self.sortOrder == 'asc',
			stick = self.sortStickFolders,
			rules = self.sortRules,
			sort  = rules[type],
			d1    = file1.mime == 'directory',
			d2    = file2.mime == 'directory',
			res;
			
		if (stick) {
			if (d1 && !d2) {
				return -1;
			} else if (!d1 && d2) {
				return 1;
			}
		}
		
		res = asc ? sort(file1, file2) : sort(file2, file1);
		
		return type != 'name' && res == 0
			? res = asc ? rules.name(file1, file2) : rules.name(file2, file1)
			: res;
	},
	
	/**
	 * Sort files based on config
	 *
	 * @param  Array  files
	 * @return Array
	 */
	sortFiles : function(files) {
		return files.sort(this.compare);
	},
	
	/**
	 * Open notification dialog 
	 * and append/update message for required notification type.
	 *
	 * @param  Object  options
	 * @example  
	 * this.notify({
	 *    type : 'copy',
	 *    msg : 'Copy files', // not required for known types @see this.notifyType
	 *    cnt : 3,
	 *    hideCnt : false, // true for not show count
	 *    progress : 10 // progress bar percents (use cnt : 0 to update progress bar)
	 * })
	 * @return elFinder
	 */
	notify : function(opts) {
		var type     = opts.type,
			msg      = this.messages['ntf'+type] ? this.i18n('ntf'+type) : this.i18n('ntfsmth'),
			ndialog  = this.ui.notify,
			notify   = ndialog.children('.elfinder-notify-'+type),
			ntpl     = '<div class="elfinder-notify elfinder-notify-{type}"><span class="elfinder-dialog-icon elfinder-dialog-icon-{type}"/><span class="elfinder-notify-msg">{msg}</span> <span class="elfinder-notify-cnt"/><div class="elfinder-notify-progressbar"><div class="elfinder-notify-progress"/></div></div>',
			delta    = opts.cnt,
			progress = opts.progress >= 0 ? opts.progress : 0,
			cnt, total, prc;

		if (!type) {
			return this;
		}
		
		if (!notify.length) {
			notify = $(ntpl.replace(/\{type\}/g, type).replace(/\{msg\}/g, msg))
				.appendTo(ndialog)
				.data('cnt', 0);

			if (progress) {
				notify.data({progress : 0, total : 0});
			}
		}

		cnt = delta + parseInt(notify.data('cnt'));
		
		if (cnt > 0) {
			!opts.hideCnt && notify.children('.elfinder-notify-cnt').text('('+cnt+')');
			ndialog.is(':hidden') && ndialog.elfinderdialog('open');
			notify.data('cnt', cnt);
			
			if (progress
			&& (total = notify.data('total')) >= 0
			&& (prc = notify.data('progress')) >= 0) {

				total    = delta + parseInt(notify.data('total'));
				prc      = progress + prc;
				progress = parseInt(prc/total);
				notify.data({progress : prc, total : total});
				
				ndialog.find('.elfinder-notify-progress')
					.animate({
						width : (progress < 100 ? progress : 100)+'%'
					}, 20);
			}
			
		} else {
			notify.remove();
			!ndialog.children().length && ndialog.elfinderdialog('close');
		}
		
		return this;
	},
	
	/**
	 * Open confirmation dialog 
	 *
	 * @param  Object  options
	 * @example  
	 * this.confirm({
	 *    title : 'Remove files',
	 *    text  : 'Here is question text',
	 *    accept : {  // accept callback - required
	 *      label : 'Continue',
	 *      callback : function(applyToAll) { fm.log('Ok') }
	 *    },
	 *    cancel : { // cancel callback - required
	 *      label : 'Cancel',
	 *      callback : function() { fm.log('Cancel')}
	 *    },
	 *    reject : { // reject callback - optionally
	 *      label : 'No',
	 *      callback : function(applyToAll) { fm.log('No')}
	 *   },
	 *   all : true  // display checkbox "Apply to all"
	 * })
	 * @return elFinder
	 */
	confirm : function(opts) {
		var complete = false,
			options = {
				cssClass  : 'elfinder-dialog-confirm',
				modal     : true,
				resizable : false,
				title     : this.i18n(opts.title || 'confirmReq'),
				buttons   : {},
				close     : function() { 
					!complete && opts.cancel.callback();
					$(this).elfinderdialog('destroy');
				}
			},
			apply = this.i18n('apllyAll'),
			label, checkbox;

		
		if (opts.reject) {
			options.buttons[this.i18n(opts.reject.label)] = function() {
				opts.reject.callback(!!(checkbox && checkbox.prop('checked')))
				complete = true;
				$(this).elfinderdialog('close')
			};
		}
		
		options.buttons[this.i18n(opts.accept.label)] = function() {
			opts.accept.callback(!!(checkbox && checkbox.prop('checked')))
			complete = true;
			$(this).elfinderdialog('close')
		};
		
		options.buttons[this.i18n(opts.cancel.label)] = function() {
			$(this).elfinderdialog('close')
		};
		
		if (opts.all) {
			if (opts.reject) {
				options.width = 370;
			}
			options.create = function() {
				checkbox = $('<input type="checkbox" />');
				$(this).next().children().before($('<label>'+apply+'</label>').prepend(checkbox));
			}
			
			options.open = function() {
				var pane = $(this).next(),
					width = parseInt(pane.children(':first').outerWidth() + pane.children(':last').outerWidth());

				if (width > parseInt(pane.width())) {
					$(this).closest('.elfinder-dialog').width(width+30);
				}
			}
		}
		
		return this.dialog('<span class="elfinder-dialog-icon elfinder-dialog-icon-confirm"/>' + this.i18n(opts.text), options);
	},
	
	/**
	 * Create unique file name in required dir
	 * 
	 * @param  String  file name
	 * @param  String  parent dir hash
	 * @return String
	 */
	uniqueName : function(prefix, phash) {
		var i = 0, ext = '', p, name;
		
		prefix = this.i18n(prefix); 
		phash = phash || this.cwd().hash;

		if ((p = prefix.indexOf('.txt')) != -1) {
			ext    = '.txt';
			prefix = prefix.substr(0, p);
		}
		
		name   = prefix+ext;
		
		if (!this.fileByName(name, phash)) {
			return name;
		}
		while (i < 10000) {
			name = prefix + ' ' + (++i) + ext;
			if (!this.fileByName(name, phash)) {
				return name;
			}
		}
		return prefix + Math.random() + ext;
	},
	
	/**
	 * Return message translated onto current language
	 *
	 * @param  String|Array  message[s]
	 * @return String
	 **/
	i18n : function() {
		var self = this,
			messages = this.messages, 
			input    = [],
			ignore   = [], 
			message = function(m) {
				var file;
				if (m.indexOf('#') === 0) {
					if ((file = self.file(m.substr(1)))) {
						return file.name;
					}
				}
				return m;
			},
			i, j, m;
			
		for (i = 0; i< arguments.length; i++) {
			m = arguments[i];
			
			if (typeof m == 'string') {
				input.push(message(m));
			} else if ($.isArray(m)) {
				for (j = 0; j < m.length; j++) {
					if (typeof m[j] == 'string') {
						input.push(message(m[j]));
					}
				}
			}
		}
		
		for (i = 0; i < input.length; i++) {
			// dont translate placeholders
			if ($.inArray(i, ignore) !== -1) {
				continue;
			}
			m = input[i];
			// translate message
			m = messages[m] || m;
			// replace placeholders in message
			m = m.replace(/\$(\d+)/g, function(match, placeholder) {
				placeholder = i + parseInt(placeholder);
				if (placeholder > 0 && input[placeholder]) {
					ignore.push(placeholder)
				}
				return input[placeholder] || '';
			});

			input[i] = m;
		}

		return $.map(input, function(m, i) { return $.inArray(i, ignore) === -1 ? m : null; }).join('<br>');
	},
	
	/**
	 * Convert mimetype into css classes
	 * 
	 * @param  String  file mimetype
	 * @return String
	 */
	mime2class : function(mime) {
		var prefix = 'elfinder-cwd-icon-';
		
		mime = mime.split('/');
		
		return prefix+mime[0]+(mime[0] != 'image' && mime[1] ? ' '+prefix+mime[1].replace(/(\.|\+)/g, '-') : '');
	},
	
	/**
	 * Return localized kind of file
	 * 
	 * @param  Object|String  file or file mimetype
	 * @return String
	 */
	mime2kind : function(f) {
		var mime = typeof(f) == 'object' ? f.mime : f, kind;
		
		if (f.alias) {
			kind = 'Alias';
		} else if (this.kinds[mime]) {
			kind = this.kinds[mime];
		} else {
			if (mime.indexOf('text') === 0) {
				kind = 'Text';
			} else if (mime.indexOf('image') === 0) {
				kind = 'Image';
			} else if (mime.indexOf('audio') === 0) {
				kind = 'Audio';
			} else if (mime.indexOf('video') === 0) {
				kind = 'Video';
			} else if (mime.indexOf('application') === 0) {
				kind = 'App';
			} else {
				kind = mime;
			}
		}
		
		return this.messages['kind'+kind] ? this.i18n('kind'+kind) : mime;
		
		var mime = typeof(f) == 'object' ? f.mime : f,
			kind = this.kinds[mime]||'unknown';

		if (f.alias) {
			kind = 'Alias';
		} else if (kind == 'unknown') {
			if (mime.indexOf('text') === 0) {
				kind = 'Text';
			} else if (mime.indexOf('image') === 0) {
				kind = 'Image';
			} else if (mime.indexOf('audio') === 0) {
				kind = 'Audio';
			} else if (mime.indexOf('video') === 0) {
				kind = 'Video';
			} else if (mime.indexOf('application') === 0) {
				kind = 'Application';
			}
		}
		
		return this.i18n(kind);
	},
	
	/**
	 * Return localized date
	 * 
	 * @param  Object  file object
	 * @return String
	 */
	formatDate : function(file, ts) {
		var self = this, 
			ts   = ts || file.ts, 
			i18  = self.i18,
			date, format, output, d, dw, m, y, h, g, i, s;

		if (self.options.clientFormatDate && ts > 0) {

			date = new Date(ts*1000);
			
			h  = date[self.getHours]();
			g  = h > 12 ? h - 12 : h;
			i  = date[self.getMinutes]();
			s  = date[self.getSeconds]();
			d  = date[self.getDate]();
			dw = date[self.getDay]();
			m  = date[self.getMonth]() + 1;
			y  = date[self.getFullYear]();
			
			format = ts >= this.yesterday 
				? this.fancyFormat 
				: this.dateFormat;

			output = format.replace(/[a-z]/gi, function(val) {
				switch (val) {
					case 'd': return d > 9 ? d : '0'+d;
					case 'j': return d;
					case 'D': return self.i18n(i18.daysShort[dw]);
					case 'l': return self.i18n(i18.days[dw]);
					case 'm': return m > 9 ? m : '0'+m;
					case 'n': return m;
					case 'M': return self.i18n(i18.monthsShort[m-1]);
					case 'F': return self.i18n(i18.months[m-1]);
					case 'Y': return y;
					case 'y': return (''+y).substr(2);
					case 'H': return h > 9 ? h : '0'+h;
					case 'G': return h;
					case 'g': return g;
					case 'h': return g > 9 ? g : '0'+g;
					case 'a': return h > 12 ? 'pm' : 'am';
					case 'A': return h > 12 ? 'PM' : 'AM';
					case 'i': return i > 9 ? i : '0'+i;
					case 's': return s > 9 ? s : '0'+s;
				}
				return val;
			});
			
			return ts >= this.yesterday
				? output.replace('$1', this.i18n(ts >= this.today ? 'Today' : 'Yesterday'))
				: output;
		} else if (file.date) {
			return file.date.replace(/([a-z]+)\s/i, function(a1, a2) { return self.i18n(a2)+' '; });
		}
		
		return self.i18n('dateUnknown');
	},
	
	/**
	 * Return css class marks file permissions
	 * 
	 * @param  Object  file 
	 * @return String
	 */
	perms2class : function(o) {
		var c = '';
		
		if (!o.read && !o.write) {
			c = 'elfinder-na';
		} else if (!o.read) {
			c = 'elfinder-wo';
		} else if (!o.write) {
			c = 'elfinder-ro';
		}
		return c;
	},
	
	/**
	 * Return localized string with file permissions
	 * 
	 * @param  Object  file
	 * @return String
	 */
	formatPermissions : function(f) {
		var p  = [];
			
		f.read && p.push(this.i18n('read'));
		f.write && p.push(this.i18n('write'));	

		return p.length ? p.join(' '+this.i18n('and')+' ') : this.i18n('noaccess');
	},
	
	/**
	 * Return formated file size
	 * 
	 * @param  Number  file size
	 * @return String
	 */
	formatSize : function(s) {
		var n = 1, u = 'b';
		
		if (s == 'unknown') {
			return this.i18n('unknown');
		}
		
		if (s > 1073741824) {
			n = 1073741824;
			u = 'GB';
		} else if (s > 1048576) {
			n = 1048576;
			u = 'MB';
		} else if (s > 1024) {
			n = 1024;
			u = 'KB';
		}
		s = s/n;
		return (s > 0 ? n >= 1048576 ? s.toFixed(2) : Math.round(s) : 0) +' '+u;
	},
	
	
	navHash2Id : function(hash) {
		return 'nav-'+hash;
	},
	
	navId2Hash : function(id) {
		return typeof(id) == 'string' ? id.substr(4) : false;
	},
	
	log : function(m) { window.console && window.console.log && window.console.log(m); return this; },
	
	debug : function(type, m) {
		var d = this.options.debug;

		if (d == 'all' || d === true || ($.isArray(d) && $.inArray(type, d) != -1)) {
			window.console && window.console.log && window.console.log('elfinder debug: ['+type+'] ['+this.id+']', m);
		} 
		return this;
	},
	time : function(l) { window.console && window.console.time && window.console.time(l); },
	timeEnd : function(l) { window.console && window.console.timeEnd && window.console.timeEnd(l); }
	

}
;"use strict"
/**
 * @class  elFinder command "archive"
 * Archive selected files
 *
 * @author Dmitry (dio) Levashov
 **/
elFinder.prototype.commands.archive = function() {
	var self  = this,
		fm    = self.fm,
		mimes = [];
		
	this.variants = [];
	
	this.disableOnSearch = true;
	
	/**
	 * Update mimes on open/reload
	 *
	 * @return void
	 **/
	fm.bind('open reload', function() {
		self.variants = [];
		$.each((mimes = fm.option('archivers')['create'] || []), function(i, mime) {
			self.variants.push([mime, fm.mime2kind(mime)])
		});
		self.change();
	});
	
	this.getstate = function() {
		return !this._disabled && mimes.length && fm.selected().length && fm.cwd().write ? 0 : -1;
	}
	
	this.exec = function(hashes, type) {
		var files = this.files(hashes),
			cnt   = files.length,
			mime  = type || mimes[0],
			cwd   = fm.cwd(),
			error = ['errArchive', 'errPerm', 'errCreatingTempDir', 'errFtpDownloadFile', 'errFtpUploadFile', 'errFtpMkdir', 'errArchiveExec', 'errExtractExec', 'errRm'],
			dfrd  = $.Deferred().fail(function(error) {
				error && fm.error(error);
			}), 
			i;

		if (!(this.enabled() && cnt && mimes.length && $.inArray(mime, mimes) !== -1)) {
			return dfrd.reject();
		}
		
		if (!cwd.write) {
			return dfrd.reject(error);
		}
		
		for (i = 0; i < cnt; i++) {
			if (!files[i].read) {
				return dfrd.reject(error);
			}
		}

		return fm.request({
			data       : {cmd : 'archive', targets : this.hashes(hashes), type : mime},
			notify     : {type : 'archive', cnt : 1},
			syncOnFail : true
		});
	}

};"use strict";
/**
 * @class  elFinder command "back"
 * Open last visited folder
 *
 * @author Dmitry (dio) Levashov
 **/
elFinder.prototype.commands.back = function() {
	this.alwaysEnabled  = true;
	this.updateOnSelect = false;
	this.shortcuts      = [{
		pattern     : 'ctrl+left backspace'
	}];
	
	this.getstate = function() {
		return this.fm.history.canBack() ? 0 : -1;
	}
	
	this.exec = function() {
		return this.fm.history.back();
	}

};"use strict";
/**
 * @class elFinder command "copy".
 * Put files in filemanager clipboard.
 *
 * @type  elFinder.command
 * @author  Dmitry (dio) Levashov
 */
elFinder.prototype.commands.copy = function() {
	
	this.shortcuts = [{
		pattern     : 'ctrl+c ctrl+insert'
	}];
	
	this.getstate = function(sel) {
		var sel = this.files(sel),
			cnt = sel.length;

		return cnt && $.map(sel, function(f) { return f.phash && f.read ? f : null  }).length == cnt ? 0 : -1;
	}
	
	this.exec = function(hashes) {
		var fm   = this.fm,
			dfrd = $.Deferred()
				.fail(function(error) {
					fm.error(error);
				});

		$.each(this.files(hashes), function(i, file) {
			if (!(file.read && file.phash)) {
				return !dfrd.reject(['errCopy', file.name, 'errPerm']);
			}
		});
		
		return dfrd.state() == 'rejected' ? dfrd : dfrd.resolve(fm.clipboard(this.hashes(hashes)));
	}

};"use strict";
/**
 * @class elFinder command "copy".
 * Put files in filemanager clipboard.
 *
 * @type  elFinder.command
 * @author  Dmitry (dio) Levashov
 */
elFinder.prototype.commands.cut = function() {
	
	this.shortcuts = [{
		pattern     : 'ctrl+x shift+insert'
	}];
	
	this.getstate = function(sel) {
		var sel = this.files(sel),
			cnt = sel.length;
		
		return cnt && $.map(sel, function(f) { return f.phash && f.read && !f.locked ? f : null  }).length == cnt ? 0 : -1;
	}
	
	this.exec = function(hashes) {
		var fm     = this.fm,
			dfrd   = $.Deferred()
				.fail(function(error) {
					fm.error(error);
				});

		$.each(this.files(hashes), function(i, file) {
			if (!(file.read && file.phash) ) {
				return !dfrd.reject(['errCopy', file.name, 'errPerm']);
			}
			if (file.locked) {
				return !dfrd.reject(['errLocked', file.name]);
			}
		});
		
		return dfrd.state() == 'rejected' ? dfrd : dfrd.resolve(fm.clipboard(this.hashes(hashes), true));
	}

};"use strict";
/**
 * @class elFinder command "download". 
 * Download selected files.
 * Only for new api
 *
 * @author Dmitry (dio) Levashov, dio@std42.ru
 **/
elFinder.prototype.commands.download = function() {
	var self   = this,
		fm     = this.fm,
		filter = function(hashes) {
			return $.map(self.files(hashes), function(f) { return f.mime == 'directory' ? null : f });
		};
	
	this.shortcuts = [{
		pattern     : 'shift+enter'
	}];
	
	this.getstate = function() {
		var sel = this.fm.selected(),
			cnt = sel.length;
		
		return  !this._disabled && cnt && (!fm.UA.IE || cnt == 1) && cnt == filter(sel).length ? 0 : -1;
	}
	
	this.exec = function(hashes) {
		var fm      = this.fm,
			base    = fm.options.url,
			files   = filter(hashes),
			dfrd    = $.Deferred(),
			iframes = '',
			cdata   = '',
			i, url;
			
		if (this.disabled()) {
			return dfrd.reject();
		}
			
		if (fm.oldAPI) {
			fm.error('errCmdNoSupport');
			return dfrd.reject();
		}
		
		cdata = $.param(fm.options.customData || {});
		if (cdata) {
			cdata = '&' + cdata;
		}
		
		base += base.indexOf('?') === -1 ? '?' : '&';
		
		for (i = 0; i < files.length; i++) {
			iframes += '<iframe class="downloader" id="downloader-' + files[i].hash+'" style="display:none" src="'+base + 'cmd=file&target=' + files[i].hash+'&download=1'+cdata+'"/>';
		}
		$(iframes)
			.appendTo('body')
			.ready(function() {
				setTimeout(function() {
					$(iframes).each(function() {
						$('#' + $(this).attr('id')).remove();
					});
				}, fm.UA.Firefox? (20000 + (10000 * i)) : 1000); // give mozilla 20 sec + 10 sec for each file to be saved
			});
		fm.trigger('download', {files : files});
		return dfrd.resolve(hashes);
	}

};"use strict";
/**
 * @class elFinder command "duplicate"
 * Create file/folder copy with suffix "copy Number"
 *
 * @type  elFinder.command
 * @author  Dmitry (dio) Levashov
 */
elFinder.prototype.commands.duplicate = function() {
	var fm = this.fm;
	
	this.getstate = function(sel) {
		var sel = this.files(sel),
			cnt = sel.length;

		return !this._disabled && cnt && fm.cwd().write && $.map(sel, function(f) { return f.phash && f.read ? f : null  }).length == cnt ? 0 : -1;
	}
	
	this.exec = function(hashes) {
		var fm     = this.fm,
			files  = this.files(hashes),
			cnt    = files.length,
			dfrd   = $.Deferred()
				.fail(function(error) {
					error && fm.error(error);
				}), 
			args = [];
			
		if (!cnt || this._disabled) {
			return dfrd.reject();
		}
		
		$.each(files, function(i, file) {
			if (!file.read || !fm.file(file.phash).write) {
				return !dfrd.reject(['errCopy', file.name, 'errPerm']);
			}
		});
		
		if (dfrd.state() == 'rejected') {
			return dfrd;
		}
		
		return fm.request({
			data   : {cmd : 'duplicate', targets : this.hashes(hashes)},
			notify : {type : 'copy', cnt : cnt}
		});
		
	}

};"use strict"
/**
 * @class elFinder command "edit". 
 * Edit text file in dialog window
 *
 * @author Dmitry (dio) Levashov, dio@std42.ru
 **/
elFinder.prototype.commands.edit = function() {
	var self  = this,
		fm    = this.fm,
		mimes = fm.res('mimes', 'text') || [],
		
		/**
		 * Return files acceptable to edit
		 *
		 * @param  Array  files hashes
		 * @return Array
		 **/
		filter = function(files) {
			return $.map(files, function(file) {
				return (file.mime.indexOf('text/') === 0 || $.inArray(file.mime, mimes) !== -1) 
					&& file.mime.indexOf('text/rtf')
					&& (!self.onlyMimes.length || $.inArray(file.mime, self.onlyMimes) !== -1)
					&& file.read && file.write ? file : null;
			});
		},
		
		/**
		 * Open dialog with textarea to edit file
		 *
		 * @param  String  id       dialog id
		 * @param  Object  file     file object
		 * @param  String  content  file content
		 * @return $.Deferred
		 **/
		dialog = function(id, file, content) {

			var dfrd = $.Deferred(),
				ta   = $('<textarea class="elfinder-file-edit" rows="20" id="'+id+'-ta">'+fm.escape(content)+'</textarea>'),
				save = function() {
					ta.editor && ta.editor.save(ta[0], ta.editor.instance);
					dfrd.resolve(ta.getContent());
					ta.elfinderdialog('close');
				},
				cancel = function() {
					dfrd.reject();
					ta.elfinderdialog('close');
				},
				opts = {
					title   : file.name,
					width   : self.options.dialogWidth || 450,
					buttons : {},
					close   : function() { 
						ta.editor && ta.editor.close(ta[0], ta.editor.instance);
						$(this).elfinderdialog('destroy'); 
					},
					open    : function() { 
						fm.disable();
						ta.focus(); 
						ta[0].setSelectionRange && ta[0].setSelectionRange(0, 0);
						ta.editor && ta.editor.load(ta[0]);
					}
					
				};
				
				ta.getContent = function() {
					return ta.val()
				}
				
				$.each(self.options.editors || [], function(i, editor) {
					if ($.inArray(file.mime, editor.mimes || []) !== -1 
					&& typeof editor.load == 'function'
					&& typeof editor.save == 'function') {
						ta.editor = {
							load     : editor.load,
							save     : editor.save,
							close    : typeof editor.close == 'function' ? editor.close : function() {},
							instance : null
						}
						
						return false;
					}
				});
				
				if (!ta.editor) {
					ta.keydown(function(e) {
						var code = e.keyCode,
							value, start;
						
						e.stopPropagation();
						if (code == 9) {
							e.preventDefault();
							// insert tab on tab press
							if (this.setSelectionRange) {
								value = this.value;
								start = this.selectionStart;
								this.value = value.substr(0, start) + "\t" + value.substr(this.selectionEnd);
								start += 1;
								this.setSelectionRange(start, start);
							}
						}
						
						if (e.ctrlKey || e.metaKey) {
							// close on ctrl+w/q
							if (code == 81 || code == 87) {
								e.preventDefault();
								cancel();
							}
							if (code == 83) {
								e.preventDefault();
								save();
							}
						}
						
					})
				}
				
				opts.buttons[fm.i18n('Save')]   = save;
				opts.buttons[fm.i18n('Cancel')] = cancel
				
				fm.dialog(ta, opts).attr('id', id);
				return dfrd.promise();
		},
		
		/**
		 * Get file content and
		 * open dialog with textarea to edit file content
		 *
		 * @param  String  file hash
		 * @return jQuery.Deferred
		 **/
		edit = function(file) {
			var hash   = file.hash,
				opts   = fm.options,
				dfrd   = $.Deferred(), 
				data   = {cmd : 'file', target : hash},
				url    = fm.url(hash) || fm.options.url,
				id    = 'edit-'+fm.namespace+'-'+file.hash,
				d = fm.getUI().find('#'+id), 
				error;
			
			
			if (d.length) {
				d.elfinderdialog('toTop');
				return dfrd.resolve();
			}
			
			if (!file.read || !file.write) {
				error = ['errOpen', file.name, 'errPerm']
				fm.error(error)
				return dfrd.reject(error);
			}
			
			fm.request({
				data   : {cmd : 'get', target  : hash},
				notify : {type : 'openfile', cnt : 1},
				syncOnFail : true
			})
			.done(function(data) {
				dialog(id, file, data.content)
					.done(function(content) {
						fm.request({
							options : {type : 'post'},
							data : {
								cmd     : 'put',
								target  : hash,
								content : content
							},
							notify : {type : 'save', cnt : 1},
							syncOnFail : true
						})
						.fail(function(error) {
							dfrd.reject(error);
						})
						.done(function(data) {
							data.changed && data.changed.length && fm.change(data);
							dfrd.resolve(data);
						});
					})
			})
			.fail(function(error) {
				dfrd.reject(error);
			})

			return dfrd.promise();
		};
	
	
	
	this.shortcuts = [{
		pattern     : 'ctrl+e'
	}];
	
	this.init = function() {
		this.onlyMimes = this.options.mimes || []
	}
	
	this.getstate = function(sel) {
		var sel = this.files(sel),
			cnt = sel.length;

		return !this._disabled && cnt && filter(sel).length == cnt ? 0 : -1;
	}
	
	this.exec = function(hashes) {
		var files = filter(this.files(hashes)),
			list  = [],
			file;

		if (this.disabled()) {
			return $.Deferred().reject();
		}

		while ((file = files.shift())) {
			list.push(edit(file));
		}
		
		return list.length 
			? $.when.apply(null, list)
			: $.Deferred().reject();
	}

};"use strict"
/**
 * @class  elFinder command "extract"
 * Extract files from archive
 *
 * @author Dmitry (dio) Levashov
 **/
elFinder.prototype.commands.extract = function() {
	var self    = this,
		fm      = self.fm,
		mimes   = [],
		filter  = function(files) {
			return $.map(files, function(file) { 
				return file.read && $.inArray(file.mime, mimes) !== -1 ? file : null
				
			})
		};
	
	this.disableOnSearch = true;
	
	// Update mimes list on open/reload
	fm.bind('open reload', function() {
		mimes = fm.option('archivers')['extract'] || [];
		self.change();
	});
	
	this.getstate = function(sel) {
		var sel = this.files(sel),
			cnt = sel.length;
		
		return !this._disabled && cnt && this.fm.cwd().write && filter(sel).length == cnt ? 0 : -1;
	}
	
	this.exec = function(hashes) {
		var files    = this.files(hashes),
			dfrd     = $.Deferred(),
			cnt      = files.length, 
			i, error,
			decision;

		var overwriteAll = false;
		var omitAll = false;

		var names = $.map(fm.files(hashes), function(file) { return file.name; });
		var map = {};
		$.map(fm.files(hashes), function(file) { map[file.name] = file; });
		
		var decide = function(decision) {
			switch (decision) {
				case 'overwrite_all' :
					overwriteAll = true;
					break;
				case 'omit_all':
					omitAll = true;
					break;
			}
		};

		var unpack = function(file) {
			if (!(file.read && fm.file(file.phash).write)) {
				error = ['errExtract', file.name, 'errPerm'];
				fm.error(error);
				dfrd.reject(error);
			} else if ($.inArray(file.mime, mimes) === -1) {
				error = ['errExtract', file.name, 'errNoArchive'];
				fm.error(error);
				dfrd.reject(error);
			} else {
				fm.request({
					data:{cmd:'extract', target:file.hash},
					notify:{type:'extract', cnt:1},
					syncOnFail:true
				})
				.fail(function (error) {
					if (dfrd.state() != 'rejected') {
						dfrd.reject(error);
					}
				})
				.done(function () {
				});
			}
		};
		
		var confirm = function(files, index) {
			var file = files[index];
			var name = file.name.replace(/\.((tar\.(gz|bz|bz2|z|lzo))|cpio\.gz|ps\.gz|xcf\.(gz|bz2)|[a-z0-9]{1,4})$/ig, '');
			var existed = ($.inArray(name, names) >= 0);
			if(existed && map[name].mime != 'directory') {
				fm.confirm(
					{
						title : fm.i18n('ntfextract'),
						text  : fm.i18n(['errExists', name, 'confirmRepl']),
						accept:{
							label : 'btnYes',
							callback:function (all) {
								decision = all ? 'overwrite_all' : 'overwrite';
								decide(decision);
								if(!overwriteAll && !omitAll) {
									if('overwrite' == decision) {
										unpack(file);
									}
									if((index+1) < cnt) {
										confirm(files, index+1);
									} else {
										dfrd.resolve();
									}
								} else if(overwriteAll) {
									for (i = 0; i < cnt; i++) {
										unpack(files[i]);
									}
									dfrd.resolve();
								}
							}
						},
						reject : {
							label : 'btnNo',
							callback:function (all) {
								decision = all ? 'omit_all' : 'omit';
								decide(decision);
								if(!overwriteAll && !omitAll && (index+1) < cnt) {
									confirm(files, index+1);
								} else if (omitAll) {
									dfrd.resolve();
								}
							}
						},
						cancel : {
							label : 'btnCancel',
							callback:function () {
								dfrd.resolve();
							}
						},
						all : (cnt > 1)
					}
				);
			} else {
				unpack(file);
				if((index+1) < cnt) {
					confirm(files, index+1);
				} else {
					dfrd.resolve();
				}
			}
		};
		
		if (!(this.enabled() && cnt && mimes.length)) {
			return dfrd.reject();
		}
		
		if(cnt > 0) {
			confirm(files, 0);
		}

		return dfrd;
	}

};/**
 * @class  elFinder command "forward"
 * Open next visited folder
 *
 * @author Dmitry (dio) Levashov
 **/
elFinder.prototype.commands.forward = function() {
	this.alwaysEnabled = true;
	this.updateOnSelect = true;
	this.shortcuts = [{
		pattern     : 'ctrl+right'
	}];
	
	this.getstate = function() {
		return this.fm.history.canForward() ? 0 : -1;
	}
	
	this.exec = function() {
		return this.fm.history.forward();
	}
	
};"use strict";
/**
 * @class elFinder command "getfile". 
 * Return selected files info into outer callback.
 * For use elFinder with wysiwyg editors etc.
 *
 * @author Dmitry (dio) Levashov, dio@std42.ru
 **/
elFinder.prototype.commands.getfile = function() {
	var self   = this,
		fm     = this.fm,
		filter = function(files) {
			var o = self.options;

			files = $.map(files, function(file) {
				return file.mime != 'directory' || o.folders ? file : null;
			});

			return o.multiple || files.length == 1 ? files : [];
		};
	
	this.alwaysEnabled = true;
	this.callback      = fm.options.getFileCallback;
	this._disabled     = typeof(this.callback) == 'function';
	
	this.getstate = function(sel) {
		var sel = this.files(sel),
			cnt = sel.length;
			
		return this.callback && cnt && filter(sel).length == cnt ? 0 : -1;
	}
	
	this.exec = function(hashes) {
		var fm    = this.fm,
			opts  = this.options,
			files = this.files(hashes),
			cnt   = files.length,
			url   = fm.option('url'),
			tmb   = fm.option('tmbUrl'),
			dfrd  = $.Deferred()
				.done(function(data) {
					fm.trigger('getfile', {files : data});
					self.callback(data, fm);
					
					if (opts.oncomplete == 'close') {
						fm.hide();
					} else if (opts.oncomplete == 'destroy') {
						fm.destroy();
					}
				}),
			result = function(file) {
				return opts.onlyURL
					? opts.multiple ? $.map(files, function(f) { return f.url; }) : files[0].url
					: opts.multiple ? files : files[0];
			},
			req = [], 
			i, file, dim;

		if (this.getstate() == -1) {
			return dfrd.reject();
		}
			
		for (i = 0; i < cnt; i++) {
			file = files[i];
			if (file.mime == 'directory' && !opts.folders) {
				return dfrd.reject();
			}
			file.baseUrl = url;
			file.url     = fm.url(file.hash);
			file.path    = fm.path(file.hash);
			if (file.tmb && file.tmb != 1) {
				file.tmb = tmb + file.tmb;
			}
			if (!file.width && !file.height) {
				if (file.dim) {
					dim = file.dim.split('x');
					file.width = dim[0];
					file.height = dim[1];
				} else if (file.mime.indexOf('image') !== -1) {
					req.push(fm.request({
						data : {cmd : 'dim', target : file.hash},
						notify : {type : 'dim', cnt : 1, hideCnt : true},
						preventDefault : true
					})
					.done(function(data) {
						if (data.dim) {
							var dim = data.dim.split('x');
							var rfile = fm.file(this.hash);
							rfile.width = this.width = dim[0];
							rfile.height = this.height = dim[1];
						}
					}.bind(file)));
				}
			}
		}
		
		if (req.length) {
			$.when.apply(null, req).always(function() {
				dfrd.resolve(result(files));
			})
			return dfrd;
		}
		
		return dfrd.resolve(result(files));
	}

};"use strict";
/**
 * @class  elFinder command "help"
 * "About" dialog
 *
 * @author Dmitry (dio) Levashov
 **/
elFinder.prototype.commands.help = function() {
	var fm   = this.fm,
		self = this,
		linktpl = '<div class="elfinder-help-link"> <a href="{url}">{link}</a></div>',
		linktpltgt = '<div class="elfinder-help-link"> <a href="{url}" target="_blank">{link}</a></div>',
		atpl    = '<div class="elfinder-help-team"><div>{author}</div>{work}</div>',
		url     = /\{url\}/,
		link    = /\{link\}/,
		author  = /\{author\}/,
		work    = /\{work\}/,
		r       = 'replace',
		prim    = 'ui-priority-primary',
		sec     = 'ui-priority-secondary',
		lic     = 'elfinder-help-license',
		tab     = '<li class="ui-state-default ui-corner-top"><a href="#{id}">{title}</a></li>',
		html    = ['<div class="ui-tabs ui-widget ui-widget-content ui-corner-all elfinder-help">', 
				'<ul class="ui-tabs-nav ui-helper-reset ui-helper-clearfix ui-widget-header ui-corner-all">'],
		stpl    = '<div class="elfinder-help-shortcut"><div class="elfinder-help-shortcut-pattern">{pattern}</div> {descrip}</div>',
		sep     = '<div class="elfinder-help-separator"/>',
		
		
		about = function() {
			html.push('<div id="about" class="ui-tabs-panel ui-widget-content ui-corner-bottom"><div class="elfinder-help-logo"/>')
			html.push('<h3>elFinder</h3>');
			html.push('<div class="'+prim+'">'+fm.i18n('webfm')+'</div>');
			html.push('<div class="'+sec+'">'+fm.i18n('ver')+': '+fm.version+', '+fm.i18n('protocolver')+': <span id="apiver"></span></div>');
			html.push('<div class="'+sec+'">jQuery/jQuery UI: '+$().jquery+'/'+$.ui.version+'</div>');

			html.push(sep);
			
			html.push(linktpltgt[r](url, 'http://elfinder.org/')[r](link, fm.i18n('homepage')));
			html.push(linktpltgt[r](url, 'https://github.com/Studio-42/elFinder/wiki')[r](link, fm.i18n('docs')));
			html.push(linktpltgt[r](url, 'https://github.com/Studio-42/elFinder')[r](link, fm.i18n('github')));
			html.push(linktpltgt[r](url, 'http://twitter.com/elrte_elfinder')[r](link, fm.i18n('twitter')));
			
			html.push(sep);
			
			html.push('<div class="'+prim+'">'+fm.i18n('team')+'</div>');
			
			html.push(atpl[r](author, 'Dmitry "dio" Levashov &lt;dio@std42.ru&gt;')[r](work, fm.i18n('chiefdev')));
			html.push(atpl[r](author, 'Troex Nevelin &lt;troex@fury.scancode.ru&gt;')[r](work, fm.i18n('maintainer')));
			html.push(atpl[r](author, 'Alexey Sukhotin &lt;strogg@yandex.ru&gt;')[r](work, fm.i18n('contributor')));
			html.push(atpl[r](author, 'Naoki Sawada &lt;hypweb@gmail.com&gt;')[r](work, fm.i18n('contributor')));
			
			fm.i18[fm.lang].translator && html.push(atpl[r](author, fm.i18[fm.lang].translator)[r](work, fm.i18n('translator')+' ('+fm.i18[fm.lang].language+')'));
			
			html.push(sep);
			html.push('<div class="'+lic+'">'+fm.i18n('icons')+': Pixelmixer, <a href="http://p.yusukekamiyamane.com" target="_blank">Fugue</a></div>');
			
			html.push(sep);
			html.push('<div class="'+lic+'">Licence: BSD Licence</div>');
			html.push('<div class="'+lic+'">Copyright © 2009-2011, Studio 42</div>');
			html.push('<div class="'+lic+'">„ …'+fm.i18n('dontforget')+' ”</div>');
			html.push('</div>');
		},
		shortcuts = function() {
			var sh = fm.shortcuts();
			// shortcuts tab
			html.push('<div id="shortcuts" class="ui-tabs-panel ui-widget-content ui-corner-bottom">');
			
			if (sh.length) {
				html.push('<div class="ui-widget-content elfinder-help-shortcuts">');
				$.each(sh, function(i, s) {
					html.push(stpl.replace(/\{pattern\}/, s[0]).replace(/\{descrip\}/, s[1]));
				});
			
				html.push('</div>');
			} else {
				html.push('<div class="elfinder-help-disabled">'+fm.i18n('shortcutsof')+'</div>')
			}
			
			
			html.push('</div>')
			
		},
		help = function() {
			// help tab
			html.push('<div id="help" class="ui-tabs-panel ui-widget-content ui-corner-bottom">');
			html.push('<a href="http://elfinder.org/forum/" target="_blank" class="elfinder-dont-panic"><span>DON\'T PANIC</span></a>');
			html.push('</div>');
			// end help
		},
		content;
	
	this.alwaysEnabled  = true;
	this.updateOnSelect = false;
	this.state = 0;
	
	this.shortcuts = [{
		pattern     : 'f1',
		description : this.title
	}];
	
	setTimeout(function() {
		var parts = self.options.view || ['about', 'shortcuts', 'help'];
		
		$.each(parts, function(i, title) {
			html.push(tab[r](/\{id\}/, title)[r](/\{title\}/, fm.i18n(title)));
		});
		
		html.push('</ul>');

		$.inArray('about', parts) !== -1 && about();
		$.inArray('shortcuts', parts) !== -1 && shortcuts();
		$.inArray('help', parts) !== -1 && help();
		
		html.push('</div>');
		content = $(html.join(''));
		
		fm.one('load', function setapi() { content.find('#apiver').text(fm.api); });
		
		content.find('.ui-tabs-nav li')
			.hover(function() {
				$(this).toggleClass('ui-state-hover')
			})
			.children()
			.click(function(e) {
				var link = $(this);
				
				e.preventDefault();
				e.stopPropagation();
				
				if (!link.is('.ui-tabs-selected')) {
					link.parent().addClass('ui-tabs-selected ui-state-active').siblings().removeClass('ui-tabs-selected').removeClass('ui-state-active');
					content.find('.ui-tabs-panel').hide().filter(link.attr('href')).show();
				}
				
			})
			.filter(':first').click();
		
	}, 200)
	
	this.getstate = function() {
		return 0;
	}
	
	this.exec = function() {
		if (!this.dialog) {
			this.dialog = this.fm.dialog(content, {title : this.title, width : 530, autoOpen : false, destroyOnClose : false});
		}
		
		this.dialog.elfinderdialog('open').find('.ui-tabs-nav li a:first').click();
	}

}
;
elFinder.prototype.commands.home = function() {
	this.title = 'Home';
	this.alwaysEnabled  = true;
	this.updateOnSelect = false;
	this.shortcuts = [{
		pattern     : 'ctrl+home ctrl+shift+up',
		description : 'Home'
	}];
	
	this.getstate = function() {
		var root = this.fm.root(),
			cwd  = this.fm.cwd().hash;
			
		return root && cwd && root != cwd ? 0: -1;
	}
	
	this.exec = function() {
		return this.fm.exec('open', this.fm.root());
	}
	

};"use strict";
/**
 * @class elFinder command "info". 
 * Display dialog with file properties.
 *
 * @author Dmitry (dio) Levashov, dio@std42.ru
 **/
elFinder.prototype.commands.info = function() {
	var m   = 'msg',
		fm  = this.fm,
		spclass = 'elfinder-info-spinner',
		msg = {
			calc     : fm.i18n('calc'),
			size     : fm.i18n('size'),
			unknown  : fm.i18n('unknown'),
			path     : fm.i18n('path'),
			aliasfor : fm.i18n('aliasfor'),
			modify   : fm.i18n('modify'),
			perms    : fm.i18n('perms'),
			locked   : fm.i18n('locked'),
			dim      : fm.i18n('dim'),
			kind     : fm.i18n('kind'),
			files    : fm.i18n('files'),
			folders  : fm.i18n('folders'),
			items    : fm.i18n('items'),
			yes      : fm.i18n('yes'),
			no       : fm.i18n('no'),
			link     : fm.i18n('link')
		};
		
	this.tpl = {
		main       : '<div class="ui-helper-clearfix elfinder-info-title"><span class="elfinder-cwd-icon {class} ui-corner-all"/>{title}</div><table class="elfinder-info-tb">{content}</table>',
		itemTitle  : '<strong>{name}</strong><span class="elfinder-info-kind">{kind}</span>',
		groupTitle : '<strong>{items}: {num}</strong>',
		row        : '<tr><td>{label} : </td><td>{value}</td></tr>',
		spinner    : '<span>{text}</span> <span class="'+spclass+'"/>'
	}
	
	this.alwaysEnabled = true;
	this.updateOnSelect = false;
	this.shortcuts = [{
		pattern     : 'ctrl+i'
	}];
	
	this.init = function() {
		$.each(msg, function(k, v) {
			msg[k] = fm.i18n(v);
		});
	}
	
	this.getstate = function() {
		return 0;
	}
	
	this.exec = function(hashes) {
		var files   = this.files(hashes);
		if (! files.length) {
			files   = this.files([ this.fm.cwd().hash ]);
		}
		var self    = this,
			fm      = this.fm,
			o       = this.options,
			tpl     = this.tpl,
			row     = tpl.row,
			cnt     = files.length,
			content = [],
			view    = tpl.main,
			l       = '{label}',
			v       = '{value}',
			opts    = {
				title : this.title,
				width : 'auto',
				close : function() { $(this).elfinderdialog('destroy'); }
			},
			count = [],
			replSpinner = function(msg) { dialog.find('.'+spclass).parent().text(msg); },
			id = fm.namespace+'-info-'+$.map(files, function(f) { return f.hash }).join('-'),
			dialog = fm.getUI().find('#'+id), 
			size, tmb, file, title, dcnt;
			
		if (!cnt) {
			return $.Deferred().reject();
		}
			
		if (dialog.length) {
			dialog.elfinderdialog('toTop');
			return $.Deferred().resolve();
		}
		
			
		if (cnt == 1) {
			file  = files[0];
			
			view  = view.replace('{class}', fm.mime2class(file.mime));
			title = tpl.itemTitle.replace('{name}', fm.escape(file.i18 || file.name)).replace('{kind}', fm.mime2kind(file));

			if (file.tmb) {
				tmb = fm.option('tmbUrl')+file.tmb;
			}
			
			if (!file.read) {
				size = msg.unknown;
			} else if (file.mime != 'directory' || file.alias) {
				size = fm.formatSize(file.size);
			} else {
				size = tpl.spinner.replace('{text}', msg.calc);
				count.push(file.hash);
			}
			
			content.push(row.replace(l, msg.size).replace(v, size));
			file.alias && content.push(row.replace(l, msg.aliasfor).replace(v, file.alias));
			content.push(row.replace(l, msg.path).replace(v, fm.escape(fm.path(file.hash, true))));
			if (file.read) {
				var href;
				if (o.nullUrlDirLinkSelf && file.mime == 'directory' && file.url === null) {
					var loc = window.location;
					href = loc.pathname + loc.search + '#elf_' + file.hash;
				} else {
					href = fm.url(file.hash);
				}
				content.push(row.replace(l, msg.link).replace(v,  '<a href="'+href+'" target="_blank">'+file.name+'</a>'));
			}
			
			if (file.dim) { // old api
				content.push(row.replace(l, msg.dim).replace(v, file.dim));
			} else if (file.mime.indexOf('image') !== -1) {
				if (file.width && file.height) {
					content.push(row.replace(l, msg.dim).replace(v, file.width+'x'+file.height));
				} else {
					content.push(row.replace(l, msg.dim).replace(v, tpl.spinner.replace('{text}', msg.calc)));
					fm.request({
						data : {cmd : 'dim', target : file.hash},
						preventDefault : true
					})
					.fail(function() {
						replSpinner(msg.unknown);
					})
					.done(function(data) {
						replSpinner(data.dim || msg.unknown);
						if (data.dim) {
							var dim = data.dim.split('x');
							var rfile = fm.file(file.hash);
							rfile.width = dim[0];
							rfile.height = dim[1];
						}
					});
				}
			}
			
			
			content.push(row.replace(l, msg.modify).replace(v, fm.formatDate(file)));
			content.push(row.replace(l, msg.perms).replace(v, fm.formatPermissions(file)));
			content.push(row.replace(l, msg.locked).replace(v, file.locked ? msg.yes : msg.no));
		} else {
			view  = view.replace('{class}', 'elfinder-cwd-icon-group');
			title = tpl.groupTitle.replace('{items}', msg.items).replace('{num}', cnt);
			dcnt  = $.map(files, function(f) { return f.mime == 'directory' ? 1 : null }).length;
			if (!dcnt) {
				size = 0;
				$.each(files, function(h, f) { 
					var s = parseInt(f.size);
					
					if (s >= 0 && size >= 0) {
						size += s;
					} else {
						size = 'unknown';
					}
				});
				content.push(row.replace(l, msg.kind).replace(v, msg.files));
				content.push(row.replace(l, msg.size).replace(v, fm.formatSize(size)));
			} else {
				content.push(row.replace(l, msg.kind).replace(v, dcnt == cnt ? msg.folders : msg.folders+' '+dcnt+', '+msg.files+' '+(cnt-dcnt)))
				content.push(row.replace(l, msg.size).replace(v, tpl.spinner.replace('{text}', msg.calc)));
				count = $.map(files, function(f) { return f.hash });
				
			}
		}
		
		view = view.replace('{title}', title).replace('{content}', content.join(''));
		
		dialog = fm.dialog(view, opts);
		dialog.attr('id', id)

		// load thumbnail
		if (tmb) {
			$('<img/>')
				.load(function() { dialog.find('.elfinder-cwd-icon').css('background', 'url("'+tmb+'") center center no-repeat'); })
				.attr('src', tmb);
		}
		
		// send request to count total size
		if (count.length) {
			fm.request({
					data : {cmd : 'size', targets : count},
					preventDefault : true
				})
				.fail(function() {
					replSpinner(msg.unknown);
				})
				.done(function(data) {
					var size = parseInt(data.size);
					replSpinner(size >= 0 ? fm.formatSize(size) : msg.unknown);
				});
		}
		
	}
	
}
;"use strict";
/**
 * @class  elFinder command "mkdir"
 * Create new folder
 *
 * @author Dmitry (dio) Levashov
 **/
elFinder.prototype.commands.mkdir = function() {
	this.disableOnSearch = true;
	this.updateOnSelect  = false;
	this.mime            = 'directory';
	this.prefix          = 'untitled folder';
	this.exec            = $.proxy(this.fm.res('mixin', 'make'), this);
	
	this.shortcuts = [{
		pattern     : 'ctrl+shift+n'
	}];
	
	this.getstate = function() {
		return !this._disabled && this.fm.cwd().write ? 0 : -1;
	}

}
;"use strict";
/**
 * @class  elFinder command "mkfile"
 * Create new empty file
 *
 * @author Dmitry (dio) Levashov
 **/
elFinder.prototype.commands.mkfile = function() {
	this.disableOnSearch = true;
	this.updateOnSelect  = false;
	this.mime            = 'text/plain';
	this.prefix          = 'untitled file.txt';
	this.exec            = $.proxy(this.fm.res('mixin', 'make'), this);
	
	this.getstate = function() {
		return !this._disabled && this.fm.cwd().write ? 0 : -1;
	}

}
;"use strict"
/**
 * @class  elFinder command "netmount"
 * Mount network volume with user credentials.
 *
 * @author Dmitry (dio) Levashov
 **/
elFinder.prototype.commands.netmount = function() {
	var self = this;

	this.alwaysEnabled  = true;
	this.updateOnSelect = false;

	this.drivers = [];
	
	this.handlers = {
		load : function() {
			this.drivers = this.fm.netDrivers;
		}
	}

	this.getstate = function() {
		return this.drivers.length ? 0 : -1;
	}
	
	this.exec = function() {
		var fm = self.fm,
			dfrd = $.Deferred(),
			create = function() {
				var inputs = {
						protocol : $('<select/>'),
						host     : $('<input type="text"/>'),
						port     : $('<input type="text"/>'),
						path     : $('<input type="text" value="/"/>'),
						user     : $('<input type="text"/>'),
						pass     : $('<input type="password"/>')
					},
					opts = {
						title          : fm.i18n('netMountDialogTitle'),
						resizable      : false,
						modal          : true,
						destroyOnClose : true,
						close          : function() { 
							delete self.dialog; 
							dfrd.state() == 'pending' && dfrd.reject();
						},
						buttons        : {}
					},
					content = $('<table class="elfinder-info-tb elfinder-netmount-tb"/>');

				$.each(self.drivers, function(i, protocol) {
					inputs.protocol.append('<option value="'+protocol+'">'+fm.i18n(protocol)+'</option>');
				});


				$.each(inputs, function(name, input) {
					name != 'protocol' && input.addClass('ui-corner-all');
					content.append($('<tr/>').append($('<td>'+fm.i18n(name)+'</td>')).append($('<td/>').append(input)));
				});

				opts.buttons[fm.i18n('btnMount')] = function() {
					var data = {cmd : 'netmount'};

					$.each(inputs, function(name, input) {
						var val = $.trim(input.val());

						if (val) {
							data[name] = val;
						}
					});

					if (!data.host) {
						return self.fm.trigger('error', {error : 'errNetMountHostReq'});
					}

					self.fm.request({data : data, notify : {type : 'netmount', cnt : 1}})
						.done(function() { dfrd.resolve(); })
						.fail(function(error) { dfrd.reject(error); });

					self.dialog.elfinderdialog('close');	
				}

				opts.buttons[fm.i18n('btnCancel')] = function() {
					self.dialog.elfinderdialog('close');
				}

				return fm.dialog(content, opts);
			}
			;

		if (!self.dialog) {
			self.dialog = create()
		}

		return dfrd.promise();
	}

};"use strict"
/**
 * @class  elFinder command "open"
 * Enter folder or open files in new windows
 *
 * @author Dmitry (dio) Levashov
 **/  
elFinder.prototype.commands.open = function() {
	this.alwaysEnabled = true;
	
	this._handlers = {
		dblclick : function(e) { e.preventDefault(); this.exec() },
		'select enable disable reload' : function(e) { this.update(e.type == 'disable' ? -1 : void(0));  }
	}
	
	this.shortcuts = [{
		pattern     : 'ctrl+down numpad_enter'+(this.fm.OS != 'mac' && ' enter')
	}];

	this.getstate = function(sel) {
		var sel = this.files(sel),
			cnt = sel.length;
		
		return cnt == 1 
			? 0 
			: cnt ? ($.map(sel, function(file) { return file.mime == 'directory' ? null : file}).length == cnt ? 0 : -1) : -1
	}
	
	this.exec = function(hashes) {
		var fm    = this.fm, 
			dfrd  = $.Deferred().fail(function(error) { error && fm.error(error); }),
			files = this.files(hashes),
			cnt   = files.length,
			file, url, s, w;

		if (!cnt) {
			return dfrd.reject();
		}

		// open folder
		if (cnt == 1 && (file = files[0]) && file.mime == 'directory') {
			return file && !file.read
				? dfrd.reject(['errOpen', file.name, 'errPerm'])
				: fm.request({
						data   : {cmd  : 'open', target : file.thash || file.hash},
						notify : {type : 'open', cnt : 1, hideCnt : true},
						syncOnFail : true
					});
		}
		
		files = $.map(files, function(file) { return file.mime != 'directory' ? file : null });
		
		// nothing to open or files and folders selected - do nothing
		if (cnt != files.length) {
			return dfrd.reject();
		}
		
		// open files
		cnt = files.length;
		while (cnt--) {
			file = files[cnt];
			
			if (!file.read) {
				return dfrd.reject(['errOpen', file.name, 'errPerm']);
			}
			
			if (!(url = fm.url(/*file.thash || */file.hash))) {
				url = fm.options.url;
				url = url + (url.indexOf('?') === -1 ? '?' : '&')
					+ (fm.oldAPI ? 'cmd=open&current='+file.phash : 'cmd=file')
					+ '&target=' + file.hash;
			}
			
			// set window size for image if set
			if (file.dim) {
				s = file.dim.split('x');
				w = 'width='+(parseInt(s[0])+20) + ',height='+(parseInt(s[1])+20);
			} else {
				w = 'width='+parseInt(2*$(window).width()/3)+',height='+parseInt(2*$(window).height()/3);
			}

			var wnd = window.open('', 'new_window', w + ',top=50,left=50,scrollbars=yes,resizable=yes');
			if (!wnd) {
				return dfrd.reject('errPopup');
			}
			
			var form = document.createElement("form");
			form.action = fm.options.url;
			form.method = 'POST';
			form.target = 'new_window';
			form.style.display = 'none';
			var params = $.extend({}, fm.options.customData, {
				cmd: 'file',
				target: file.hash
			});
			$.each(params, function(key, val)
			{
				var input = document.createElement("input");
				input.name = key;
				input.value = val;
				form.appendChild(input);
			});
			
			document.body.appendChild(form);
			form.submit();
		}
		return dfrd.resolve(hashes);
	}

};"use strict";
/**
 * @class  elFinder command "paste"
 * Paste filesfrom clipboard into directory.
 * If files pasted in its parent directory - files duplicates will created
 *
 * @author Dmitry (dio) Levashov
 **/
elFinder.prototype.commands.paste = function() {
	
	this.updateOnSelect  = false;
	
	this.handlers = {
		changeclipboard : function() { this.update(); }
	}

	this.shortcuts = [{
		pattern     : 'ctrl+v shift+insert'
	}];
	
	this.getstate = function(dst) {
		if (this._disabled) {
			return -1;
		}
		if (dst) {
			if ($.isArray(dst)) {
				if (dst.length != 1) {
					return -1;
				}
				dst = this.fm.file(dst[0]);
			}
		} else {
			dst = this.fm.cwd();
		}

		return this.fm.clipboard().length && dst.mime == 'directory' && dst.write ? 0 : -1;
	}
	
	this.exec = function(dst) {
		var self   = this,
			fm     = self.fm,
			dst    = dst ? this.files(dst)[0] : fm.cwd(),
			files  = fm.clipboard(),
			cnt    = files.length,
			cut    = cnt ? files[0].cut : false,
			error  = cut ? 'errMove' : 'errCopy',
			fpaste = [],
			fcopy  = [],
			dfrd   = $.Deferred()
				.fail(function(error) {
					error && fm.error(error);
				}),
			copy  = function(files) {
				return files.length && fm._commands.duplicate
					? fm.exec('duplicate', files)
					: $.Deferred().resolve();
			},
			paste = function(files) {
				var dfrd      = $.Deferred(),
					existed   = [],
					intersect = function(files, names) {
						var ret = [], 
							i   = files.length;

						while (i--) {
							$.inArray(files[i].name, names) !== -1 && ret.unshift(i);
						}
						return ret;
					},
					confirm   = function(ndx) {
						var i    = existed[ndx],
							file = files[i],
							last = ndx == existed.length-1;

						if (!file) {
							return;
						}

						fm.confirm({
							title  : fm.i18n(cut ? 'moveFiles' : 'copyFiles'),
							text   : fm.i18n(['errExists', file.name, 'confirmRepl']), 
							all    : !last,
							accept : {
								label    : 'btnYes',
								callback : function(all) {
									!last && !all
										? confirm(++ndx)
										: paste(files);
								}
							},
							reject : {
								label    : 'btnNo',
								callback : function(all) {
									var i;

									if (all) {
										i = existed.length;
										while (ndx < i--) {
											files[existed[i]].remove = true
										}
									} else {
										files[existed[ndx]].remove = true;
									}

									!last && !all
										? confirm(++ndx)
										: paste(files);
								}
							},
							cancel : {
								label    : 'btnCancel',
								callback : function() {
									dfrd.resolve();
								}
							}
						})
					},
					valid     = function(names) {
						existed = intersect(files, names);
						existed.length ? confirm(0) : paste(files);
					},
					paste     = function(files) {
						var files  = $.map(files, function(file) { return !file.remove ? file : null } ),
							cnt    = files.length,
							groups = {},
							args   = [],
							src;

						if (!cnt) {
							return dfrd.resolve();
						}

						src = files[0].phash;
						files = $.map(files, function(f) { return f.hash});
						
						fm.request({
								data   : {cmd : 'paste', dst : dst.hash, targets : files, cut : cut ? 1 : 0, src : src},
								notify : {type : cut ? 'move' : 'copy', cnt : cnt}
							})
							.always(function() {
								dfrd.resolve();
								fm.unlockfiles({files : files});
							});
					}
					;

				if (self._disabled || !files.length) {
					return dfrd.resolve();
				}
				
					
				if (fm.oldAPI) {
					paste(files);
				} else {
					
					if (!fm.option('copyOverwrite')) {
						paste(files);
					} else {

						dst.hash == fm.cwd().hash
							? valid($.map(fm.files(), function(file) { return file.phash == dst.hash ? file.name : null }))
							: fm.request({
								data : {cmd : 'ls', target : dst.hash},
								notify : {type : 'prepare', cnt : 1, hideCnt : true},
								preventFail : true
							})
							.always(function(data) {
								valid(data.list || [])
							});
					}
				}
				
				return dfrd;
			},
			parents, fparents;


		if (!cnt || !dst || dst.mime != 'directory') {
			return dfrd.reject();
		}
			
		if (!dst.write)	{
			return dfrd.reject([error, files[0].name, 'errPerm']);
		}
		
		parents = fm.parents(dst.hash);
		
		$.each(files, function(i, file) {
			if (!file.read) {
				return !dfrd.reject([error, files[0].name, 'errPerm']);
			}
			
			if (cut && file.locked) {
				return !dfrd.reject(['errLocked', file.name]);
			}
			
			if ($.inArray(file.hash, parents) !== -1) {
				return !dfrd.reject(['errCopyInItself', file.name]);
			}
			
			fparents = fm.parents(file.hash);
			fparents.pop();
			if ($.inArray(dst.hash, fparents) !== -1) {
				
				if ($.map(fparents, function(h) { var d = fm.file(h); return d.phash == dst.hash && d.name == file.name ? d : null }).length) {
					return !dfrd.reject(['errReplByChild', file.name]);
				}
			}
			
			if (file.phash == dst.hash) {
				fcopy.push(file.hash);
			} else {
				fpaste.push({
					hash  : file.hash,
					phash : file.phash,
					name  : file.name
				});
			}
		});

		if (dfrd.state() == 'rejected') {
			return dfrd;
		}

		return $.when(
			copy(fcopy),
			paste(fpaste)
		).always(function() {
			cut && fm.clipboard([]);
		});
	}

};"use strict"
/**
 * @class  elFinder command "quicklook"
 * Fast preview for some files types
 *
 * @author Dmitry (dio) Levashov
 **/
elFinder.prototype.commands.quicklook = function() {
	var self       = this,
		fm         = self.fm,
		/**
		 * window closed state
		 *
		 * @type Number
		 **/
		closed     = 0,
		/**
		 * window animated state
		 *
		 * @type Number
		 **/
		animated   = 1,
		/**
		 * window opened state
		 *
		 * @type Number
		 **/
		opened     = 2,
		/**
		 * window state
		 *
		 * @type Number
		 **/
		state      = closed,
		/**
		 * next/prev event name (requied to cwd catch it)
		 *
		 * @type Number
		 **/
		// keydown    = fm.UA.Firefox || fm.UA.Opera ? 'keypress' : 'keydown',
		/**
		 * navbar icon class
		 *
		 * @type Number
		 **/
		navicon    = 'elfinder-quicklook-navbar-icon',
		/**
		 * navbar "fullscreen" icon class
		 *
		 * @type Number
		 **/
		fullscreen  = 'elfinder-quicklook-fullscreen',
		/**
		 * Triger keydown/keypress event with left/right arrow key code
		 *
		 * @param  Number  left/right arrow key code
		 * @return void
		 **/
		navtrigger = function(code) {
			$(document).trigger($.Event('keydown', { keyCode: code, ctrlKey : false, shiftKey : false, altKey : false, metaKey : false }));
		},
		/**
		 * Return css for closed window
		 *
		 * @param  jQuery  file node in cwd
		 * @return void
		 **/
		closedCss = function(node) {
			return {
				opacity : 0,
				width   : 20,//node.width(),
				height  : fm.view == 'list' ? 1 : 20,
				top     : node.offset().top+'px', 
				left    : node.offset().left+'px' 
			}
		},
		/**
		 * Return css for opened window
		 *
		 * @return void
		 **/
		openedCss = function() {
			var win = $(window);
			return {
				opacity : 1,
				width  : width,
				height : height,
				top    : parseInt((win.height() - height)/2 + win.scrollTop()),
				left   : parseInt((win.width() - width)/2 + win.scrollLeft())
			}
		},
		
		support = function(codec) {
			var media = document.createElement(codec.substr(0, codec.indexOf('/'))),
				value = false;
			
			try {
				value = media.canPlayType && media.canPlayType(codec);
			} catch (e) {
				
			}
			
			return value && value !== '' && value != 'no';
		},
		
		/**
		 * Opened window width (from config)
		 *
		 * @type Number
		 **/
		width, 
		/**
		 * Opened window height (from config)
		 *
		 * @type Number
		 **/
		height, 
		/**
		 * elFinder node
		 *
		 * @type jQuery
		 **/
		parent, 
		/**
		 * elFinder current directory node
		 *
		 * @type jQuery
		 **/
		cwd, 
		title   = $('<div class="elfinder-quicklook-title"/>'),
		icon    = $('<div/>'),
		info    = $('<div class="elfinder-quicklook-info"/>'),//.hide(),
		fsicon  = $('<div class="'+navicon+' '+navicon+'-fullscreen"/>')
			.mousedown(function(e) {
				var win     = self.window,
					full    = win.is('.'+fullscreen),
					scroll  = 'scroll.'+fm.namespace,
					$window = $(window);
					
				e.stopPropagation();
				
				if (full) {
					win.css(win.data('position')).unbind('mousemove');
					$window.unbind(scroll).trigger(self.resize).unbind(self.resize);
					navbar.unbind('mouseenter').unbind('mousemove');
				} else {
					win.data('position', {
						left   : win.css('left'), 
						top    : win.css('top'), 
						width  : win.width(), 
						height : win.height()
					})
					.css({
						width  : '100%',
						height : '100%'
					});

					$(window).bind(scroll, function() {
						win.css({
							left   : parseInt($(window).scrollLeft())+'px',
							top    : parseInt($(window).scrollTop()) +'px'
						})
					})
					.bind(self.resize, function(e) {
						self.preview.trigger('changesize');
					})
					.trigger(scroll)
					.trigger(self.resize);
					
					win.bind('mousemove', function(e) {
						navbar.stop(true, true).show().delay(3000).fadeOut('slow');
					})
					.mousemove();
					
					navbar.mouseenter(function() {
						navbar.stop(true, true).show();
					})
					.mousemove(function(e) {
						e.stopPropagation();
					});
				}
				navbar.attr('style', '').draggable(full ? 'destroy' : {});
				win.toggleClass(fullscreen);
				$(this).toggleClass(navicon+'-fullscreen-off');
				$.fn.resizable && parent.add(win).resizable(full ? 'enable' : 'disable').removeClass('ui-state-disabled');
			}),
			
		navbar  = $('<div class="elfinder-quicklook-navbar"/>')
			.append($('<div class="'+navicon+' '+navicon+'-prev"/>').mousedown(function() { navtrigger(37); }))
			.append(fsicon)
			.append($('<div class="'+navicon+' '+navicon+'-next"/>').mousedown(function() { navtrigger(39); }))
			.append('<div class="elfinder-quicklook-navbar-separator"/>')
			.append($('<div class="'+navicon+' '+navicon+'-close"/>').mousedown(function() { self.window.trigger('close'); }))
		;

	this.resize = 'resize.'+fm.namespace;
	this.info = $('<div class="elfinder-quicklook-info-wrapper"/>')
		.append(icon)
		.append(info);
		
	this.preview = $('<div class="elfinder-quicklook-preview ui-helper-clearfix"/>')
		// clean info/icon
		.bind('change', function(e) {
			self.info.attr('style', '').hide();
			icon.removeAttr('class').attr('style', '');
			info.html('');

		})
		// update info/icon
		.bind('update', function(e) {
			var fm      = self.fm,
				preview = self.preview,
				file    = e.file,
				tpl     = '<div class="elfinder-quicklook-info-data">{value}</div>',
				tmb;

			if (file) {
				!file.read && e.stopImmediatePropagation();
				self.window.data('hash', file.hash);
				self.preview.unbind('changesize').trigger('change').children().remove();
				title.html(fm.escape(file.name));
				
				info.html(
						tpl.replace(/\{value\}/, file.name)
						+ tpl.replace(/\{value\}/, fm.mime2kind(file))
						+ (file.mime == 'directory' ? '' : tpl.replace(/\{value\}/, fm.formatSize(file.size)))
						+ tpl.replace(/\{value\}/, fm.i18n('modify')+': '+ fm.formatDate(file))
					)
				icon.addClass('elfinder-cwd-icon ui-corner-all '+fm.mime2class(file.mime));

				if (file.tmb) {
					$('<img/>')
						.hide()
						.appendTo(self.preview)
						.load(function() {
							icon.css('background', 'url("'+tmb+'") center center no-repeat');
							$(this).remove();
						})
						.attr('src', (tmb = fm.tmb(file.hash)));
				}
				self.info.delay(100).fadeIn(10);
			} else { 
				e.stopImmediatePropagation();
			}
		});
		

	

	this.window = $('<div class="ui-helper-reset ui-widget elfinder-quicklook" style="position:absolute"/>')
		.click(function(e) { e.stopPropagation();  })
		.append(
			$('<div class="elfinder-quicklook-titlebar"/>')
				.append(title)
				.append($('<span class="ui-icon ui-icon-circle-close"/>').mousedown(function(e) {
					e.stopPropagation();
					self.window.trigger('close');
				}))
		)
		.append(this.preview.add(navbar))
		.append(self.info.hide())
		.draggable({handle : 'div.elfinder-quicklook-titlebar'})
		.bind('open', function(e) {
			var win  = self.window, 
				file = self.value,
				node;

			if (self.closed() && file && (node = cwd.find('#'+file.hash)).length) {
				navbar.attr('style', '');
				state = animated;
				node.trigger('scrolltoview');
				win.css(closedCss(node))
					.show()
					.animate(openedCss(), 550, function() {
						state = opened;
						self.update(1, self.value);
					});
			}
		})
		.bind('close', function(e) {
			var win     = self.window,
				preview = self.preview.trigger('change'),
				file    = self.value,
				node    = cwd.find('#'+win.data('hash')),
				close   = function() {
					state = closed;
					win.hide();
					preview.children().remove();
					self.update(0, self.value);
					
				};
				
			if (self.opened()) {
				state = animated;
				win.is('.'+fullscreen) && fsicon.mousedown()
				node.length
					? win.animate(closedCss(node), 500, close)
					: close();
			}
		});

	/**
	 * This command cannot be disable by backend
	 *
	 * @type Boolean
	 **/
	this.alwaysEnabled = true;
	
	/**
	 * Selected file
	 *
	 * @type Object
	 **/
	this.value = null;
	
	this.handlers = {
		// save selected file
		select : function() { this.update(void(0), this.fm.selectedFiles()[0]); },
		error  : function() { self.window.is(':visible') && self.window.data('hash', '').trigger('close'); },
		'searchshow searchhide' : function() { this.opened() && this.window.trigger('close'); }
	}
	
	this.shortcuts = [{
		pattern     : 'space'
	}];
	
	this.support = {
		audio : {
			ogg : support('audio/ogg; codecs="vorbis"'),
			mp3 : support('audio/mpeg;'),
			wav : support('audio/wav; codecs="1"'),
			m4a : support('audio/x-m4a;') || support('audio/aac;')
		},
		video : {
			ogg  : support('video/ogg; codecs="theora"'),
			webm : support('video/webm; codecs="vp8, vorbis"'),
			mp4  : support('video/mp4; codecs="avc1.42E01E"') || support('video/mp4; codecs="avc1.42E01E, mp4a.40.2"') 
		}
	}
	
	
	/**
	 * Return true if quickLoock window is visible and not animated
	 *
	 * @return Boolean
	 **/
	this.closed = function() {
		return state == closed;
	}
	
	/**
	 * Return true if quickLoock window is hidden
	 *
	 * @return Boolean
	 **/
	this.opened = function() {
		return state == opened;
	}
	
	/**
	 * Init command.
	 * Add default plugins and init other plugins
	 *
	 * @return Object
	 **/
	this.init = function() {
		var o       = this.options, 
			win     = this.window,
			preview = this.preview,
			i, p;
		
		width  = o.width  > 0 ? parseInt(o.width)  : 450;	
		height = o.height > 0 ? parseInt(o.height) : 300;

		fm.one('load', function() {
			parent = fm.getUI();
			cwd    = fm.getUI('cwd');

			win.appendTo('body').zIndex(100 + parent.zIndex());
			
			// close window on escape
			$(document).keydown(function(e) {
				e.keyCode == 27 && self.opened() && win.trigger('close')
			})
			
			if ($.fn.resizable) {
				win.resizable({ 
					handles   : 'se', 
					minWidth  : 350, 
					minHeight : 120, 
					resize    : function() { 
						// use another event to avoid recursion in fullscreen mode
						// may be there is clever solution, but i cant find it :(
						preview.trigger('changesize'); 
					}
				});
			}
			
			self.change(function() {
				if (self.opened()) {
					self.value ? preview.trigger($.Event('update', {file : self.value})) : win.trigger('close');
				}
			});
			
			$.each(fm.commands.quicklook.plugins || [], function(i, plugin) {
				if (typeof(plugin) == 'function') {
					new plugin(self)
				}
			});
			
			preview.bind('update', function() {
				self.info.show();
			});
		});
		
	}
	
	this.getstate = function() {
		return this.fm.selected().length == 1 ? state == opened ? 1 : 0 : -1;
	}
	
	this.exec = function() {
		this.enabled() && this.window.trigger(this.opened() ? 'close' : 'open');
	}

	this.hideinfo = function() {
		this.info.stop(true).hide();
	}

}

;
elFinder.prototype.commands.quicklook.plugins = [
	
	/**
	 * Images preview plugin
	 *
	 * @param elFinder.commands.quicklook
	 **/
	function(ql) {
		var mimes   = ['image/jpeg', 'image/png', 'image/gif'],
			preview = ql.preview;
		
		// what kind of images we can display
		$.each(navigator.mimeTypes, function(i, o) {
			var mime = o.type;
			
			if (mime.indexOf('image/') === 0 && $.inArray(mime, mimes)) {
				mimes.push(mime);
			} 
		});
			
		preview.bind('update', function(e) {
			var file = e.file,
				img;

			if ($.inArray(file.mime, mimes) !== -1) {
				// this is our file - stop event propagation
				e.stopImmediatePropagation();

				img = $('<img/>')
					.hide()
					.appendTo(preview)
					.load(function() {
						// timeout - because of strange safari bug - 
						// sometimes cant get image height 0_o
						setTimeout(function() {
							var prop = (img.width()/img.height()).toFixed(2);
							preview.bind('changesize', function() {
								var pw = parseInt(preview.width()),
									ph = parseInt(preview.height()),
									w, h;
							
								if (prop < (pw/ph).toFixed(2)) {
									h = ph;
									w = Math.floor(h * prop);
								} else {
									w = pw;
									h = Math.floor(w/prop);
								}
								img.width(w).height(h).css('margin-top', h < ph ? Math.floor((ph - h)/2) : 0);
							
							})
							.trigger('changesize');
							
							// hide info/icon
							ql.hideinfo();
							//show image
							img.fadeIn(100);
						}, 1)
					})
					.attr('src', ql.fm.url(file.hash));
			}
			
		});
	},
	
	/**
	 * HTML preview plugin
	 *
	 * @param elFinder.commands.quicklook
	 **/
	function(ql) {
		var mimes   = ['text/html', 'application/xhtml+xml'],
			preview = ql.preview,
			fm      = ql.fm;
			
		preview.bind('update', function(e) {
			var file = e.file, jqxhr;
			
			if ($.inArray(file.mime, mimes) !== -1) {
				e.stopImmediatePropagation();

				// stop loading on change file if not loaded yet
				preview.one('change', function() {
					jqxhr.state() == 'pending' && jqxhr.reject();
				});
				
				jqxhr = fm.request({
					data           : {cmd : 'get', target  : file.hash, current : file.phash},
					preventDefault : true
				})
				.done(function(data) {
					ql.hideinfo();
					doc = $('<iframe class="elfinder-quicklook-preview-html"/>').appendTo(preview)[0].contentWindow.document;
					doc.open();
					doc.write(data.content);
					doc.close();
				});
			}
		})
	},
	
	/**
	 * Texts preview plugin
	 *
	 * @param elFinder.commands.quicklook
	 **/
	function(ql) {
		var fm      = ql.fm,
			mimes   = fm.res('mimes', 'text'),
			preview = ql.preview;
				
			
		preview.bind('update', function(e) {
			var file = e.file,
				mime = file.mime,
				jqxhr;
			
			if (mime.indexOf('text/') === 0 || $.inArray(mime, mimes) !== -1) {
				e.stopImmediatePropagation();
				
				// stop loading on change file if not loadin yet
				preview.one('change', function() {
					jqxhr.state() == 'pending' && jqxhr.reject();
				});
				
				jqxhr = fm.request({
					data   : {cmd     : 'get', target  : file.hash },
					preventDefault : true
				})
				.done(function(data) {
					ql.hideinfo();
					$('<div class="elfinder-quicklook-preview-text-wrapper"><pre class="elfinder-quicklook-preview-text">'+fm.escape(data.content)+'</pre></div>').appendTo(preview);
				});
			}
		});
	},
	
	/**
	 * PDF preview plugin
	 *
	 * @param elFinder.commands.quicklook
	 **/
	function(ql) {
		var fm      = ql.fm,
			mime    = 'application/pdf',
			preview = ql.preview,
			active  = false;
			
		if ((fm.UA.Safari && fm.OS == 'mac') || fm.UA.IE) {
			active = true;
		} else {
			$.each(navigator.plugins, function(i, plugins) {
				$.each(plugins, function(i, plugin) {
					if (plugin.type == mime) {
						return !(active = true);
					}
				});
			});
		}

		active && preview.bind('update', function(e) {
			var file = e.file, node;
			
			if (file.mime == mime) {
				e.stopImmediatePropagation();
				preview.one('change', function() {
					node.unbind('load').remove();
				});
				
				node = $('<iframe class="elfinder-quicklook-preview-pdf"/>')
					.hide()
					.appendTo(preview)
					.load(function() { 
						ql.hideinfo();
						node.show(); 
					})
					.attr('src', fm.url(file.hash));
			}
			
		})
		
			
	},
	
	/**
	 * Flash preview plugin
	 *
	 * @param elFinder.commands.quicklook
	 **/
	function(ql) {
		var fm      = ql.fm,
			mime    = 'application/x-shockwave-flash',
			preview = ql.preview,
			active  = false;

		$.each(navigator.plugins, function(i, plugins) {
			$.each(plugins, function(i, plugin) {
				if (plugin.type == mime) {
					return !(active = true);
				}
			});
		});
		
		active && preview.bind('update', function(e) {
			var file = e.file,
				node;
				
			if (file.mime == mime) {
				e.stopImmediatePropagation();
				ql.hideinfo();
				preview.append((node = $('<embed class="elfinder-quicklook-preview-flash" pluginspage="http://www.macromedia.com/go/getflashplayer" src="'+fm.url(file.hash)+'" quality="high" type="application/x-shockwave-flash" />')));
			}
		});
	},
	
	/**
	 * HTML5 audio preview plugin
	 *
	 * @param elFinder.commands.quicklook
	 **/
	function(ql) {
		var preview  = ql.preview,
			autoplay = !!ql.options['autoplay'],
			mimes    = {
				'audio/mpeg'    : 'mp3',
				'audio/mpeg3'   : 'mp3',
				'audio/mp3'     : 'mp3',
				'audio/x-mpeg3' : 'mp3',
				'audio/x-mp3'   : 'mp3',
				'audio/x-wav'   : 'wav',
				'audio/wav'     : 'wav',
				'audio/x-m4a'   : 'm4a',
				'audio/aac'     : 'm4a',
				'audio/mp4'     : 'm4a',
				'audio/x-mp4'   : 'm4a',
				'audio/ogg'     : 'ogg'
			},
			node;

		preview.bind('update', function(e) {
			var file = e.file,
				type = mimes[file.mime];

			if (ql.support.audio[type]) {
				e.stopImmediatePropagation();
				
				node = $('<audio class="elfinder-quicklook-preview-audio" controls preload="auto" autobuffer><source src="'+ql.fm.url(file.hash)+'" /></audio>')
					.appendTo(preview);
				autoplay && node[0].play();
			}
		}).bind('change', function() {
			if (node && node.parent().length) {
				node[0].pause();
				node.remove();
				node= null;
			}
		});
	},
	
	/**
	 * HTML5 video preview plugin
	 *
	 * @param elFinder.commands.quicklook
	 **/
	function(ql) {
		var preview  = ql.preview,
			autoplay = !!ql.options['autoplay'],
			mimes    = {
				'video/mp4'       : 'mp4',
				'video/x-m4v'     : 'mp4',
				'video/ogg'       : 'ogg',
				'application/ogg' : 'ogg',
				'video/webm'      : 'webm'
			},
			node;

		preview.bind('update', function(e) {
			var file = e.file,
				type = mimes[file.mime];
				
			if (ql.support.video[type]) {
				e.stopImmediatePropagation();

				ql.hideinfo();
				node = $('<video class="elfinder-quicklook-preview-video" controls preload="auto" autobuffer><source src="'+ql.fm.url(file.hash)+'" /></video>').appendTo(preview);
				autoplay && node[0].play();
				
			}
		}).bind('change', function() {
			if (node && node.parent().length) {
				node[0].pause();
				node.remove();
				node= null;
			}
		});
	},
	
	/**
	 * Audio/video preview plugin using browser plugins
	 *
	 * @param elFinder.commands.quicklook
	 **/
	function(ql) {
		var preview = ql.preview,
			mimes   = [],
			node;
			
		$.each(navigator.plugins, function(i, plugins) {
			$.each(plugins, function(i, plugin) {
				(plugin.type.indexOf('audio/') === 0 || plugin.type.indexOf('video/') === 0) && mimes.push(plugin.type);
			});
		});
		
		preview.bind('update', function(e) {
			var file  = e.file,
				mime  = file.mime,
				video;
			
			if ($.inArray(file.mime, mimes) !== -1) {
				e.stopImmediatePropagation();
				(video = mime.indexOf('video/') === 0) && ql.hideinfo();
				node = $('<embed src="'+ql.fm.url(file.hash)+'" type="'+mime+'" class="elfinder-quicklook-preview-'+(video ? 'video' : 'audio')+'"/>')
					.appendTo(preview);
			}
		}).bind('change', function() {
			if (node && node.parent().length) {
				node.remove();
				node= null;
			}
		});
		
	}
	
];/**
 * @class  elFinder command "reload"
 * Sync files and folders
 *
 * @author Dmitry (dio) Levashov
 **/
elFinder.prototype.commands.reload = function() {
	
	this.alwaysEnabled = true;
	this.updateOnSelect = true;
	
	this.shortcuts = [{
		pattern     : 'ctrl+shift+r f5'
	}];
	
	this.getstate = function() {
		return 0;
	}
	
	this.exec = function() {
		var fm      = this.fm,
			dfrd    = fm.sync(),
			timeout = setTimeout(function() {
				fm.notify({type : 'reload', cnt : 1, hideCnt : true});
				dfrd.always(function() { fm.notify({type : 'reload', cnt  : -1}); });
			}, fm.notifyDelay);
			
		return dfrd.always(function() { 
			clearTimeout(timeout); 
			fm.trigger('reload');
		});
	}

};"use strict";
/**
 * @class elFinder command "rename". 
 * Rename selected file.
 *
 * @author Dmitry (dio) Levashov, dio@std42.ru
 **/
elFinder.prototype.commands.rename = function() {
	
	this.shortcuts = [{
		pattern     : 'f2'+(this.fm.OS == 'mac' ? ' enter' : '')
	}];
	
	this.getstate = function() {
		var sel = this.fm.selectedFiles();

		return !this._disabled && sel.length == 1 && sel[0].phash && !sel[0].locked  ? 0 : -1;
	}
	
	this.exec = function() {
		var fm       = this.fm,
			cwd      = fm.getUI('cwd'),
			sel      = fm.selected(),
			cnt      = sel.length,
			file     = fm.file(sel.shift()),
			filename = '.elfinder-cwd-filename',
			dfrd     = $.Deferred()
				.fail(function(error) {
					var parent = input.parent(),
						name   = fm.escape(file.name);

					
					if (parent.length) {
						input.remove();
						parent.html(name);
					} else {
						cwd.find('#'+file.hash).find(filename).html(name);
						setTimeout(function() {
							cwd.find('#'+file.hash).click();
						}, 50);
					}
					
					error && fm.error(error);
				})
				.always(function() {
					fm.enable();
				}),
			input = $('<input type="text"/>')
				.keydown(function(e) {
					e.stopPropagation();
					e.stopImmediatePropagation();
					if (e.keyCode == $.ui.keyCode.ESCAPE) {
						dfrd.reject();
					} else if (e.keyCode == $.ui.keyCode.ENTER) {
						input.blur();
					}
				})
				.mousedown(function(e) {
					e.stopPropagation();
				})
				.dblclick(function(e) {
					e.stopPropagation();
					e.preventDefault();
				})
				.blur(function() {
					var name   = $.trim(input.val()),
						parent = input.parent();

					if (parent.length) {
						if (input[0].setSelectionRange) {
							input[0].setSelectionRange(0, 0)
						}
						if (name == file.name) {
							return dfrd.reject();
						}
						if (!name) {
							return dfrd.reject('errInvName');
						}
						if (fm.fileByName(name, file.phash)) {
							return dfrd.reject(['errExists', name]);
						}
						
						parent.html(fm.escape(name));
						fm.lockfiles({files : [file.hash]});
						fm.request({
								data   : {cmd : 'rename', target : file.hash, name : name},
								notify : {type : 'rename', cnt : 1}
							})
							.fail(function(error) {
								dfrd.reject();
								fm.sync();
							})
							.done(function(data) {
								dfrd.resolve(data);
							})
							.always(function() {
								fm.unlockfiles({files : [file.hash]})
							});
						
					}
				}),
			node = cwd.find('#'+file.hash).find(filename).empty().append(input.val(file.name)),
			name = input.val().replace(/\.((tar\.(gz|bz|bz2|z|lzo))|cpio\.gz|ps\.gz|xcf\.(gz|bz2)|[a-z0-9]{1,4})$/ig, '')
			;
		
		if (this.disabled()) {
			return dfrd.reject();
		}
		
		if (!file || cnt > 1 || !node.length) {
			return dfrd.reject('errCmdParams', this.title);
		}
		
		if (file.locked) {
			return dfrd.reject(['errLocked', file.name]);
		}
		
		fm.one('select', function() {
			input.parent().length && file && $.inArray(file.hash, fm.selected()) === -1 && input.blur();
		})
		
		input.select().focus();
		
		input[0].setSelectionRange && input[0].setSelectionRange(0, name.length);
		
		return dfrd;
	}

}
;"use strict";
/**
 * @class  elFinder command "resize"
 * Open dialog to resize image
 *
 * @author Dmitry (dio) Levashov
 * @author Alexey Sukhotin
 * @author Naoki Sawada
 * @author Sergio Jovani
 **/
elFinder.prototype.commands.resize = function() {

	this.updateOnSelect = false;
	
	this.getstate = function() {
		var sel = this.fm.selectedFiles();
		return !this._disabled && sel.length == 1 && sel[0].read && sel[0].write && sel[0].mime.indexOf('image/') !== -1 ? 0 : -1;
	};
	
	this.exec = function(hashes) {
		var fm    = this.fm,
			files = this.files(hashes),
			dfrd  = $.Deferred(),
			
			open = function(file, id) {
				var dialog   = $('<div class="elfinder-dialog-resize"/>'),
					input    = '<input type="text" size="5"/>',
					row      = '<div class="elfinder-resize-row"/>',
					label    = '<div class="elfinder-resize-label"/>',
					control  = $('<div class="elfinder-resize-control"/>'),
					preview  = $('<div class="elfinder-resize-preview"/>'),
					spinner  = $('<div class="elfinder-resize-spinner">'+fm.i18n('ntfloadimg')+'</div>'),
					rhandle  = $('<div class="elfinder-resize-handle"/>'),
					rhandlec = $('<div class="elfinder-resize-handle"/>'),
					uiresize = $('<div class="elfinder-resize-uiresize"/>'),
					uicrop   = $('<div class="elfinder-resize-uicrop"/>'),
					uibuttonset = '<div class="ui-widget-content ui-corner-all elfinder-buttonset"/>',
					uibutton    = '<div class="ui-state-default elfinder-button"/>',
					uiseparator = '<span class="ui-widget-content elfinder-toolbar-button-separator"/>',
					uirotate    = $('<div class="elfinder-resize-rotate"/>'),
					uideg270    = $(uibutton).attr('title',fm.i18n('rotate-cw')).append($('<span class="elfinder-button-icon elfinder-button-icon-rotate-l"/>')
						.click(function(){
							rdegree = rdegree - 90;
							rotate.update(rdegree);
						})),
					uideg90     = $(uibutton).attr('title',fm.i18n('rotate-ccw')).append($('<span class="elfinder-button-icon elfinder-button-icon-rotate-r"/>')
						.click(function(){
							rdegree = rdegree + 90;
							rotate.update(rdegree);
						})),
					uiprop   = $('<span />'),
					reset    = $('<div class="ui-state-default ui-corner-all elfinder-resize-reset"><span class="ui-icon ui-icon-arrowreturnthick-1-w"/></div>'),
					uitype   = $('<div class="elfinder-resize-type"/>')
						.append('<input type="radio" name="type" id="'+id+'-resize" value="resize" checked="checked" /><label for="'+id+'-resize">'+fm.i18n('resize')+'</label>')
						.append('<input type="radio" name="type" id="'+id+'-crop" value="crop" /><label for="'+id+'-crop">'+fm.i18n('crop')+'</label>')
						.append('<input type="radio" name="type" id="'+id+'-rotate" value="rotate" /><label for="'+id+'-rotate">'+fm.i18n('rotate')+'</label>'),
					type     = $('input', uitype).attr('disabled', 'disabled')
						.change(function() {
							var val = $('input:checked', uitype).val();
							
							resetView();
							resizable(true);
							croppable(true);
							rotateable(true);
							
							if (val == 'resize') {
								uiresize.show();
								uirotate.hide();
								uicrop.hide();
								resizable();
							}
							else if (val == 'crop') {
								uirotate.hide();
								uiresize.hide();
								uicrop.show();
								croppable();
							} else if (val == 'rotate') {
								uiresize.hide();
								uicrop.hide();
								uirotate.show();
								rotateable();
							}
						}),
					constr  = $('<input type="checkbox" checked="checked"/>')
						.change(function() {
							cratio = !!constr.prop('checked');
							resize.fixHeight();
							resizable(true);
							resizable();
						}),
					width   = $(input)
						.change(function() {
							var w = parseInt(width.val()),
								h = parseInt(cratio ? Math.round(w/ratio) : height.val());

							if (w > 0 && h > 0) {
								resize.updateView(w, h);
								height.val(h);
							}
						}),
					height  = $(input)
						.change(function() {
							var h = parseInt(height.val()),
								w = parseInt(cratio ? Math.round(h*ratio) : width.val());

							if (w > 0 && h > 0) {
								resize.updateView(w, h);
								width.val(w);
							}
						}),
					pointX  = $(input).change(function(){crop.updateView();}),
					pointY  = $(input).change(function(){crop.updateView();}),
					offsetX = $(input).change(function(){crop.updateView();}),
					offsetY = $(input).change(function(){crop.updateView();}),
					degree = $('<input type="text" size="3" maxlength="3" value="0" />')
						.change(function() {
							rotate.update();
						}),
					uidegslider = $('<div class="elfinder-resize-rotate-slider"/>')
						.slider({
							min: 0,
							max: 359,
							value: degree.val(),
							animate: true,
							change: function(event, ui) {
								if (ui.value != uidegslider.slider('value')) {
									rotate.update(ui.value);
								}
							},
							slide: function(event, ui) {
								rotate.update(ui.value, false);
							}
						}),
					ratio   = 1,
					prop    = 1,
					owidth  = 0,
					oheight = 0,
					cratio  = true,
					pwidth  = 0,
					pheight = 0,
					rwidth  = 0,
					rheight = 0,
					rdegree = 0,
					img     = $('<img/>')
						.load(function() {
							spinner.remove();
							
							owidth  = img.width();
							oheight = img.height();
							ratio   = owidth/oheight;
							resize.updateView(owidth, oheight);

							rhandle.append(img.show()).show();
							width.val(owidth);
							height.val(oheight);
							
							var r_scale = Math.min(pwidth, pheight) / Math.sqrt(Math.pow(owidth, 2) + Math.pow(oheight, 2));
							rwidth = owidth * r_scale;
							rheight = oheight * r_scale;
							
							type.button('enable');
							control.find('input,select').removeAttr('disabled')
								.filter(':text').keydown(function(e) {
									var c = e.keyCode, i;

									e.stopPropagation();
								
									if ((c >= 37 && c <= 40) 
									|| c == $.ui.keyCode.BACKSPACE 
									|| c == $.ui.keyCode.DELETE 
									|| (c == 65 && (e.ctrlKey||e.metaKey))
									|| c == 27) {
										return;
									}
								
									if (c == 9) {
										i = $(this).parent()[e.shiftKey ? 'prev' : 'next']('.elfinder-resize-row').children(':text');

										if (i.length) {
											i.focus();
										} else {
											$(this).parent().parent().find(':text:' + (e.shiftKey ? 'last' : 'first')).focus();
										}
									}
								
									if (c == 13) {
										fm.confirm({
											title  : $('input:checked', uitype).val(),
											text   : 'confirmReq',
											accept : {
												label    : 'btnApply',
												callback : function() {  
													save();
												}
											},
											cancel : {
												label    : 'btnCancel',
												callback : function(){}
											}
										});
										return;
									}
								
									if (!((c >= 48 && c <= 57) || (c >= 96 && c <= 105))) {
										e.preventDefault();
									}
								})
								.filter(':first').focus();
								
							resizable();
							
							reset.hover(function() { reset.toggleClass('ui-state-hover'); }).click(resetView);
							
						})
						.error(function() {
							spinner.text('Unable to load image').css('background', 'transparent');
						}),
					basec = $('<div/>'),
					imgc = $('<img/>'),
					coverc = $('<div/>'),
					imgr = $('<img/>'),
					resetView = function() {
						width.val(owidth);
						height.val(oheight);
						resize.updateView(owidth, oheight);
					},
					resize = {
						update : function() {
							width.val(Math.round(img.width()/prop));
							height.val(Math.round(img.height()/prop));
						},
						
						updateView : function(w, h) {
							if (w > pwidth || h > pheight) {
								if (w / pwidth > h / pheight) {
									prop = pwidth / w;
									img.width(pwidth).height(Math.ceil(h*prop));
								} else {
									prop = pheight / h;
									img.height(pheight).width(Math.ceil(w*prop));
								}
							} else {
								img.width(w).height(h);
							}
							
							prop = img.width()/w;
							uiprop.text('1 : '+(1/prop).toFixed(2));
							resize.updateHandle();
						},
						
						updateHandle : function() {
							rhandle.width(img.width()).height(img.height());
						},
						fixWidth : function() {
							var w, h;
							if (cratio) {
								h = height.val();
								h = Math.round(h*ratio);
								resize.updateView(w, h);
								width.val(w);
							}
						},
						fixHeight : function() {
							var w, h;
							if (cratio) {
								w = width.val();
								h = Math.round(w/ratio);
								resize.updateView(w, h);
								height.val(h);
							}
						}
					},
					crop = {
						update : function() {
							offsetX.val(Math.round((rhandlec.data('w')||rhandlec.width())/prop));
							offsetY.val(Math.round((rhandlec.data('h')||rhandlec.height())/prop));
							pointX.val(Math.round(((rhandlec.data('x')||rhandlec.offset().left)-imgc.offset().left)/prop));
							pointY.val(Math.round(((rhandlec.data('y')||rhandlec.offset().top)-imgc.offset().top)/prop));
						},
						updateView : function() {
							var x = parseInt(pointX.val()) * prop + imgc.offset().left;
							var y = parseInt(pointY.val()) * prop + imgc.offset().top;
							var w = offsetX.val() * prop;
							var h = offsetY.val() * prop;
							rhandlec.data({x: x, y: y, w: w, h: h});
							rhandlec.width(Math.round(w));
							rhandlec.height(Math.round(h));
							coverc.width(rhandlec.width());
							coverc.height(rhandlec.height());
							rhandlec.offset({left: Math.round(x), top: Math.round(y)});
						},
						resize_update : function() {
							rhandlec.data({w: null, h: null});
							crop.update();
							coverc.width(rhandlec.width());
							coverc.height(rhandlec.height());
						},
						drag_update : function() {
							rhandlec.data({x: null, y: null});
							crop.update();
						}
					},
					rotate = {
						mouseStartAngle : 0,
						imageStartAngle : 0,
						imageBeingRotated : false,
							
						update : function(value, animate) {
							if (typeof value == 'undefined') {
								rdegree = value = parseInt(degree.val());
							}
							if (typeof animate == 'undefined') {
								animate = true;
							}
							if (! animate || fm.UA.Opera || fm.UA.ltIE8) {
								imgr.rotate(value);
							} else {
								imgr.animate({rotate: value + 'deg'});
							}
							value = value % 360;
							if (value < 0) {
								value += 360;
							}
							degree.val(parseInt(value));

							uidegslider.slider('value', degree.val());
						},
						
						execute : function ( e ) {
							
							if ( !rotate.imageBeingRotated ) return;
							
							var imageCentre = rotate.getCenter( imgr );
							var mouseXFromCentre = e.pageX - imageCentre[0];
							var mouseYFromCentre = e.pageY - imageCentre[1];
							var mouseAngle = Math.atan2( mouseYFromCentre, mouseXFromCentre );
							
							var rotateAngle = mouseAngle - rotate.mouseStartAngle + rotate.imageStartAngle;
							rotateAngle = Math.round(parseFloat(rotateAngle) * 180 / Math.PI);
							
							if ( e.shiftKey ) {
								rotateAngle = Math.round((rotateAngle + 6)/15) * 15;
							}
							
							imgr.rotate(rotateAngle);
							
							rotateAngle = rotateAngle % 360;
							if (rotateAngle < 0) {
								rotateAngle += 360;
							}
							degree.val(rotateAngle);

							uidegslider.slider('value', degree.val());
							
							return false;
						},
						
						start : function ( e ) {
							
							rotate.imageBeingRotated = true;
							
							var imageCentre = rotate.getCenter( imgr );
							var mouseStartXFromCentre = e.pageX - imageCentre[0];
							var mouseStartYFromCentre = e.pageY - imageCentre[1];
							rotate.mouseStartAngle = Math.atan2( mouseStartYFromCentre, mouseStartXFromCentre );
							
							rotate.imageStartAngle = parseFloat(imgr.rotate()) * Math.PI / 180.0;
							
							$(document).mousemove( rotate.execute );
							
							return false;
						},
							
						stop : function ( e ) {
							
							if ( !rotate.imageBeingRotated ) return;
							
							$(document).unbind( 'mousemove' , rotate.execute);
							
							setTimeout( function() { rotate.imageBeingRotated = false; }, 10 );
							return false;
						},
						
						getCenter : function ( image ) {
							
							var currentRotation = imgr.rotate();
							imgr.rotate(0);
							
							var imageOffset = imgr.offset();
							var imageCentreX = imageOffset.left + imgr.width() / 2;
							var imageCentreY = imageOffset.top + imgr.height() / 2;
							
							imgr.rotate(currentRotation);
							
							return Array( imageCentreX, imageCentreY );
						}
					},
					resizable = function(destroy) {
						if ($.fn.resizable) {
							if (destroy) {
								rhandle.filter(':ui-resizable').resizable('destroy');
								rhandle.hide();
							}
							else {
								rhandle.show();
								rhandle.resizable({
									alsoResize  : img,
									aspectRatio : cratio,
									resize      : resize.update,
									stop        : resize.fixHeight
								});
							}
						}
					},
					croppable = function(destroy) {
						if ($.fn.draggable && $.fn.resizable) {
							if (destroy) {
								rhandlec.filter(':ui-resizable').resizable('destroy');
								rhandlec.filter(':ui-draggable').draggable('destroy');
								basec.hide();
							}
							else {
								imgc
									.width(img.width())
									.height(img.height());
								
								coverc
									.width(img.width())
									.height(img.height());
								
								rhandlec
									.width(imgc.width())
									.height(imgc.height())
									.offset(imgc.offset())
									.resizable({
										containment : basec,
										resize      : crop.resize_update,
										handles     : 'all'
									})
									.draggable({
										handle      : coverc,
										containment : imgc,
										drag        : crop.drag_update
									});
								
								basec.show()
									.width(img.width())
									.height(img.height());
								
								crop.update();
							}
						}
					},
					rotateable = function(destroy) {
						if ($.fn.draggable && $.fn.resizable) {
							if (destroy) {
								imgr.hide();
							}
							else {
								imgr.show()
									.width(rwidth)
									.height(rheight)
									.css('margin-top', (pheight-rheight)/2 + 'px')
									.css('margin-left', (pwidth-rwidth)/2 + 'px');

							}
						}
					},
					save = function() {
						var w, h, x, y, d;
						var mode = $('input:checked', uitype).val();
						
						//width.add(height).change(); // may be unnecessary
						
						if (mode == 'resize') {
							w = parseInt(width.val()) || 0;
							h = parseInt(height.val()) || 0;
						} else if (mode == 'crop') {
							w = parseInt(offsetX.val()) || 0;
							h = parseInt(offsetY.val()) || 0;
							x = parseInt(pointX.val()) || 0;
							y = parseInt(pointY.val()) || 0;
						} else if (mode == 'rotate') {
							w = owidth;
							h = oheight;
							d = parseInt(degree.val()) || 0;
							if (d < 0 || d > 360) {
								return fm.error('Invalid rotate degree');
							}
							if (d == 0 || d == 360) {
								return fm.error('Image dose not rotated');
							}
						}
						
						if (mode != 'rotate') {

							if (w <= 0 || h <= 0) {
								return fm.error('Invalid image size');
							}
							
							if (w == owidth && h == oheight) {
								return fm.error('Image size not changed');
							}

						}
						
						dialog.elfinderdialog('close');
						
						fm.request({
							data : {
								cmd    : 'resize',
								target : file.hash,
								width  : w,
								height : h,
								x      : x,
								y      : y,
								degree : d,
								mode   : mode
							},
							notify : {type : 'resize', cnt : 1}
						})
						.fail(function(error) {
							dfrd.reject(error);
						})
						.done(function() {
							dfrd.resolve();
						});
						
					},
					buttons = {},
					hline   = 'elfinder-resize-handle-hline',
					vline   = 'elfinder-resize-handle-vline',
					rpoint  = 'elfinder-resize-handle-point',
					src     = fm.url(file.hash)
					;
				
				imgr.mousedown( rotate.start );
				$(document).mouseup( rotate.stop );
					
				uiresize.append($(row).append($(label).text(fm.i18n('width'))).append(width).append(reset))
					.append($(row).append($(label).text(fm.i18n('height'))).append(height))
					.append($(row).append($('<label/>').text(fm.i18n('aspectRatio')).prepend(constr)))
					.append($(row).append(fm.i18n('scale')+' ').append(uiprop));
				
				uicrop.append($(row).append($(label).text('X')).append(pointX))
					.append($(row).append($(label).text('Y')).append(pointY))
					.append($(row).append($(label).text(fm.i18n('width'))).append(offsetX))
					.append($(row).append($(label).text(fm.i18n('height'))).append(offsetY));
				
				uirotate.append($(row)
					.append($(label).text(fm.i18n('rotate')))
					.append($('<div style="float:left; width: 130px;">')
						.append($('<div style="float:left;">')
							.append(degree)
							.append($('<span/>').text(fm.i18n('degree')))
						)
						.append($(uibuttonset).append(uideg270).append($(uiseparator)).append(uideg90))
					)
					.append(uidegslider)
				);

				
				dialog.append(uitype);

				control.append($(row))
					.append(uiresize)
					.append(uicrop.hide())
					.append(uirotate.hide())
					.find('input,select').attr('disabled', 'disabled');
				
				rhandle.append('<div class="'+hline+' '+hline+'-top"/>')
					.append('<div class="'+hline+' '+hline+'-bottom"/>')
					.append('<div class="'+vline+' '+vline+'-left"/>')
					.append('<div class="'+vline+' '+vline+'-right"/>')
					.append('<div class="'+rpoint+' '+rpoint+'-e"/>')
					.append('<div class="'+rpoint+' '+rpoint+'-se"/>')
					.append('<div class="'+rpoint+' '+rpoint+'-s"/>');
					
				preview.append(spinner).append(rhandle.hide()).append(img.hide());

				rhandlec.css('position', 'absolute')
					.append('<div class="'+hline+' '+hline+'-top"/>')
					.append('<div class="'+hline+' '+hline+'-bottom"/>')
					.append('<div class="'+vline+' '+vline+'-left"/>')
					.append('<div class="'+vline+' '+vline+'-right"/>')
					.append('<div class="'+rpoint+' '+rpoint+'-n"/>')
					.append('<div class="'+rpoint+' '+rpoint+'-e"/>')
					.append('<div class="'+rpoint+' '+rpoint+'-s"/>')
					.append('<div class="'+rpoint+' '+rpoint+'-w"/>')
					.append('<div class="'+rpoint+' '+rpoint+'-ne"/>')
					.append('<div class="'+rpoint+' '+rpoint+'-se"/>')
					.append('<div class="'+rpoint+' '+rpoint+'-sw"/>')
					.append('<div class="'+rpoint+' '+rpoint+'-nw"/>');

				preview.append(basec.css('position', 'absolute').hide().append(imgc).append(rhandlec.append(coverc)));
				
				preview.append(imgr.hide());
				
				preview.css('overflow', 'hidden');
				
				dialog.append(preview).append(control);
				
				buttons[fm.i18n('btnApply')] = save;
				buttons[fm.i18n('btnCancel')] = function() { dialog.elfinderdialog('close'); };
				
				fm.dialog(dialog, {
					title          : file.name,
					width          : 650,
					resizable      : false,
					destroyOnClose : true,
					buttons        : buttons,
					open           : function() { preview.zIndex(1+$(this).parent().zIndex()); }
				}).attr('id', id);
				
				// for IE < 9 dialog mising at open second+ time.
				if (fm.UA.ltIE8) {
					$('.elfinder-dialog').css('filter', '');
				}
				
				reset.css('left', width.position().left + width.width() + 12);
				
				coverc.css({ 'opacity': 0.2, 'background-color': '#fff', 'position': 'absolute'}),
				rhandlec.css('cursor', 'move');
				rhandlec.find('.elfinder-resize-handle-point').css({
					'background-color' : '#fff',
					'opacity': 0.5,
					'border-color':'#000'
				});

				imgr.css('cursor', 'pointer');
				
				uitype.buttonset();
				
				pwidth  = preview.width()  - (rhandle.outerWidth()  - rhandle.width());
				pheight = preview.height() - (rhandle.outerHeight() - rhandle.height());
				
				img.attr('src', src + (src.indexOf('?') === -1 ? '?' : '&')+'_='+Math.random());
				imgc.attr('src', img.attr('src'));
				imgr.attr('src', img.attr('src'));
				
			},
			
			id, dialog
			;
			

		if (!files.length || files[0].mime.indexOf('image/') === -1) {
			return dfrd.reject();
		}
		
		id = 'resize-'+fm.namespace+'-'+files[0].hash;
		dialog = fm.getUI().find('#'+id);
		
		if (dialog.length) {
			dialog.elfinderdialog('toTop');
			return dfrd.resolve();
		}
		
		open(files[0], id);
			
		return dfrd;
	};

};

(function ($) {
	
	var findProperty = function (styleObject, styleArgs) {
		var i = 0 ;
		for( i in styleArgs) {
	        if (typeof styleObject[styleArgs[i]] != 'undefined') 
	        	return styleArgs[i];
		}
		styleObject[styleArgs[i]] = '';
	    return styleArgs[i];
	};
	
	$.cssHooks.rotate = {
		get: function(elem, computed, extra) {
			return $(elem).rotate();
		},
		set: function(elem, value) {
			$(elem).rotate(value);
			return value;
		}
	};
	$.cssHooks.transform = {
		get: function(elem, computed, extra) {
			var name = findProperty( elem.style , 
				['WebkitTransform', 'MozTransform', 'OTransform' , 'msTransform' , 'transform'] );
			return elem.style[name];
		},
		set: function(elem, value) {
			var name = findProperty( elem.style , 
				['WebkitTransform', 'MozTransform', 'OTransform' , 'msTransform' , 'transform'] );
			elem.style[name] = value;
			return value;
		}
	};
	
	$.fn.rotate = function(val) {
		if (typeof val == 'undefined') {
			if (!!window.opera) {
				var r = this.css('transform').match(/rotate\((.*?)\)/);
				return  ( r && r[1])?
					Math.round(parseFloat(r[1]) * 180 / Math.PI) : 0;
			} else {
				var r = this.css('transform').match(/rotate\((.*?)\)/);
				return  ( r && r[1])? parseInt(r[1]) : 0;
			}
		}
		this.css('transform', 
			this.css('transform').replace(/none|rotate\(.*?\)/, '') + 'rotate(' + parseInt(val) + 'deg)');
		return this;
	};

	$.fx.step.rotate  = function(fx) {
		if ( fx.state == 0 ) {
			fx.start = $(fx.elem).rotate();
			fx.now = fx.start;
		}
		$(fx.elem).rotate(fx.now);
	};

	if (typeof window.addEventListener == "undefined" && typeof document.getElementsByClassName == "undefined") { // IE & IE<9
		var GetAbsoluteXY = function(element) {
			var pnode = element;
			var x = pnode.offsetLeft;
			var y = pnode.offsetTop;
			
			while ( pnode.offsetParent ) {
				pnode = pnode.offsetParent;
				if (pnode != document.body && pnode.currentStyle['position'] != 'static') {
					break;
				}
				if (pnode != document.body && pnode != document.documentElement) {
					x -= pnode.scrollLeft;
					y -= pnode.scrollTop;
				}
				x += pnode.offsetLeft;
				y += pnode.offsetTop;
			}
			
			return { x: x, y: y };
		};
		
		var StaticToAbsolute = function (element) {
			if ( element.currentStyle['position'] != 'static') {
				return ;
			}

			var xy = GetAbsoluteXY(element);
			element.style.position = 'absolute' ;
			element.style.left = xy.x + 'px';
			element.style.top = xy.y + 'px';
		};

		var IETransform = function(element,transform){

			var r;
			var m11 = 1;
			var m12 = 1;
			var m21 = 1;
			var m22 = 1;

			if (typeof element.style['msTransform'] != 'undefined'){
				return true;
			}

			StaticToAbsolute(element);

			r = transform.match(/rotate\((.*?)\)/);
			var rotate =  ( r && r[1])	?	parseInt(r[1])	:	0;

			rotate = rotate % 360;
			if (rotate < 0) rotate = 360 + rotate;

			var radian= rotate * Math.PI / 180;
			var cosX =Math.cos(radian);
			var sinY =Math.sin(radian);

			m11 *= cosX;
			m12 *= -sinY;
			m21 *= sinY;
			m22 *= cosX;

			element.style.filter =  (element.style.filter || '').replace(/progid:DXImageTransform\.Microsoft\.Matrix\([^)]*\)/, "" ) +
				("progid:DXImageTransform.Microsoft.Matrix(" + 
					 "M11=" + m11 + 
					",M12=" + m12 + 
					",M21=" + m21 + 
					",M22=" + m22 + 
					",FilterType='bilinear',sizingMethod='auto expand')") 
				;

	  		var ow = parseInt(element.style.width || element.width || 0 );
	  		var oh = parseInt(element.style.height || element.height || 0 );

			var radian = rotate * Math.PI / 180;
			var absCosX =Math.abs(Math.cos(radian));
			var absSinY =Math.abs(Math.sin(radian));

			var dx = (ow - (ow * absCosX + oh * absSinY)) / 2;
			var dy = (oh - (ow * absSinY + oh * absCosX)) / 2;

			element.style.marginLeft = Math.floor(dx) + "px";
			element.style.marginTop  = Math.floor(dy) + "px";

			return(true);
		};
		
		var transform_set = $.cssHooks.transform.set;
		$.cssHooks.transform.set = function(elem, value) {
			transform_set.apply(this, [elem, value] );
			IETransform(elem,value);
			return value;
		};
	}

})(jQuery);
;"use strict"
/**
 * @class  elFinder command "rm"
 * Delete files
 *
 * @author Dmitry (dio) Levashov
 **/
elFinder.prototype.commands.rm = function() {
	
	this.shortcuts = [{
		pattern     : 'delete ctrl+backspace'
	}];
	
	this.getstate = function(sel) {
		var fm = this.fm;
		sel = sel || fm.selected();
		return !this._disabled && sel.length && $.map(sel, function(h) { var f = fm.file(h); return f && f.phash && !f.locked ? h : null }).length == sel.length
			? 0 : -1;
	}
	
	this.exec = function(hashes) {
		var self   = this,
			fm     = this.fm,
			dfrd   = $.Deferred()
				.fail(function(error) {
					error && fm.error(error);
				}),
			files  = this.files(hashes),
			cnt    = files.length,
			cwd    = fm.cwd().hash,
			goroot = false;

		if (!cnt || this._disabled) {
			return dfrd.reject();
		}
		
		$.each(files, function(i, file) {
			if (!file.phash) {
				return !dfrd.reject(['errRm', file.name, 'errPerm']);
			}
			if (file.locked) {
				return !dfrd.reject(['errLocked', file.name]);
			}
			if (file.hash == cwd) {
				goroot = fm.root(file.hash);
			}
		});

		if (dfrd.state() == 'pending') {
			files = this.hashes(hashes);
			
			fm.confirm({
				title  : self.title,
				text   : 'confirmRm',
				accept : {
					label    : 'btnRm',
					callback : function() {  
						fm.lockfiles({files : files});
						fm.request({
							data   : {cmd  : 'rm', targets : files}, 
							notify : {type : 'rm', cnt : cnt},
							preventFail : true
						})
						.fail(function(error) {
							dfrd.reject(error);
						})
						.done(function(data) {
							dfrd.done(data);
							goroot && fm.exec('open', goroot)
						}
						).always(function() {
							fm.unlockfiles({files : files});
						});
					}
				},
				cancel : {
					label    : 'btnCancel',
					callback : function() { dfrd.reject(); }
				}
			});
		}
			
		return dfrd;
	}

};"use strict"
/**
 * @class  elFinder command "search"
 * Find files
 *
 * @author Dmitry (dio) Levashov
 **/
elFinder.prototype.commands.search = function() {
	this.title          = 'Find files';
	this.options        = {ui : 'searchbutton'}
	this.alwaysEnabled  = true;
	this.updateOnSelect = false;
	
	/**
	 * Return command status.
	 * Search does not support old api.
	 *
	 * @return Number
	 **/
	this.getstate = function() {
		return 0;
	}
	
	/**
	 * Send search request to backend.
	 *
	 * @param  String  search string
	 * @return $.Deferred
	 **/
	this.exec = function(q) {
		var fm = this.fm;
		
		if (typeof(q) == 'string' && q) {
			fm.trigger('searchstart', {query : q});
			
			return fm.request({
				data   : {cmd : 'search', q : q},
				notify : {type : 'search', cnt : 1, hideCnt : true}
			});
		}
		fm.getUI('toolbar').find('.'+fm.res('class', 'searchbtn')+' :text').focus();
		return $.Deferred().reject();
	}

};"use strict"
/**
 * @class  elFinder command "sort"
 * Change sort files rule
 *
 * @author Dmitry (dio) Levashov
 **/
elFinder.prototype.commands.sort = function() {
	/**
	 * Command options
	 *
	 * @type  Object
	 */
	this.options = {ui : 'sortbutton'};
	
	this.getstate = function() {
		return 0;
	}
	
	this.exec = function(hashes, sort) {
		var fm = this.fm,
			sort = $.extend({
				type  : fm.sortType,
				order : fm.sortOrder,
				stick : fm.sortStickFolders
			}, sort);

		this.fm.setSort(sort.type, sort.order, sort.stick);
		return $.Deferred().resolve();
	}

};"use strict";
/**
 * @class  elFinder command "up"
 * Go into parent directory
 *
 * @author Dmitry (dio) Levashov
 **/
elFinder.prototype.commands.up = function() {
	this.alwaysEnabled = true;
	this.updateOnSelect = false;
	
	this.shortcuts = [{
		pattern     : 'ctrl+up'
	}];
	
	this.getstate = function() {
		return this.fm.cwd().phash ? 0 : -1;
	}
	
	this.exec = function() {
		return this.fm.cwd().phash ? this.fm.exec('open', this.fm.cwd().phash) : $.Deferred().reject();
	}

};"use strict";
/**
 * @class elFinder command "upload"
 * Upload files using iframe or XMLHttpRequest & FormData.
 * Dialog allow to send files using drag and drop
 *
 * @type  elFinder.command
 * @author  Dmitry (dio) Levashov
 */
elFinder.prototype.commands.upload = function() {
	var hover = this.fm.res('class', 'hover');
	
	this.disableOnSearch = true;
	this.updateOnSelect  = false;
	
	// Shortcut opens dialog
	this.shortcuts = [{
		pattern     : 'ctrl+u'
	}];
	
	/**
	 * Return command state
	 *
	 * @return Number
	 **/
	this.getstate = function() {
		return !this._disabled && this.fm.cwd().write ? 0 : -1;
	};
	
	
	this.exec = function(data) {
		var fm = this.fm,
			upload = function(data) {
				dialog.elfinderdialog('close');
				fm.upload(data)
					.fail(function(error) {
						dfrd.reject(error);
					})
					.done(function(data) {
						dfrd.resolve(data);
					});
			},
			dfrd, dialog, input, button, dropbox, pastebox;
		
		if (this.disabled()) {
			return $.Deferred().reject();
		}
		
		if (data && (data.input || data.files)) {
			return fm.upload(data);
		}
		
		dfrd = $.Deferred();
		
		
		input = $('<input type="file" multiple="true"/>')
			.change(function() {
				upload({input : input[0]});
			});

		button = $('<div class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only"><span class="ui-button-text">'+fm.i18n('selectForUpload')+'</span></div>')
			.append($('<form/>').append(input))
			.hover(function() {
				button.toggleClass(hover)
			});
			
		dialog = $('<div class="elfinder-upload-dialog-wrapper"/>')
			.append(button);
		
		pastebox = $('<div class="ui-corner-all elfinder-upload-dropbox" contenteditable=true></div>')
			.on('paste drop', function (evt) {
				var e = evt.originalEvent || evt;
				var files = [];
				var file;
				if (e.clipboardData && e.clipboardData.items && e.clipboardData.items.length){
					for (var i=0; i < e.clipboardData.items.length; i++) {
						if (e.clipboardData.items[i].kind == 'file') {
							file = e.clipboardData.items[i].getAsFile();
							files.push(file);
						}
					}
					if (files.length) {
						upload({files : files, type : 'files'});
						return;
					}
				}
				var my = e.target;
				setTimeout(function () {
					if (my.innerHTML) {
						var src = my.innerHTML.replace(/<br[^>]*>/gi, ' ');
						var type = src.match(/<[^>]+>/)? 'html' : 'text';
						my.innerHTML = '';
						upload({files : [ src ], type : type});
					}
				}, 1);
			})
			.on('dragenter mouseover', function(){
				pastebox.addClass(hover);
			})
			.on('dragleave mouseout', function(){
				pastebox.removeClass(hover);
			});
		
		if (fm.dragUpload) {
			dropbox = $('<div class="ui-corner-all elfinder-upload-dropbox">'+fm.i18n('dropFiles')+'</div>')
				.prependTo(dialog)
				.after('<div class="elfinder-upload-dialog-or">'+fm.i18n('or')+'</div>')
				.after(pastebox)
				.after('<div>'+fm.i18n('dropFilesBrowser')+'</div>')
				.after('<div class="elfinder-upload-dialog-or">'+fm.i18n('or')+'</div>')[0];
			
			dropbox.addEventListener('dragenter', function(e) {
				e.stopPropagation();
			  	e.preventDefault();
				$(dropbox).addClass(hover);
			}, false);

			dropbox.addEventListener('dragleave', function(e) {
				e.stopPropagation();
			  	e.preventDefault();
				$(dropbox).removeClass(hover);
			}, false);

			dropbox.addEventListener('dragover', function(e) {
				e.stopPropagation();
			  	e.preventDefault();
			  	$(dropbox).addClass(hover);
			}, false);

			dropbox.addEventListener('drop', function(e) {
				e.stopPropagation();
			  	e.preventDefault();
				var file = false;
				var type = '';
				if (e.dataTransfer && e.dataTransfer.items &&  e.dataTransfer.items.length) {
					file = e.dataTransfer.items;
					type = 'data';
				} else if (e.dataTransfer && e.dataTransfer.files &&  e.dataTransfer.files.length) {
					file = e.dataTransfer.files;
					type = 'files';
				} else if (e.dataTransfer.getData('text/html')) {
					file = [ e.dataTransfer.getData('text/html') ];
					type = 'html';
				} else if (e.dataTransfer.getData('text')) {
					file = [ e.dataTransfer.getData('text') ];
					type = 'text';
				}
				if (file) {
					upload({files : file, type : type});
				}
			}, false);
			
		} else {
			$('<div>'+fm.i18n('dropFilesBrowser')+'</div>')
				.append(pastebox)
				.prependTo(dialog)
				.after('<div class="elfinder-upload-dialog-or">'+fm.i18n('or')+'</div>')[0];
			
		}
		
		fm.dialog(dialog, {
			title          : this.title,
			modal          : true,
			resizable      : false,
			destroyOnClose : true
		});
		
		return dfrd;
	};

};;"use strict";
/**
 * @class  elFinder command "view"
 * Change current directory view (icons/list)
 *
 * @author Dmitry (dio) Levashov
 **/
elFinder.prototype.commands.view = function() {
	this.value          = this.fm.viewType;
	this.alwaysEnabled  = true;
	this.updateOnSelect = false;

	this.options = { ui : 'viewbutton'};
	
	this.getstate = function() {
		return 0;
	}
	
	this.exec = function() {
		var value = this.fm.storage('view', this.value == 'list' ? 'icons' : 'list');
		this.fm.viewchange();
		this.update(void(0), value);
	}

};/**
 * elFinder command prototype
 *
 * @type  elFinder.command
 * @author  Dmitry (dio) Levashov
 */
elFinder.prototype.command = function(fm) {

	/**
	 * elFinder instance
	 *
	 * @type  elFinder
	 */
	this.fm = fm;
	
	/**
	 * Command name, same as class name
	 *
	 * @type  String
	 */
	this.name = '';
	
	/**
	 * Short command description
	 *
	 * @type  String
	 */
	this.title = '';
	
	/**
	 * Current command state
	 *
	 * @example
	 * this.state = -1; // command disabled
	 * this.state = 0;  // command enabled
	 * this.state = 1;  // command active (for example "fullscreen" command while elfinder in fullscreen mode)
	 * @default -1
	 * @type  Number
	 */
	this.state = -1;
	
	/**
	 * If true, command can not be disabled by connector.
	 * @see this.update()
	 *
	 * @type  Boolen
	 */
	this.alwaysEnabled = false;
	
	/**
	 * If true, this means command was disabled by connector.
	 * @see this.update()
	 *
	 * @type  Boolen
	 */
	this._disabled = false;
	
	this.disableOnSearch = false;
	
	this.updateOnSelect = true;
	
	/**
	 * elFinder events defaults handlers.
	 * Inside handlers "this" is current command object
	 *
	 * @type  Object
	 */
	this._handlers = {
		enable  : function() { this.update(void(0), this.value); },
		disable : function() { this.update(-1, this.value); },
		'open reload load'    : function(e) { 
			this._disabled = !(this.alwaysEnabled || this.fm.isCommandEnabled(this.name));
			this.update(void(0), this.value)
			this.change(); 
		}
	};
	
	/**
	 * elFinder events handlers.
	 * Inside handlers "this" is current command object
	 *
	 * @type  Object
	 */
	this.handlers = {}
	
	/**
	 * Shortcuts
	 *
	 * @type  Array
	 */
	this.shortcuts = [];
	
	/**
	 * Command options
	 *
	 * @type  Object
	 */
	this.options = {ui : 'button'};
	
	/**
	 * Prepare object -
	 * bind events and shortcuts
	 *
	 * @return void
	 */
	this.setup = function(name, opts) {
		var self = this,
			fm   = this.fm, i, s;

		this.name      = name;
		this.title     = fm.messages['cmd'+name] ? fm.i18n('cmd'+name) : name, 
		this.options   = $.extend({}, this.options, opts);
		this.listeners = [];

		if (this.updateOnSelect) {
			this._handlers.select = function() { this.update(void(0), this.value); }
		}

		$.each($.extend({}, self._handlers, self.handlers), function(cmd, handler) {
			fm.bind(cmd, $.proxy(handler, self));
		});

		for (i = 0; i < this.shortcuts.length; i++) {
			s = this.shortcuts[i];
			s.callback = $.proxy(s.callback || function() { this.exec() }, this);
			!s.description && (s.description = this.title);
			fm.shortcut(s);
		}

		if (this.disableOnSearch) {
			fm.bind('search searchend', function(e) {
				self._disabled = e.type == 'search';
				self.update(void(0), self.value);
			});
		}

		this.init();
	}

	/**
	 * Command specific init stuffs
	 *
	 * @return void
	 */
	this.init = function() { }

	/**
	 * Exec command
	 *
	 * @param  Array         target files hashes
	 * @param  Array|Object  command value
	 * @return $.Deferred
	 */
	this.exec = function(files, opts) { 
		return $.Deferred().reject(); 
	}
	
	/**
	 * Return true if command disabled.
	 *
	 * @return Boolen
	 */
	this.disabled = function() {
		return this.state < 0;
	}
	
	/**
	 * Return true if command enabled.
	 *
	 * @return Boolen
	 */
	this.enabled = function() {
		return this.state > -1;
	}
	
	/**
	 * Return true if command active.
	 *
	 * @return Boolen
	 */
	this.active = function() {
		return this.state > 0;
	}
	
	/**
	 * Return current command state.
	 * Must be overloaded in most commands
	 *
	 * @return Number
	 */
	this.getstate = function() {
		return -1;
	}
	
	/**
	 * Update command state/value
	 * and rize 'change' event if smth changed
	 *
	 * @param  Number  new state or undefined to auto update state
	 * @param  mixed   new value
	 * @return void
	 */
	this.update = function(s, v) {
		var state = this.state,
			value = this.value;

		if (this._disabled) {
			this.state = -1;
		} else {
			this.state = s !== void(0) ? s : this.getstate();
		}

		this.value = v;
		
		if (state != this.state || value != this.value) {
			this.change();
		}
	}
	
	/**
	 * Bind handler / fire 'change' event.
	 *
	 * @param  Function|undefined  event callback
	 * @return void
	 */
	this.change = function(c) {
		var cmd, i;
		
		if (typeof(c) === 'function') {
			this.listeners.push(c);			
		} else {
			for (i = 0; i < this.listeners.length; i++) {
				cmd = this.listeners[i];
				try {
					cmd(this.state, this.value);
				} catch (e) {
					this.fm.debug('error', e)
				}
			}
		}
		return this;
	}
	

	/**
	 * With argument check given files hashes and return list of existed files hashes.
	 * Without argument return selected files hashes.
	 *
	 * @param  Array|String|void  hashes
	 * @return Array
	 */
	this.hashes = function(hashes) {
		return hashes
			? $.map($.isArray(hashes) ? hashes : [hashes], function(hash) { return fm.file(hash) ? hash : null; })
			: fm.selected();
	}
	
	/**
	 * Return only existed files from given fils hashes | selected files
	 *
	 * @param  Array|String|void  hashes
	 * @return Array
	 */
	this.files = function(hashes) {
		var fm = this.fm;
		
		return hashes
			? $.map($.isArray(hashes) ? hashes : [hashes], function(hash) { return fm.file(hash) || null })
			: fm.selectedFiles();
	}
}


;/**
 * @class elFinder.history
 * Store visited folders
 * and provide "back" and "forward" methods
 *
 * @author Dmitry (dio) Levashov
 */
elFinder.prototype.history = function(fm) {
	var self = this,
		/**
		 * Update history on "open" event?
		 *
		 * @type Boolean
		 */
		update = true,
		/**
		 * Directories hashes storage
		 *
		 * @type Array
		 */
		history = [],
		/**
		 * Current directory index in history
		 *
		 * @type Number
		 */
		current,
		/**
		 * Clear history
		 *
		 * @return void
		 */
		reset = function() {
			history = [fm.cwd().hash];
			current = 0;
			update  = true;
		},
		/**
		 * Browser native history object
		 */
		nativeHistory = (fm.options.useBrowserHistory && window.history && window.history.pushState)? window.history : null,
		/**
		 * Open prev/next folder
		 *
		 * @Boolen  open next folder?
		 * @return jQuery.Deferred
		 */
		go = function(fwd) {
			if ((fwd && self.canForward()) || (!fwd && self.canBack())) {
				update = false;
				return fm.exec('open', history[fwd ? ++current : --current]).fail(reset);
			}
			return $.Deferred().reject();
		};
	
	/**
	 * Return true if there is previous visited directories
	 *
	 * @return Boolen
	 */
	this.canBack = function() {
		return current > 0;
	}
	
	/**
	 * Return true if can go forward
	 *
	 * @return Boolen
	 */
	this.canForward = function() {
		return current < history.length - 1;
	}
	
	/**
	 * Go back
	 *
	 * @return void
	 */
	this.back = go;
	
	/**
	 * Go forward
	 *
	 * @return void
	 */
	this.forward = function() {
		return go(true);
	}
	
	// bind to elfinder events
	fm.open(function(e) {
		var l = history.length,
			cwd = fm.cwd().hash;

		if (update) {
			current >= 0 && l > current + 1 && history.splice(current+1);
			history[history.length-1] != cwd && history.push(cwd);
			current = history.length - 1;
		}
		update = true;

		if (nativeHistory) {
			if (! nativeHistory.state) {
				nativeHistory.replaceState({thash: cwd}, null, location.pathname + location.search + '#elf_' + cwd);
			} else {
				nativeHistory.state.thash != cwd && nativeHistory.pushState({thash: cwd}, null, location.pathname + location.search + '#elf_' + cwd);
			}
		}
	})
	.reload(reset);
	
};/**
 * Default elFinder config
 *
 * @type  Object
 * @autor Dmitry (dio) Levashov
 */
elFinder.prototype._options = {
	/**
	 * Connector url. Required!
	 *
	 * @type String
	 */
	url : '',

	/**
	 * Ajax request type.
	 *
	 * @type String
	 * @default "get"
	 */
	requestType : 'get',

	/**
	 * Transport to send request to backend.
	 * Required for future extensions using websockets/webdav etc.
	 * Must be an object with "send" method.
	 * transport.send must return $.Deferred() object
	 *
	 * @type Object
	 * @default null
	 * @example
	 *  transport : {
	 *    init : function(elfinderInstance) { },
	 *    send : function(options) {
	 *      var dfrd = $.Deferred();
	 *      // connect to backend ...
	 *      return dfrd;
	 *    },
	 *    upload : function(data) {
	 *      var dfrd = $.Deferred();
	 *      // upload ...
	 *      return dfrd;
	 *    }
	 *    
	 *  }
	 **/
	transport : {},

	/**
	 * URL to upload file to.
	 * If not set - connector URL will be used
	 *
	 * @type String
	 * @default  ''
	 */
	urlUpload : '',

	/**
	 * Allow to drag and drop to upload files
	 *
	 * @type Boolean|String
	 * @default  'auto'
	 */
	dragUploadAllow : 'auto',
	
	/**
	 * Timeout for upload using iframe
	 *
	 * @type Number
	 * @default  0 - no timeout
	 */
	iframeTimeout : 0,
	
	/**
	 * Data to append to all requests and to upload files
	 *
	 * @type Object
	 * @default  {}
	 */
	customData : {},
	
	/**
	 * Event listeners to bind on elFinder init
	 *
	 * @type Object
	 * @default  {}
	 */
	handlers : {},

	/**
	 * Any custom headers to send across every ajax request
	 *
	 * @type Object
	 * @default {}
	 */
	customHeaders : {},

	/**
	 * Any custom xhrFields to send across every ajax request
	 *
	 * @type Object
	 * @default {}
	 */
	xhrFields : {},

	/**
	 * Interface language
	 *
	 * @type String
	 * @default "en"
	 */
	lang : 'en',

	/**
	 * Additional css class for filemanager node.
	 *
	 * @type String
	 */
	cssClass : '',

	/**
	 * Active commands list
	 * If some required commands will be missed here, elFinder will add its
	 *
	 * @type Array
	 */
	commands : [
		'open', 'reload', 'home', 'up', 'back', 'forward', 'getfile', 'quicklook', 
		'download', 'rm', 'duplicate', 'rename', 'mkdir', 'mkfile', 'upload', 'copy', 
		'cut', 'paste', 'edit', 'extract', 'archive', 'search', 'info', 'view', 'help', 'resize', 'sort', 'netmount'
	],
	
	/**
	 * Commands options.
	 *
	 * @type Object
	 **/
	commandsOptions : {
		// "getfile" command options.
		getfile : {
			onlyURL  : false,
			// allow to return multiple files info
			multiple : false,
			// allow to return filers info
			folders  : false,
			// action after callback (""/"close"/"destroy")
			oncomplete : ''
		},
		// "upload" command options.
		upload : {
			ui : 'uploadbutton'
		},
		// "quicklook" command options.
		quicklook : {
			autoplay : true,
			jplayer  : 'extensions/jplayer'
		},
		// "quicklook" command options.
		edit : {
			// list of allowed mimetypes to edit
			// if empty - any text files can be edited
			mimes : [],
			// edit files in wysisyg's
			editors : [
				// {
				// 	/**
				// 	 * files mimetypes allowed to edit in current wysisyg
				// 	 * @type  Array
				// 	 */
				// 	mimes : ['text/html'], 
				// 	/**
				// 	 * Called when "edit" dialog loaded.
				// 	 * Place to init wysisyg.
				// 	 * Can return wysisyg instance
				// 	 *
				// 	 * @param  DOMElement  textarea node
				// 	 * @return Object
				// 	 */
				// 	load : function(textarea) { },
				// 	/**
				// 	 * Called before "edit" dialog closed.
				// 	 * Place to destroy wysisyg instance.
				// 	 *
				// 	 * @param  DOMElement  textarea node
				// 	 * @param  Object      wysisyg instance (if was returned by "load" callback)
				// 	 * @return void
				// 	 */
				// 	close : function(textarea, instance) { },
				// 	/**
				// 	 * Called before file content send to backend.
				// 	 * Place to update textarea content if needed.
				// 	 *
				// 	 * @param  DOMElement  textarea node
				// 	 * @param  Object      wysisyg instance (if was returned by "load" callback)
				// 	 * @return void
				// 	 */
				// 	save : function(textarea, editor) {}
				// 
				// }
			]
		},
		// "info" command options.
		info : {nullUrlDirLinkSelf : true},
		

		help : {view : ['about', 'shortcuts', 'help']}
	},
	
	/**
	 * Callback for "getfile" commands.
	 * Required to use elFinder with WYSIWYG editors etc..
	 *
	 * @type Function
	 * @default null (command not active)
	 */
	getFileCallback : null,
	
	/**
	 * Default directory view. icons/list
	 *
	 * @type String
	 * @default "icons"
	 */
	defaultView : 'icons',
	
	/**
	 * UI plugins to load.
	 * Current dir ui and dialogs loads always.
	 * Here set not required plugins as folders tree/toolbar/statusbar etc.
	 *
	 * @type Array
	 * @default ['toolbar', 'tree', 'path', 'stat']
	 * @full ['toolbar', 'places', 'tree', 'path', 'stat']
	 */
	ui : ['toolbar', 'tree', 'path', 'stat'],
	
	/**
	 * Some UI plugins options.
	 * @type Object
	 */
	uiOptions : {
		// toolbar configuration
		toolbar : [
			['back', 'forward'],
			['netmount'],
			// ['reload'],
			// ['home', 'up'],
			['mkdir', 'mkfile', 'upload'],
			['open', 'download', 'getfile'],
			['info'],
			['quicklook'],
			['copy', 'cut', 'paste'],
			['rm'],
			['duplicate', 'rename', 'edit', 'resize'],
			['extract', 'archive'],
			['search'],
			['view', 'sort'],
			['help']
		],
		// directories tree options
		tree : {
			// expand current root on init
			openRootOnLoad : true,
			// auto load current dir parents
			syncTree : true
		},
		// navbar options
		navbar : {
			minWidth : 150,
			maxWidth : 500
		},
		cwd : {
			// display parent folder with ".." name :)
			oldSchool : false
		}
	},

	/**
	 * Display only required files by types
	 *
	 * @type Array
	 * @default []
	 * @example
	 *  onlyMimes : ["image"] - display all images
	 *  onlyMimes : ["image/png", "application/x-shockwave-flash"] - display png and flash
	 */
	onlyMimes : [],

	/**
	 * Custom files sort rules.
	 * All default rules (name/size/kind/date) set in elFinder._sortRules
	 *
	 * @type {Object}
	 * @example
	 * sortRules : {
	 *   name : function(file1, file2) { return file1.name.toLowerCase().localeCompare(file2.name.toLowerCase()); }
	 * }
	 */
	sortRules : {},

	/**
	 * Default sort type.
	 *
	 * @type {String}
	 */
	sortType : 'name',
	
	/**
	 * Default sort order.
	 *
	 * @type {String}
	 * @default "asc"
	 */
	sortOrder : 'asc',
	
	/**
	 * Display folders first?
	 *
	 * @type {Boolean}
	 * @default true
	 */
	sortStickFolders : true,
	
	/**
	 * If true - elFinder will formating dates itself, 
	 * otherwise - backend date will be used.
	 *
	 * @type Boolean
	 */
	clientFormatDate : true,
	
	/**
	 * Show UTC dates.
	 * Required set clientFormatDate to true
	 *
	 * @type Boolean
	 */
	UTCDate : false,
	
	/**
	 * File modification datetime format.
	 * Value from selected language data  is used by default.
	 * Set format here to overwrite it.
	 *
	 * @type String
	 * @default  ""
	 */
	dateFormat : '',
	
	/**
	 * File modification datetime format in form "Yesterday 12:23:01".
	 * Value from selected language data is used by default.
	 * Set format here to overwrite it.
	 * Use $1 for "Today"/"Yesterday" placeholder
	 *
	 * @type String
	 * @default  ""
	 * @example "$1 H:m:i"
	 */
	fancyDateFormat : '',
	
	/**
	 * elFinder width
	 *
	 * @type String|Number
	 * @default  "auto"
	 */
	width : 'auto',
	
	/**
	 * elFinder height
	 *
	 * @type Number
	 * @default  "auto"
	 */
	height : 400,
	
	/**
	 * Make elFinder resizable if jquery ui resizable available
	 *
	 * @type Boolean
	 * @default  true
	 */
	resizable : true,
	
	/**
	 * Timeout before open notifications dialogs
	 *
	 * @type Number
	 * @default  500 (.5 sec)
	 */
	notifyDelay : 500,
	
	/**
	 * Allow shortcuts
	 *
	 * @type Boolean
	 * @default  true
	 */
	allowShortcuts : true,
	
	/**
	 * Remeber last opened dir to open it after reload or in next session
	 *
	 * @type Boolean
	 * @default  true
	 */
	rememberLastDir : true,
	
	/**
	 * Use browser native history with supported browsers
	 *
	 * @type Boolean
	 * @default  true
	 */
	useBrowserHistory : true,
	
	/**
	 * Lazy load config.
	 * How many files display at once?
	 *
	 * @type Number
	 * @default  50
	 */
	showFiles : 30,
	
	/**
	 * Lazy load config.
	 * Distance in px to cwd bottom edge to start display files
	 *
	 * @type Number
	 * @default  50
	 */
	showThreshold : 50,
	
	/**
	 * Additional rule to valid new file name.
	 * By default not allowed empty names or '..'
	 *
	 * @type false|RegExp|function
	 * @default  false
	 * @example
	 *  disable names with spaces:
	 *  validName : /^[^\s]$/
	 */
	validName : false,
	
	/**
	 * Sync content interval
	 * @todo - fix in elFinder
	 * @type Number
	 * @default  0 (do not sync)
	 */
	sync : 0,
	
	/**
	 * How many thumbnails create in one request
	 *
	 * @type Number
	 * @default  5
	 */
	loadTmbs : 5,
	
	/**
	 * Cookie option for browsersdoes not suppot localStorage
	 *
	 * @type Object
	 */
	cookie         : {
		expires : 30,
		domain  : '',
		path    : '/',
		secure  : false
	},
	
	/**
	 * Contextmenu config
	 *
	 * @type Object
	 */
	contextmenu : {
		// navbarfolder menu
		navbar : ['open', '|', 'copy', 'cut', 'paste', 'duplicate', '|', 'rm', '|', 'info'],
		// current directory menu
		cwd    : ['reload', 'back', '|', 'upload', 'mkdir', 'mkfile', 'paste', '|', 'sort', '|', 'info'],
		// current directory file menu
		files  : ['getfile', '|','open', 'quicklook', '|', 'download', '|', 'copy', 'cut', 'paste', 'duplicate', '|', 'rm', '|', 'edit', 'rename', 'resize', '|', 'archive', 'extract', '|', 'info']
	},

	/**
	 * Debug config
	 *
	 * @type Array|Boolean
	 */
	// debug : true
	debug : ['error', 'warning', 'event-destroy']
}
;"use strict"
/**
 * elFinder resources registry.
 * Store shared data
 *
 * @type Object
 * @author Dmitry (dio) Levashov
 **/
elFinder.prototype.resources = {
	'class' : {
		hover       : 'ui-state-hover',
		active      : 'ui-state-active',
		disabled    : 'ui-state-disabled',
		draggable   : 'ui-draggable',
		droppable   : 'ui-droppable',
		adroppable  : 'elfinder-droppable-active',
		cwdfile     : 'elfinder-cwd-file',
		cwd         : 'elfinder-cwd',
		tree        : 'elfinder-tree',
		treeroot    : 'elfinder-navbar-root',
		navdir      : 'elfinder-navbar-dir',
		navdirwrap  : 'elfinder-navbar-dir-wrapper',
		navarrow    : 'elfinder-navbar-arrow',
		navsubtree  : 'elfinder-navbar-subtree',
		navcollapse : 'elfinder-navbar-collapsed',
		navexpand   : 'elfinder-navbar-expanded',
		treedir     : 'elfinder-tree-dir',
		placedir    : 'elfinder-place-dir',
		searchbtn   : 'elfinder-button-search'
	},
	tpl : {
		perms      : '<span class="elfinder-perms"/>',
		symlink    : '<span class="elfinder-symlink"/>',
		navicon    : '<span class="elfinder-nav-icon"/>',
		navspinner : '<span class="elfinder-navbar-spinner"/>',
		navdir     : '<div class="elfinder-navbar-wrapper"><span id="{id}" class="ui-corner-all elfinder-navbar-dir {cssclass}"><span class="elfinder-navbar-arrow"/><span class="elfinder-navbar-icon"/>{symlink}{permissions}{name}</span><div class="elfinder-navbar-subtree"/></div>'
		
	},
	
	mimes : {
		text : [
			'application/x-empty',
			'application/javascript', 
			'application/xhtml+xml', 
			'audio/x-mp3-playlist', 
			'application/x-web-config',
			'application/docbook+xml',
			'application/x-php',
			'application/x-perl',
			'application/x-awk',
			'application/x-config',
			'application/x-csh',
			'application/xml'
		]
	},
	
	mixin : {
		make : function() {
			var fm   = this.fm,
				cmd  = this.name,
				cwd  = fm.getUI('cwd'),
				dfrd = $.Deferred()
					.fail(function(error) {
						cwd.trigger('unselectall');
						error && fm.error(error);
					})
					.always(function() {
						input.remove();
						node.remove();
						fm.enable();
					}),
				id    = 'tmp_'+parseInt(Math.random()*100000),
				phash = fm.cwd().hash,
				date = new Date(),
				file   = {
					hash  : id,
					name  : fm.uniqueName(this.prefix),
					mime  : this.mime,
					read  : true,
					write : true,
					date  : 'Today '+date.getHours()+':'+date.getMinutes()
				},
				node = cwd.trigger('create.'+fm.namespace, file).find('#'+id),
				input = $('<input type="text"/>')
					.keydown(function(e) {
						e.stopImmediatePropagation();

						if (e.keyCode == $.ui.keyCode.ESCAPE) {
							dfrd.reject();
						} else if (e.keyCode == $.ui.keyCode.ENTER) {
							input.blur();
						}
					})
					.mousedown(function(e) {
						e.stopPropagation();
					})
					.blur(function() {
						var name   = $.trim(input.val()),
							parent = input.parent();

						if (parent.length) {

							if (!name) {
								return dfrd.reject('errInvName');
							}
							if (fm.fileByName(name, phash)) {
								return dfrd.reject(['errExists', name]);
							}

							parent.html(fm.escape(name));

							fm.lockfiles({files : [id]});

							fm.request({
									data        : {cmd : cmd, name : name, target : phash}, 
									notify      : {type : cmd, cnt : 1},
									preventFail : true,
									syncOnFail  : true
								})
								.fail(function(error) {
									dfrd.reject(error);
								})
								.done(function(data) {
									dfrd.resolve(data);
								});
						}
					});


			if (this.disabled() || !node.length) {
				return dfrd.reject();
			}

			fm.disable();
			node.find('.elfinder-cwd-filename').empty('').append(input.val(file.name));
			input.select().focus();
			input[0].setSelectionRange && input[0].setSelectionRange(0, file.name.replace(/\..+$/, '').length);

			return dfrd;



		}
		
	}
}

;/**
 * Application version
 *
 * @type String
 **/
elFinder.prototype.version = '2.0.2';

;/**
 * elFinder translation template
 * use this file to create new translation
 * submit new translation via https://github.com/Studio-42/elFinder/issues
 * or make a pull request
 */
 
/**
 * XXXXX translation
 * @author Translator Name <translator@email.tld>
 * @version 201x-xx-xx
 */
if (elFinder && elFinder.prototype && typeof(elFinder.prototype.i18) == 'object') {
	elFinder.prototype.i18.REPLACE_WITH_xx_OR_xx_YY_LANG_CODE = {
		translator : 'Translator name &lt;translator@email.tld&gt;',
		language   : 'Language of translation in your language',
		direction  : 'ltr',
		dateFormat : 'd.m.Y H:i',
		fancyDateFormat : '$1 H:i',
		messages   : {
			
			/********************************** errors **********************************/
			'error'                : 'Error',
			'errUnknown'           : 'Unknown error.',
			'errUnknownCmd'        : 'Unknown command.',
			'errJqui'              : 'Invalid jQuery UI configuration. Selectable, draggable and droppable components must be included.',
			'errNode'              : 'elFinder requires DOM Element to be created.',
			'errURL'               : 'Invalid elFinder configuration! URL option is not set.',
			'errAccess'            : 'Access denied.',
			'errConnect'           : 'Unable to connect to backend.',
			'errAbort'             : 'Connection aborted.',
			'errTimeout'           : 'Connection timeout.',
			'errNotFound'          : 'Backend not found.',
			'errResponse'          : 'Invalid backend response.',
			'errConf'              : 'Invalid backend configuration.',
			'errJSON'              : 'PHP JSON module not installed.',
			'errNoVolumes'         : 'Readable volumes not available.',
			'errCmdParams'         : 'Invalid parameters for command "$1".',
			'errDataNotJSON'       : 'Data is not JSON.',
			'errDataEmpty'         : 'Data is empty.',
			'errCmdReq'            : 'Backend request requires command name.',
			'errOpen'              : 'Unable to open "$1".',
			'errNotFolder'         : 'Object is not a folder.',
			'errNotFile'           : 'Object is not a file.',
			'errRead'              : 'Unable to read "$1".',
			'errWrite'             : 'Unable to write into "$1".',
			'errPerm'              : 'Permission denied.',
			'errLocked'            : '"$1" is locked and can not be renamed, moved or removed.',
			'errExists'            : 'File named "$1" already exists.',
			'errInvName'           : 'Invalid file name.',
			'errFolderNotFound'    : 'Folder not found.',
			'errFileNotFound'      : 'File not found.',
			'errTrgFolderNotFound' : 'Target folder "$1" not found.',
			'errPopup'             : 'Browser prevented opening popup window. To open file enable it in browser options.',
			'errMkdir'             : 'Unable to create folder "$1".',
			'errMkfile'            : 'Unable to create file "$1".',
			'errRename'            : 'Unable to rename "$1".',
			'errCopyFrom'          : 'Copying files from volume "$1" not allowed.',
			'errCopyTo'            : 'Copying files to volume "$1" not allowed.',
			'errUploadCommon'      : 'Upload error.',
			'errUpload'            : 'Unable to upload "$1".',
			'errUploadNoFiles'     : 'No files found for upload.',
			'errMaxSize'           : 'Data exceeds the maximum allowed size.',
			'errFileMaxSize'       : 'File exceeds maximum allowed size.',
			'errUploadMime'        : 'File type not allowed.',
			'errUploadTransfer'    : '"$1" transfer error.', 
			'errSave'              : 'Unable to save "$1".',
			'errCopy'              : 'Unable to copy "$1".',
			'errMove'              : 'Unable to move "$1".',
			'errCopyInItself'      : 'Unable to copy "$1" into itself.',
			'errRm'                : 'Unable to remove "$1".',
			'errExtract'           : 'Unable to extract files from "$1".',
			'errArchive'           : 'Unable to create archive.',
			'errArcType'           : 'Unsupported archive type.',
			'errNoArchive'         : 'File is not archive or has unsupported archive type.',
			'errCmdNoSupport'      : 'Backend does not support this command.',
			'errReplByChild'       : 'The folder “$1” can’t be replaced by an item it contains.',
			'errArcSymlinks'       : 'For security reason denied to unpack archives contains symlinks or files with not allowed names.', // edited 24.06.2012
			'errArcMaxSize'        : 'Archive files exceeds maximum allowed size.',
			'errResize'            : 'Unable to resize "$1".',
			'errUsupportType'      : 'Unsupported file type.',
			'errNotUTF8Content'    : 'File "$1" is not in UTF-8 and cannot be edited.',  // added 9.11.2011
			'errNetMount'          : 'Unable to mount "$1".',     // added 17.04.2012
			'errNetMountNoDriver'  : 'Unsupported protocol.',     // added 17.04.2012
			'errNetMountFailed'    : 'Mount failed.',             // added 17.04.2012
			'errNetMountHostReq'   : 'Host required.', // added 18.04.2012
			/******************************* commands names ********************************/
			'cmdarchive'   : 'Create archive',
			'cmdback'      : 'Back',
			'cmdcopy'      : 'Copy',
			'cmdcut'       : 'Cut',
			'cmddownload'  : 'Download',
			'cmdduplicate' : 'Duplicate',
			'cmdedit'      : 'Edit file',
			'cmdextract'   : 'Extract files from archive',
			'cmdforward'   : 'Forward',
			'cmdgetfile'   : 'Select files',
			'cmdhelp'      : 'About this software',
			'cmdhome'      : 'Home',
			'cmdinfo'      : 'Get info',
			'cmdmkdir'     : 'New folder',
			'cmdmkfile'    : 'New text file',
			'cmdopen'      : 'Open',
			'cmdpaste'     : 'Paste',
			'cmdquicklook' : 'Preview',
			'cmdreload'    : 'Reload',
			'cmdrename'    : 'Rename',
			'cmdrm'        : 'Delete',
			'cmdsearch'    : 'Find files',
			'cmdup'        : 'Go to parent directory',
			'cmdupload'    : 'Upload files',
			'cmdview'      : 'View',
			'cmdresize'    : 'Resize image',
			'cmdsort'      : 'Sort',
			
			/*********************************** buttons ***********************************/ 
			'btnClose'  : 'Close',
			'btnSave'   : 'Save',
			'btnRm'     : 'Remove',
			'btnApply'  : 'Apply',
			'btnCancel' : 'Cancel',
			'btnNo'     : 'No',
			'btnYes'    : 'Yes',
			
			/******************************** notifications ********************************/
			'ntfopen'     : 'Open folder',
			'ntffile'     : 'Open file',
			'ntfreload'   : 'Reload folder content',
			'ntfmkdir'    : 'Creating directory',
			'ntfmkfile'   : 'Creating files',
			'ntfrm'       : 'Delete files',
			'ntfcopy'     : 'Copy files',
			'ntfmove'     : 'Move files',
			'ntfprepare'  : 'Prepare to copy files',
			'ntfrename'   : 'Rename files',
			'ntfupload'   : 'Uploading files',
			'ntfdownload' : 'Downloading files',
			'ntfsave'     : 'Save files',
			'ntfarchive'  : 'Creating archive',
			'ntfextract'  : 'Extracting files from archive',
			'ntfsearch'   : 'Searching files',
			'ntfsmth'     : 'Doing something >_<',
			'ntfloadimg'  : 'Loading image',
			'ntfnetmount' : 'Mounting network volume', // added 18.04.2012
			
			/************************************ dates **********************************/
			'dateUnknown' : 'unknown',
			'Today'       : 'Today',
			'Yesterday'   : 'Yesterday',
			'Jan'         : 'Jan',
			'Feb'         : 'Feb',
			'Mar'         : 'Mar',
			'Apr'         : 'Apr',
			'May'         : 'May',
			'Jun'         : 'Jun',
			'Jul'         : 'Jul',
			'Aug'         : 'Aug',
			'Sep'         : 'Sep',
			'Oct'         : 'Oct',
			'Nov'         : 'Nov',
			'Dec'         : 'Dec',
			'January'     : 'January',
			'February'    : 'February',
			'March'       : 'March',
			'April'       : 'April',
			'May'         : 'May',
			'June'        : 'June',
			'July'        : 'July',
			'August'      : 'August',
			'September'   : 'September',
			'October'     : 'October',
			'November'    : 'November',
			'December'    : 'December',
			'Sunday'      : 'Sunday', 
			'Monday'      : 'Monday', 
			'Tuesday'     : 'Tuesday', 
			'Wednesday'   : 'Wednesday', 
			'Thursday'    : 'Thursday', 
			'Friday'      : 'Friday', 
			'Saturday'    : 'Saturday',
			'Sun'         : 'Sun', 
			'Mon'         : 'Mon', 
			'Tue'         : 'Tue', 
			'Wed'         : 'Wed', 
			'Thu'         : 'Thu', 
			'Fri'         : 'Fri', 
			'Sat'         : 'Sat',
			/******************************** sort variants ********************************/
			'sortname'          : 'by name', 
			'sortkind'          : 'by kind', 
			'sortsize'          : 'by size',
			'sortdate'          : 'by date',
			'sortFoldersFirst'  : 'Folders first', // added 22.06.2012
			
			/********************************** messages **********************************/
			'confirmReq'      : 'Confirmation required',
			'confirmRm'       : 'Are you sure you want to remove files?<br/>This cannot be undone!',
			'confirmRepl'     : 'Replace old file with new one?',
			'apllyAll'        : 'Apply to all',
			'name'            : 'Name',
			'size'            : 'Size',
			'perms'           : 'Permissions',
			'modify'          : 'Modified',
			'kind'            : 'Kind',
			'read'            : 'read',
			'write'           : 'write',
			'noaccess'        : 'no access',
			'and'             : 'and',
			'unknown'         : 'unknown',
			'selectall'       : 'Select all files',
			'selectfiles'     : 'Select file(s)',
			'selectffile'     : 'Select first file',
			'selectlfile'     : 'Select last file',
			'viewlist'        : 'List view',
			'viewicons'       : 'Icons view',
			'places'          : 'Places',
			'calc'            : 'Calculate', 
			'path'            : 'Path',
			'aliasfor'        : 'Alias for',
			'locked'          : 'Locked',
			'dim'             : 'Dimensions',
			'files'           : 'Files',
			'folders'         : 'Folders',
			'items'           : 'Items',
			'yes'             : 'yes',
			'no'              : 'no',
			'link'            : 'Link',
			'searcresult'     : 'Search results',  
			'selected'        : 'selected items',
			'about'           : 'About',
			'shortcuts'       : 'Shortcuts',
			'help'            : 'Help',
			'webfm'           : 'Web file manager',
			'ver'             : 'Version',
			'protocolver'     : 'protocol version',
			'homepage'        : 'Project home',
			'docs'            : 'Documentation',
			'github'          : 'Fork us on Github',
			'twitter'         : 'Follow us on twitter',
			'facebook'        : 'Join us on facebook',
			'team'            : 'Team',
			'chiefdev'        : 'chief developer',
			'developer'       : 'developer',
			'contributor'     : 'contributor',
			'maintainer'      : 'maintainer',
			'translator'      : 'translator',
			'icons'           : 'Icons',
			'dontforget'      : 'and don\'t forget to take your towel',
			'shortcutsof'     : 'Shortcuts disabled',
			'dropFiles'       : 'Drop files here',
			'or'              : 'or',
			'selectForUpload' : 'Select files to upload',
			'moveFiles'       : 'Move files',
			'copyFiles'       : 'Copy files',
			'rmFromPlaces'    : 'Remove from places',
			'untitled folder' : 'untitled folder',
			'untitled file.txt' : 'untitled file.txt',
			'aspectRatio'     : 'Aspect ratio',
			'scale'           : 'Scale',
			'width'           : 'Width',
			'height'          : 'Height',
			'mode'            : 'Mode',
			'resize'          : 'Resize',
			'crop'            : 'Crop',
			'rotate'          : 'Rotate',
			'rotate-cw'       : 'Rotate 90 degrees CW',
			'rotate-ccw'      : 'Rotate 90 degrees CCW',
			'degree'          : 'Degree',
			'netMountDialogTitle' : 'Mount network volume', // added 18.04.2012
			'protocol'            : 'Protocol', // added 18.04.2012
			'host'                : 'Host', // added 18.04.2012
			'port'                : 'Port', // added 18.04.2012
			'user'                : 'User', // added 18.04.2012
			'pass'                : 'Password', // added 18.04.2012
			/********************************** mimetypes **********************************/
			'kindUnknown'     : 'Unknown',
			'kindFolder'      : 'Folder',
			'kindAlias'       : 'Alias',
			'kindAliasBroken' : 'Broken alias',
			// applications
			'kindApp'         : 'Application',
			'kindPostscript'  : 'Postscript document',
			'kindMsOffice'    : 'Microsoft Office document',
			'kindMsWord'      : 'Microsoft Word document',
			'kindMsExcel'     : 'Microsoft Excel document',
			'kindMsPP'        : 'Microsoft Powerpoint presentation',
			'kindOO'          : 'Open Office document',
			'kindAppFlash'    : 'Flash application',
			'kindPDF'         : 'Portable Document Format (PDF)',
			'kindTorrent'     : 'Bittorrent file',
			'kind7z'          : '7z archive',
			'kindTAR'         : 'TAR archive',
			'kindGZIP'        : 'GZIP archive',
			'kindBZIP'        : 'BZIP archive',
			'kindZIP'         : 'ZIP archive',
			'kindRAR'         : 'RAR archive',
			'kindJAR'         : 'Java JAR file',
			'kindTTF'         : 'True Type font',
			'kindOTF'         : 'Open Type font',
			'kindRPM'         : 'RPM package',
			// texts
			'kindText'        : 'Text document',
			'kindTextPlain'   : 'Plain text',
			'kindPHP'         : 'PHP source',
			'kindCSS'         : 'Cascading style sheet',
			'kindHTML'        : 'HTML document',
			'kindJS'          : 'Javascript source',
			'kindRTF'         : 'Rich Text Format',
			'kindC'           : 'C source',
			'kindCHeader'     : 'C header source',
			'kindCPP'         : 'C++ source',
			'kindCPPHeader'   : 'C++ header source',
			'kindShell'       : 'Unix shell script',
			'kindPython'      : 'Python source',
			'kindJava'        : 'Java source',
			'kindRuby'        : 'Ruby source',
			'kindPerl'        : 'Perl script',
			'kindSQL'         : 'SQL source',
			'kindXML'         : 'XML document',
			'kindAWK'         : 'AWK source',
			'kindCSV'         : 'Comma separated values',
			'kindDOCBOOK'     : 'Docbook XML document',
			// images
			'kindImage'       : 'Image',
			'kindBMP'         : 'BMP image',
			'kindJPEG'        : 'JPEG image',
			'kindGIF'         : 'GIF Image',
			'kindPNG'         : 'PNG Image',
			'kindTIFF'        : 'TIFF image',
			'kindTGA'         : 'TGA image',
			'kindPSD'         : 'Adobe Photoshop image',
			'kindXBITMAP'     : 'X bitmap image',
			'kindPXM'         : 'Pixelmator image',
			// media
			'kindAudio'       : 'Audio media',
			'kindAudioMPEG'   : 'MPEG audio',
			'kindAudioMPEG4'  : 'MPEG-4 audio',
			'kindAudioMIDI'   : 'MIDI audio',
			'kindAudioOGG'    : 'Ogg Vorbis audio',
			'kindAudioWAV'    : 'WAV audio',
			'AudioPlaylist'   : 'MP3 playlist',
			'kindVideo'       : 'Video media',
			'kindVideoDV'     : 'DV movie',
			'kindVideoMPEG'   : 'MPEG movie',
			'kindVideoMPEG4'  : 'MPEG-4 movie',
			'kindVideoAVI'    : 'AVI movie',
			'kindVideoMOV'    : 'Quick Time movie',
			'kindVideoWM'     : 'Windows Media movie',
			'kindVideoFlash'  : 'Flash movie',
			'kindVideoMKV'    : 'Matroska movie',
			'kindVideoOGG'    : 'Ogg movie'
		}
	}
}

;/**
 * Arabic translation (Syrian Localization, it may differ if you aren't from Syria or any Country in Middle East)
 * @author Tawfek Daghistani <tawfekov@gmail.com>
 * @version 2011-07-09
 */
if (elFinder && elFinder.prototype && typeof(elFinder.prototype.i18) == 'object') {
	elFinder.prototype.i18.ar = {
		translator : 'Tawfek Daghistani &lt;tawfekov@gmail.com&gt;',
		language   : 'العربية',
		direction  : 'rtl',
		messages   : {

			/********************************** errors **********************************/
			'error'                : 'خطأ',
			'errUnknown'           : 'خطأ غير معروف .',
			'errUnknownCmd'        : 'أمر غير معروف .',
			'errJqui'              : 'إعدادات jQuery UI غير كاملة الرجاء التأكد من وجود كل من selectable, draggable and droppable',
			'errNode'              : '. موجود DOM إلى عنصر  elFinder تحتاج  ',
			'errURL'               : 'إعدادات خاطئة , عليك وضع الرابط ضمن الإعدادات',
			'errAccess'            : 'وصول مرفوض .',
			'errConnect'           : 'غير قادر على الاتصال بالخادم الخلفي  (backend)',
			'errAbort'             : 'تم فصل الإتصال',
			'errTimeout'           : 'مهلة الإتصال قد إنتهت .',
			'errNotFound'          : 'الخادم الخلفي غير موجود .',
			'errResponse'          : 'رد غير مقبول من الخادم الخلفي',
			'errConf'              : 'خطأ في الإعدادات الخاصة بالخادم الخلفي ',
			'errJSON'              : 'الميزة PHP JSON module غير موجودة ',
			'errNoVolumes'         : 'لا يمكن القراءة من أي من الوسائط الموجودة ',
			'errCmdParams'         : 'البيانات المرسلة للأمر غير مقبولة "$1".',
			'errDataNotJSON'       : 'المعلومات المرسلة ليست من نوع JSON ',
			'errDataEmpty'         : 'لا يوجد معلومات مرسلة',
			'errCmdReq'            : 'الخادم الخلفي يطلب وجود اسم الأمر ',
			'errOpen'              : 'غير قادر على فتح  "$1".',
			'errNotFolder'         : 'العنصر المختار ليس مجلد',
			'errNotFile'           : 'العنصر المختار ليس ملف',
			'errRead'              : 'غير قادر على القراءة "$1".',
			'errWrite'             : 'غير قادر على الكتابة "$1".',
			'errPerm'              : 'وصول مرفوض ',
			'errLocked'            : ' محمي و لا يمكن التعديل أو النقل أو إعادة التسمية"$1"',
			'errExists'            : ' موجود مسبقاً "$1"',
			'errInvName'           : 'الاسم مرفوض',
			'errFolderNotFound'    : 'المجلد غير موجود',
			'errFileNotFound'      : 'الملف غير موجود',
			'errTrgFolderNotFound' : 'الملف الهدف  "$1" غير موجود ',
			'errPopup'             : 'يمنعني المتصفح من إنشاء نافذة منبثقة , الرجاء تعديل الخيارات الخاصة  من إعدادات المتصفح ',
			'errMkdir'             : ' غير قادر على إنشاء مجلد جديد "$1".',
			'errMkfile'            : ' غير قادر على إنشاء ملف جديد"$1".',
			'errRename'            : 'غير قادر على إعادة تسمية ال  "$1".',
			'errCopyFrom'          : 'نسخ الملفات من الوسط المحدد "$1"غير مسموح.',
			'errCopyTo'            : 'نسخ الملفات إلى الوسط المحدد "$1" غير مسموح .',
			'errUploadCommon'      : 'خطأ أثناء عملية الرفع',
			'errUpload'            : 'غير قادر على رفع "$1".',
			'errUploadNoFiles'     : 'لم يتم رفع أي ملف ',
			'errMaxSize'           : 'حجم البيانات أكبر من الحجم المسموح به ',
			'errFileMaxSize'       : 'حجم الملف أكبر من الحجم المسموح به',
			'errUploadMime'        : 'نوع ملف غير مسموح ',
			'errUploadTransfer'    : '"$1" خطأ أثناء عملية النقل', 
			'errSave'              : 'غير قادر على الحفظ في  "$1".',
			'errCopy'              : 'غير قادر على النسخ إلى"$1".',
			'errMove'              : 'غير قادر على القص إلى "$1".',
			'errCopyInItself'      : 'غير قادر على نسخ الملف "$1" ضمن الملف نفسه.',
			'errRm'                : 'غير قادر على الحذف "$1".',
			'errExtract'           : 'غير قادر على استخراج الملفات من  "$1".',
			'errArchive'           : 'غير قادر على إنشاء ملف  مضغوط',
			'errArcType'           : 'نوع الملف المضغوط غير مدعومة',
			'errNoArchive'         : 'هذا الملف ليس ملف مضغوط أو ذو صسغة غير مدعومة ',
			'errCmdNoSupport'      : 'الخادم الخلفي لا يدعم هذا الأمر ',
			'errReplByChild'       : 'The folder “$1” can’t be replaced by an item it contains.',
			'errArcSymlinks'       : 'For security reason denied to unpack archives contains symlinks.',
			'errArcMaxSize'        : 'Archive files exceeds maximum allowed size.',
			
			/******************************* commands names ********************************/
			'cmdarchive'   : 'أنشئ مجلد مضغوط',
			'cmdback'      : 'الخلف',
			'cmdcopy'      : 'نسخ',
			'cmdcut'       : 'قص',
			'cmddownload'  : 'تحميل',
			'cmdduplicate' : 'تكرار',
			'cmdedit'      : 'تعديل الملف',
			'cmdextract'   : 'استخراج الملفات',
			'cmdforward'   : 'الأمام',
			'cmdgetfile'   : 'أختيار الملفات',
			'cmdhelp'      : 'عن هذا المشروع',
			'cmdhome'      : 'المجلد الرئيسي',
			'cmdinfo'      : 'معلومات ',
			'cmdmkdir'     : 'مجلد جديد',
			'cmdmkfile'    : 'ملف نصي جديد',
			'cmdopen'      : 'فتح',
			'cmdpaste'     : 'لصق',
			'cmdquicklook' : 'معاينة',
			'cmdreload'    : 'إعادة تحميل',
			'cmdrename'    : 'إعادة تسمية',
			'cmdrm'        : 'حذف',
			'cmdsearch'    : 'بحث عن ملفات',
			'cmdup'        : 'تغيير المسار إلى مستوى أعلى',
			'cmdupload'    : 'رفع ملفات',
			'cmdview'      : 'عرض',

			/*********************************** buttons ***********************************/ 
			'btnClose'  : 'إغلاق',
			'btnSave'   : 'حفظ',
			'btnRm'     : 'إزالة',
			'btnCancel' : 'إلغاء',
			'btnNo'     : 'لا',
			'btnYes'    : 'نعم',

			/******************************** notifications ********************************/
			'ntfopen'     : 'فتح مجلد',
			'ntffile'     : 'فتح ملف',
			'ntfreload'   : 'إعادة عرض محتويات المجلد ',
			'ntfmkdir'    : 'ينشئ المجلدات',
			'ntfmkfile'   : 'ينشئ الملفات',
			'ntfrm'       : 'حذف الملفات',
			'ntfcopy'     : 'نسخ الملفات',
			'ntfmove'     : 'نقل الملفات',
			'ntfprepare'  : 'تحضير لنسخ الملفات',
			'ntfrename'   : 'إعادة تسمية الملفات',
			'ntfupload'   : 'رفع الملفات',
			'ntfdownload' : 'تحميل الملفات',
			'ntfsave'     : 'حفظ الملفات',
			'ntfarchive'  : 'ينشئ ملف مضغوط',
			'ntfextract'  : 'استخراج ملفات من الملف المضغوط ',
			'ntfsearch'   : 'يبحث عن ملفات',
			'ntfsmth'     : 'يحضر لشيء ما >_<',

			/************************************ dates **********************************/
			'dateUnknown' : 'غير معلوم',
			'Today'       : 'اليوم',
			'Yesterday'   : 'البارحة',
			'Jan'         : 'كانون الثاني',
			'Feb'         : 'شباط',
			'Mar'         : 'آذار',
			'Apr'         : 'نيسان',
			'May'         : 'أيار',
			'Jun'         : 'حزيران',
			'Jul'         : 'تموز',
			'Aug'         : 'آب',
			'Sep'         : 'أيلول',
			'Oct'         : 'تشرين الأول',
			'Nov'         : 'تشرين الثاني',
			'Dec'         : 'كانون الأول ',

			/********************************** messages **********************************/
			'confirmReq'      : 'يرجى التأكيد',
			'confirmRm'       : 'هل انت متأكد من انك تريد الحذف<br/>لا يمكن التراجع عن هذه العملية ',
			'confirmRepl'     : 'استبدال الملف القديم بملف جديد ؟',
			'apllyAll'        : 'تطبيق على الكل',
			'name'            : 'الأسم',
			'size'            : 'الحجم',
			'perms'           : 'الصلاحيات',
			'modify'          : 'أخر تعديل',
			'kind'            : 'نوع الملف',
			'read'            : 'قراءة',
			'write'           : 'كتابة',
			'noaccess'        : 'وصول ممنوع',
			'and'             : 'و',
			'unknown'         : 'غير معروف',
			'selectall'       : 'تحديد كل الملفات',
			'selectfiles'     : 'تحديد ملفات',
			'selectffile'     : 'تحديد الملف الاول',
			'selectlfile'     : 'تحديد الملف الأخير',
			'viewlist'        : 'اعرض ك قائمة',
			'viewicons'       : 'اعرض ك ايقونات',
			'places'          : 'المواقع',
			'calc'            : 'حساب', 
			'path'            : 'مسار',
			'aliasfor'        : 'Alias for',
			'locked'          : 'مقفول',
			'dim'             : 'الابعاد',
			'files'           : 'ملفات',
			'folders'         : 'مجلدات',
			'items'           : 'عناصر',
			'yes'             : 'نعم',
			'no'              : 'لا',
			'link'            : 'اربتاط',
			'searcresult'     : 'نتائج البحث',  
			'selected'        : 'العناصر المحددة',
			'about'           : 'عن البرنامج',
			'shortcuts'       : 'الاختصارات',
			'help'            : 'مساعدة',
			'webfm'           : 'مدير ملفات الويب',
			'ver'             : 'رقم الإصدار',
			'protocolver'     : 'اصدار البرتوكول',
			'homepage'        : 'الصفحة الرئيسية',
			'docs'            : 'التعليمات',
			'github'          : 'شاركنا بتطوير المشروع على Github',
			'twitter'         : 'تابعنا على تويتر',
			'facebook'        : 'انضم إلينا على الفيس بوك',
			'team'            : 'الفريق',
			'chiefdev'        : 'رئيس المبرمجين',
			'developer'       : 'مبرمح',
			'contributor'     : 'مبرمح',
			'maintainer'      : 'مشارك',
			'translator'      : 'مترجم',
			'icons'           : 'أيقونات',
			'dontforget'      : 'and don\'t forget to take your towel',
			'shortcutsof'     : 'الاختصارات غير مفعلة',
			'dropFiles'       : 'لصق الملفات هنا',
			'or'              : 'أو',
			'selectForUpload' : 'اختر الملفات التي تريد رفعها',
			'moveFiles'       : 'قص الملفات',
			'copyFiles'       : 'نسخ الملفات',
			'rmFromPlaces'    : 'Remove from places',
			'untitled folder' : 'untitled folder',
			'untitled file.txt' : 'untitled file.txt',
			
			/********************************** mimetypes **********************************/
			'kindUnknown'     : 'غير معروف',
			'kindFolder'      : 'مجلد',
			'kindAlias'       : 'اختصار',
			'kindAliasBroken' : 'اختصار غير صالح',
			// applications
			'kindApp'         : 'برنامج',
			'kindPostscript'  : 'Postscript ملف',
			'kindMsOffice'    : 'Microsoft Office ملف',
			'kindMsWord'      : 'Microsoft Word ملف',
			'kindMsExcel'     : 'Microsoft Excel ملف',
			'kindMsPP'        : 'Microsoft Powerpoint عرض تقديمي ',
			'kindOO'          : 'Open Office ملف',
			'kindAppFlash'    : 'تطبيق فلاش',
			'kindPDF'         : 'ملف (PDF)',
			'kindTorrent'     : 'Bittorrent ملف',
			'kind7z'          : '7z ملف',
			'kindTAR'         : 'TAR ملف',
			'kindGZIP'        : 'GZIP ملف',
			'kindBZIP'        : 'BZIP ملف',
			'kindZIP'         : 'ZIP ملف',
			'kindRAR'         : 'RAR ملف',
			'kindJAR'         : 'Java JAR ملف',
			'kindTTF'         : 'True Type خط ',
			'kindOTF'         : 'Open Type خط ',
			'kindRPM'         : 'RPM ملف تنصيب',
			// texts
			'kindText'        : 'Text ملف',
			'kindTextPlain'   : 'مستند نصي',
			'kindPHP'         : 'PHP ملف نصي برمجي لـ',
			'kindCSS'         : 'Cascading style sheet',
			'kindHTML'        : 'HTML ملف',
			'kindJS'          : 'Javascript ملف نصي برمجي لـ',
			'kindRTF'         : 'Rich Text Format',
			'kindC'           : 'C ملف نصي برمجي لـ',
			'kindCHeader'     : 'C header ملف نصي برمجي لـ',
			'kindCPP'         : 'C++ ملف نصي برمجي لـ',
			'kindCPPHeader'   : 'C++ header ملف نصي برمجي لـ',
			'kindShell'       : 'Unix shell script',
			'kindPython'      : 'Python ملف نصي برمجي لـ',
			'kindJava'        : 'Java ملف نصي برمجي لـ',
			'kindRuby'        : 'Ruby ملف نصي برمجي لـ',
			'kindPerl'        : 'Perl script',
			'kindSQL'         : 'SQL ملف نصي برمجي لـ',
			'kindXML'         : 'XML ملف',
			'kindAWK'         : 'AWK ملف نصي برمجي لـ',
			'kindCSV'         : 'ملف CSV',
			'kindDOCBOOK'     : 'Docbook XML ملف',
			// images
			'kindصورة'       : 'صورة',
			'kindBMP'         : 'BMP صورة',
			'kindJPEG'        : 'JPEG صورة',
			'kindGIF'         : 'GIF صورة',
			'kindPNG'         : 'PNG صورة',
			'kindTIFF'        : 'TIFF صورة',
			'kindTGA'         : 'TGA صورة',
			'kindPSD'         : 'Adobe Photoshop صورة',
			'kindXBITMAP'     : 'X bitmap صورة',
			'kindPXM'         : 'Pixelmator صورة',
			// media
			'kindAudio'       : 'ملف صوتي',
			'kindAudioMPEG'   : 'MPEG ملف صوتي',
			'kindAudioMPEG4'  : 'MPEG-4 ملف صوتي',
			'kindAudioMIDI'   : 'MIDI ملف صوتي',
			'kindAudioOGG'    : 'Ogg Vorbis ملف صوتي',
			'kindAudioWAV'    : 'WAV ملف صوتي',
			'AudioPlaylist'   : 'MP3 قائمة تشغيل',
			'kindVideo'       : 'ملف فيديو',
			'kindVideoDV'     : 'DV ملف فيديو',
			'kindVideoMPEG'   : 'MPEG ملف فيديو',
			'kindVideoMPEG4'  : 'MPEG-4 ملف فيديو',
			'kindVideoAVI'    : 'AVI ملف فيديو',
			'kindVideoMOV'    : 'Quick Time ملف فيديو',
			'kindVideoWM'     : 'Windows Media ملف فيديو',
			'kindVideoFlash'  : 'Flash ملف فيديو',
			'kindVideoMKV'    : 'Matroska ملف فيديو',
			'kindVideoOGG'    : 'Ogg ملف فيديو'
		}
	}
}


 

;/**
 * Bulgarian translation
 * @author Stamo Petkov <stamo.petkov@gmail.com>
 * @version 2012-02-18
 */
if (elFinder && elFinder.prototype && typeof(elFinder.prototype.i18) == 'object') {
	elFinder.prototype.i18.bg = {
		translator : 'Stamo Petkov &lt;stamo.petkov@gmail.com&gt;',
		language   : 'Български',
		direction  : 'ltr',
		messages   : {
			
			/********************************** errors **********************************/
			'error'                : 'Грешка',
			'errUnknown'           : 'Непозната грешка.',
			'errUnknownCmd'        : 'Непозната команда.',
			'errJqui'              : 'Грешна конфигурация на jQuery UI. Компонентите selectable, draggable и droppable трябва да са включени.',
			'errNode'              : 'elFinder изисква да бъде създаден DOM елемент.',
			'errURL'               : 'Грешка в настройките на elFinder! не е зададена стойност на URL.',
			'errAccess'            : 'Достъп отказан.',
			'errConnect'           : 'Няма връзка със сървъра.',
			'errAbort'             : 'Връзката е прекъсната.',
			'errTimeout'           : 'Просрочена връзка.',
			'errNotFound'          : 'Сървърът не е намерен.', 
			'errResponse'          : 'Грешен отговор от сървъра.',
			'errConf'              : 'Грешни настройки на сървъра.', 
			'errJSON'              : 'Не е инсталиран модул на PHP за JSON.',
			'errNoVolumes'         : 'Няма дялове достъпни за четене.',
			'errCmdParams'         : 'Грешни параметри на командата "$1".',
			'errDataNotJSON'       : 'Данните не са JSON.',
			'errDataEmpty'         : 'Липсват данни.',
			'errCmdReq'            : 'Запитването от сървъра изисква име на команда.',
			'errOpen'              : 'Не мога да отворя "$1".',
			'errNotFolder'         : 'Обектът не е папка.',
			'errNotFile'           : 'Обектът не е фаил.',
			'errRead'              : 'Не мога да прочета "$1".',
			'errWrite'             : 'Не мога да пиша в "$1".',
			'errPerm'              : 'Разрешение отказано.',
			'errLocked'            : '"$1" е заключен и не може да бъде преименуван, местен или премахван.',
			'errExists'            : 'Вече съществува файл с име "$1"',
			'errInvName'           : 'Грешно име на фаил.',
			'errFolderNotFound'    : 'Папката не е открита.',
			'errFileNotFound'      : 'Фаилът не е открит.',
			'errTrgFolderNotFound' : 'Целевата папка "$1" не е намерена.',
			'errPopup'             : 'Браузъра блокира отварянето на прозорец. За да отворите файла, разрешете отварянето в настройките на браузъра.',
			'errMkdir'             : 'Не мога да създам папка"$1".',
			'errMkfile'            : 'Не мога да създам файл "$1".',
			'errRename'            : 'Не мога да преименувам "$1".',
			'errCopyFrom'          : 'Копирането на файлове от том "$1" не е разрешено.',
			'errCopyTo'            : 'Копирането на файлове в том "$1" не е разрешено.',
			'errUploadCommon'      : 'Грешка при качване.',
			'errUpload'            : 'Не мога да кача "$1".',
			'errUploadNoFiles'     : 'Не са намерени файлове за качване.',
			'errMaxSize'           : 'Данните превишават максимално допостумия размер.',
			'errFileMaxSize'       : 'Файла превишава максимално допустимия размер.',
			'errUploadMime'        : 'Не е позволен тип на файла.',
			'errUploadTransfer'    : '"$1" грешка при предаване.', 
			'errSave'              : 'Не мога да запиша "$1".',
			'errCopy'              : 'Не мога да копирам "$1".',
			'errMove'              : 'Не мога да преместя "$1".',
			'errCopyInItself'      : 'Не мога да копирам "$1" върху самия него.',
			'errRm'                : 'Не мога да премахна "$1".',
			'errExtract'           : 'Не мога да извлеча файловете от "$1".',
			'errArchive'           : 'Не мога да създам архив.',
			'errArcType'           : 'Неподдържан тип на архива.',
			'errNoArchive'         : 'Файлът не е архив или е от неподдържан тип.',
			'errCmdNoSupport'      : 'Сървъра не поддържа тази команда.', 
			'errReplByChild'       : 'Папката “$1” не може да бъде заменена от съдържащ се в нея елемент.',
			'errArcSymlinks'       : 'От съображения за сигурност няма да бъдат разопаковани архиви съдържащи symlinks.',
			'errArcMaxSize'        : 'Архивните файлове превишават максимално допустимия размер.',
			'errResize'            : 'Не мога да преоразмеря "$1".',
			'errUsupportType'      : 'Неподдържан тип файл.',
			
			/******************************* commands names ********************************/
			'cmdarchive'   : 'Създай архив',
			'cmdback'      : 'Назад',
			'cmdcopy'      : 'Копирай',
			'cmdcut'       : 'Изрежи',
			'cmddownload'  : 'Свали',
			'cmdduplicate' : 'Дублирай',
			'cmdedit'      : 'Редактирай файл',
			'cmdextract'   : 'Извлечи файловете от архива',
			'cmdforward'   : 'Напред',
			'cmdgetfile'   : 'Избери файлове',
			'cmdhelp'      : 'За тази програма',
			'cmdhome'      : 'Начало',
			'cmdinfo'      : 'Информация',
			'cmdmkdir'     : 'Нова папка',
			'cmdmkfile'    : 'Нов текстови файл',
			'cmdopen'      : 'Отвори',
			'cmdpaste'     : 'Вмъкни',
			'cmdquicklook' : 'Преглед',
			'cmdreload'    : 'Презареди',
			'cmdrename'    : 'Преименувай',
			'cmdrm'        : 'Изтрий',
			'cmdsearch'    : 'Намери файлове',
			'cmdup'        : 'Една директория нагоре',
			'cmdupload'    : 'Качи файловете',
			'cmdview'      : 'Виж',
			'cmdresize'    : 'Размер на изображение',
			'cmdsort'      : 'Подреди',
			
			/*********************************** buttons ***********************************/ 
			'btnClose'  : 'Затвори',
			'btnSave'   : 'Запиши',
			'btnRm'     : 'Премахни',
			'btnApply'  : 'Приложи',
			'btnCancel' : 'Отказ',
			'btnNo'     : 'Не',
			'btnYes'    : 'Да',
			
			/******************************** notifications ********************************/
			'ntfopen'     : 'Отваряне на папка',
			'ntffile'     : 'Отваряне на файл',
			'ntfreload'   : 'Презареждане съдържанието на папка',
			'ntfmkdir'    : 'Създавам директория',
			'ntfmkfile'   : 'Създавам файл',
			'ntfrm'       : 'Изтриване на файлове',
			'ntfcopy'     : 'Копиране на файлове',
			'ntfmove'     : 'Преместване на файлове',
			'ntfprepare'  : 'Подготовка за копиране на файлове',
			'ntfrename'   : 'Преименуване на файлове',
			'ntfupload'   : 'Качвам файлове',
			'ntfdownload' : 'Свалям файлове',
			'ntfsave'     : 'Запис на файлове',
			'ntfarchive'  : 'Създавам архив',
			'ntfextract'  : 'Извличам файловете от архив',
			'ntfsearch'   : 'Търся файлове',
			'ntfsmth'     : 'Зает съм >_<',
			'ntfloadimg'  : 'Зареждам изображения',
			
			/************************************ dates **********************************/
			'dateUnknown' : 'неизвестна',
			'Today'       : 'Днес',
			'Yesterday'   : 'Вчера',
			'Jan'         : 'Яну',
			'Feb'         : 'Фев',
			'Mar'         : 'Мар',
			'Apr'         : 'Апр',
			'May'         : 'Май',
			'Jun'         : 'Юни',
			'Jul'         : 'Юли',
			'Aug'         : 'Авг',
			'Sep'         : 'Сеп',
			'Oct'         : 'Окт',
			'Nov'         : 'Ное',
			'Dec'         : 'Дек',
			
			/******************************** sort variants ********************************/
			'sortnameDirsFirst' : 'по име (първо папките)', 
			'sortkindDirsFirst' : 'по вид (първо папките)', 
			'sortsizeDirsFirst' : 'по размер (първо папките)', 
			'sortdateDirsFirst' : 'по дата (първо папките)', 
			'sortname'          : 'по име', 
			'sortkind'          : 'по вид', 
			'sortsize'          : 'по размер',
			'sortdate'          : 'по дата',
			
			/********************************** messages **********************************/
			'confirmReq'      : 'Изисква се подтвърждение',
			'confirmRm'       : 'Сигурни ли сте, че желаете да премахнете файловете?<br/>Това действие е необратимо!',
			'confirmRepl'     : 'Да заменя ли стария фаил с новия?',
			'apllyAll'        : 'Приложи за всички',
			'name'            : 'Име',
			'size'            : 'Размер',
			'perms'           : 'Привилегии',
			'modify'          : 'Променен',
			'kind'            : 'Вид',
			'read'            : 'четене',
			'write'           : 'запис',
			'noaccess'        : 'без достъп',
			'and'             : 'и',
			'unknown'         : 'непознат',
			'selectall'       : 'Избери всички файлове',
			'selectfiles'     : 'Избери фаил(ове)',
			'selectffile'     : 'Избери първият файл',
			'selectlfile'     : 'Избери последният файл',
			'viewlist'        : 'Изглед списък',
			'viewicons'       : 'Изглед икони',
			'places'          : 'Места',
			'calc'            : 'Изчисли', 
			'path'            : 'Път',
			'aliasfor'        : 'Връзка към',
			'locked'          : 'Заключен',
			'dim'             : 'Размери',
			'files'           : 'Файлове',
			'folders'         : 'Папки',
			'items'           : 'Елементи',
			'yes'             : 'да',
			'no'              : 'не',
			'link'            : 'Връзка',
			'searcresult'     : 'Резултати от търсенето',  
			'selected'        : 'Избрани елементи',
			'about'           : 'За',
			'shortcuts'       : 'преки пътища',
			'help'            : 'Помощ',
			'webfm'           : 'Файлов менаджер за web',
			'ver'             : 'Версия',
			'protocolver'        : 'версия на протокола',
			'homepage'        : 'Начало',
			'docs'            : 'Документация',
			'github'          : 'Разклонение в Github',
			'twitter'         : 'Последвайте ни в Twitter',
			'facebook'        : 'Присъединете се към нас във Facebook',
			'team'            : 'Екип',
			'chiefdev'        : 'Главен разработчик',
			'developer'       : 'разработчик',
			'contributor'     : 'сътрудник',
			'maintainer'      : 'поддръжка',
			'translator'      : 'преводач',
			'icons'           : 'Икони',
			'dontforget'      : 'и не забравяйте да си вземете кърпата',
			'shortcutsof'     : 'Преките пътища са изключени',
			'dropFiles'       : 'Пуснете файловете тук',
			'or'              : 'или',
			'selectForUpload' : 'Изберете файлове за качване',
			'moveFiles'       : 'Премести файлове',
			'copyFiles'       : 'Копирай файлове',
			'rmFromPlaces'    : 'Премахни от Места',
			'untitled folder' : 'Неозаглавена папка',
			'untitled file.txt' : 'неозаглавен_файл.txt',
			'aspectRatio'     : 'Отношение',
			'scale'           : 'Мащаб',
			'width'           : 'Ширина',
			'height'          : 'Височина',
			'mode'            : 'Режим',
			'resize'          : 'Преоразмери',
			'crop'            : 'Отрежи',

			
			/********************************** mimetypes **********************************/
			'kindUnknown'     : 'Непознат',
			'kindFolder'      : 'Папка',
			'kindAlias'       : 'Връзка',
			'kindAliasBroken' : 'Счупена връзка',
			// applications
			'kindApp'         : 'Приложение',
			'kindPostscript'  : 'Postscript документ',
			'kindMsOffice'    : 'Microsoft Office документ',
			'kindMsWord'      : 'Microsoft Word документ',
			'kindMsExcel'     : 'Microsoft Excel документ',
			'kindMsPP'        : 'Microsoft Powerpoint презентация',
			'kindOO'          : 'Open Office документ',
			'kindAppFlash'    : 'Flash приложение',
			'kindPDF'         : 'PDF документ',
			'kindTorrent'     : 'Bittorrent файл',
			'kind7z'          : '7z архив',
			'kindTAR'         : 'TAR архив',
			'kindGZIP'        : 'GZIP архив',
			'kindBZIP'        : 'BZIP архив',
			'kindZIP'         : 'ZIP архив',
			'kindRAR'         : 'RAR архив',
			'kindJAR'         : 'Java JAR файл',
			'kindTTF'         : 'True Type шрифт',
			'kindOTF'         : 'Open Type шрифт',
			'kindRPM'         : 'RPM пакет',
			// texts
			'kindText'        : 'Текстов документ',
			'kindTextPlain'   : 'Чист текст',
			'kindPHP'         : 'PHP изходен код',
			'kindCSS'         : 'CSS таблица със стилове',
			'kindHTML'        : 'HTML документ',
			'kindJS'          : 'Javascript изходен код',
			'kindRTF'         : 'RTF текстови файл',
			'kindC'           : 'C изходен код',
			'kindCHeader'     : 'C header изходен код',
			'kindCPP'         : 'C++ изходен код',
			'kindCPPHeader'   : 'C++ header изходен код',
			'kindShell'       : 'Unix shell script',
			'kindPython'      : 'Python изходен код',
			'kindJava'        : 'Java изходен код',
			'kindRuby'        : 'Ruby изходен код',
			'kindPerl'        : 'Perl изходен код',
			'kindSQL'         : 'SQL изходен код',
			'kindXML'         : 'XML документ',
			'kindAWK'         : 'AWK изходен код',
			'kindCSV'         : 'CSV стойности разделени със запетая',
			'kindDOCBOOK'     : 'Docbook XML документ',
			// images
			'kindImage'       : 'Изображение',
			'kindBMP'         : 'BMP изображение',
			'kindJPEG'        : 'JPEG изображение',
			'kindGIF'         : 'GIF изображение',
			'kindPNG'         : 'PNG изображение',
			'kindTIFF'        : 'TIFF изображение',
			'kindTGA'         : 'TGA изображение',
			'kindPSD'         : 'Adobe Photoshop изображение',
			'kindXBITMAP'     : 'X bitmap изображение',
			'kindPXM'         : 'Pixelmator изображение',
			// media
			'kindAudio'       : 'Аудио медия',
			'kindAudioMPEG'   : 'MPEG звук',
			'kindAudioMPEG4'  : 'MPEG-4 звук',
			'kindAudioMIDI'   : 'MIDI звук',
			'kindAudioOGG'    : 'Ogg Vorbis звук',
			'kindAudioWAV'    : 'WAV звук',
			'AudioPlaylist'   : 'MP3 списък за изпълнение',
			'kindVideo'       : 'Видео медия',
			'kindVideoDV'     : 'DV филм',
			'kindVideoMPEG'   : 'MPEG филм',
			'kindVideoMPEG4'  : 'MPEG-4 филм',
			'kindVideoAVI'    : 'AVI филм',
			'kindVideoMOV'    : 'Quick Time филм',
			'kindVideoWM'     : 'Windows Media филм',
			'kindVideoFlash'  : 'Flash филм',
			'kindVideoMKV'    : 'Matroska филм',
			'kindVideoOGG'    : 'Ogg филм'
		}
	}
}
;/**
 * Catalan translation
 * @author Sergio Jovani <lesergi@gmail.com>
 * @version 2011-11-13
 */
if (elFinder && elFinder.prototype && typeof(elFinder.prototype.i18) == 'object') {
	elFinder.prototype.i18.ca = {
		translator : 'Sergio Jovani &lt;lesergi@gmail.com&gt;',
		language   : 'Català',
		direction  : 'ltr',
		messages   : {
			
			/********************************** errors **********************************/
			'error'                : 'Error',
			'errUnknown'           : 'Error desconegut.',
			'errUnknownCmd'        : 'Ordre desconeguda.',
			'errJqui'              : 'La configuració de jQuery UI no és vàlida. S\'han d\'incloure els components "selectable", "draggable" i "droppable".',
			'errNode'              : 'elFinder necessita crear elements DOM.',
			'errURL'               : 'La configuració de l\'elFinder no és vàlida! L\'opció URL no està configurada.',
			'errAccess'            : 'Accés denegat.',
			'errConnect'           : 'No s\'ha pogut connectar amb el rerefons.',
			'errAbort'             : 'S\'ha interromput la connexió.',
			'errTimeout'           : 'Temps de connexió excedit.',
			'errNotFound'          : 'No s\'ha trobat el rerefons.',
			'errResponse'          : 'La resposta del rerefons no és vàlida.',
			'errConf'              : 'La configuració del rerefons no és vàlida.',
			'errJSON'              : 'No està instal·lat el mòdul JSON del PHP.',
			'errNoVolumes'         : 'No s\'han trobat volums llegibles.',
			'errCmdParams'         : 'Els paràmetres per l\'ordre "$1" no són vàlids.',
			'errDataNotJSON'       : 'Les dades no són JSON.',
			'errDataEmpty'         : 'Les dades estan buides.',
			'errCmdReq'            : 'La sol·licitud del rerefons necessita el nom de l\'ordre.',
			'errOpen'              : 'No s\'ha pogut obrir "$1".',
			'errNotFolder'         : 'L\'objecte no és una carpeta.',
			'errNotFile'           : 'L\'objecte no és un fitxer.',
			'errRead'              : 'No s\'ha pogut llegir "$1".',
			'errWrite'             : 'No s\'ha pogut escriure a "$1".',
			'errPerm'              : 'Permís denegat.',
			'errLocked'            : '"$1" està bloquejat i no podeu canviar-li el nom, moure-lo ni suprimir-lo.',
			'errExists'            : 'Ja existeix un fitxer anomenat "$1".',
			'errInvName'           : 'El nom de fitxer no és vàlid.',
			'errFolderNotFound'    : 'No s\'ha trobat la carpeta.',
			'errFileNotFound'      : 'No s\'ha trobat el fitxer.',
			'errTrgFolderNotFound' : 'No s\'ha trobat la carpeta de destí "$1".',
			'errPopup'             : 'El navegador ha evitat obrir una finestra emergent. Autoritzeu-la per obrir el fitxer.',
			'errMkdir'             : 'No s\'ha pogut crear la carpeta "$1".',
			'errMkfile'            : 'No s\'ha pogut crear el fitxer "$1".',
			'errRename'            : 'No s\'ha pogut canviar el nom de "$1".',
			'errCopyFrom'          : 'No està permès copiar fitxers des del volum "$1".',
			'errCopyTo'            : 'No està permès copiar fitxers al volum "$1".',
			'errUploadCommon'      : 'S\'ha produït un error en la càrrega.',
			'errUpload'            : 'No s\'ha pogut carregar "$1".',
			'errUploadNoFiles'     : 'No s\'han trobat fitxers per carregar.',
			'errMaxSize'           : 'Les dades excedeixen la mida màxima permesa.',
			'errFileMaxSize'       : 'El fitxer excedeix la mida màxima permesa.',
			'errUploadMime'        : 'El tipus de fitxer no està permès.',
			'errUploadTransfer'    : 'S\'ha produït un error en transferir "$1".', 
			'errSave'              : 'No s\'ha pogut desar "$1".',
			'errCopy'              : 'No s\'ha pogut copiar "$1".',
			'errMove'              : 'No s\'ha pogut moure "$1".',
			'errCopyInItself'      : 'No s\'ha pogut copiar "$1" a si mateix.',
			'errRm'                : 'No s\'ha pogut suprimir "$1".',
			'errExtract'           : 'No s\'han pogut extreure els fitxers de "$1".',
			'errArchive'           : 'No s\'ha pogut crear l\'arxiu.',
			'errArcType'           : 'El tipus d\'arxiu no està suportat.',
			'errNoArchive'         : 'El fitxer no és un arxiu o és un tipus no suportat.',
			'errCmdNoSupport'      : 'El rerefons no suporta aquesta ordre.',
			'errReplByChild'       : 'No es pot reemplaçar la carpeta “$1” per un element que conté.',
			'errArcSymlinks'       : 'Per raons de seguretat, no es permet extreure arxius que contenen enllaços simbòlics.',
			'errArcMaxSize'        : 'Els fitxers de l\'arxiu excedeixen la mida màxima permesa.',
			'errResize'            : 'No s\'ha pogut redimensionar "$1".',
			'errUsupportType'      : 'El tipus de fitxer no està suportat.',
			
			/******************************* commands names ********************************/
			'cmdarchive'   : 'Crea arxiu',
			'cmdback'      : 'Enrere',
			'cmdcopy'      : 'Copia',
			'cmdcut'       : 'Retalla',
			'cmddownload'  : 'Descarrega',
			'cmdduplicate' : 'Duplica',
			'cmdedit'      : 'Edita el fitxer',
			'cmdextract'   : 'Extreu els fitxers de l\'arxiu',
			'cmdforward'   : 'Endavant',
			'cmdgetfile'   : 'Selecciona els fitxers',
			'cmdhelp'      : 'Quant a aquest programari',
			'cmdhome'      : 'Inici',
			'cmdinfo'      : 'Obté informació',
			'cmdmkdir'     : 'Nova carpeta',
			'cmdmkfile'    : 'Nou fitxer de text',
			'cmdopen'      : 'Obre',
			'cmdpaste'     : 'Enganxa',
			'cmdquicklook' : 'Previsualitza',
			'cmdreload'    : 'Torna a carregar',
			'cmdrename'    : 'Canvia el nom',
			'cmdrm'        : 'Suprimeix',
			'cmdsearch'    : 'Cerca fitxers',
			'cmdup'        : 'Vés al directori superior',
			'cmdupload'    : 'Carrega fitxers',
			'cmdview'      : 'Visualitza',
			'cmdresize'    : 'Redimensiona la imatge',
			'cmdsort'      : 'Ordena',
			
			/*********************************** buttons ***********************************/ 
			'btnClose'  : 'Tanca',
			'btnSave'   : 'Desa',
			'btnRm'     : 'Suprimeix',
			'btnApply'  : 'Aplica',
			'btnCancel' : 'Cancel·la',
			'btnNo'     : 'No',
			'btnYes'    : 'Sí',
			
			/******************************** notifications ********************************/
			'ntfopen'     : 'S\'està obrint la carpeta',
			'ntffile'     : 'S\'està obrint el fitxer',
			'ntfreload'   : 'S\'està tornant a carregar el contingut de la carpeta',
			'ntfmkdir'    : 'S\'està creant el directori',
			'ntfmkfile'   : 'S\'estan creant el fitxers',
			'ntfrm'       : 'S\'estan suprimint els fitxers',
			'ntfcopy'     : 'S\'estan copiant els fitxers',
			'ntfmove'     : 'S\'estan movent els fitxers',
			'ntfprepare'  : 'S\'està preparant per copiar fitxers',
			'ntfrename'   : 'S\'estan canviant els noms del fitxers',
			'ntfupload'   : 'S\'estan carregant els fitxers',
			'ntfdownload' : 'S\'estan descarregant els fitxers',
			'ntfsave'     : 'S\'estan desant els fitxers',
			'ntfarchive'  : 'S\'està creant l\'arxiu',
			'ntfextract'  : 'S\'estan extreient els fitxers de l\'arxiu',
			'ntfsearch'   : 'S\'estan cercant els fitxers',
			'ntfsmth'     : 'S\'estan realitzant operacions',
      'ntfloadimg'  : 'S\'està carregant la imatge',
			
			/************************************ dates **********************************/
			'dateUnknown' : 'desconegut',
			'Today'       : 'Avui',
			'Yesterday'   : 'Ahir',
			'Jan'         : 'gen.',
			'Feb'         : 'febr.',
			'Mar'         : 'març',
			'Apr'         : 'abr.',
			'May'         : 'maig',
			'Jun'         : 'juny',
			'Jul'         : 'jul.',
			'Aug'         : 'ag.',
			'Sep'         : 'set.',
			'Oct'         : 'oct.',
			'Nov'         : 'nov.',
			'Dec'         : 'des.',
			
			/******************************** sort variants ********************************/
			'sortnameDirsFirst' : 'per nom (carpetes primer)', 
			'sortkindDirsFirst' : 'per tipus (carpetes primer)', 
			'sortsizeDirsFirst' : 'per mida (carpetes primer)', 
			'sortdateDirsFirst' : 'per data (carpetes primer)', 
			'sortname'          : 'per nom', 
			'sortkind'          : 'per tipus', 
			'sortsize'          : 'per mida',
			'sortdate'          : 'per data',
			
			/********************************** messages **********************************/
			'confirmReq'      : 'Es necessita confirmació',
			'confirmRm'       : 'Voleu suprimir els fitxers?<br />L\'acció es podrà desfer!',
			'confirmRepl'     : 'Voleu reemplaçar el fitxer antic amb el nou?',
			'apllyAll'        : 'Aplica a tot',
			'name'            : 'Nom',
			'size'            : 'Mida',
			'perms'           : 'Permisos',
			'modify'          : 'Modificat',
			'kind'            : 'Tipus',
			'read'            : 'llegir',
			'write'           : 'escriure',
			'noaccess'        : 'sense accés',
			'and'             : 'i',
			'unknown'         : 'desconegut',
			'selectall'       : 'Selecciona tots els fitxers',
			'selectfiles'     : 'Selecciona el(s) fitxer(s)',
			'selectffile'     : 'Selecciona el primer fitxer',
			'selectlfile'     : 'Selecciona l\'últim fitxer',
			'viewlist'        : 'Vista en llista',
			'viewicons'       : 'Vista en icones',
			'places'          : 'Llocs',
			'calc'            : 'Calcula', 
			'path'            : 'Camí',
			'aliasfor'        : 'Àlies per',
			'locked'          : 'Bloquejat',
			'dim'             : 'Dimensions',
			'files'           : 'Fitxers',
			'folders'         : 'Carpetes',
			'items'           : 'Elements',
			'yes'             : 'sí',
			'no'              : 'no',
			'link'            : 'Enllaç',
			'searcresult'     : 'Resultats de la cerca',  
			'selected'        : 'Elements seleccionats',
			'about'           : 'Quant a',
			'shortcuts'       : 'Dreceres',
			'help'            : 'Ajuda',
			'webfm'           : 'Gestor de fitxers web',
			'ver'             : 'Versió',
			'protocolver'     : 'versió de protocol',
			'homepage'        : 'Pàgina del projecte',
			'docs'            : 'Documentació',
			'github'          : 'Bifurca\'ns a GitHub',
			'twitter'         : 'Segueix-nos a Twitter',
			'facebook'        : 'Uniu-vos a Facebook',
			'team'            : 'Equip',
			'chiefdev'        : 'cap desenvolupador',
			'developer'       : 'desenvolupador',
			'contributor'     : 'col·laborador',
			'maintainer'      : 'mantenidor',
			'translator'      : 'traductor',
			'icons'           : 'Icones',
			'dontforget'      : 'i no oblideu agafar la vostra tovallola',
			'shortcutsof'     : 'Les dreceres estan inhabilitades',
			'dropFiles'       : 'Arrossegueu els fitxers aquí',
			'or'              : 'o',
			'selectForUpload' : 'Seleccioneu els fitxer a carregar',
			'moveFiles'       : 'Mou els fitxers',
			'copyFiles'       : 'Copia els fitxers',
			'rmFromPlaces'    : 'Suprimeix dels llocs',
			'untitled folder' : 'carpeta sense nom',
			'untitled file.txt' : 'fitxer sense nom.txt',
			'aspectRatio'     : 'Relació d\'aspecte',
			'scale'           : 'Escala',
			'width'           : 'Amplada',
			'height'          : 'Alçada',
      'mode'            : 'Mode',
      'resize'          : 'Redimensiona',
      'crop'            : 'Retalla',
			
			/********************************** mimetypes **********************************/
			'kindUnknown'     : 'Desconegut',
			'kindFolder'      : 'Carpeta',
			'kindAlias'       : 'Àlies',
			'kindAliasBroken' : 'Àlies no vàlid',
			// applications
			'kindApp'         : 'Aplicació',
			'kindPostscript'  : 'Document Postscript',
			'kindMsOffice'    : 'Document del Microsoft Office',
			'kindMsWord'      : 'Document del Microsoft Word',
			'kindMsExcel'     : 'Document del Microsoft Excel',
			'kindMsPP'        : 'Presentació del Microsoft Powerpoint',
			'kindOO'          : 'Document de l\'Open Office',
			'kindAppFlash'    : 'Aplicació Flash',
			'kindPDF'         : 'Document PDF',
			'kindTorrent'     : 'Fitxer Bittorrent',
			'kind7z'          : 'Arxiu 7z',
			'kindTAR'         : 'Arxiu TAR',
			'kindGZIP'        : 'Arxiu GZIP',
			'kindBZIP'        : 'Arxiu BZIP',
			'kindZIP'         : 'Arxiu ZIP',
			'kindRAR'         : 'Arxiu RAR',
			'kindJAR'         : 'Fitxer JAR de Java',
			'kindTTF'         : 'Tipus de lletra True Type',
			'kindOTF'         : 'Tipus de lletra Open Type',
			'kindRPM'         : 'Paquet RPM',
			// texts
			'kindText'        : 'Document de text',
			'kindTextPlain'   : 'Document de text net',
			'kindPHP'         : 'Codi PHP',
			'kindCSS'         : 'Full d\'estils CSS',
			'kindHTML'        : 'Document HTML',
			'kindJS'          : 'Codi Javascript',
			'kindRTF'         : 'Document RTF',
			'kindC'           : 'Codi C',
			'kindCHeader'     : 'Codi de caçalera C',
			'kindCPP'         : 'Codi C++',
			'kindCPPHeader'   : 'Codi de caçalera C++',
			'kindShell'       : 'Script Unix',
			'kindPython'      : 'Codi Python',
			'kindJava'        : 'Codi Java',
			'kindRuby'        : 'Codi Ruby',
			'kindPerl'        : 'Script Perl',
			'kindSQL'         : 'Codi SQL',
			'kindXML'         : 'Document XML',
			'kindAWK'         : 'Codi AWK',
			'kindCSV'         : 'Document CSV',
			'kindDOCBOOK'     : 'Document XML de Docbook',
			// images
			'kindImage'       : 'Imatge',
			'kindBMP'         : 'Imatge BMP',
			'kindJPEG'        : 'Imatge JPEG',
			'kindGIF'         : 'Imatge GIF',
			'kindPNG'         : 'Imatge PNG',
			'kindTIFF'        : 'Imatge TIFF',
			'kindTGA'         : 'Imatge TGA',
			'kindPSD'         : 'Imatge Adobe Photoshop',
			'kindXBITMAP'     : 'Imatge X bitmap',
			'kindPXM'         : 'Imatge Pixelmator',
			// media
			'kindAudio'       : 'Fitxer d\'àudio',
			'kindAudioMPEG'   : 'Fitxer d\'àudio MPEG',
			'kindAudioMPEG4'  : 'Fitxer d\'àudio MPEG-4',
			'kindAudioMIDI'   : 'Fitxer d\'àudio MIDI',
			'kindAudioOGG'    : 'Fitxer d\'àudio Ogg Vorbis',
			'kindAudioWAV'    : 'Fitxer d\'àudio WAV',
			'AudioPlaylist'   : 'Llista de reproducció MP3',
			'kindVideo'       : 'Fitxer de vídeo',
			'kindVideoDV'     : 'Fitxer de vídeo DV',
			'kindVideoMPEG'   : 'Fitxer de vídeo MPEG',
			'kindVideoMPEG4'  : 'Fitxer de vídeo MPEG-4',
			'kindVideoAVI'    : 'Fitxer de vídeo AVI',
			'kindVideoMOV'    : 'Fitxer de vídeo Quick Time',
			'kindVideoWM'     : 'Fitxer de vídeo Windows Media',
			'kindVideoFlash'  : 'Fitxer de vídeo Flash',
			'kindVideoMKV'    : 'Fitxer de vídeo Matroska',
			'kindVideoOGG'    : 'Fitxer de vídeo Ogg'
		}
	}
}

;/**
 * Czech translation
 * @author Jay Gridley <gridley.jay@hotmail.com>
 * @version 2012-03-23
 */
if (elFinder && elFinder.prototype && typeof(elFinder.prototype.i18) == 'object') {
	elFinder.prototype.i18.cs = {
		translator : 'Jay Gridley &lt;gridley.jay@hotmail.com&gt;',
		language   : 'čeština',
		direction  : 'ltr',
		dateFormat : 'd. m. Y H:i',
		fancyDateFormat : '$1 H:i',
		messages   : {

			/********************************** errors **********************************/
			'error'                : 'Chyba',
			'errUnknown'           : 'Neznámá chyba.',
			'errUnknownCmd'        : 'Neznámý příkaz.',
			'errJqui'              : 'Nedostačující konfigurace jQuery UI. Musí být zahrnuty komponenty Selectable, Draggable a Droppable.',
			'errNode'              : 'elFinder vyžaduje vytvořený DOM Element.',
			'errURL'               : 'Chybná konfigurace elFinderu! Není nastavena hodnota URL.',
			'errAccess'            : 'Přístup zamítnut.',
			'errConnect'           : 'Nepodařilo se připojit k backendu (konektoru).',
			'errAbort'             : 'Připojení zrušeno.',
			'errTimeout'           : 'Vypšel limit pro připojení.',
			'errNotFound'          : 'Backend nenalezen.',
			'errResponse'          : 'Nesprávná odpověď backendu.',
			'errConf'              : 'Nepsrávná konfigurace backendu.',
			'errJSON'              : 'PHP modul JSON není nainstalován.',
			'errNoVolumes'         : 'Není dostupný čitelný oddíl.',
			'errCmdParams'         : 'Nesprávné parametry příkazu "$1".',
			'errDataNotJSON'       : 'Data nejsou ve formátu JSON.',
			'errDataEmpty'         : 'Data jsou prázdná.',
			'errCmdReq'            : 'Dotaz backendu vyžaduje název příkazu.',
			'errOpen'              : 'Chyba při otevírání "$1".',
			'errNotFolder'         : 'Objekt není složka.',
			'errNotFile'           : 'Objekt není soubor.',
			'errRead'              : 'Chyba při čtení "$1".',
			'errWrite'             : 'Chyba při zápisu do "$1".',
			'errPerm'              : 'Přístup odepřen.',
			'errLocked'            : '"$1" je uzamčený a nemůže být přejmenován, přesunut nebo smazán.',
			'errExists'            : 'Soubor s názvem "$1" již existuje.',
			'errInvName'           : 'Nesprávný název souboru.',
			'errFolderNotFound'    : 'Složka nenalezena.',
			'errFileNotFound'      : 'Soubor nenalezen.',
			'errTrgFolderNotFound' : 'Cílová složka "$1" nenalezena.',
			'errPopup'             : 'Prohlížeč zabránil otevření vyskakovacího okna. K otevření souboru, povolte vyskakovací okno v prohlížeči.',
			'errMkdir'             : 'Nepodařilo se vytvořit složku "$1".',
			'errMkfile'            : 'Nepodařilo se vytvořit soubor "$1".',
			'errRename'            : 'Nepodařilo se přejmenovat "$1".',
			'errCopyFrom'          : 'Kopírování souborů z oddílu "$1" není povoleno.',
			'errCopyTo'            : 'Kopírování souborů do oddílu "$1" není povoleno.',
			'errUploadCommon'      : 'Chyba nahrávání.',
			'errUpload'            : 'Nepodařilo se nahrát "$1".',
			'errUploadNoFiles'     : 'Nejsou vybrány žádné soubory k nahrání.',
			'errMaxSize'           : 'Překročena maximální povolená velikost dat.',
			'errFileMaxSize'       : 'Překročena maximální povolená velikost souboru.',
			'errUploadMime'        : 'Nepovolený typ souboru.',
			'errUploadTransfer'    : '"$1" chyba přenosu.',
			'errSave'              : '"$1" nelze uložit.',
			'errCopy'              : '"$1" nelze zkopírovat.',
			'errMove'              : '"$1" nelze přemístit.',
			'errCopyInItself'      : '"$1" nelze zkopírovat do sebe sama.',
			'errRm'                : '"$1" nelze odstranit.',
			'errExtract'           : 'Nelze extrahovat soubory z "$1".',
			'errArchive'           : 'Nelze vytvořit archív.',
			'errArcType'           : 'Nepodporovaný typ archívu.',
			'errNoArchive'         : 'Soubor není archív nebo má nepodporovaný formát.',
			'errCmdNoSupport'      : 'Backend tento příkaz nepodporuje.',
			'errReplByChild'       : 'Složka "$1" nemůže být nahrazena souborem, který sama obsahuje.',
			'errArcSymlinks'       : 'Z bezpečnostních důvodů je zakázáno rozbalit archívy obsahující symlinky.',
			'errArcMaxSize'        : 'Soubory archívu překračují maximální povolenou velikost.',
			'errResize'            : 'Nepodařilo se změnit velikost obrázku "$1".',
			'errUsupportType'      : 'Nepodporovaný typ souboru.',

			/******************************* commands names ********************************/
			'cmdarchive'   : 'Vytvořit archív',
			'cmdback'      : 'Zpět',
			'cmdcopy'      : 'Kopírovat',
			'cmdcut'       : 'Vyjmout',
			'cmddownload'  : 'Stáhnout',
			'cmdduplicate' : 'Duplikovat',
			'cmdedit'      : 'Upravit soubor',
			'cmdextract'   : 'Rozbalit archív',
			'cmdforward'   : 'Vpřed',
			'cmdgetfile'   : 'Vybrat soubory',
			'cmdhelp'      : 'O softwaru',
			'cmdhome'      : 'Domů',
			'cmdinfo'      : 'Zobrazit informace',
			'cmdmkdir'     : 'Nová složka',
			'cmdmkfile'    : 'Nový textový soubor',
			'cmdopen'      : 'Otevřít',
			'cmdpaste'     : 'Vložit',
			'cmdquicklook' : 'Náhled',
			'cmdreload'    : 'Obnovit',
			'cmdrename'    : 'Přejmenovat',
			'cmdrm'        : 'Smazat',
			'cmdsearch'    : 'Najít soubory',
			'cmdup'        : 'Přejít do nadřazené složky',
			'cmdupload'    : 'Nahrát soubor(y)',
			'cmdview'      : 'Zobrazit',
			'cmdresize'    : 'Změnit velikost',
			'cmdsort'      : 'Seřadit',

			/*********************************** buttons ***********************************/
			'btnClose'  : 'Zavřít',
			'btnSave'   : 'Uložit',
			'btnRm'     : 'Odstranit',
			'btnApply'  : 'Použít',
			'btnCancel' : 'Zrušit',
			'btnNo'     : 'Ne',
			'btnYes'    : 'Ano',

			/******************************** notifications ********************************/
			'ntfopen'     : 'Otevírání složky',
			'ntffile'     : 'Otevírání souboru',
			'ntfreload'   : 'Obnovování obsahu složky',
			'ntfmkdir'    : 'Vytváření složky',
			'ntfmkfile'   : 'Vytváření souborů',
			'ntfrm'       : 'Mazání souborů',
			'ntfcopy'     : 'Kopírování souborů',
			'ntfmove'     : 'Přesunování souborů',
			'ntfprepare'  : 'Příprava ke kopírování souborů',
			'ntfrename'   : 'Přejmenovávání souborů',
			'ntfupload'   : 'Nahrávání souborů',
			'ntfdownload' : 'Stahování souborů',
			'ntfsave'     : 'Ukládání souborů',
			'ntfarchive'  : 'Vytváření archívu',
			'ntfextract'  : 'Rozbalování souborů z archívu',
			'ntfsearch'   : 'Vyhledávání souborů',
			'ntfsmth'     : 'Čekejte prosím...',
			'ntfloadimg'  : 'Načítání obrázků',

			/************************************ dates **********************************/
			'dateUnknown' : 'neznámý',
			'Today'       : 'Dnes',
			'Yesterday'   : 'Včera',
			'Jan'         : 'Led',
			'Feb'         : 'Úno',
			'Mar'         : 'Bře',
			'Apr'         : 'Dub',
			'May'         : 'Kvě',
			'Jun'         : 'Čer',
			'Jul'         : 'Čec',
			'Aug'         : 'Srp',
			'Sep'         : 'Zář',
			'Oct'         : 'Říj',
			'Nov'         : 'Lis',
			'Dec'         : 'Pro',
			'January'     : 'Leden',
			'February'    : 'Únor',
			'March'       : 'Březen',
			'April'       : 'Duben',
			'May'         : 'Květen',
			'June'        : 'Červen',
			'July'        : 'Červenec',
			'August'      : 'Srpen',
			'September'   : 'Září',
			'October'     : 'Říjen',
			'November'    : 'Listopad',
			'December'    : 'Prosinec',
			'Sunday'      : 'Neděle',
			'Monday'      : 'Pondělí',
			'Tuesday'     : 'Úterý',
			'Wednesday'   : 'Středa',
			'Thursday'    : 'Čtvrtek',
			'Friday'      : 'Pátek',
			'Saturday'    : 'Sobota',
			'Sun'         : 'Ne',
			'Mon'         : 'Po',
			'Tue'         : 'Út',
			'Wed'         : 'St',
			'Thu'         : 'Čt',
			'Fri'         : 'Pá',
			'Sat'         : 'So',
			/******************************** sort variants ********************************/
			'sortnameDirsFirst' : 'dle jména (složky přednostně)',
			'sortkindDirsFirst' : 'dle typu (složky přednostně)',
			'sortsizeDirsFirst' : 'dle veliksoti (složky přednostně)',
			'sortdateDirsFirst' : 'dle data (složky přednostně',
			'sortname'          : 'dle jména',
			'sortkind'          : 'dle typu',
			'sortsize'          : 'dle velikosti',
			'sortdate'          : 'dle data',

			/********************************** messages **********************************/
			'confirmReq'      : 'Požadováno potvržení',
			'confirmRm'       : 'Opravdu chcete odstranit tyto soubory?<br/>Operace nelze vrátit!',
			'confirmRepl'     : 'Nahradit staré soubory novými?',
			'apllyAll'        : 'Všem',
			'name'            : 'Název',
			'size'            : 'Velikost',
			'perms'           : 'Práva',
			'modify'          : 'Upravený',
			'kind'            : 'Typ',
			'read'            : 'čtení',
			'write'           : 'zápis',
			'noaccess'        : 'přístup nepovolen',
			'and'             : 'a',
			'unknown'         : 'neznámý',
			'selectall'       : 'Vybrat všechny soubory',
			'selectfiles'     : 'Vybrat soubor(y)',
			'selectffile'     : 'Vybrat první soubor',
			'selectlfile'     : 'Vybrat poslední soubor',
			'viewlist'        : 'Seznam',
			'viewicons'       : 'Ikony',
			'places'          : 'Místa',
			'calc'            : 'Vypočítat',
			'path'            : 'Cesta',
			'aliasfor'        : 'Zástupce pro',
			'locked'          : 'Uzamčený',
			'dim'             : 'Rozměry',
			'files'           : 'Soubory',
			'folders'         : 'Složky',
			'items'           : 'Položky',
			'yes'             : 'ano',
			'no'              : 'ne',
			'link'            : 'Odkaz',
			'searcresult'     : 'Výsledky hledání',
			'selected'        : 'vybrané položky',
			'about'           : 'O softwaru',
			'shortcuts'       : 'Zástupci',
			'help'            : 'Nápověda',
			'webfm'           : 'Webový správce souborů',
			'ver'             : 'Verze',
			'protocolver'     : 'verze protokolu',
			'homepage'        : 'Domovská stránka projektu',
			'docs'            : 'Dokumentace',
			'github'          : 'Fork us on Github',
			'twitter'         : 'Follow us on Twitter',
			'facebook'        : 'Join us on Facebook',
			'team'            : 'Tým',
			'chiefdev'        : 'séf vývojářů',
			'developer'       : 'vývojár',
			'contributor'     : 'spolupracovník',
			'maintainer'      : 'údržba',
			'translator'      : 'překlad',
			'icons'           : 'Ikony',
			'dontforget'      : 'a nezapomeňte si vzít plavky',
			'shortcutsof'     : 'Zástupci nejsou povoleni',
			'dropFiles'       : 'Přetáhněte soubory sem',
			'or'              : 'nebo',
			'selectForUpload' : 'Vyberte soubory',
			'moveFiles'       : 'Přesunout sobory',
			'copyFiles'       : 'Zkupírovat soubory',
			'rmFromPlaces'    : 'Odstranit z míst',
			'untitled folder' : 'bez názvu',
			'untitled file.txt' : 'nepojmenovaný soubor.txt',
			'aspectRatio'     : 'Poměr stran',
			'scale'           : 'Měřítko',
			'width'           : 'Šířka',
			'height'          : 'Výška',
			'mode'            : 'Mód',
			'resize'          : 'Změnit vel.',
			'crop'            : 'Ožezat',
			'rotate'          : 'Otočit',
			'rotate-cw'       : 'Otočit o +90 stupňů',
			'rotate-ccw'      : 'Otočit o -90 stupňů',
			'degree'          : ' stupňů',

			/********************************** mimetypes **********************************/
			'kindUnknown'     : 'Neznámý',
			'kindFolder'      : 'Složka',
			'kindAlias'       : 'Odkaz',
			'kindAliasBroken' : 'Neplatný odkaz',
			// applications
			'kindApp'         : 'Aplikace',
			'kindPostscript'  : 'Dokument Postscriptu',
			'kindMsOffice'    : 'Dokument Microsoft Office',
			'kindMsWord'      : 'Dokument Microsoft Word',
			'kindMsExcel'     : 'Dokument Microsoft Excel',
			'kindMsPP'        : 'Prezentace Microsoft Powerpoint',
			'kindOO'          : 'Otevřít dokument Office',
			'kindAppFlash'    : 'Flash aplikace',
			'kindPDF'         : 'PDF',
			'kindTorrent'     : 'Soubor BitTorrent',
			'kind7z'          : 'Archív 7z',
			'kindTAR'         : 'Archív TAR',
			'kindGZIP'        : 'Archív GZIP',
			'kindBZIP'        : 'Archív BZIP',
			'kindZIP'         : 'Archív ZIP',
			'kindRAR'         : 'Archív RAR',
			'kindJAR'         : 'Soubor Java JAR',
			'kindTTF'         : 'True Type font',
			'kindOTF'         : 'Open Type font',
			'kindRPM'         : 'RPM balíček',
			// texts
			'kindText'        : 'Textový dokument',
			'kindTextPlain'   : 'Čistý text',
			'kindPHP'         : 'PHP zdrojový kód',
			'kindCSS'         : 'Kaskádové styly',
			'kindHTML'        : 'HTML dokument',
			'kindJS'          : 'Javascript zdrojový kód',
			'kindRTF'         : 'Rich Text Format',
			'kindC'           : 'C zdrojový kód',
			'kindCHeader'     : 'C hlavička',
			'kindCPP'         : 'C++ zdrojový kód',
			'kindCPPHeader'   : 'C++ hlavička',
			'kindShell'       : 'Unix shell skript',
			'kindPython'      : 'Python zdrojový kód',
			'kindJava'        : 'Java zdrojový kód',
			'kindRuby'        : 'Ruby zdrojový kód',
			'kindPerl'        : 'Perl skript',
			'kindSQL'         : 'SQL zdrojový kód',
			'kindXML'         : 'Dokument XML',
			'kindAWK'         : 'AWK zdrojový kód',
			'kindCSV'         : 'CSV',
			'kindDOCBOOK'     : 'Docbook XML dokument',
			// images
			'kindImage'       : 'Obrázek',
			'kindBMP'         : 'Obrázek BMP',
			'kindJPEG'        : 'Obrázek JPEG',
			'kindGIF'         : 'Obrázek GIF',
			'kindPNG'         : 'Obrázek PNG',
			'kindTIFF'        : 'Obrázek TIFF',
			'kindTGA'         : 'Obrázek TGA',
			'kindPSD'         : 'Obrázek Adobe Photoshop',
			'kindXBITMAP'     : 'Obrázek X bitmapa',
			'kindPXM'         : 'Obrázek Pixelmator',
			// media
			'kindAudio'       : 'Audio sobory',
			'kindAudioMPEG'   : 'MPEG audio',
			'kindAudioMPEG4'  : 'MPEG-4 audio',
			'kindAudioMIDI'   : 'MIDI audio',
			'kindAudioOGG'    : 'Ogg Vorbis audio',
			'kindAudioWAV'    : 'WAV audio',
			'AudioPlaylist'   : 'MP3 playlist',
			'kindVideo'       : 'Video sobory',
			'kindVideoDV'     : 'DV video',
			'kindVideoMPEG'   : 'MPEG video',
			'kindVideoMPEG4'  : 'MPEG-4 video',
			'kindVideoAVI'    : 'AVI video',
			'kindVideoMOV'    : 'Quick Time video',
			'kindVideoWM'     : 'Windows Media video',
			'kindVideoFlash'  : 'Flash video',
			'kindVideoMKV'    : 'Matroska video',
			'kindVideoOGG'    : 'Ogg video'
		}
	}
}
;/**
 * German translation
 * @author JPG & Mace <dev@flying-datacenter.de>
 * @author tora60 from pragmaMx.org
 * @version 2013-05-01
 */
if (elFinder && elFinder.prototype && typeof(elFinder.prototype.i18) == 'object') {
	elFinder.prototype.i18.de = {
		translator : 'JPG & Mace &lt;dev@flying-datacenter.de&gt;, tora60 from pragmaMx.org',
		language   : 'Deutsch',
		direction  : 'ltr',
		dateFormat : 'd. M. Y h:i', // 13. Mai 2012 05:27
		fancyDateFormat : '$1 h:i', // will produce smth like: Today 12:25 PM
		messages   : {
			
			/********************************** errors **********************************/
			'error'                : 'Fehler',
			'errUnknown'           : 'Unbekannter Fehler.',
			'errUnknownCmd'        : 'Unbekannter Befehl.',
			'errJqui'              : 'Ungültige jQuery UI Konfiguration. Die Komponenten Selectable, draggable und droppable müssen inkludiert sein.',
			'errNode'              : 'Für elFinder muss das DOM Element erstellt werden.',
			'errURL'               : 'Ungültige elFinder Konfiguration! Die URL Option nicht gesetzt.',
			'errAccess'            : 'Zugriff verweigert.',
			'errConnect'           : 'Verbindung zum Backend fehlgeschlagen',
			'errAbort'             : 'Verbindung abgebrochen.',
			'errTimeout'           : 'Zeitüberschreitung der Verbindung.',
			'errNotFound'          : 'Backend nicht gefunden.',
			'errResponse'          : 'Ungültige Backend Antwort.',
			'errConf'              : 'Ungültige Backend Konfiguration.',
			'errJSON'              : 'PHP JSON Modul nicht vorhanden.',
			'errNoVolumes'         : 'Lesbare Volumes nicht vorhanden.',
			'errCmdParams'         : 'Ungültige Parameter für Befehl: "$1".',
			'errDataNotJSON'       : 'Daten nicht im JSON Format.',
			'errDataEmpty'         : 'Daten sind leer.',
			'errCmdReq'            : 'Backend Anfrage benötigt Befehl.',
			'errOpen'              : 'Kann "$1" nicht öffnen',
			'errNotFolder'         : 'Objekt ist kein Ordner.',
			'errNotFile'           : 'Objekt ist keine Datei.',
			'errRead'              : 'Kann "$1" nicht öffnen.',
			'errWrite'             : 'Kann nicht in "$1" schreiben.',
			'errPerm'              : 'Zugriff verweigert.',
			'errLocked'            : '"$1" ist gelockt und kann nicht umbenannt, verschoben oder gelöscht werden.',
			'errExists'            : 'Die Datei "$1" existiert bereits.',
			'errInvName'           : 'Ungültiger Datei Name.',
			'errFolderNotFound'    : 'Ordner nicht gefunden.',
			'errFileNotFound'      : 'Datei nicht gefunden.',
			'errTrgFolderNotFound' : 'Zielordner "$1" nicht gefunden.',
			'errPopup'             : 'Der Browser hat das Pop-Up-Fenster unterbunden. Um die Datei zu öffnen, Pop-Ups in den Browser Einstellungen aktivieren.',
			'errMkdir'             : 'Kann Ordner "$1" nicht erstellen.',
			'errMkfile'            : 'Kann Datei "$1" nicht erstellen.',
			'errRename'            : 'Kann "$1" nicht umbenennen.',
			'errCopyFrom'          : 'Kopieren von Dateien von "$1" nicht erlaubt.',
			'errCopyTo'            : 'Kopieren von Dateien nach "$1" nicht erlaubt.',
			'errUpload'            : 'Upload Fehler.',
			'errUploadFile'        : 'Kann "$1" nicht hochladen.',
			'errUploadNoFiles'     : 'Keine Dateien zum Hochladen gefunden.',
			'errUploadTotalSize'   : 'Daten überschreiten die Maximalgröße.',
			'errUploadFileSize'    : 'Die Datei überschreitet die Maximalgröße',
			'errUploadMime'        : 'Dateityp nicht zulässig.',
			'errUploadTransfer'    : '"$1" Transfer Fehler.',
			'errNotReplace'        : 'Das Objekt "$1" existiert bereits an dieser Stelle und kann nicht durch ein Objekt eines anderen Typs ersetzt werden.',
			'errReplace'           : 'Kann "$1" nicht ersetzen.',
			'errSave'              : 'Kann "$1" nicht speichern.',
			'errCopy'              : 'Kann "$1" nicht kopieren.',
			'errMove'              : 'Kann "$1" nicht verschieben.',
			'errCopyInItself'      : '"$1" kann sich nicht in sich selbst kopieren.',
			'errRm'                : 'Kann "$1" nicht enfernen.',
			'errRmSrc'             : 'Kann Quelldatei(en) nicht entfernen.',
			'errExtract'           : 'Kann "$1" nicht entpacken .',
			'errArchive'           : 'Archiv konnte nicht erstellt werden.',
			'errArcType'           : 'Archivtyp nicht untersützt.',
			'errNoArchive'         : 'Bei der Datei handelt es nicht um ein Archiv oder der Archivtyp nicht unterstütz.',
			'errCmdNoSupport'      : 'Das Backend unterstütz diesen Befehl nicht.',
			'errReplByChild'       : 'Der Ordner “$1” kann nicht durch etwas ersetzt werden, das ihn selbst enthält.',
			'errArcSymlinks'       : 'Aus Sicherheitsgründen ist es verboten, ein Archiv mit symbolischen Links zu extrahieren.',
			'errArcMaxSize'        : 'Die Archiv Dateien übersteigen die maximal erlaubte Größe.',
			'errResize'            : 'Größe von "$1" kann nicht geändert werden.',
			'errUsupportType'      : 'Nicht unterstützter Dateityp.',
			'errNotUTF8Content'    : 'Die Datei "$1" ist nicht im UTF-8 Format und kann nicht editiert werden.',
			'errNetMount'          : 'Verbindung mit "$1" nicht möglich.',
			'errNetMountNoDriver'  : 'Nicht unterstütztes Protokoll.',
			'errNetMountFailed'    : 'Verbindung fehlgeschlagen.',
			'errNetMountHostReq'   : 'Host benötigz.',
			'errSessionExpires'    : 'Ihre Sitzung ist aufgrund von Inaktivität abgelaufen',
			'errCreatingTempDir'   : 'Erstellung des temporären Ordners nicht möglich: "$1"',
			'errFtpDownloadFile'   : 'Download der Datei über FTP nicht möglich: "$1"',
			'errFtpUploadFile'     : 'Upload der Datei zu FTP nicht möglich: "$1"',
			'errFtpMkdir'          : 'Erstellung des Remote-Ordners auf FTP nicht möglich: "$1"',
			'errArchiveExec'       : 'Fehler bei der Archivierung der Dateien: "$1"',
			'errExtractExec'       : 'Fehler beim Extrahieren der Dateien: "$1"',

			/******************************* commands names ********************************/
			'cmdarchive'   : 'Archiv erstellen',
			'cmdback'      : 'Zurück',
			'cmdcopy'      : 'Kopieren',
			'cmdcut'       : 'Ausschreiden',
			'cmddownload'  : 'Herunterladen',
			'cmdduplicate' : 'Duplizieren',
			'cmdedit'      : 'Datei bearbeiten',
			'cmdextract'   : 'Archiv entpacken',
			'cmdforward'   : 'Vorwärts',
			'cmdgetfile'   : 'Datei auswählen',
			'cmdhelp'      : 'über diese Software',
			'cmdhome'      : 'Startordner',
			'cmdinfo'      : 'Informationen',
			'cmdmkdir'     : 'Neuer Ordner',
			'cmdmkfile'    : 'Neue Textdatei',
			'cmdopen'      : 'öffnen',
			'cmdpaste'     : 'Einfügen',
			'cmdquicklook' : 'Vorschau',
			'cmdreload'    : 'Neuladen',
			'cmdrename'    : 'Umbenennen',
			'cmdrm'        : 'Löschen',
			'cmdsearch'    : 'Suchen',
			'cmdup'        : 'In übergeordneten Ordner wechseln',
			'cmdupload'    : 'Datei hochladen',
			'cmdview'      : 'Ansehen',
			'cmdresize'    : 'Größe ändern & drehen',
			'cmdsort'      : 'Sortieren',
			'cmdnetmount'  : 'Verbinde mit Netzwerkspeicher',
			
			/*********************************** buttons ***********************************/ 
			'btnClose'  : 'Schließen',
			'btnSave'   : 'Speichern',
			'btnRm'     : 'Entfernen',
			'btnApply'  : 'Anwenden',
			'btnCancel' : 'Abbrechen',
			'btnNo'     : 'Nein',
			'btnYes'    : 'Ja',
			'btnMount'  : 'Verbinden',
			/******************************** notifications ********************************/
			'ntfopen'     : 'öffne Ordner',
			'ntffile'     : 'öffne Datei',
			'ntfreload'   : 'Ordnerinhalt neu',
			'ntfmkdir'    : 'Erstelle Ordner',
			'ntfmkfile'   : 'Erstelle Dateien',
			'ntfrm'       : 'Lösche Dateien',
			'ntfcopy'     : 'Kopiere Dateien files',
			'ntfmove'     : 'Verschiebe Dateien',
			'ntfprepare'  : 'Kopiervorgang initialisieren',
			'ntfrename'   : 'Benenne Dateien um',
			'ntfupload'   : 'Dateien hochladen',
			'ntfdownload' : 'Dateien herunterladen',
			'ntfsave'     : 'Speichere Datei',
			'ntfarchive'  : 'Erstelle Archiv',
			'ntfextract'  : 'Entpacke Dateien',
			'ntfsearch'   : 'Suche',
			'ntfresize'   : 'Bildgrößen ändern',
			'ntfsmth'     : 'Bin beschäftigt',
			'ntfloadimg'  : 'Bild laden',
			'ntfnetmount' : 'Mit Netzwerkspeicher verbinden',
			
			/************************************ dates **********************************/
			'dateUnknown' : 'unbekannt',
			'Today'       : 'Heute',
			'Yesterday'   : 'Gestern',
			'Jan'         : 'Jan',
			'Feb'         : 'Feb',
			'Mar'         : 'Mär',
			'Apr'         : 'Apr',
			'May'         : 'Mai',
			'Jun'         : 'Jun',
			'Jul'         : 'Jul',
			'Aug'         : 'Aug',
			'Sep'         : 'Sep',
			'Oct'         : 'Okt',
			'Nov'         : 'Nov',
			'Dec'         : 'Dez',

			/******************************** sort variants ********************************/
			'sortname'         : 'nach Name',
			'sortkind'         : 'nach Typ',
			'sortsize'         : 'nach Größe',
			'sortdate'         : 'nach Datum',
			'sortFoldersFirst' : 'Ordner zuerst',

			/********************************** messages **********************************/
			'confirmReq'      : 'Bestätigung Benötigt',
			'confirmRm'       : 'Sollen die Dateien gelöscht werden?<br/>Dies kann nicht rückgängig gemacht werden!',
			'confirmRepl'     : 'Datei ersetzen?',
			'apllyAll'        : 'Alles bestätigen',
			'name'            : 'Name',
			'size'            : 'Größe',
			'perms'           : 'Berechtigungen',
			'modify'          : '&Auml;nderungsdatum',
			'kind'            : 'Typ',
			'read'            : 'lesen',
			'write'           : 'schreiben',
			'noaccess'        : 'Kein Zugriff',
			'and'             : 'und',
			'unknown'         : 'unbekannt',
			'selectall'       : 'Alle Dateien auswählen',
			'selectfiles'     : 'Dateien auswählen',
			'selectffile'     : 'Erste Datei auswhählen',
			'selectlfile'     : 'Letzte Datei auswählen',
			'viewlist'        : 'Spaltenansicht',
			'viewicons'       : 'Symbolansicht',
			'places'          : 'Orte',
			'calc'            : 'Berechne',
			'path'            : 'Pfad',
			'aliasfor'        : 'Verknüpfund zu',
			'locked'          : 'Gesperrt',
			'dim'             : 'Bildgröße',
			'files'           : 'Dateien',
			'folders'         : 'Ordner',
			'items'           : 'Objekte',
			'yes'             : 'ja',
			'no'              : 'nein',
			'link'            : 'Link',
			'searcresult'     : 'Suchergebnisse',
			'selected'        : 'Objekte ausgewählt',
			'about'           : 'über',
			'shortcuts'       : 'Tastenkombinationen',
			'help'            : 'Hilfe',
			'webfm'           : 'Web Datei Manager',
			'ver'             : 'Version',
			'protocolver'     : 'Protokol Version',
			'homepage'        : 'Projekt Website',
			'docs'            : 'Dokumentation',
			'github'          : 'Forke uns auf Github',
			'twitter'         : 'Folge uns auf twitter',
			'facebook'        : 'Begleite uns auf facebook',
			'team'            : 'Team',
			'chiefdev'        : 'Chefentwickler',
			'developer'       : 'Entwickler',
			'contributor'     : 'Üntersützer',
			'maintainer'      : 'Maintainer',
			'translator'      : 'Übersetzer',
			'icons'           : 'Icons',
			'dontforget'      : 'und vergiss dein Handtuch nicht',
			'shortcutsof'     : 'Tastenkombinationen deaktiviert',
			'dropFiles'       : 'Dateien hier ablegen',
			'dropFilesBrowser': 'Verschieben oder Einfügen von Dateien aus dem Browser',
			'or'              : 'oder',
			'selectForUpload' : 'Dateien zum Upload auswählen',
			'moveFiles'       : 'Dateien verschieben',
			'copyFiles'       : 'Dateien kopieren',
			'rmFromPlaces'    : 'Lösche von Orte',
			'aspectRatio'     : 'Seitenverhältnis',
			'scale'           : 'Maßstab',
			'width'           : 'Breite',
			'height'          : 'Höhe',
			'resize'          : 'Größe ändern',
			'crop'            : 'Zuschneiden',
			'rotate'          : 'Drehen',
			'rotate-cw'       : 'Drehe 90° im Uhrzeigersinn',
			'rotate-ccw'      : 'Drehe 90° gegen den Uhrzeigersinn',
			'degree'          : '°',
			'netMountDialogTitle' : 'verbinde Netzwerk Speicher',
			'protocol'            : 'Protokoll',
			'host'                : 'Host',
			'port'                : 'Port',
			'user'                : 'Benutzer',
			'pass'                : 'Passwort',

			/********************************** mimetypes **********************************/
			'kindUnknown'     : 'Unbekannt',
			'kindFolder'      : 'Ordner',
			'kindAlias'       : 'Verknüpfung',
			'kindAliasBroken' : 'Defekte Verknüpfung',
			// applications
			'kindApp'         : 'Programm',
			'kindPostscript'  : 'Postscript Dokument',
			'kindMsOffice'    : 'Microsoft Office Dokument',
			'kindMsWord'      : 'Microsoft Word Dokument',
			'kindMsExcel'     : 'Microsoft Excel Dokument',
			'kindMsPP'        : 'Microsoft Powerpoint Präsentation',
			'kindOO'          : 'Open Office Dokument',
			'kindAppFlash'    : 'Flash Programm',
			'kindPDF'         : 'Portables Dokumentenformat (PDF)',
			'kindTorrent'     : 'Bittorrent Datei',
			'kind7z'          : '7z Archiv',
			'kindTAR'         : 'TAR Archiv',
			'kindGZIP'        : 'GZIP Archiv',
			'kindBZIP'        : 'BZIP Archiv',
			'kindZIP'         : 'ZIP Archiv',
			'kindRAR'         : 'RAR Archiv',
			'kindJAR'         : 'Java JAR Datei',
			'kindTTF'         : 'True Type Schrift',
			'kindOTF'         : 'Open Type Schrift',
			'kindRPM'         : 'RPM Paket',
			// texts
			'kindText'        : 'Text Dokument',
			'kindTextPlain'   : 'Text Dokument',
			'kindPHP'         : 'PHP Quelltext',
			'kindCSS'         : 'Cascading Stylesheet',
			'kindHTML'        : 'HTML Dokument',
			'kindJS'          : 'Javascript Quelltext',
			'kindRTF'         : 'Formatierte Textdatei',
			'kindC'           : 'C Quelltext',
			'kindCHeader'     : 'C Header Quelltext',
			'kindCPP'         : 'C++ Quelltext',
			'kindCPPHeader'   : 'C++ Header Quelltext',
			'kindShell'       : 'Unix-Shell-Skript',
			'kindPython'      : 'Python Quelltext',
			'kindJava'        : 'Java Quelltext',
			'kindRuby'        : 'Ruby Quelltext',
			'kindPerl'        : 'Perl Script',
			'kindSQL'         : 'SQL Quelltext',
			'kindXML'         : 'XML Dokument',
			'kindAWK'         : 'AWK Quelltext',
			'kindCSV'         : 'Komma getrennte Daten',
			'kindDOCBOOK'     : 'Docbook XML Dokument',
			// images
			'kindImage'       : 'Bild',
			'kindBMP'         : 'Bitmap Bild',
			'kindJPEG'        : 'JPEG Bild',
			'kindGIF'         : 'GIF Bild',
			'kindPNG'         : 'PNG Bild',
			'kindTIFF'        : 'TIFF Bild',
			'kindTGA'         : 'TGA Bild',
			'kindPSD'         : 'Adobe Photoshop Bild',
			'kindXBITMAP'     : 'X Bitmap Bild',
			'kindPXM'         : 'Pixelmator Bild',
			// media
			'kindAudio'       : 'Audiodatei',
			'kindAudioMPEG'   : 'MPEG Audio',
			'kindAudioMPEG4'  : 'MPEG-4 Audio',
			'kindAudioMIDI'   : 'MIDI Audio',
			'kindAudioOGG'    : 'Ogg Vorbis Audio',
			'kindAudioWAV'    : 'WAV Audio',
			'AudioPlaylist'   : 'MP3 Playlist',
			'kindVideo'       : 'Videodatei',
			'kindVideoDV'     : 'DV Film',
			'kindVideoMPEG'   : 'MPEG Film',
			'kindVideoMPEG4'  : 'MPEG-4 Film',
			'kindVideoAVI'    : 'AVI Film',
			'kindVideoMOV'    : 'Quick Time Film',
			'kindVideoWM'     : 'Windows Media Film',
			'kindVideoFlash'  : 'Flash Film',
			'kindVideoMKV'    : 'Matroska Film',
			'kindVideoOGG'    : 'Ogg Film'
		}
	}
}
;/**
 * elFinder translation template
 * use this file to create new translation
 * submit new translation via https://github.com/Studio-42/elFinder/issues
 * or make a pull request
 */
 
/**
 * Greek translation
 * @author yawd <info@yawd.eu>, Romanos
 * @version 2014-02-09
 */
if (elFinder && elFinder.prototype && typeof(elFinder.prototype.i18) == 'object') {
	elFinder.prototype.i18.el = {
		translator : 'yawd &lt;ingo@yawd.eu&gt;',
		language   : 'Ελληνικά',
		direction  : 'ltr',
		dateFormat : 'd.m.Y H:i',
		fancyDateFormat : '$1 H:i',
		messages   : {
			
			/********************************** errors **********************************/
			'error'                : 'Πρόβλημα',
			'errUnknown'           : 'Άγνωστο πρόβλημα.',
			'errUnknownCmd'        : 'Άγνωστη εντολή.',
			'errJqui'              : 'Μη έγκυρη ρύθμιση του jQuery UI. Τα components "selectable", "draggable" και "droppable" πρέπει να περιληφούν.',
			'errNode'              : 'το elFinder χρειάζεται να έχει δημιουργηθεί το DOM Element.',
			'errURL'               : 'Μη έγκυρες ρυθμίσεις για το elFinder! η επιλογή URL δεν έχει οριστεί.',
			'errAccess'            : 'Απαγορεύεται η πρόσβαση.',
			'errConnect'           : 'Δεν ήταν δυνατή η σύνδεση με το backend.',
			'errAbort'             : 'Η σύνδεση εγκαταλείφθηκε.',
			'errTimeout'           : 'Η σύνδεση έληξε.',
			'errNotFound'          : 'Δε βρέθηκε το backend.',
			'errResponse'          : 'Μή έγκυρη απάντηση από το backend.',
			'errConf'              : 'Μη έγκυρες ρυθμίσεις για το backend.',
			'errJSON'              : 'Το PHP JSON module δεν είναι εγκατεστημένο.',
			'errNoVolumes'         : 'Δεν βρέθηκαν αναγνώσιμα volumes.',
			'errCmdParams'         : 'Μη έγκυρες παράμετροι για την εντολή "$1".',
			'errDataNotJSON'       : 'Τα δεδομένα δεν είναι JSON.',
			'errDataEmpty'         : 'Τα δεδομένα είναι άδεια.',
			'errCmdReq'            : 'Το Backend request χρειάζεται όνομα εντολής.',
			'errOpen'              : 'Δεν ήταν δυνατό να ανοίξει το "$1".',
			'errNotFolder'         : 'Το αντικείμενο δεν είναι φάκελος.',
			'errNotFile'           : 'Το αντικείμενο δεν είναι αρχείο.',
			'errRead'              : 'Δεν ήταν δυνατόν να διαβαστεί το "$1".',
			'errWrite'             : 'Δεν ήταν δυνατή η εγγραφή στο "$1".',
			'errPerm'              : 'Απαγορεύεται η πρόσβαση.',
			'errLocked'            : '"$1" είναι κλειδωμένο και δεν μπορεί να μετονομαστεί, μετακινηθεί ή διαγραφεί.',
			'errExists'            : 'Το αρχείο με όνομα "$1" υπάρχει ήδη.',
			'errInvName'           : 'Μη έγκυρο όνομα αρχείου.',
			'errFolderNotFound'    : 'Ο φάκελος δε βρέθηκε.',
			'errFileNotFound'      : 'Το αρχείο δε βρέθηκε.',
			'errTrgFolderNotFound' : 'Ο φάκελος "$1" δε βρέθηκε.',
			'errPopup'             : 'Το πρόγραμμα πλήγησης εμπόδισε το άνοιγμα αναδυόμενου παραθύρου. Για ανοίξετε το αρχείο ενεργοποιήστε το στις επιλογές του περιηγητή.',
			'errMkdir'             : 'Η δυμιουργία του φακέλου "$1" δεν ήταν δυνατή.',
			'errMkfile'            : 'Η δημιουργία του αρχείου "$1" δεν ήταν δυνατή.',
			'errRename'            : 'Η μετονομασία του αρχείου "$1" δεν ήταν δυνατή.',
			'errCopyFrom'          : 'Δεν επιτρέπεται η αντιγραφή αρχείων από το volume "$1".',
			'errCopyTo'            : 'Δεν επιτρέπεται η αντιγραφή αρχείων στο volume "$1".',
			'errUploadCommon'      : 'Πρόβλημα κατά το upload.',
			'errUpload'            : 'Το αρχείο "$1" δεν μπόρεσε να γίνει upload.',
			'errUploadNoFiles'     : 'Δεν βρέθηκαν αρχεία για upload.',
			'errMaxSize'           : 'Τα δεδομένα υπερβαίνουν το επιτρεπόμενο μέγιστο μέγεθος δεδομένων.',
			'errFileMaxSize'       : 'Το αρχείο υπερβαίνει το επιτρεπόμενο μέγιστο μέγεθος.',
			'errUploadMime'        : 'Ο τύπος αρχείου δεν επιτρέπεται.',
			'errUploadTransfer'    : 'Πρόβλημα μεταφοράς για το "$1".', 
			'errSave'              : 'Το "$1" δεν ήταν δυνατόν να αποθηκευτεί.',
			'errCopy'              : 'Δεν ήταν δυνατή η αντιγραφή του "$1".',
			'errMove'              : 'Δεν ήταν δυνατή η μετακίνηση του "$1".',
			'errCopyInItself'      : 'Δεν είναι δυνατή η αντιγραφή του "$1" στον εαυτό του.',
			'errRm'                : 'Δεν ήταν δυνατή η αφαίρεση του "$1".',
			'errExtract'           : 'Δεν ήταν δυνατή η ανάγνωση των αρχείων από "$1".',
			'errArchive'           : 'Δεν ήταν δυνατή η δημιουργία του αρχείου.',
			'errArcType'           : 'Ο τύπος αρχείου δεν υποστηρίζεται.',
			'errNoArchive'         : 'Το αρχείο δεν είναι έγκυρο ή δεν υποστηρίζεται ο τύπος του.',
			'errCmdNoSupport'      : 'Το backend δεν υποστηρίζει αυτή την εντολή.',
			'errReplByChild'       : 'Ο φάκελος “$1” δεν μπορεί να αντικατασταθεί από οποιοδήποτε αρχείο περιέχεται σε αυτόν.',
			'errArcSymlinks'       : 'Για λόγους ασφαλείας δεν είναι δυνατόν να διαβαστούν αρχεία που περιέχουν symlinks orη αρχεία με μη επιτρεπτά ονόματα.', // edited 24.06.2012
			'errArcMaxSize'        : 'Το μέγεθος του αρχείου υπερβαίνει το μέγιστο επιτρεπτό όριο.',
			'errResize'            : 'Δεν ήταν δυνατή η αλλαγή μεγέθους του "$1".',
			'errUsupportType'      : 'Ο τύπος αρχείου δεν υποστηρίζεται.',
			'errNotUTF8Content'    : 'Το αρχείο "$1" δεν είναι UTF-8 και δεν μπορεί να επεξεργασθεί.',  // added 9.11.2011
			'errNetMount'          : 'Δεν ήταν δυνατή η φόρτωση του "$1".',     // added 17.04.2012
			'errNetMountNoDriver'  : 'Μη υποστηριζόμενο πρωτόκολο.',     // added 17.04.2012
			'errNetMountFailed'    : 'Η φόρτωση απέτυχε.',             // added 17.04.2012
			'errNetMountHostReq'   : 'Απαιτείται host εξυπηρετητής.', // added 18.04.2012
			/******************************* commands names ********************************/
			'cmdarchive'   : 'Δημιουργία archive αρχείου',
			'cmdback'      : 'Πίσω',
			'cmdcopy'      : 'Αντιγραφή',
			'cmdcut'       : 'Αφαίρεση',
			'cmddownload'  : 'Μεταφόρτωση',
			'cmdduplicate' : 'Αντίγραφο',
			'cmdedit'      : 'Επεξεργασία αρχείου',
			'cmdextract'   : 'Εξαγωγή αρχείων από archive',
			'cmdforward'   : 'Προώθηση',
			'cmdgetfile'   : 'Επιλέξτε αρχεία',
			'cmdhelp'      : 'Σχετικά με αυτό το λογισμικό',
			'cmdhome'      : 'Home',
			'cmdinfo'      : 'Πληροφορίες',
			'cmdmkdir'     : 'Νέος φάκελος',
			'cmdmkfile'    : 'Νέο αρχείο κειμένου',
			'cmdopen'      : 'Άνοιγμα',
			'cmdpaste'     : 'Επικόλληση',
			'cmdquicklook' : 'Προεπισκόπηση',
			'cmdreload'    : 'Ανανέωση',
			'cmdrename'    : 'Μετονομασία',
			'cmdrm'        : 'Διαγραφή',
			'cmdsearch'    : 'Έυρεση αρχείων',
			'cmdup'        : 'Μετάβαση στο γονικό φάκελο',
			'cmdupload'    : 'Ανέβασμα αρχείων',
			'cmdview'      : 'Προβολή',
			'cmdresize'    : 'Αλλαγή μεγέθους εικόνας',
			'cmdsort'      : 'Ταξινόμηση',
			
			/*********************************** buttons ***********************************/ 
			'btnClose'  : 'Κλείσιμο',
			'btnSave'   : 'Αποθήκευση',
			'btnRm'     : 'Αφαίρεση',
			'btnApply'  : 'Εφαρμογή',
			'btnCancel' : 'Ακύρωση',
			'btnNo'     : 'Όχι',
			'btnYes'    : 'Ναι',
			
			/******************************** notifications ********************************/
			'ntfopen'     : 'Άνοιγμα φακέλου',
			'ntffile'     : 'Άνοιγμα αρχείου',
			'ntfreload'   : 'Ανανέωση περιεχομένων φακέλου',
			'ntfmkdir'    : 'Δημιουργία φακέλου',
			'ntfmkfile'   : 'Δημιουργία αρχείων',
			'ntfrm'       : 'Διαγραφή αρχείων',
			'ntfcopy'     : 'Αντιγραφή αρχείων',
			'ntfmove'     : 'Μετακίνηση αρχείων',
			'ntfprepare'  : 'Προετοιμασία αντιγραφής αρχείων',
			'ntfrename'   : 'Μετονομασία αρχείων',
			'ntfupload'   : 'Ανέβασμα αρχείων',
			'ntfdownload' : 'Μεταφόρτωση αρχείων',
			'ntfsave'     : 'Αποθήκευση αρχείων',
			'ntfarchive'  : 'Δημιουργία αρχείου',
			'ntfextract'  : 'Εξαγωγή αρχείων από το archive',
			'ntfsearch'   : 'Αναζήτηση αρχείων',
			'ntfsmth'     : 'Σύστημα απασχολημένο>_<',
			'ntfloadimg'  : 'Φόρτωση εικόνας',
			'ntfnetmount' : 'Φόρτωση δικτυακού δίσκου', // added 18.04.2012
			
			/************************************ dates **********************************/
			'dateUnknown' : 'άγνωστο',
			'Today'       : 'Σήμερα',
			'Yesterday'   : 'Χθές',
			'Jan'         : 'Ιαν',
			'Feb'         : 'Φεβ',
			'Mar'         : 'Μαρ',
			'Apr'         : 'Απρ',
			'May'         : 'Μαϊ',
			'Jun'         : 'Ιουν',
			'Jul'         : 'Ιουλ',
			'Aug'         : 'Αυγ',
			'Sep'         : 'Σεπ',
			'Oct'         : 'Οκτ',
			'Nov'         : 'Νοεμ',
			'Dec'         : 'Δεκ',
			'January'     : 'Ιανουάριος',
			'February'    : 'Φεβρουάριος',
			'March'       : 'Μάρτιος',
			'April'       : 'Απρίλιος',
			'May'         : 'Μάϊος',
			'June'        : 'Ιούνιος',
			'July'        : 'Ιούλιος',
			'August'      : 'Αύγουστος',
			'September'   : 'Σεπτέμβριος',
			'October'     : 'Οκτώβριος',
			'November'    : 'Νοέμβριος',
			'December'    : 'Δεκέμβριος',
			'Sunday'      : 'Κυριακή', 
			'Monday'      : 'Δευτέρα', 
			'Tuesday'     : 'Τρίτη', 
			'Wednesday'   : 'Τετάρτη', 
			'Thursday'    : 'Πέμπτη', 
			'Friday'      : 'Παρασκευή', 
			'Saturday'    : 'Σάββατο',
			'Sun'         : 'Κυρ', 
			'Mon'         : 'Δευ', 
			'Tue'         : 'Τρ', 
			'Wed'         : 'Τετ', 
			'Thu'         : 'Πεμ', 
			'Fri'         : 'Παρ', 
			'Sat'         : 'Σαβ',
			/******************************** sort variants ********************************/
			'sortname'          : 'κατά όνομα', 
			'sortkind'          : 'κατά είδος', 
			'sortsize'          : 'κατά μέγεθος',
			'sortdate'          : 'κατά ημερομηνία',
			'sortFoldersFirst'  : 'Πρώτα οι φάκελοι', // added 22.06.2012
			
			/********************************** messages **********************************/
			'confirmReq'      : 'Απαιτείται επιβεβαίωση',
			'confirmRm'       : 'Είστε σίγουροι πως θέλετε να διαγράψετε τα αρχεία?<br/>Οι αλλαγές θα είναι μόνιμες!',
			'confirmRepl'     : 'Αντικατάσταση του παλιού αρχείου με το νέο?',
			'apllyAll'        : 'Εφαρμογή σε όλα',
			'name'            : 'Όνομα',
			'size'            : 'Μέγεθος',
			'perms'           : 'Δικαιώματα',
			'modify'          : 'Τροποποιήθηκε',
			'kind'            : 'Είδος',
			'read'            : 'ανάγνωση',
			'write'           : 'εγγραφή',
			'noaccess'        : 'δεν υπάρχει πρόσβαση',
			'and'             : 'και',
			'unknown'         : 'άγνωστο',
			'selectall'       : 'Επιλογή όλων',
			'selectfiles'     : 'Επιλογή αρχείων',
			'selectffile'     : 'Επιλογή πρώτου αρχείου',
			'selectlfile'     : 'Επιλογή τελευταίου αρχείου',
			'viewlist'        : 'Προβολή λίστας',
			'viewicons'       : 'Προβολή εικονιδίων',
			'places'          : 'Τοποθεσίες',
			'calc'            : 'Υπολογισμός', 
			'path'            : 'Διαδρομή',
			'aliasfor'        : 'Ψευδώνυμο για',
			'locked'          : 'Κλειδωμένο',
			'dim'             : 'Διαστάσεις',
			'files'           : 'Αρχεία',
			'folders'         : 'Φάκελοι',
			'items'           : 'Αντικείμενα',
			'yes'             : 'ναι',
			'no'              : 'όχι',
			'link'            : 'Σύνδεσμος',
			'searcresult'     : 'Αποτελέσματα αναζήτησης',  
			'selected'        : 'επιλεγμένα αντικείμενα',
			'about'           : 'Σχετικά',
			'shortcuts'       : 'Συντομεύσεις',
			'help'            : 'Βοήθεια',
			'webfm'           : 'εργαλείο διαχείρισης αρχείων από το web',
			'ver'             : 'Έκδοση',
			'protocolver'     : 'έκδοση πρωτοκόλλου',
			'homepage'        : 'Σελίδα του project',
			'docs'            : 'Τεκμηρίωση (documentation)',
			'github'          : 'Κάντε μας fork στο Github',
			'twitter'         : 'Ακολουθήστε μας στο twitter',
			'facebook'        : 'Βρείτε μας στο facebook',
			'team'            : 'Ομάδα',
			'chiefdev'        : 'κύριος προγραμματιστής',
			'developer'       : 'προγραμματιστής',
			'contributor'     : 'συνεισφορά',
			'maintainer'      : 'συντηρητής',
			'translator'      : 'μεταφραστής',
			'icons'           : 'Εικονίδια',
			'dontforget'      : 'και μην ξεχάσεις την πετσέτα σου!',
			'shortcutsof'     : 'Οι συντομεύσεις είναι απενεργοποιημένες',
			'dropFiles'       : 'Κάντε drop τα αρχεία εδώ',
			'or'              : 'ή',
			'selectForUpload' : 'Επιλογή αρχείων για ανέβασμα',
			'moveFiles'       : 'Μετακίνηση αρχείων',
			'copyFiles'       : 'Αντιγραφή αρχείων',
			'rmFromPlaces'    : 'Αντιγραφή από τοποθεσίες',
			'untitled folder' : 'untitled folder',
			'untitled file.txt' : 'untitled file.txt',
			'aspectRatio'     : 'Αναλογία διαστάσεων',
			'scale'           : 'Κλίμακα',
			'width'           : 'Πλάτος',
			'height'          : 'Ύψος',
			'mode'            : 'Κατάσταση',
			'resize'          : 'Αλλαγή μεγέθους',
			'crop'            : 'Crop',
			'rotate'          : 'Περιστροφή',
			'rotate-cw'       : 'Περιστροφή κατά 90 βαθμούς CW',
			'rotate-ccw'      : 'Περιστροφή κατά 90 βαθμούς CCW',
			'degree'          : 'Βαθμός',
			'netMountDialogTitle' : 'Φορτώστε δικτυακό δίσκο', // added 18.04.2012
			'protocol'            : 'Πρωτόκολλο', // added 18.04.2012
			'host'                : 'Host', // added 18.04.2012
			'port'                : 'Port', // added 18.04.2012
			'user'                : 'Χρήστης', // added 18.04.2012
			'pass'                : 'Κωδικός', // added 18.04.2012
			/********************************** mimetypes **********************************/
			'kindUnknown'     : 'Άγνωστο',
			'kindFolder'      : 'Φάκελος',
			'kindAlias'       : 'Ψευδώνυμο (alias)',
			'kindAliasBroken' : 'Μη έγκυρο ψευδώνυμο',
			// applications
			'kindApp'         : 'Εφαρμογή',
			'kindPostscript'  : 'Έγγραφο Postscript',
			'kindMsOffice'    : 'Έγγραφο Microsoft Office',
			'kindMsWord'      : 'Έγγραφο Microsoft Word',
			'kindMsExcel'     : 'Έγγραφο Microsoft Excel',
			'kindMsPP'        : 'Παρουσίαση Microsoft Powerpoint',
			'kindOO'          : 'Έγγραφο Open Office',
			'kindAppFlash'    : 'Εφαρμογή Flash',
			'kindPDF'         : 'Portable Document Format (PDF)',
			'kindTorrent'     : 'Αρχείο Bittorrent',
			'kind7z'          : 'Αρχείο 7z',
			'kindTAR'         : 'Αρχείο TAR',
			'kindGZIP'        : 'Αρχείο GZIP',
			'kindBZIP'        : 'Αρχείο BZIP',
			'kindZIP'         : 'Αρχείο ZIP',
			'kindRAR'         : 'Αρχείο RAR',
			'kindJAR'         : 'Αρχείο Java JAR',
			'kindTTF'         : 'Γραμματοσειρά True Type',
			'kindOTF'         : 'Γραμματοσειρά Open Type',
			'kindRPM'         : 'Πακέτο RPM',
			// texts
			'kindText'        : 'Έγγραφο κειμένου',
			'kindTextPlain'   : 'Απλό κείμενο',
			'kindPHP'         : 'Κώδικας PHP',
			'kindCSS'         : 'Cascading style sheet',
			'kindHTML'        : 'Έγγραφο HTML',
			'kindJS'          : 'Κώδικας Javascript',
			'kindRTF'         : 'Rich Text Format',
			'kindC'           : 'Κώδικας C',
			'kindCHeader'     : 'Κώδικας κεφαλίδας C',
			'kindCPP'         : 'Κώδικας C++',
			'kindCPPHeader'   : 'Κώδικας κεφαλίδας C++',
			'kindShell'       : 'Unix shell script',
			'kindPython'      : 'Κώδικας Python',
			'kindJava'        : 'Κώδικας Java',
			'kindRuby'        : 'Κώδικας Ruby',
			'kindPerl'        : 'Perl script',
			'kindSQL'         : 'Κώδικας SQL',
			'kindXML'         : 'Έγγραφο XML',
			'kindAWK'         : 'Κώδικας AWK',
			'kindCSV'         : 'Τιμές χωρισμένες με κόμμα',
			'kindDOCBOOK'     : 'Έγγραφο Docbook XML',
			// images
			'kindImage'       : 'Εικόνα',
			'kindBMP'         : 'Εικόνα BMP',
			'kindJPEG'        : 'Εικόνα JPEG',
			'kindGIF'         : 'Εικόνα GIF',
			'kindPNG'         : 'Εικόνα PNG',
			'kindTIFF'        : 'Εικόνα TIFF',
			'kindTGA'         : 'Εικόνα TGA',
			'kindPSD'         : 'Εικόνα Adobe Photoshop',
			'kindXBITMAP'     : 'Εικόνα X bitmap',
			'kindPXM'         : 'Εικόνα Pixelmator',
			// media
			'kindAudio'       : 'Αρχεία ήχου',
			'kindAudioMPEG'   : 'Ήχος MPEG',
			'kindAudioMPEG4'  : 'Εικόνα MPEG-4',
			'kindAudioMIDI'   : 'Εικόνα MIDI',
			'kindAudioOGG'    : 'Εικόνα Ogg Vorbis',
			'kindAudioWAV'    : 'Εικόνα WAV',
			'AudioPlaylist'   : 'MP3 playlist',
			'kindVideo'       : 'Αρχεία media',
			'kindVideoDV'     : 'Ταινία DV',
			'kindVideoMPEG'   : 'Ταινία MPEG',
			'kindVideoMPEG4'  : 'Ταινία MPEG-4',
			'kindVideoAVI'    : 'Ταινία AVI',
			'kindVideoMOV'    : 'Ταινία Quick Time',
			'kindVideoWM'     : 'Ταινία Windows Media',
			'kindVideoFlash'  : 'Ταινία flash',
			'kindVideoMKV'    : 'Ταινία matroska',
			'kindVideoOGG'    : 'Ταινία ogg'
		}
	}
}

;/**
 * English translation
 * @author Troex Nevelin <troex@fury.scancode.ru>
 * @version 2013-07-03
 */
if (elFinder && elFinder.prototype && typeof(elFinder.prototype.i18) == 'object') {
	elFinder.prototype.i18.en = {
		translator : 'Troex Nevelin &lt;troex@fury.scancode.ru&gt;',
		language   : 'English',
		direction  : 'ltr',
		dateFormat : 'M d, Y h:i A', // Mar 13, 2012 05:27 PM
		fancyDateFormat : '$1 h:i A', // will produce smth like: Today 12:25 PM
		messages   : {
			
			/********************************** errors **********************************/
			'error'                : 'Error',
			'errUnknown'           : 'Unknown error.',
			'errUnknownCmd'        : 'Unknown command.',
			'errJqui'              : 'Invalid jQuery UI configuration. Selectable, draggable and droppable components must be included.',
			'errNode'              : 'elFinder requires DOM Element to be created.',
			'errURL'               : 'Invalid elFinder configuration! URL option is not set.',
			'errAccess'            : 'Access denied.',
			'errConnect'           : 'Unable to connect to backend.',
			'errAbort'             : 'Connection aborted.',
			'errTimeout'           : 'Connection timeout.',
			'errNotFound'          : 'Backend not found.',
			'errResponse'          : 'Invalid backend response.',
			'errConf'              : 'Invalid backend configuration.',
			'errJSON'              : 'PHP JSON module not installed.',
			'errNoVolumes'         : 'Readable volumes not available.',
			'errCmdParams'         : 'Invalid parameters for command "$1".',
			'errDataNotJSON'       : 'Data is not JSON.',
			'errDataEmpty'         : 'Data is empty.',
			'errCmdReq'            : 'Backend request requires command name.',
			'errOpen'              : 'Unable to open "$1".',
			'errNotFolder'         : 'Object is not a folder.',
			'errNotFile'           : 'Object is not a file.',
			'errRead'              : 'Unable to read "$1".',
			'errWrite'             : 'Unable to write into "$1".',
			'errPerm'              : 'Permission denied.',
			'errLocked'            : '"$1" is locked and can not be renamed, moved or removed.',
			'errExists'            : 'File named "$1" already exists.',
			'errInvName'           : 'Invalid file name.',
			'errFolderNotFound'    : 'Folder not found.',
			'errFileNotFound'      : 'File not found.',
			'errTrgFolderNotFound' : 'Target folder "$1" not found.',
			'errPopup'             : 'Browser prevented opening popup window. To open file enable it in browser options.',
			'errMkdir'             : 'Unable to create folder "$1".',
			'errMkfile'            : 'Unable to create file "$1".',
			'errRename'            : 'Unable to rename "$1".',
			'errCopyFrom'          : 'Copying files from volume "$1" not allowed.',
			'errCopyTo'            : 'Copying files to volume "$1" not allowed.',
			'errUpload'            : 'Upload error.',  // old name - errUploadCommon
			'errUploadFile'        : 'Unable to upload "$1".', // old name - errUpload
			'errUploadNoFiles'     : 'No files found for upload.', 
			'errUploadTotalSize'   : 'Data exceeds the maximum allowed size.', // old name - errMaxSize
			'errUploadFileSize'    : 'File exceeds maximum allowed size.', //  old name - errFileMaxSize
			'errUploadMime'        : 'File type not allowed.', 
			'errUploadTransfer'    : '"$1" transfer error.', 
			'errNotReplace'        : 'Object "$1" already exists at this location and can not be replaced by object with another type.', // new
			'errReplace'           : 'Unable to replace "$1".',
			'errSave'              : 'Unable to save "$1".',
			'errCopy'              : 'Unable to copy "$1".',
			'errMove'              : 'Unable to move "$1".',
			'errCopyInItself'      : 'Unable to copy "$1" into itself.',
			'errRm'                : 'Unable to remove "$1".',
			'errRmSrc'             : 'Unable remove source file(s).',
			'errExtract'           : 'Unable to extract files from "$1".',
			'errArchive'           : 'Unable to create archive.',
			'errArcType'           : 'Unsupported archive type.',
			'errNoArchive'         : 'File is not archive or has unsupported archive type.',
			'errCmdNoSupport'      : 'Backend does not support this command.',
			'errReplByChild'       : 'The folder “$1” can’t be replaced by an item it contains.',
			'errArcSymlinks'       : 'For security reason denied to unpack archives contains symlinks or files with not allowed names.', // edited 24.06.2012
			'errArcMaxSize'        : 'Archive files exceeds maximum allowed size.',
			'errResize'            : 'Unable to resize "$1".',
			'errResizeDegree'      : 'Invalid rotate degree.',  // added 7.3.2013
			'errResizeRotate'      : 'Image dose not rotated.',  // added 7.3.2013
			'errResizeSize'        : 'Invalid image size.',  // added 7.3.2013
			'errResizeNoChange'    : 'Image size not changed.',  // added 7.3.2013
			'errUsupportType'      : 'Unsupported file type.',
			'errNotUTF8Content'    : 'File "$1" is not in UTF-8 and cannot be edited.',  // added 9.11.2011
			'errNetMount'          : 'Unable to mount "$1".', // added 17.04.2012
			'errNetMountNoDriver'  : 'Unsupported protocol.',     // added 17.04.2012
			'errNetMountFailed'    : 'Mount failed.',         // added 17.04.2012
			'errNetMountHostReq'   : 'Host required.', // added 18.04.2012
			'errSessionExpires'    : 'Your session has expired due to inactivity.',
			'errCreatingTempDir'   : 'Unable to create temporary directory: "$1"',
			'errFtpDownloadFile'   : 'Unable to download file from FTP: "$1"',
			'errFtpUploadFile'     : 'Unable to upload file to FTP: "$1"',
			'errFtpMkdir'          : 'Unable to create remote directory on FTP: "$1"',
			'errArchiveExec'       : 'Error while archiving files: "$1"',
			'errExtractExec'       : 'Error while extracting files: "$1"',

			/******************************* commands names ********************************/
			'cmdarchive'   : 'Create archive',
			'cmdback'      : 'Back',
			'cmdcopy'      : 'Copy',
			'cmdcut'       : 'Cut',
			'cmddownload'  : 'Download',
			'cmdduplicate' : 'Duplicate',
			'cmdedit'      : 'Edit file',
			'cmdextract'   : 'Extract files from archive',
			'cmdforward'   : 'Forward',
			'cmdgetfile'   : 'Select files',
			'cmdhelp'      : 'About this software',
			'cmdhome'      : 'Home',
			'cmdinfo'      : 'Get info',
			'cmdmkdir'     : 'New folder',
			'cmdmkfile'    : 'New text file',
			'cmdopen'      : 'Open',
			'cmdpaste'     : 'Paste',
			'cmdquicklook' : 'Preview',
			'cmdreload'    : 'Reload',
			'cmdrename'    : 'Rename',
			'cmdrm'        : 'Delete',
			'cmdsearch'    : 'Find files',
			'cmdup'        : 'Go to parent directory',
			'cmdupload'    : 'Upload files',
			'cmdview'      : 'View',
			'cmdresize'    : 'Resize & Rotate',
			'cmdsort'      : 'Sort',
			'cmdnetmount'  : 'Mount network volume', // added 18.04.2012
			
			/*********************************** buttons ***********************************/ 
			'btnClose'  : 'Close',
			'btnSave'   : 'Save',
			'btnRm'     : 'Remove',
			'btnApply'  : 'Apply',
			'btnCancel' : 'Cancel',
			'btnNo'     : 'No',
			'btnYes'    : 'Yes',
			'btnMount'  : 'Mount',  // added 18.04.2012
			/******************************** notifications ********************************/
			'ntfopen'     : 'Open folder',
			'ntffile'     : 'Open file',
			'ntfreload'   : 'Reload folder content',
			'ntfmkdir'    : 'Creating directory',
			'ntfmkfile'   : 'Creating files',
			'ntfrm'       : 'Delete files',
			'ntfcopy'     : 'Copy files',
			'ntfmove'     : 'Move files',
			'ntfprepare'  : 'Prepare to copy files',
			'ntfrename'   : 'Rename files',
			'ntfupload'   : 'Uploading files',
			'ntfdownload' : 'Downloading files',
			'ntfsave'     : 'Save files',
			'ntfarchive'  : 'Creating archive',
			'ntfextract'  : 'Extracting files from archive',
			'ntfsearch'   : 'Searching files',
			'ntfresize'   : 'Resizing images',
			'ntfsmth'     : 'Doing something',
			'ntfloadimg'  : 'Loading image',
			'ntfnetmount' : 'Mounting network volume', // added 18.04.2012
			'ntfdim'      : 'Acquiring image dimension', // added 20.05.2013
			'ntfreaddir'  : 'Reading folder infomation', // added 01.07.2013
			
			/************************************ dates **********************************/
			'dateUnknown' : 'unknown',
			'Today'       : 'Today',
			'Yesterday'   : 'Yesterday',
			'Jan'         : 'Jan',
			'Feb'         : 'Feb',
			'Mar'         : 'Mar',
			'Apr'         : 'Apr',
			'May'         : 'May',
			'Jun'         : 'Jun',
			'Jul'         : 'Jul',
			'Aug'         : 'Aug',
			'Sep'         : 'Sep',
			'Oct'         : 'Oct',
			'Nov'         : 'Nov',
			'Dec'         : 'Dec',

			/******************************** sort variants ********************************/
			'sortname'          : 'by name', 
			'sortkind'          : 'by kind', 
			'sortsize'          : 'by size',
			'sortdate'          : 'by date',
			'sortFoldersFirst'  : 'Folders first',

			/********************************** messages **********************************/
			'confirmReq'      : 'Confirmation required',
			'confirmRm'       : 'Are you sure you want to remove files?<br/>This cannot be undone!',
			'confirmRepl'     : 'Replace old file with new one?',
			'apllyAll'        : 'Apply to all',
			'name'            : 'Name',
			'size'            : 'Size',
			'perms'           : 'Permissions',
			'modify'          : 'Modified',
			'kind'            : 'Kind',
			'read'            : 'read',
			'write'           : 'write',
			'noaccess'        : 'no access',
			'and'             : 'and',
			'unknown'         : 'unknown',
			'selectall'       : 'Select all files',
			'selectfiles'     : 'Select file(s)',
			'selectffile'     : 'Select first file',
			'selectlfile'     : 'Select last file',
			'viewlist'        : 'List view',
			'viewicons'       : 'Icons view',
			'places'          : 'Places',
			'calc'            : 'Calculate', 
			'path'            : 'Path',
			'aliasfor'        : 'Alias for',
			'locked'          : 'Locked',
			'dim'             : 'Dimensions',
			'files'           : 'Files',
			'folders'         : 'Folders',
			'items'           : 'Items',
			'yes'             : 'yes',
			'no'              : 'no',
			'link'            : 'Link',
			'searcresult'     : 'Search results',  
			'selected'        : 'selected items',
			'about'           : 'About',
			'shortcuts'       : 'Shortcuts',
			'help'            : 'Help',
			'webfm'           : 'Web file manager',
			'ver'             : 'Version',
			'protocolver'     : 'protocol version',
			'homepage'        : 'Project home',
			'docs'            : 'Documentation',
			'github'          : 'Fork us on Github',
			'twitter'         : 'Follow us on twitter',
			'facebook'        : 'Join us on facebook',
			'team'            : 'Team',
			'chiefdev'        : 'chief developer',
			'developer'       : 'developer',
			'contributor'     : 'contributor',
			'maintainer'      : 'maintainer',
			'translator'      : 'translator',
			'icons'           : 'Icons',
			'dontforget'      : 'and don\'t forget to take your towel',
			'shortcutsof'     : 'Shortcuts disabled',
			'dropFiles'       : 'Drop files here',
			'dropFilesBrowser': 'Drop or paste files from browser', // added 30.05.2012
			'or'              : 'or',
			'selectForUpload' : 'Select files to upload',
			'moveFiles'       : 'Move files',
			'copyFiles'       : 'Copy files',
			'rmFromPlaces'    : 'Remove from places',
			'aspectRatio'     : 'Aspect ratio',
			'scale'           : 'Scale',
			'width'           : 'Width',
			'height'          : 'Height',
			'resize'          : 'Resize',
			'crop'            : 'Crop',
			'rotate'          : 'Rotate',
			'rotate-cw'       : 'Rotate 90 degrees CW',
			'rotate-ccw'      : 'Rotate 90 degrees CCW',
			'degree'          : '°',
			'netMountDialogTitle' : 'Mount network volume', // added 18.04.2012
			'protocol'            : 'Protocol', // added 18.04.2012
			'host'                : 'Host', // added 18.04.2012
			'port'                : 'Port', // added 18.04.2012
			'user'                : 'User', // added 18.04.2012
			'pass'                : 'Password', // added 18.04.2012

			/********************************** mimetypes **********************************/
			'kindUnknown'     : 'Unknown',
			'kindFolder'      : 'Folder',
			'kindAlias'       : 'Alias',
			'kindAliasBroken' : 'Broken alias',
			// applications
			'kindApp'         : 'Application',
			'kindPostscript'  : 'Postscript document',
			'kindMsOffice'    : 'Microsoft Office document',
			'kindMsWord'      : 'Microsoft Word document',
			'kindMsExcel'     : 'Microsoft Excel document',
			'kindMsPP'        : 'Microsoft Powerpoint presentation',
			'kindOO'          : 'Open Office document',
			'kindAppFlash'    : 'Flash application',
			'kindPDF'         : 'Portable Document Format (PDF)',
			'kindTorrent'     : 'Bittorrent file',
			'kind7z'          : '7z archive',
			'kindTAR'         : 'TAR archive',
			'kindGZIP'        : 'GZIP archive',
			'kindBZIP'        : 'BZIP archive',
			'kindZIP'         : 'ZIP archive',
			'kindRAR'         : 'RAR archive',
			'kindJAR'         : 'Java JAR file',
			'kindTTF'         : 'True Type font',
			'kindOTF'         : 'Open Type font',
			'kindRPM'         : 'RPM package',
			// texts
			'kindText'        : 'Text document',
			'kindTextPlain'   : 'Plain text',
			'kindPHP'         : 'PHP source',
			'kindCSS'         : 'Cascading style sheet',
			'kindHTML'        : 'HTML document',
			'kindJS'          : 'Javascript source',
			'kindRTF'         : 'Rich Text Format',
			'kindC'           : 'C source',
			'kindCHeader'     : 'C header source',
			'kindCPP'         : 'C++ source',
			'kindCPPHeader'   : 'C++ header source',
			'kindShell'       : 'Unix shell script',
			'kindPython'      : 'Python source',
			'kindJava'        : 'Java source',
			'kindRuby'        : 'Ruby source',
			'kindPerl'        : 'Perl script',
			'kindSQL'         : 'SQL source',
			'kindXML'         : 'XML document',
			'kindAWK'         : 'AWK source',
			'kindCSV'         : 'Comma separated values',
			'kindDOCBOOK'     : 'Docbook XML document',
			// images
			'kindImage'       : 'Image',
			'kindBMP'         : 'BMP image',
			'kindJPEG'        : 'JPEG image',
			'kindGIF'         : 'GIF Image',
			'kindPNG'         : 'PNG Image',
			'kindTIFF'        : 'TIFF image',
			'kindTGA'         : 'TGA image',
			'kindPSD'         : 'Adobe Photoshop image',
			'kindXBITMAP'     : 'X bitmap image',
			'kindPXM'         : 'Pixelmator image',
			// media
			'kindAudio'       : 'Audio media',
			'kindAudioMPEG'   : 'MPEG audio',
			'kindAudioMPEG4'  : 'MPEG-4 audio',
			'kindAudioMIDI'   : 'MIDI audio',
			'kindAudioOGG'    : 'Ogg Vorbis audio',
			'kindAudioWAV'    : 'WAV audio',
			'AudioPlaylist'   : 'MP3 playlist',
			'kindVideo'       : 'Video media',
			'kindVideoDV'     : 'DV movie',
			'kindVideoMPEG'   : 'MPEG movie',
			'kindVideoMPEG4'  : 'MPEG-4 movie',
			'kindVideoAVI'    : 'AVI movie',
			'kindVideoMOV'    : 'Quick Time movie',
			'kindVideoWM'     : 'Windows Media movie',
			'kindVideoFlash'  : 'Flash movie',
			'kindVideoMKV'    : 'Matroska movie',
			'kindVideoOGG'    : 'Ogg movie'
		}
	}
}

;/**
 * Spanish translation
 * @author Julián Torres <julian.torres@pabernosmatao.com>
 * @author Julio Montoya <gugli100@gmail.com> - Fixing typos
 * @version 2013-05-08
 */
if (elFinder && elFinder.prototype && typeof(elFinder.prototype.i18) == 'object') {
	elFinder.prototype.i18.es = {
		translator : 'Julián Torres &lt;julian.torres@pabernosmatao.com&gt;',
		language   : 'Español internacional',
		direction  : 'ltr',
		messages   : {
			
			/********************************** errors **********************************/
			'error'                : 'Error',
			'errUnknown'           : 'Error desconocido.',
			'errUnknownCmd'        : 'Comando desconocido.',
			'errJqui'              : 'Configuración no válida de jQuery UI. deben estar incluidos los componentes selectable, draggable y droppable.',
			'errNode'              : 'elFinder necesita crear elementos DOM.',
			'errURL'               : 'Configuración no válida de elFinder! La opción URL no está configurada.',
			'errAccess'            : 'Acceso denegado.',
			'errConnect'           : 'No se ha podido conectar con el backend.',
			'errAbort'             : 'Conexión cancelada.',
			'errTimeout'           : 'Conexión cancelada por timeout.',
			'errNotFound'          : 'Backend no encontrado.',
			'errResponse'          : 'Respuesta no válida del backend.',
			'errConf'              : 'Configuración no válida del backend .',
			'errJSON'              : 'El módulo PHP JSON no está instalado.',
			'errNoVolumes'         : 'No hay disponibles volúmenes legibles.',
			'errCmdParams'         : 'Parámetros no válidos para el comando "$1".',
			'errDataNotJSON'       : 'los datos no estan en formato JSON.',
			'errDataEmpty'         : 'No hay datos.',
			'errCmdReq'            : 'La petición del backend necesita un nombre de comando.',
			'errOpen'              : 'No se puede abrir "$1".',
			'errNotFolder'         : 'El objeto no es una carpeta.',
			'errNotFile'           : 'El objeto no es un archivo.',
			'errRead'              : 'No se puede leer "$1".',
			'errWrite'             : 'No se puede escribir en "$1".',
			'errPerm'              : 'Permiso denegado.',
			'errLocked'            : '"$1" está bloqueado y no puede ser renombrado, movido o borrado.',
			'errExists'            : 'Ya existe un archivo llamado "$1".',
			'errInvName'           : 'Nombre de archivo no válido.',
			'errFolderNotFound'    : 'Carpeta no encontrada.',
			'errFileNotFound'      : 'Archivo no encontrado.',
			'errTrgFolderNotFound' : 'Carpeta de destino "$1" no encontrada.',
			'errPopup'             : 'El navegador impide abrir nuevas ventanas. Puede activarlo en las opciones del navegador.',
			'errMkdir'             : 'No se puede crear la carpeta "$1".',
			'errMkfile'            : 'No se puede crear el archivo "$1".',
			'errRename'            : 'No se puede renombrar "$1".',
			'errCopyFrom'          : 'No se permite copiar archivos desde el volumen "$1".',
			'errCopyTo'            : 'No se permite copiar archivos al volumen "$1".',
			'errUploadCommon'      : 'Error en el envio.',
			'errUpload'            : 'No se puede subir "$1".',
			'errUploadNoFiles'     : 'No hay archivos para subir.',
			'errMaxSize'           : 'El tamaño de los datos excede el máximo permitido.',
			'errFileMaxSize'       : 'El tamaño del archivo excede el máximo permitido.',
			'errUploadMime'        : 'Tipo de archivo no permitido.',
			'errUploadTransfer'    : 'Error al transferir "$1".', 
			'errSave'              : 'No se puede guardar "$1".',
			'errCopy'              : 'No se puede copiar "$1".',
			'errMove'              : 'No se puede mover "$1".',
			'errCopyInItself'      : 'No se puede copiar "$1" en si mismo.',
			'errRm'                : 'No se puede borrar "$1".',
			'errExtract'           : 'No se puede extraer archivos desde "$1".',
			'errArchive'           : 'No se puede crear el archivo.',
			'errArcType'           : 'Tipo de archivo no soportado.',
			'errNoArchive'         : 'El archivo no es de tipo archivo o es de un tipo no soportado.',
			'errCmdNoSupport'      : 'El backend no soporta este comando.',
			'errReplByChild'       : 'La carpeta “$1” no puede ser reemplazada por un elemento contenido en ella.',
			'errArcSymlinks'       : 'Por razones de seguridad no se pueden descomprimir archivos que contengan symlinks.',
			'errArcMaxSize'        : 'El tamaño del archivo excede el máximo permitido.',

			'errSessionExpires'    : 'La sesión ha expirado por inactividad',
			'errCreatingTempDir'   : 'No se ha podido crear al directorio temporal: "$1"',
			'errFtpDownloadFile'   : 'No se ha podido descargar el archivo desde FTP: "$1"',
			'errFtpUploadFile'     : 'No se ha podido cargar el archivo a FTP: "$1"',
			'errFtpMkdir'          : 'No se ha podido crear el directorio remoto en FTP: "$1"',
			'errArchiveExec'       : 'Se ha producido un error durante la archivación: "$1"',
			'errExtractExec'       : 'Se ha producido un error durante la extracción de archivos: "$1"',
			'cmdsort'              : 'Clasificar',
			'sortkind'             : 'por tipo',
			'sortname'             : 'por nombre',
			'sortsize'             : 'por tamaño',
			'sortdate'             : 'por fecha',
			'sortFoldersFirst'     : 'Las carpetas en primer lugar',
			'errUploadFile'        : 'No se ha podido cargar "$1".',
			
			/******************************* commands names ********************************/
			'cmdarchive'   : 'Crear archivo',
			'cmdback'      : 'Atrás',
			'cmdcopy'      : 'Copiar',
			'cmdcut'       : 'Cortar',
			'cmddownload'  : 'Descargar',
			'cmdduplicate' : 'Duplicar',
			'cmdedit'      : 'Editar archivo',
			'cmdextract'   : 'Extraer elementos del archivo',
			'cmdforward'   : 'Adelante',
			'cmdgetfile'   : 'Seleccionar archivos',
			'cmdhelp'      : 'Acerca de este software',
			'cmdhome'      : 'Inicio',
			'cmdinfo'      : 'Obtener información',
			'cmdmkdir'     : 'Nueva carpeta',
			'cmdmkfile'    : 'Nuevo archivo de texto',
			'cmdopen'      : 'Abrir',
			'cmdpaste'     : 'Pegar',
			'cmdquicklook' : 'Previsualizar',
			'cmdreload'    : 'Recargar',
			'cmdrename'    : 'Cambiar nombre',
			'cmdrm'        : 'Eliminar',
			'cmdsearch'    : 'Buscar archivos',
			'cmdup'        : 'Ir a la carpeta raíz',
			'cmdupload'    : 'Subir archivos',
			'cmdview'      : 'Ver',
			
			/*********************************** buttons ***********************************/ 
			'btnClose'  : 'Cerrar',
			'btnSave'   : 'Guardar',
			'btnRm'     : 'Eliminar',
			'btnCancel' : 'Cancelar',
			'btnNo'     : 'No',
			'btnYes'    : 'Sí',
			
			/******************************** notifications ********************************/
			'ntfopen'     : 'Abrir carpeta',
			'ntffile'     : 'Abrir archivo',
			'ntfreload'   : 'Actualizar contenido de la carpeta',
			'ntfmkdir'    : 'Creando directorio',
			'ntfmkfile'   : 'Creando archivos',
			'ntfrm'       : 'Eliminando archivos',
			'ntfcopy'     : 'Copiar archivos',
			'ntfmove'     : 'Mover archivos',
			'ntfprepare'  : 'Preparar copia de archivos',
			'ntfrename'   : 'Renombrar archivos',
			'ntfupload'   : 'Subiendo archivos',
			'ntfdownload' : 'Descargando archivos',
			'ntfsave'     : 'Guardar archivos',
			'ntfarchive'  : 'Creando archivo',
			'ntfextract'  : 'Extrayendo elementos del archivo',
			'ntfsearch'   : 'Buscando archivos',
			'ntfsmth'     : 'Haciendo algo',
			'ntfloadimg'  : 'Cargando imagen',
			
			/************************************ dates **********************************/
			'dateUnknown' : 'desconocida',
			'Today'       : 'Hoy',
			'Yesterday'   : 'Ayer',
			'Jan'         : 'Ene',
			'Feb'         : 'Feb',
			'Mar'         : 'Mar',
			'Apr'         : 'Abr',
			'May'         : 'May',
			'Jun'         : 'Jun',
			'Jul'         : 'Jul',
			'Aug'         : 'Ago',
			'Sep'         : 'Sep',
			'Oct'         : 'Oct',
			'Nov'         : 'Nov',
			'Dec'         : 'Dic',

			/********************************** messages **********************************/
			'confirmReq'      : 'Se necesita confirmación',
			'confirmRm'       : '¿Está seguro de querer eliminar archivos?<br/>Esto no tiene vuelta atrás!',
			'confirmRepl'     : '¿Reemplazar el antiguo archivo con el nuevo?',
			'apllyAll'        : 'Aplicar a todo',
			'name'            : 'Nombre',
			'size'            : 'Tamaño',
			'perms'           : 'Permisos',
			'modify'          : 'Modificado',
			'kind'            : 'Tipo',
			'read'            : 'lectura',
			'write'           : 'escritura',
			'noaccess'        : 'sin acceso',
			'and'             : 'y',
			'unknown'         : 'desconocido',
			'selectall'       : 'Seleccionar todos los archivos',
			'selectfiles'     : 'Seleccionar archivo(s)',
			'selectffile'     : 'Seleccionar primer archivo',
			'selectlfile'     : 'Seleccionar último archivo',
			'viewlist'        : 'ver como lista',
			'viewicons'       : 'Ver como iconos',
			'places'          : 'Lugares',
			'calc'            : 'Calcular', 
			'path'            : 'Ruta',
			'aliasfor'        : 'Alias para',
			'locked'          : 'Bloqueado',
			'dim'             : 'Dimensiones',
			'files'           : 'Archivos',
			'folders'         : 'Carpetas',
			'items'           : 'Elementos',
			'yes'             : 'si',
			'no'              : 'no',
			'link'            : 'Enlace',
			'searcresult'     : 'Resultados de la búsqueda',  
			'selected'        : 'elementos seleccionados',
			'about'           : 'Acerca',
			'shortcuts'       : 'Accesos directos',
			'help'            : 'Ayuda',
			'webfm'           : 'Administrador de archivos web',
			'ver'             : 'Version',
			'protocolver'     : 'versión del protocolo',
			'homepage'        : 'Inicio',
			'docs'            : 'Documentación',
			'github'          : 'Fork us on Github',
			'twitter'         : 'Síguenos en Twitter',
			'facebook'        : 'Únete a nosotros en Facebook',
			'team'            : 'Equipo',
			'chiefdev'        : 'desarrollador jefe',
			'developer'       : 'desarrollador',
			'contributor'     : 'contribuyente',
			'maintainer'      : 'mantenedor',
			'translator'      : 'traductor',
			'icons'           : 'Iconos',
			'dontforget'      : 'y no olvide traer su toalla',
			'shortcutsof'     : 'Accesos directos desactivados',
			'dropFiles'       : 'Arrastre archivos aquí',
			'or'              : 'o',
			'selectForUpload' : 'Seleccione archivos para subir',
			'moveFiles'       : 'Mover archivos',
			'copyFiles'       : 'Copiar archivos',
			'rmFromPlaces'    : 'Eliminar en sus ubicaciones',
			'untitled folder' : 'carpeta sin título',
			'untitled archivo.txt' : 'archivo.txt sin título',
      'mode'            : 'Modo',
      'resize'          : 'Redimensionar',
      'crop'            : 'Recortar',
			
			/********************************** mimetypes **********************************/
			'kindUnknown'     : 'Desconocido',
			'kindFolder'      : 'Carpeta',
			'kindAlias'       : 'Alias',
			'kindAliasBroken' : 'Alias roto',
			// applications
			'kindApp'         : 'Aplicación',
			'kindPostscript'  : 'Documento Postscript',
			'kindMsOffice'    : 'Documento Microsoft Office',
			'kindMsWord'      : 'Documento Microsoft Word',
			'kindMsExcel'     : 'Documento Microsoft Excel',
			'kindMsPP'        : 'Presentación Microsoft Powerpoint',
			'kindOO'          : 'Documento Open Office',
			'kindAppFlash'    : 'Aplicación Flash',
			'kindPDF'         : 'Documento PDF',
			'kindTorrent'     : 'Archivo Bittorrent',
			'kind7z'          : 'Archivo 7z',
			'kindTAR'         : 'Archivo TAR',
			'kindGZIP'        : 'Archivo GZIP',
			'kindBZIP'        : 'Archivo BZIP',
			'kindZIP'         : 'Archivo ZIP',
			'kindRAR'         : 'Archivo RAR',
			'kindJAR'         : 'Archivo Java JAR',
			'kindTTF'         : 'Fuente True Type',
			'kindOTF'         : 'Fuente Open Type',
			'kindRPM'         : 'Paquete RPM',
			// texts
			'kindText'        : 'Documento de texto',
			'kindTextPlain'   : 'Texto plano',
			'kindPHP'         : 'Código PHP',
			'kindCSS'         : 'Hoja de estilo CSS',
			'kindHTML'        : 'Documento HTML',
			'kindJS'          : 'Código Javascript',
			'kindRTF'         : 'Documento RTF',
			'kindC'           : 'Código C source',
			'kindCHeader'     : 'Código C header',
			'kindCPP'         : 'Código C++',
			'kindCPPHeader'   : 'Código C++ header',
			'kindShell'       : 'Script Unix shell',
			'kindPython'      : 'Código Python',
			'kindJava'        : 'Código Java',
			'kindRuby'        : 'Código Ruby',
			'kindPerl'        : 'Código Perl',
			'kindSQL'         : 'SCódigo QL',
			'kindXML'         : 'Documento XML',
			'kindAWK'         : 'Código AWK source',
			'kindCSV'         : 'Documento CSV (valores separados por comas)',
			'kindDOCBOOK'     : 'Documento Docbook XML',
			// images
			'kindImage'       : 'Imagen',
			'kindBMP'         : 'Imagen BMP',
			'kindJPEG'        : 'Imagen JPEG',
			'kindGIF'         : 'Imagen GIF',
			'kindPNG'         : 'Imagen PNG',
			'kindTIFF'        : 'Imagen TIFF',
			'kindTGA'         : 'Imagen TGA',
			'kindPSD'         : 'Imagen Adobe Photoshop',
			'kindXBITMAP'     : 'Imagen X bitmap',
			'kindPXM'         : 'Imagen Pixelmator',
			// media
			'kindAudio'       : 'Audio media',
			'kindAudioMPEG'   : 'Audio MPEG',
			'kindAudioMPEG4'  : 'Audio MPEG-4',
			'kindAudioMIDI'   : 'Audio MIDI',
			'kindAudioOGG'    : 'Audio Ogg Vorbis',
			'kindAudioWAV'    : 'Audio WAV',
			'AudioPlaylist'   : 'Playlist MP3',
			'kindVideo'       : 'Video media',
			'kindVideoDV'     : 'Película DV',
			'kindVideoMPEG'   : 'Película MPEG',
			'kindVideoMPEG4'  : 'Película MPEG-4',
			'kindVideoAVI'    : 'Película AVI',
			'kindVideoMOV'    : 'Película Quick Time',
			'kindVideoWM'     : 'Película Windows Media',
			'kindVideoFlash'  : 'Película Flash',
			'kindVideoMKV'    : 'Película Matroska MKV',
			'kindVideoOGG'    : 'Película Ogg'
		}
	}
}

;/**
 * Persian-Farsi Translation
 * @author Keyhan Mohammadpour <keyhan_universityworks@yahoo.com>
 * @version 2012-04-07
 */
if (elFinder && elFinder.prototype && typeof(elFinder.prototype.i18) == 'object') {
	elFinder.prototype.i18.fa = {
		translator : 'Keyhan Mohammadpour &lt;keyhan_universityworks@yahoo.com&gt;',
		language   : 'فارسی',
		direction  : 'rtl',
		dateFormat : 'd.m.Y H:i',
		fancyDateFormat : '$1 H:i',
		messages   : {

			/********************************** errors **********************************/
			'error'                : 'خطا',
			'errUnknown'           : 'خطای ناشناخته .',
			'errUnknownCmd'        : 'دستور ناشناخته .',
			'errJqui'              : 'تنظیمات کتابخانه JQuery UI شما به درستی تنظیم نشده است . این کتابخانه بایستی شامل Resizable ، Draggable و Droppable باشد .',
			'errNode'              : 'شی elfinder به درستی ایجاد نشده است .',
			'errURL'               : 'تنظیمات elfinder شما به درستی انجام نشده است . تنظیم Url را به درستی انجام دهید .',
			'errAccess'            : 'محدودیت سطح دسترسی',
			'errAbort'             : 'ارتباط قطع شده است .',
			'errTimeout'           : 'مهلت زمانی Connection شما به انتها رسیده ایت .',
			'errNotFound'          : 'تنظیم Backend یافت نشد .',
			'errResponse'          : 'پاسخ دریافتی از Backend صحیح نمی باشد .',
			'errConf'              : 'تنطیمات Backend به درستی انجام نشده است .',
			'errJSON'              : 'ماژول PHP JSON نصب نگردیده است .',
			'errNoVolumes'         : 'درایوهای قابل خواندن یافت نشدند .',
			'errCmdParams'         : 'پارامترهای دستور "$1" به صورت صحیح داده نشده است .',
			'errDataNotJSON'       : 'داده ها در قالب JSON نمی باشند .',
			'errDataEmpty'         : 'داده ها تهی می باشند .',
			'errCmdReq'            : 'درخواست از سمت Backend نیازمند نام دستور می باشد .',
			'errOpen'              : 'قادر به باز نمودن "$1" نمی باشد .',
			'errNotFolder'         : 'شی به صورت پوشه نمی باشد .',
			'errNotFile'           : 'شی به صورت فایل نمی باشد .',
			'errRead'              : 'قادر به خواندن "$1" نمی باشد .',
			'errWrite'             : 'قادر به نوشتن در درون "$1" نمی باشد .',
			'errPerm'              : 'شما مجاز به انجام این عمل نمی باشید .',
			'errLocked'            : '"$1"قفل گردیده است و شما قادر به تغییر نام ، حذف و یا جابجایی آن نمی باشید .',
			'errExists'            : 'فایلی با نام "$1" هم اکنون وجود دارد .',
			'errInvName'           : 'نام انتخابی شما صحیح نمی باشد .',
			'errFolderNotFound'    : 'پوشه مورد نظر شما یافت نشد .',
			'errFileNotFound'      : 'فایل مورد نظر شما یافت نشد .',
			'errTrgFolderNotFound' : 'پوشه مقصد با نام "$1" یافت نشد .',
			'errPopup'             : 'مرورگر شما ار باز شدن پنجره popup جلوگیری می نماید ، اطفا تنطیم مربوطه را در مرورگر خود فعال نمایید .',
			'errMkdir'             : 'قادر به ایجاد نمودن پوشه ای با نام "$1" نمی باشد .',
			'errMkfile'            : 'قادر به ابجاد نمودن فایلی با نبم "$1" نمی باشد .',
			'errRename'            : 'قادر به تغییر نام فایل "$1" نمی باشد .',
			'errCopyFrom'          : 'کپی نمودن از درایو با نام "$1" امکان پذیر نمی باشد .',
			'errCopyTo'            : 'کپی نمودن به درایو با نام "$1" امکان پذیر نمی باشد .',
			'errUploadCommon'      : 'خطای بارگذاری ',
			'errUpload'            : 'قادر به بارگذاری "$1" نمی باشد .',
			'errUploadNoFiles'     : 'هیچ فایلی برای بارگذاری یافت نشد .',
			'errMaxSize'           : 'حجم داده ها بیشتر از حد مجاز تعیین شده است .',
			'errFileMaxSize'       : 'حجم فایل بیشتر از حد مجاز تعیین شده است .',
			'errUploadMime'        : 'نوع فایل انتخابی شما مجاز نمی باشد .',
			'errUploadTransfer'    : 'در تبادل "$1" خطایی رخ داده است .',
			'errSave'              : 'قادر به دخیره کردن "$1" نمی باشد .',
			'errCopy'              : 'قادر به کپی نمودن "$1" نمی باشد .',
			'errMove'              : 'قادر به جابجایی "$1" نمی باشد .',
			'errCopyInItself'      : 'قادر به کپی نمودن "$1" در درون خودش نمی باشد .',
			'errRm'                : 'قادر به حذف نمودن "$1" نمی باشد .',
			'errExtract'           : 'قادر به استخراج فایل فشرده "$1" نمی باشد .',
			'errArchive'           : 'قادر به ایجاد آرشیو نمی باشد .',
			'errArcType'           : 'نوع ناشناخته برای آرشیو .',
			'errNoArchive'         : 'قادر به آرشیو نمودن فایل نمی باشد و یا نوع فایل در نوع های آرشیو تعیین نشده است .',
			'errCmdNoSupport'      : 'Backend قادر به پشتیبانی از این دستور نمی باشد .',
			'errReplByChild'       : 'پوشه با نام "$1"قادر به تغییر با محتویات درونی خود نمی باشد .',
			'errArcSymlinks'       : 'به دلایل مسائل امنیتی قادر به استخراج آرشیو های دارای symlinks نمی باشد .',
			'errArcMaxSize'        : 'فایل های آرشیو شده به حداکثر اندازه تعیین شده رسیده اند .',
			'errResize'            : 'قادر به تغییر اندازه "$1" نمی باشد .',
			'errUsupportType'      : 'نوع فایل شما قابل پشتیبانی نمی باشد .',

			/******************************* commands names ********************************/
			'cmdarchive'   : 'ساختن آرشیو',
			'cmdback'      : 'قبلی',
			'cmdcopy'      : 'کپی',
			'cmdcut'       : 'جابجایی',
			'cmddownload'  : 'بارگیری',
			'cmdduplicate' : 'تکثیر نمودن',
			'cmdedit'      : 'ویرایش فایل',
			'cmdextract'   : 'از حالت فشرده خارج نمودن',
			'cmdforward'   : 'بعدی',
			'cmdgetfile'   : 'انتخاب فایل ها',
			'cmdhelp'      : 'درباره این فایل',
			'cmdhome'      : 'صفحه اصلی',
			'cmdinfo'      : 'دریافت اطلاعات',
			'cmdmkdir'     : 'پوشه جدید',
			'cmdmkfile'    : 'فایل متنی جدید',
			'cmdopen'      : 'باز نمودن',
			'cmdpaste'     : 'چسباندن',
			'cmdquicklook' : 'پیش نمایش',
			'cmdreload'    : 'بارگذاری مجدد',
			'cmdrename'    : 'تغییر نام',
			'cmdrm'        : 'حذف',
			'cmdsearch'    : 'جستجو',
			'cmdup'        : 'رفتن به پوشه والد',
			'cmdupload'    : 'بارگذاری فایل ها',
			'cmdview'      : 'نمایش',
			'cmdresize'    : 'تغییر اندازه فایل',
			'cmdsort'      : 'مرتب سازی',

			/*********************************** buttons ***********************************/ 
			'btnClose'  : 'بستن',
			'btnSave'   : 'ذخیره',
			'btnRm'     : 'حذف',
			'btnApply'  : 'اعمال',
			'btnCancel' : 'انصراف',
			'btnNo'     : 'خیر',
			'btnYes'    : 'بلی',

			/******************************** notifications ********************************/
			'ntfopen'     : 'باز نمودن پوشه',
			'ntffile'     : 'باز نمدن فایل',
			'ntfreload'   : 'بازخوانی مجدد محتویات پوشه',
			'ntfmkdir'    : 'ساختن پوشه',
			'ntfmkfile'   : 'ساختن فایل',
			'ntfrm'       : 'حذف فایل',
			'ntfcopy'     : 'کپی فایل',
			'ntfmove'     : 'انتقال فایل',
			'ntfprepare'  : 'آماده شدن برای کپی نمودن فایل ها',
			'ntfrename'   : 'تغییر نام فایل',
			'ntfupload'   : 'بارگذاری فایل',
			'ntfdownload' : 'بارگیری فایل',
			'ntfsave'     : 'ذخیره نمودن فایل ها',
			'ntfarchive'  : 'در حال ساختن آرشیو',
			'ntfextract'  : 'استخراج فایل ها از آرشیو',
			'ntfsearch'   : 'در حال جستجو فایل ها',
			'ntfsmth'     : 'درحال انجام عملیات ....',
			'ntfloadimg'  : 'در حال لود نمودن تصویر',

			/************************************ dates **********************************/
			'dateUnknown' : 'ناشناخته',
			'Today'       : 'امروز',
			'Yesterday'   : 'دیروز',
			'Jan'         : 'بهمن',
			'Feb'         : 'اسفند',
			'Mar'         : 'فروردین',
			'Apr'         : 'اردیبهشت',
			'May'         : 'خرداد',
			'Jun'         : 'تیر',
			'Jul'         : 'مرداد',
			'Aug'         : 'شهریور',
			'Sep'         : 'مهر',
			'Oct'         : 'آبان',
			'Nov'         : 'آذر',
			'Dec'         : 'دی',
			'January'     : 'بهمن',
			'February'    : 'اسفند',
			'March'       : 'فروردین',
			'April'       : 'اردیبهشت',
			'May'         : 'خرداد',
			'June'        : 'تیر',
			'July'        : 'مرداد',
			'August'      : 'شهریور',
			'September'   : 'مهر',
			'October'     : 'آبان',
			'November'    : 'آذر',
			'December'    : 'دی',
			'Sunday'      : 'یک شنبه',
			'Monday'      : 'دوشنبه',
			'Tuesday'     : 'سه شنبه',
			'Wednesday'   : 'چهار شنبه',
			'Thursday'    : 'پنج شنبه',
			'Friday'      : 'جمعه',
			'Saturday'    : 'شنبه',
			'Sun'         : 'یک شنبه',
			'Mon'         : 'دو شنبه',
			'Tue'         : 'سه شنبه',
			'Wed'         : 'چهار شنبه',
			'Thu'         : 'پنج شنبه',
			'Fri'         : 'جمعه',
			'Sat'         : 'شنبه',
			/******************************** sort variants ********************************/
			'sortnameDirsFirst' : 'بر اساس نام (پوشه ها در ابتدا قرار می گیرند ) .',
			'sortkindDirsFirst' : 'بر اساس نوع (پوشه ها در ابتدا قرار می گیرند ) .',
			'sortsizeDirsFirst' : 'بر اساس اندازه (پوشه ها در ابتدا قرار می گیرند ) .',
			'sortdateDirsFirst' : 'بر اساس تاریخ (پوشه ها در ابتدا قرار می گیرند ) .',
			'sortname'          : 'بر اساس نام',
			'sortkind'          : 'بر اساس نوع',
			'sortsize'          : 'بر اساس اندازه',
			'sortdate'          : 'بر اساس تاریخ',

			/********************************** messages **********************************/
			'confirmReq'      : 'تاییدیه نهایی نیاز است .',
			'confirmRm'       : 'آیا مطمثن به انجام عملیات حذف می باشید ؟ آیتم های حدف شده قابل بازیابی نمی باشند  !',
			'confirmRepl'     : 'آیا فایل قدیم با فایل جدید جایگزین شود ؟',
			'apllyAll'        : 'اعمال تغییرات به همه',
			'name'            : 'نام',
			'size'            : 'اندازه',
			'perms'           : 'مجوزها',
			'modify'          : 'تغییر داده شده',
			'kind'            : 'نوع',
			'read'            : 'خواندن',
			'write'           : 'نوشتن',
			'noaccess'        : 'دسترسی وجود ندارد',
			'and'             : 'و',
			'unknown'         : 'ناشناخته',
			'selectall'       : 'انتخاب همه فایل ها',
			'selectfiles'     : 'انتخاب یکی یا همه فایل ها',
			'selectffile'     : 'انتخاب اولین فایل',
			'selectlfile'     : 'انتخاب آخرین فایل',
			'viewlist'        : 'نمایش به صورت لیست',
			'viewicons'       : 'نمایش با آیکون ها',
			'places'          : 'محل ها',
			'calc'            : 'محاسبه',
			'path'            : 'مسیر',
			'aliasfor'        : 'نام مستعار برای',
			'locked'          : 'قفل شده',
			'dim'             : 'ابعاد',
			'files'           : 'فایل ها',
			'folders'         : 'پوشه ها',
			'items'           : 'آیتم ها',
			'yes'             : 'بلی',
			'no'              : 'خیر',
			'link'            : 'پیوند',
			'searcresult'     : 'جستجو در نتایج',
			'selected'        : 'آیتم های انتخاب شده',
			'about'           : 'درباره',
			'shortcuts'       : 'میانبرها',
			'help'            : 'راهنما',
			'webfm'           : 'مدیر وب فایل',
			'ver'             : 'نسخه',
			'protocol'        : 'نسخه پروتکل',
			'homepage'        : 'صفحه اصلی پروژه',
			'docs'            : 'مستندات',
			'github'          : 'دنبال کردن ما بر روی Github',
			'twitter'         : 'دنبال کردن ما در Twitter',
			'facebook'        : 'به ما در facebook بپیوندید',
			'team'            : 'گروه',
			'chiefdev'        : 'سازنده اصلی برنامه',
			'developer'       : 'سازنده',
			'contributor'     : 'همکار',
			'maintainer'      : 'پشتیبان',
			'translator'      : 'مترجم',
			'icons'           : 'آیکون ها',
			'dontforget'      : 'فراموش نشود',
			'shortcutsof'     : 'میانبرها غیرفعال شده اند .',
			'dropFiles'       : 'فایل های خود را در این محل رها نمایید .',
			'or'              : 'یا',
			'selectForUpload' : 'انتخاب فایل ها برای بارگذاری',
			'moveFiles'       : 'انتقال فایل ها',
			'copyFiles'       : 'کپی فایل ها',
			'rmFromPlaces'    : 'حدف',
			'untitled folder' : 'پوشه بدون نام',
			'untitled file.txt' : 'فایل متنی بدون نام',
			'aspectRatio'     : 'نسبت تصویر',
			'scale'           : 'مقیاس',
			'width'           : 'طول',
			'height'          : 'ارتفاع',
			'mode'            : 'مد',
			'resize'          : 'تغییر اندازه',
			'crop'            : 'بریدن',
			'rotate'          : 'چرخاندن',
			'rotate-cw'       : 'چرخاندن 90 درجه در جهت عقربه های ساعت',
			'rotate-ccw'      : 'چرخاندن 90 درجه در جهت خلاف عقربه های ساعت',
			'degree'          : 'درجه',

			/********************************** mimetypes **********************************/
			'kindUnknown'     : 'Unknown',
			'kindFolder'      : 'Folder',
			'kindAlias'       : 'Alias',
			'kindAliasBroken' : 'Broken alias',
			// applications
			'kindApp'         : 'Application',
			'kindPostscript'  : 'Postscript document',
			'kindMsOffice'    : 'Microsoft Office document',
			'kindMsWord'      : 'Microsoft Word document',
			'kindMsExcel'     : 'Microsoft Excel document',
			'kindMsPP'        : 'Microsoft Powerpoint presentation',
			'kindOO'          : 'Open Office document',
			'kindAppFlash'    : 'Flash application',
			'kindPDF'         : 'Portable Document Format (PDF)',
			'kindTorrent'     : 'Bittorrent file',
			'kind7z'          : '7z archive',
			'kindTAR'         : 'TAR archive',
			'kindGZIP'        : 'GZIP archive',
			'kindBZIP'        : 'BZIP archive',
			'kindZIP'         : 'ZIP archive',
			'kindRAR'         : 'RAR archive',
			'kindJAR'         : 'Java JAR file',
			'kindTTF'         : 'True Type font',
			'kindOTF'         : 'Open Type font',
			'kindRPM'         : 'RPM package',
			// texts
			'kindText'        : 'Text document',
			'kindTextPlain'   : 'Plain text',
			'kindPHP'         : 'PHP source',
			'kindCSS'         : 'Cascading style sheet',
			'kindHTML'        : 'HTML document',
			'kindJS'          : 'Javascript source',
			'kindRTF'         : 'Rich Text Format',
			'kindC'           : 'C source',
			'kindCHeader'     : 'C header source',
			'kindCPP'         : 'C++ source',
			'kindCPPHeader'   : 'C++ header source',
			'kindShell'       : 'Unix shell script',
			'kindPython'      : 'Python source',
			'kindJava'        : 'Java source',
			'kindRuby'        : 'Ruby source',
			'kindPerl'        : 'Perl script',
			'kindSQL'         : 'SQL source',
			'kindXML'         : 'XML document',
			'kindAWK'         : 'AWK source',
			'kindCSV'         : 'Comma separated values',
			'kindDOCBOOK'     : 'Docbook XML document',
			// images
			'kindImage'       : 'Image',
			'kindBMP'         : 'BMP image',
			'kindJPEG'        : 'JPEG image',
			'kindGIF'         : 'GIF Image',
			'kindPNG'         : 'PNG Image',
			'kindTIFF'        : 'TIFF image',
			'kindTGA'         : 'TGA image',
			'kindPSD'         : 'Adobe Photoshop image',
			'kindXBITMAP'     : 'X bitmap image',
			'kindPXM'         : 'Pixelmator image',
			// media
			'kindAudio'       : 'Audio media',
			'kindAudioMPEG'   : 'MPEG audio',
			'kindAudioMPEG4'  : 'MPEG-4 audio',
			'kindAudioMIDI'   : 'MIDI audio',
			'kindAudioOGG'    : 'Ogg Vorbis audio',
			'kindAudioWAV'    : 'WAV audio',
			'AudioPlaylist'   : 'MP3 playlist',
			'kindVideo'       : 'Video media',
			'kindVideoDV'     : 'DV movie',
			'kindVideoMPEG'   : 'MPEG movie',
			'kindVideoMPEG4'  : 'MPEG-4 movie',
			'kindVideoAVI'    : 'AVI movie',
			'kindVideoMOV'    : 'Quick Time movie',
			'kindVideoWM'     : 'Windows Media movie',
			'kindVideoFlash'  : 'Flash movie',
			'kindVideoMKV'    : 'Matroska movie',
			'kindVideoOGG'    : 'Ogg movie'
		}
	}
};/**
 * French translation
 * @author Régis Guyomarch <regisg@gmail.com>, Benoit Delachaux <benorde33@gmail.com>
 * @version 2012-08-11
 */
if (elFinder && elFinder.prototype && typeof(elFinder.prototype.i18) == 'object') {
	elFinder.prototype.i18.fr = {
		translator : 'Régis Guyomarch &lt;regisg@gmail.com&gt;Benoit Delachaux &lt;benorde33@gmail.com&gt;',
		language   : 'française',
		direction  : 'ltr',
		dateFormat : 'd M, Y H:i',
		fancyDateFormat : '$1 H:i',
		messages   : {
			
			/********************************** errors **********************************/
			'error'                : 'Erreur',
			'errUnknown'           : 'Erreur inconnue.',
			'errUnknownCmd'        : 'Commande inconnue.',
			'errJqui'              : 'Mauvaise configuration de jQuery UI. Les composants Selectable, draggable et droppable doivent être inclus.',
			'errNode'              : 'elFinder requiert que l\'élément DOM ait été créé.',
			'errURL'               : 'Mauvaise configuration d\'elFinder ! L\'option URL n\a pas été définie.',
			'errAccess'            : 'Accès refusé.',
			'errConnect'           : 'Impossible de se connecter au backend.',
			'errAbort'             : 'Connexion interrompue.',
			'errTimeout'           : 'Délai de connexion dépassé.',
			'errNotFound'          : 'Backend non trouvé.',
			'errResponse'          : 'Mauvaise réponse du backend.',
			'errConf'              : 'Mauvaise configuration du backend.',
			'errJSON'              : 'Le module PHP JSON n\'est pas installé.',
			'errNoVolumes'         : 'Aucun volume lisible.',
			'errCmdParams'         : 'Mauvais Paramétrage de la commande "$1".',
			'errDataNotJSON'       : 'Les données ne sont pas au format JSON.',
			'errDataEmpty'         : 'Données inexistantes.',
			'errCmdReq'            : 'La requête au Backend doit comporter le nom de la commande.',
			'errOpen'              : 'Impossible d\'ouvrir "$1".',
			'errNotFolder'         : 'Cet objet n\'est pas un dossier.',
			'errNotFile'           : 'Cet objet n\'est pas un fichier.',
			'errRead'              : 'Impossible de lire "$1".',
			'errWrite'             : 'Impossible d\'écrire dans "$1".',
			'errPerm'              : 'Permission refusée.',
			'errLocked'            : '"$1" est verrouillé et ne peut être déplacé ou supprimé.',
			'errExists'            : 'Un fichier nommé "$1" existe déjà.',
			'errInvName'           : 'Nom de fichier incorrect.',
			'errFolderNotFound'    : 'Dossier non trouvé.',
			'errFileNotFound'      : 'Fichier non trouvé.',
			'errTrgFolderNotFound' : 'Dossier destination "$1" non trouvé.',
			'errPopup'             : 'Le navigateur web a empêché l\'ouverture d\'une fenêtre "popup". Pour ouvrir le fichier, modifiez les options du navigateur web.',
			'errMkdir'             : 'Impossible de créer le dossier "$1".',
			'errMkfile'            : 'impossible de créer le fichier "$1".',
			'errRename'            : 'Impossible de renommer "$1".',
			'errCopyFrom'          : 'Interdiction de copier des fichiers depuis le volume "$1".',
			'errCopyTo'            : 'Interdiction de copier des fichiers vers le volume "$1".',
			'errUpload'            : 'Erreur lors de l\'envoi du fichier.',
			'errUploadFile'        : 'Impossible d\'envoyer "$1".',
			'errUploadNoFiles'     : 'Aucun fichier à envoyer.',
			'errUploadTotalSize'   : 'Les données dépassent la taille maximale allouée.',
			'errUploadFileSize'    : 'Le fichier dépasse la taille maximale allouée.',
			'errUploadMime'        : 'Type de fichier non autorisé.',
			'errUploadTransfer'    : '"$1" erreur transfert.', 
			'errNotReplace'        : 'L\'objet "$1" existe déjà à cet endroit et ne peut être remplacé par un objet d\'un type différent.', // new
			'errReplace'           : 'Impossible de remplacer "$1".', // added 11.08.1013
			'errSave'              : 'Impossible de sauvegarder "$1".',
			'errCopy'              : 'Impossible de copier "$1".',
			'errMove'              : 'Impossible de déplacer "$1".',
			'errCopyInItself'      : 'Impossible de copier "$1" sur lui-même.',
			'errRm'                : 'Impossible de supprimer "$1".',
			'errExtract'           : 'Impossible d\'extraire les fichier de "$1".',
			'errExtract'           : 'Imbossible d\'extraire les fichiers à partir de "$1".', // added 11.08.2012
			'errArchive'           : 'Impossible de créer l\'archive.',
			'errArcType'           : 'Type d\'archive non supporté.',
			'errNoArchive'         : 'Le fichier n\'est pas une archive, ou c\'est un type d\'archive non supporté.',
			'errCmdNoSupport'      : 'Le Backend ne prend pas en charge cette commande.',
			'errReplByChild'       : 'Le dossier “$1” ne peut pas être remplacé par un élément qu\'il contient.',
			'errArcSymlinks'       : 'Par mesure de sécurité, il est défendu d\'extraire une archive contenant des liens symboliques.',
			'errArcMaxSize'        : 'Les fichiers de l\'archive excèdent la taille maximale autorisée.',
			'errResize'            : 'Impossible de redimensionner "$1".',
			'errResizeDegree'      : 'Degré de rotation invalide.',  // added 11.8.2013
			'errResizeRotate'      : 'L\'image ne peut pas être tournée.',  // added 11.8.2013
			'errResizeSize'        : 'Dimension de l\'image non-valide.',  // added 11.8.2013
			'errResizeNoChange'    : 'L\'image n\'est pas redimensionnable.',  // added 11.8.2013
			'errUsupportType'      : 'Type de fichier non supporté.',
			'errNotUTF8Content'    : 'Le fichier "$1" n\'est pas en UTF-8, il ne peut être édité.',  // added 9.11.2011
			'errNetMount'          : 'Impossible de monter "$1".', // added 17.04.2012
			'errNetMountNoDriver'  : 'Protocol non supporté.',     // added 17.04.2012
			'errNetMountFailed'    : 'Echec du montage.',         // added 17.04.2012
			'errNetMountHostReq'   : 'Hôte requis.', // added 18.04.2012			
			'errSessionExpires'    : 'Votre session a expiré en raison de son inactivité',
			'errCreatingTempDir'   : 'Impossible de créer le répertoire temporaire : "$1"',
			'errFtpDownloadFile'   : 'Impossible de télécharger le file depuis l\'accès FTP : "$1"',
			'errFtpUploadFile'     : 'Impossible d\'envoyer le fichier vers l\'accès FTP : "$1"',
			'errFtpMkdir'          : 'Impossible de créer un répertoire distant sur l\'accès FTP :"$1"',
			'errArchiveExec'       : 'Erreur lors de l\'archivage des fichiers : "$1"',
			'errExtractExec'       : 'Erreur lors de l\'extraction des fichiers : "$1"',
			
			/******************************* commands names ********************************/
			'cmdarchive'   : 'Créer une archive',
			'cmdback'      : 'Précédent',
			'cmdcopy'      : 'Copier',
			'cmdcut'       : 'Couper',
			'cmddownload'  : 'Télécharger',
			'cmdduplicate' : 'Dupliquer',
			'cmdedit'      : 'Éditer le fichier',
			'cmdextract'   : 'Extraire les fichiers de l\'archive',
			'cmdforward'   : 'Suivant',
			'cmdgetfile'   : 'Sélectionner les fichiers',
			'cmdhelp'      : 'À propos de ce logiciel',
			'cmdhome'      : 'Accueil',
			'cmdinfo'      : 'Informations',
			'cmdmkdir'     : 'Nouveau dossier',
			'cmdmkfile'    : 'Nouveau fichier texte',
			'cmdopen'      : 'Ouvrir',
			'cmdpaste'     : 'Coller',
			'cmdquicklook' : 'Prévisualiser',
			'cmdreload'    : 'Actualiser',
			'cmdrename'    : 'Renommer',
			'cmdrm'        : 'Supprimer',
			'cmdsearch'    : 'Trouver les fichiers',
			'cmdup'        : 'Remonter au dossier parent',
			'cmdupload'    : 'Envoyer les fichiers',
			'cmdview'      : 'Vue',
			'cmdresize'    : 'Redimensionner l\'image',
			'cmdsort'      : 'Trier',
			'cmdnetmount'  : 'Monter un volume réseau',
			
			/*********************************** buttons ***********************************/ 
			'btnClose'  : 'Fermer',
			'btnSave'   : 'Sauvegarder',
			'btnRm'     : 'Supprimer',
			'btnCancel' : 'Annuler',
			'btnApply'  : 'Appliquer', // added 11.08.2013
			'btnNo'     : 'Non',
			'btnYes'    : 'Oui',
			'btnMount'  : 'Monter',  // added 18.04.2012
			
			/******************************** notifications ********************************/
			'ntfopen'     : 'Ouvrir le dossier',
			'ntffile'     : 'Ouvrir le fichier',
			'ntfreload'   : 'Actualiser le contenu du dossier',
			'ntfmkdir'    : 'Création du dossier',
			'ntfmkfile'   : 'Création des fichiers',
			'ntfrm'       : 'Supprimer les fichiers',
			'ntfcopy'     : 'Copier les fichiers',
			'ntfmove'     : 'Déplacer les fichiers',
			'ntfprepare'  : 'Préparation de la copie des fichiers',
			'ntfrename'   : 'Renommer les fichier',
			'ntfupload'   : 'Envoyer les fichiers',
			'ntfdownload' : 'Télécharger les fichiers',
			'ntfsave'     : 'Sauvegarde des fichiers',
			'ntfarchive'  : 'Création de l\'archive',
			'ntfextract'  : 'Extraction des fichiers de l\'archive',
			'ntfsearch'   : 'Recherche des fichiers',
			'ntfsmth'     : 'Fait quelque chose',
			'ntfloadimg'  : 'Chargement de l\' image',
			'ntfnetmount' : 'Monte le volume réseau', // added 18.04.2012			
			'ntfdim'      : 'Calcule la dimension de l\'image', // added 11.08.2013
			
			/************************************ dates **********************************/
			'dateUnknown' : 'Inconnue',
			'Today'       : 'Aujourd\'hui',
			'Yesterday'   : 'Hier',
			'Jan'         : 'Jan',
			'Feb'         : 'Fév',
			'Mar'         : 'Mar',
			'Apr'         : 'Avr',
			'May'         : 'Mai',
			'Jun'         : 'Jun',
			'Jul'         : 'Jul',
			'Aug'         : 'Aoû',
			'Sep'         : 'Sep',
			'Oct'         : 'Oct',
			'Nov'         : 'Nov',
			'Dec'         : 'Déc',

			/******************************** sort variants ********************************/
			'sortname'          : 'par nom', 
			'sortkind'          : 'par type', 
			'sortsize'          : 'par taille',
			'sortdate'          : 'par date',
			'sortFoldersFirst'  : 'Dossiers en premier',
			
			/********************************** messages **********************************/
			'confirmReq'      : 'Confirmation requise',
			'confirmRm'       : 'Êtes-vous certain de vouloir supprimer les fichiers?<br/>Cela ne peut être annulé!',
			'confirmRepl'     : 'Supprimer l\'ancien fichier par le nouveau?',
			'apllyAll'        : 'Appliquer à tous',
			'name'            : 'Nom',
			'size'            : 'Taille',
			'perms'           : 'Permissions',
			'modify'          : 'Modifié',
			'kind'            : 'Type',
			'read'            : 'Lecture',
			'write'           : 'Écriture',
			'noaccess'        : 'Pas d\'accès',
			'and'             : 'et',
			'unknown'         : 'inconnu',
			'selectall'       : 'Sélectionner tous les fichiers',
			'selectfiles'     : 'Sélectionner le(s) fichier(s)',
			'selectffile'     : 'Sélectionner le premier fichier',
			'selectlfile'     : 'Sélectionner le dernier fichier',
			'viewlist'        : 'Vue listing',
			'viewicons'       : 'Vue icônes',
			'places'          : 'Places',
			'calc'            : 'Calculer', 
			'path'            : 'Chemin',
			'aliasfor'        : 'Raccourcis pour',
			'locked'          : 'Verrouiller',
			'dim'             : 'Dimensions',
			'files'           : 'Fichiers',
			'folders'         : 'Dossiers',
			'items'           : 'Éléments',
			'yes'             : 'oui',
			'no'              : 'non',
			'link'            : 'Lien',
			'searcresult'     : 'Résultat de la recherche',  
			'selected'        : 'Éléments sélectionnés',
			'about'           : 'À propos',
			'shortcuts'       : 'Raccourcis',
			'help'            : 'Aide',
			'webfm'           : 'Gestionnaire de fichier Web',
			'ver'             : 'Version',
			'protocolver'     : 'Version du protocole',
			'homepage'        : 'Page du projet',
			'docs'            : 'Documentation',
			'github'          : 'Forker-nous sur Github',
			'twitter'         : 'Suivez nous sur twitter',
			'facebook'        : 'Joignez-nous facebook',
			'team'            : 'Équipe',
			'chiefdev'        : 'Développeur en chef',
			'developer'       : 'Développeur',
			'contributor'     : 'Contributeur',
			'maintainer'      : 'Mainteneur',
			'translator'      : 'Traducteur',
			'icons'           : 'Icônes',
			'dontforget'      : 'et n\'oubliez pas votre serviette',
			'shortcutsof'     : 'Raccourcis désactivés',
			'dropFiles'       : 'Déposez les fichiers ici',
			'or'              : 'ou',
			'selectForUpload' : 'Sélectionner les fichiers à envoyer',
			'moveFiles'       : 'Déplacer les fichiers',
			'copyFiles'       : 'Copier les fichiers',
			'rmFromPlaces'    : 'Remove from places',
			'aspectRatio'     : 'Aspect ratio',
			'scale'           : 'Mise à l\'échelle',
			'width'           : 'Largeur',
			'height'          : 'Hauteur',
			'resize'          : 'Redimensionner',
			'crop'            : 'Recadrer',
			'rotate'          : 'Rotation',
			'rotate-cw'       : 'Rotation de 90 degrés horaire',
			'rotate-ccw'      : 'Rotation de 90 degrés antihoraire',
			'degree'          : '°',
			'netMountDialogTitle' : 'Monter un volume réseau', // added 18.04.2012
			'protocol'            : 'Protocole', // added 18.04.2012
			'host'                : 'Hôte', // added 18.04.2012
			'port'                : 'Port', // added 18.04.2012
			'user'                : 'Utilisateur', // added 18.04.2012
			'pass'                : 'Mot de passe', // added 18.04.2012
			
			/********************************** mimetypes **********************************/
			'kindUnknown'     : 'Inconnu',
			'kindFolder'      : 'Dossier',
			'kindAlias'       : 'Raccourci',
			'kindAliasBroken' : 'Raccourci cassé',
			// applications
			'kindApp'         : 'Application',
			'kindPostscript'  : 'Document Postscript',
			'kindMsOffice'    : 'Document Microsoft Office',
			'kindMsWord'      : 'Document Microsoft Word',
			'kindMsExcel'     : 'Document Microsoft Excel',
			'kindMsPP'        : 'Présentation Microsoft PowerPoint',
			'kindOO'          : 'Document OpenOffice',
			'kindAppFlash'    : 'Application Flash',
			'kindPDF'         : 'Portable Document Format (PDF)',
			'kindTorrent'     : 'Fichier BitTorrent',
			'kind7z'          : 'Archive 7z',
			'kindTAR'         : 'Archive TAR',
			'kindGZIP'        : 'Archive GZIP',
			'kindBZIP'        : 'Archive BZIP',
			'kindZIP'         : 'Archive ZIP',
			'kindRAR'         : 'Archive RAR',
			'kindJAR'         : 'Fichier Java JAR',
			'kindTTF'         : 'Police True Type',
			'kindOTF'         : 'Police Open Type',
			'kindRPM'         : 'Package RPM',
			// texts
			'kindText'        : 'Document Text',
			'kindTextPlain'   : 'Texte non formaté',
			'kindPHP'         : 'Source PHP',
			'kindCSS'         : 'Feuille de style en cascade',
			'kindHTML'        : 'Document HTML',
			'kindJS'          : 'Source JavaScript',
			'kindRTF'         : 'Format de texte enrichi (Rich Text Format)',
			'kindC'           : 'Source C',
			'kindCHeader'     : 'Source header C',
			'kindCPP'         : 'Source C++',
			'kindCPPHeader'   : 'Source header C++',
			'kindShell'       : 'Shell script Unix',
			'kindPython'      : 'Source Python',
			'kindJava'        : 'Source Java',
			'kindRuby'        : 'Source Ruby',
			'kindPerl'        : 'Script Perl',
			'kindSQL'         : 'Source SQL',
			'kindXML'         : 'Document XML',
			'kindAWK'         : 'Source AWK',
			'kindCSV'         : 'CSV',
			'kindDOCBOOK'     : 'Document Docbook XML',
			// images
			'kindImage'       : 'Image',
			'kindBMP'         : 'Image BMP',
			'kindJPEG'        : 'Image JPEG',
			'kindGIF'         : 'Image GIF',
			'kindPNG'         : 'Image PNG',
			'kindTIFF'        : 'Image TIFF',
			'kindTGA'         : 'Image TGA',
			'kindPSD'         : 'Image Adobe Photoshop',
			'kindXBITMAP'     : 'Image X bitmap',
			'kindPXM'         : 'Image Pixelmator',
			// media
			'kindAudio'       : 'Son',
			'kindAudioMPEG'   : 'Son MPEG',
			'kindAudioMPEG4'  : 'Son MPEG-4',
			'kindAudioMIDI'   : 'Son MIDI',
			'kindAudioOGG'    : 'Son Ogg Vorbis',
			'kindAudioWAV'    : 'Son WAV',
			'AudioPlaylist'   : 'Liste de lecture audio',
			'kindVideo'       : 'Vidéo',
			'kindVideoDV'     : 'Vidéo DV',
			'kindVideoMPEG'   : 'Vidéo MPEG',
			'kindVideoMPEG4'  : 'Vidéo MPEG-4',
			'kindVideoAVI'    : 'Vidéo AVI',
			'kindVideoMOV'    : 'Vidéo Quick Time',
			'kindVideoWM'     : 'Vidéo Windows Media',
			'kindVideoFlash'  : 'Vidéo Flash',
			'kindVideoMKV'    : 'Vidéo Matroska',
			'kindVideoOGG'    : 'Vidéo Ogg'
		}
	}
}
;/**
 * Hungarian translation
 * @author Gáspár Lajos <info@glsys.eu>
 * @version 2011-09-10
 */
if (elFinder && elFinder.prototype && typeof(elFinder.prototype.i18) == 'object') {
	elFinder.prototype.i18.hu = {
		translator : 'Gáspár Lajos &lt;info@glsys.eu&gt;',
		language   : 'magyar',
		direction  : 'ltr',
		messages   : {
			
			/********************************** errors **********************************/
			'error'                : 'Hiba',
			'errUnknown'           : 'Ismeretlen hiba.',
			'errUnknownCmd'        : 'Ismeretlen parancs.',
			'errJqui'              : 'Hibás jQuery UI konfiguráció. A "selectable", "draggable" és a "droppable" komponensek szükségesek.',
			'errNode'              : 'elFinder requires DOM Element to be created.',
			'errURL'               : 'Hibás elFinder konfiguráció! "URL" paraméter nincs megadva.',
			'errAccess'            : 'Hozzáférés megtagadva.',
			'errConnect'           : 'Nem sikerült csatlakozni a kiszolgálóhoz.',
			'errAbort'             : 'Kapcsolat megszakítva.',
			'errTimeout'           : 'Kapcsolat időtúllépés.',
			'errNotFound'          : 'A backend nem elérhető.',
			'errResponse'          : 'Hibás backend válasz.',
			'errConf'              : 'Invalid backend configuration.',
			'errJSON'              : 'PHP JSON module not installed.',
			'errNoVolumes'         : 'Readable volumes not available.',
			'errCmdParams'         : 'Invalid parameters for command "$1".',
			'errDataNotJSON'       : 'A válasz nem JSON típusú adat.',
			'errDataEmpty'         : 'Nem érkezett adat.',
			'errCmdReq'            : 'Backend request requires command name.',
			'errOpen'              : '"$1" megnyitása nem sikerült.',
			'errNotFolder'         : 'Object is not a folder.',
			'errNotFile'           : 'Object is not a file.',
			'errRead'              : 'Unable to read "$1".',
			'errWrite'             : 'Unable to write into "$1".',
			'errPerm'              : 'Engedély megtagadva.',
			'errLocked'            : '"$1" is locked and can not be renamed, moved or removed.',
			'errExists'            : 'File named "$1" already exists.',
			'errInvName'           : 'Invalid file name.',
			'errFolderNotFound'    : 'Folder not found.',
			'errFileNotFound'      : 'File not found.',
			'errTrgFolderNotFound' : 'Target folder "$1" not found.',
			'errPopup'             : 'Browser prevented opening popup window. To open file enable it in browser options.',
			'errMkdir'             : 'Unable to create folder "$1".',
			'errMkfile'            : 'Unable to create file "$1".',
			'errRename'            : 'Unable to rename "$1".',
			'errCopyFrom'          : 'Copying files from volume "$1" not allowed.',
			'errCopyTo'            : 'Copying files to volume "$1" not allowed.',
			'errUploadCommon'      : 'Feltöltési hiba.',
			'errUpload'            : 'Nem sikerült a fájlt feltölteni. ($1)',
			'errUploadNoFiles'     : 'No files found for upload.',
			'errMaxSize'           : 'Data exceeds the maximum allowed size.',
			'errFileMaxSize'       : 'File exceeds maximum allowed size.',
			'errUploadMime'        : 'File type not allowed.',
			'errUploadTransfer'    : '"$1" transfer error.', 
			'errSave'              : '"$1" mentése nem sikerült.',
			'errCopy'              : '"$1" másolása nem sikerült.',
			'errMove'              : '"$1" áthelyezése nem sikerült.',
			'errCopyInItself'      : '"$1" nem másolható saját magára.',
			'errRm'                : '"$1" törlése nem sikerült.',
			'errExtract'           : 'Unable to extract files from "$1".',
			'errArchive'           : 'Unable to create archive.',
			'errArcType'           : 'Nem támogatott archívum típus.',
			'errNoArchive'         : 'File is not archive or has unsupported archive type.',
			'errCmdNoSupport'      : 'Backend does not support this command.',
			
			/******************************* commands names ********************************/
			'cmdarchive'   : 'Archívum létrehozása',
			'cmdback'      : 'Vissza',
			'cmdcopy'      : 'Másolás',
			'cmdcut'       : 'Kivágás',
			'cmddownload'  : 'Letöltés',
			'cmdduplicate' : 'Másolat készítés',
			'cmdedit'      : 'Szerkesztés',
			'cmdextract'   : 'Kibontás',
			'cmdforward'   : 'Előre',
			'cmdgetfile'   : 'Fájlok kijelölése',
			'cmdhelp'      : 'Erről a programról...',
			'cmdhome'      : 'Főkönyvtár',
			'cmdinfo'      : 'Tulajdonságok',
			'cmdmkdir'     : 'Új mappa',
			'cmdmkfile'    : 'Új szöveges dokumentum',
			'cmdopen'      : 'Megnyitás',
			'cmdpaste'     : 'Beillesztés',
			'cmdquicklook' : 'Előnézet',
			'cmdreload'    : 'Frissítés',
			'cmdrename'    : 'Átnevezés',
			'cmdrm'        : 'Törlés',
			'cmdsearch'    : 'Keresés',
			'cmdup'        : 'Ugrás a szülőmappába',
			'cmdupload'    : 'Feltöltés',
			'cmdview'      : 'View',
			
			/*********************************** buttons ***********************************/ 
			'btnClose'  : 'Bezár',
			'btnSave'   : 'Ment',
			'btnRm'     : 'Töröl',
			'btnCancel' : 'Mégsem',
			'btnNo'     : 'Nem',
			'btnYes'    : 'Igen',
			
			/******************************** notifications ********************************/
			'ntfopen'     : 'Mappa megnyitás',
			'ntffile'     : 'Fájl megnyitás',
			'ntfreload'   : 'Reload folder content',
			'ntfmkdir'    : 'Mappa létrehozása',
			'ntfmkfile'   : 'Creating files',
			'ntfrm'       : 'Fájlok törélse',
			'ntfcopy'     : 'Fájlok másolása',
			'ntfmove'     : 'Fájlok áthelyezése',
			'ntfprepare'  : 'Prepare to copy files',
			'ntfrename'   : 'Fájlok átnevezése',
			'ntfupload'   : 'Fájlok feltöltése',
			'ntfdownload' : 'Fájlok letöltése',
			'ntfsave'     : 'Fájlok mentése',
			'ntfarchive'  : 'Archívum létrehozása',
			'ntfextract'  : 'Kibontás archívumból',
			'ntfsearch'   : 'Fájlok keresése',
			'ntfsmth'     : 'Doing something >_<',
			
			/************************************ dates **********************************/
			'dateUnknown' : 'Ismeretlen',
			'Today'       : 'Ma',
			'Yesterday'   : 'Tegnap',
			'Jan'         : 'jan',
			'Feb'         : 'febr',
			'Mar'         : 'márc',
			'Apr'         : 'ápr',
			'May'         : 'máj',
			'Jun'         : 'jún',
			'Jul'         : 'júl',
			'Aug'         : 'aug',
			'Sep'         : 'szept',
			'Oct'         : 'okt',
			'Nov'         : 'nov',
			'Dec'         : 'dec',

			/********************************** messages **********************************/
			'confirmReq'      : 'Confirmation required',
			'confirmRm'       : 'Valóban törölni akarja a kijelölt adatokat?<br/>Ez később nem fordítható vissza!',
			'confirmRepl'     : 'Replace old file with new one?',
			'apllyAll'        : 'Apply to all',
			'name'            : 'Név',
			'size'            : 'Méret',
			'perms'           : 'Jogok',
			'modify'          : 'Módosítva',
			'kind'            : 'Típus',
			'read'            : 'olvasás',
			'write'           : 'írás',
			'noaccess'        : '-',
			'and'             : 'és',
			'unknown'         : 'ismeretlen',
			'selectall'       : 'Összes kijelölése',
			'selectfiles'     : 'Fájlok kijelölése',
			'selectffile'     : 'Első fájl kijelölése',
			'selectlfile'     : 'Utolsó fájl kijelölése',
			'viewlist'        : 'Lista nézet',
			'viewicons'       : 'Ikon nézet',
			'places'          : 'Helyek',
			'calc'            : 'Calculate',
			'path'            : 'Útvonal',
			'aliasfor'        : 'Cél',
			'locked'          : 'Zárolt',
			'dim'             : 'Méretek',
			'files'           : 'Fájlok',
			'folders'         : 'Mappák',
			'items'           : 'Elemek',
			'yes'             : 'igen',
			'no'              : 'nem',
			'link'            : 'Parancsikon',
			'searcresult'     : 'Keresés eredménye',
			'selected'        : 'kijelölt elemek',
			'about'           : 'Névjegy',
			'shortcuts'       : 'Gyorsbillenytyűk',
			'help'            : 'Súgó',
			'webfm'           : 'Web file manager',
			'ver'             : 'Verzió',
			'protocolver'     : 'protokol verzió',
			'homepage'        : 'Projekt honlap',
			'docs'            : 'Dokumentáció',
			'github'          : 'Hozz létre egy új verziót a Github-on',
			'twitter'         : 'Kövess minket a twitter-en',
			'facebook'        : 'Csatlakozz hozzánk a facebook-on',
			'team'            : 'Csapat',
			'chiefdev'        : 'vezető fejlesztő',
			'developer'       : 'fejlesztő',
			'contributor'     : 'külsős hozzájáruló',
			'maintainer'      : 'karbantartó',
			'translator'      : 'fordító',
			'icons'           : 'Ikonok',
			'dontforget'      : 'törölközőt ne felejts el hozni!',
			'shortcutsof'     : 'Shortcuts disabled',
			'dropFiles'       : 'Fájlok dobása ide',
			'or'              : 'vagy',
			'selectForUpload' : 'fájlok böngészése',
			'moveFiles'       : 'Fájlok áthelyezése',
			'copyFiles'       : 'Fájlok másolása',
			
			/********************************** mimetypes **********************************/
			'kindUnknown'     : 'Ismeretlen',
			'kindFolder'      : 'Mappa',
			'kindAlias'       : 'Parancsikon',
			'kindAliasBroken' : 'Hibás parancsikon',
			// applications
			'kindApp'         : 'Alkalmazás',
			'kindPostscript'  : 'Postscript dokumentum',
			'kindMsOffice'    : 'Microsoft Office dokumentum',
			'kindMsWord'      : 'Microsoft Word dokumentum',
			'kindMsExcel'     : 'Microsoft Excel dokumentum',
			'kindMsPP'        : 'Microsoft Powerpoint bemutató',
			'kindOO'          : 'Open Office dokumentum',
			'kindAppFlash'    : 'Flash alkalmazás',
			'kindPDF'         : 'Portable Document Format (PDF)',
			'kindTorrent'     : 'Bittorrent fájl',
			'kind7z'          : '7z archívum',
			'kindTAR'         : 'TAR archívum',
			'kindGZIP'        : 'GZIP archívum',
			'kindBZIP'        : 'BZIP archívum',
			'kindZIP'         : 'ZIP archívum',
			'kindRAR'         : 'RAR archívum',
			'kindJAR'         : 'Java JAR fájl',
			'kindTTF'         : 'True Type font',
			'kindOTF'         : 'Open Type font',
			'kindRPM'         : 'RPM csomag',
			// texts
			'kindText'        : 'Szöveges dokumentum',
			'kindTextPlain'   : 'Plain text',
			'kindPHP'         : 'PHP forráskód',
			'kindCSS'         : 'Cascading style sheet',
			'kindHTML'        : 'HTML dokumentum',
			'kindJS'          : 'Javascript forráskód',
			'kindRTF'         : 'Rich Text Format',
			'kindC'           : 'C forráskód',
			'kindCHeader'     : 'C header forráskód',
			'kindCPP'         : 'C++ forráskód',
			'kindCPPHeader'   : 'C++ header forráskód',
			'kindShell'       : 'Unix shell script',
			'kindPython'      : 'Python forráskód',
			'kindJava'        : 'Java forráskód',
			'kindRuby'        : 'Ruby forráskód',
			'kindPerl'        : 'Perl script',
			'kindSQL'         : 'SQL forráskód',
			'kindXML'         : 'XML dokumentum',
			'kindAWK'         : 'AWK forráskód',
			'kindCSV'         : 'Comma separated values',
			'kindDOCBOOK'     : 'Docbook XML dokumentum',
			// images
			'kindImage'       : 'Kép',
			'kindBMP'         : 'BMP kép',
			'kindJPEG'        : 'JPEG kép',
			'kindGIF'         : 'GIF kép',
			'kindPNG'         : 'PNG kép',
			'kindTIFF'        : 'TIFF kép',
			'kindTGA'         : 'TGA kép',
			'kindPSD'         : 'Adobe Photoshop kép',
			'kindXBITMAP'     : 'X bitmap image',
			'kindPXM'         : 'Pixelmator image',
			// media
			'kindAudio'       : 'Hangfájl',
			'kindAudioMPEG'   : 'MPEG hangfájl',
			'kindAudioMPEG4'  : 'MPEG-4 hangfájl',
			'kindAudioMIDI'   : 'MIDI hangfájl',
			'kindAudioOGG'    : 'Ogg Vorbis hangfájl',
			'kindAudioWAV'    : 'WAV hangfájl',
			'AudioPlaylist'   : 'MP3 playlist',
			'kindVideo'       : 'Film',
			'kindVideoDV'     : 'DV film',
			'kindVideoMPEG'   : 'MPEG film',
			'kindVideoMPEG4'  : 'MPEG-4 film',
			'kindVideoAVI'    : 'AVI film',
			'kindVideoMOV'    : 'Quick Time film',
			'kindVideoWM'     : 'Windows Media film',
			'kindVideoFlash'  : 'Flash film',
			'kindVideoMKV'    : 'Matroska film',
			'kindVideoOGG'    : 'Ogg film'
		}
	}
}
;/**
 * Italian translation
 * @author Alberto Tocci <alberto.tocci@gmail.com>
 * @version 2012-05-09
 */
if (elFinder && elFinder.prototype && typeof(elFinder.prototype.i18) == 'object') {
	elFinder.prototype.i18.it = {
		translator : 'Alberto Tocci (alberto.tocci@gmail.com)',
		language   : 'Italiano',
		direction  : 'ltr',
		dateFormat : 'd.m.Y H:i',
		fancyDateFormat : '$1 H:i',
		messages   : {

			/********************************** errors **********************************/
			'error'                : 'Errore',
			'errUnknown'           : 'Errore sconosciuto.',
			'errUnknownCmd'        : 'Comando sconosciuto.',
			'errJqui'              : 'Configurazione JQuery UI non valida. Devono essere inclusi i plugin Selectable, Draggable e Droppable.',
			'errNode'              : 'elFinder necessita dell\'elemento DOM per essere inizializzato.',
			'errURL'               : 'Configurazione non valida.Il parametro URL non è settato.',
			'errAccess'            : 'Accesso non consentito.',
			'errConnect'           : 'Impossibile collegarsi al backend.',
			'errAbort'             : 'Connessione terminata.',
			'errTimeout'           : 'Timeout di connessione.',
			'errNotFound'          : 'Backend non trovato.',
			'errResponse'          : 'Risposta non valida dal backend.',
			'errConf'              : 'Configurazione backend non valida.',
			'errJSON'              : 'Modulo PHP JSON non installato.',
			'errNoVolumes'         : 'Non è stato possibile leggere i volumi.',
			'errCmdParams'         : 'Parametri non validi per il comando "$1".',
			'errDataNotJSON'       : 'I dati non sono nel formato JSON.',
			'errDataEmpty'         : 'Stringa vuota.',
			'errCmdReq'            : 'Backend request requires command name.',
			'errOpen'              : 'Impossibile aprire "$1".',
			'errNotFolder'         : 'L\'oggetto non è una cartella..',
			'errNotFile'           : 'L\'oggetto non è un file.',
			'errRead'              : 'Impossibile leggere "$1".',
			'errWrite'             : 'Non è possibile scrivere in "$1".',
			'errPerm'              : 'Permesso negato.',
			'errLocked'            : '"$1" è bloccato e non può essere rinominato, spostato o eliminato.',
			'errExists'            : 'Il file "$1" è già esistente.',
			'errInvName'           : 'Nome file non valido.',
			'errFolderNotFound'    : 'Cartella non trovata.',
			'errFileNotFound'      : 'File non trovato.',
			'errTrgFolderNotFound' : 'La cartella di destinazione"$1" non è stata trovata.',
			'errPopup'             : 'Il tuo Browser non consente di aprire finestre di pop-up. Per aprire il file abilita questa opzione nelle impostazioni del tuo Browser.',
			'errMkdir'             : 'Impossibile creare la cartella "$1".',
			'errMkfile'            : 'Impossibile creare il file "$1".',
			'errRename'            : 'Impossibile rinominare "$1".',
			'errCopyFrom'          : 'Non è possibile copiare file da "$1".',
			'errCopyTo'            : 'Non è possibile copiare file in "$1".',
			'errUploadCommon'      : 'Errore di Caricamento.',
			'errUpload'            : 'Impossibile Caricare "$1".',
			'errUploadNoFiles'     : 'Non sono stati specificati file da caricare.',
			'errMaxSize'           : 'La dimensione totale dei file supera il limite massimo consentito.',
			'errFileMaxSize'       : 'Le dimensioni del file superano il massimo consentito.',
			'errUploadMime'        : 'FileType non consentito.',
			'errUploadTransfer'    : 'Trasferimento errato del file "$1".', 
			'errSave'              : 'Impossibile salvare "$1".',
			'errCopy'              : 'Impossibile copiare "$1".',
			'errMove'              : 'Impossibile spostare "$1".',
			'errCopyInItself'      : 'Sorgente e destinazione risultato essere uguali.',
			'errRm'                : 'Impossibile rimuovere "$1".',
			'errExtract'           : 'Impossibile estrarre file da "$1".',
			'errArchive'           : 'Impossibile creare archivio.',
			'errArcType'           : 'Tipo di archivio non supportato.',
			'errNoArchive'         : 'Il file non è un archivio o contiene file non supportati.',
			'errCmdNoSupport'      : 'Il Backend non supporta questo comando.',
			'errReplByChild'       : 'La cartella $1 non può essere sostituita da un oggetto in essa contenuto.',
			'errArcSymlinks'       : 'Per questioni di sicurezza non è possibile estrarre archivi che contengono collegamenti..',
			'errArcMaxSize'        : 'La dimensione dell\'archivio supera le massime dimensioni consentite.',
			'errResize'            : 'Impossibile ridimensionare "$1".',
			'errUsupportType'      : 'FileType non supportato.',

			/******************************* commands names ********************************/
			'cmdarchive'   : 'Crea Archivio',
			'cmdback'      : 'Indietro',
			'cmdcopy'      : 'Copia',
			'cmdcut'       : 'Taglia',
			'cmddownload'  : 'Download',
			'cmdduplicate' : 'Duplica',
			'cmdedit'      : 'Modifica File',
			'cmdextract'   : 'Estrai Archivio',
			'cmdforward'   : 'Avanti',
			'cmdgetfile'   : 'Seleziona File',
			'cmdhelp'      : 'About',
			'cmdhome'      : 'Home',
			'cmdinfo'      : 'Informazioni',
			'cmdmkdir'     : 'Nuova cartella',
			'cmdmkfile'    : 'Nuovo file di testo',
			'cmdopen'      : 'Apri',
			'cmdpaste'     : 'Incolla',
			'cmdquicklook' : 'Anteprima',
			'cmdreload'    : 'Ricarica',
			'cmdrename'    : 'Rinomina',
			'cmdrm'        : 'Cancella',
			'cmdsearch'    : 'Ricerca file',
			'cmdup'        : 'Vai alla directory padre',
			'cmdupload'    : 'Carica File',
			'cmdview'      : 'Visualizza',
			'cmdresize'    : 'Ridimensiona Immagine',
			'cmdsort'      : 'Ordina',

			/*********************************** buttons ***********************************/ 
			'btnClose'  : 'Chiudi',
			'btnSave'   : 'Salva',
			'btnRm'     : 'Rimuovi',
			'btnApply'  : 'Applica',
			'btnCancel' : 'Cancella',
			'btnNo'     : 'No',
			'btnYes'    : 'Si',

			/******************************** notifications ********************************/
			'ntfopen'     : 'Apri cartella',
			'ntffile'     : 'Apri file',
			'ntfreload'   : 'Ricarica il contenuto della cartella',
			'ntfmkdir'    : 'Creazione delle directory in corso',
			'ntfmkfile'   : 'Creazione dei files in corso',
			'ntfrm'       : 'Cancellazione files in corso',
			'ntfcopy'     : 'Copia file in corso',
			'ntfmove'     : 'Spostamento file in corso',
			'ntfprepare'  : 'Inizializzando la copia dei file.',
			'ntfrename'   : 'Sto rinominando i file',
			'ntfupload'   : 'Caricamento file in corso',
			'ntfdownload' : 'Downloading file in corso',
			'ntfsave'     : 'Salvataggio file in corso',
			'ntfarchive'  : 'Creazione archivio in corso',
			'ntfextract'  : 'Estrazione file dall\'archivio in corso',
			'ntfsearch'   : 'Ricerca files in corso',
			'ntfsmth'     : 'Operazione in corso. Attendere...',
			'ntfloadimg'  : 'Caricamento immagine in corso',

			/************************************ dates **********************************/
			'dateUnknown' : 'sconosciuto',
			'Today'       : 'Oggi',
			'Yesterday'   : 'Ieri',
			'Jan'         : 'Gen',
			'Feb'         : 'Feb',
			'Mar'         : 'Mar',
			'Apr'         : 'Apr',
			'May'         : 'Mag',
			'Jun'         : 'Giu',
			'Jul'         : 'Lug',
			'Aug'         : 'Ago',
			'Sep'         : 'Set',
			'Oct'         : 'Ott',
			'Nov'         : 'Nov',
			'Dec'         : 'Dic',
			'January'     : 'Gennaio',
			'February'    : 'Febbraio',
			'March'       : 'Marzo',
			'April'       : 'Aprile',
			'May'         : 'Maggio',
			'June'        : 'Giugno',
			'July'        : 'Luglio',
			'August'      : 'Agosto',
			'September'   : 'Settembre',
			'October'     : 'Ottobre',
			'November'    : 'Novembre',
			'December'    : 'Dicembre',
			'Sunday'      : 'Domenica', 
			'Monday'      : 'Lunedì', 
			'Tuesday'     : 'Martedì', 
			'Wednesday'   : 'Mercoledì', 
			'Thursday'    : 'Giovedì', 
			'Friday'      : 'Venerdì', 
			'Saturday'    : 'Sabato',
			'Sun'         : 'Dom', 
			'Mon'         : 'Lun', 
			'Tue'         : 'Mar', 
			'Wed'         : 'Mer', 
			'Thu'         : 'Gio', 
			'Fri'         : 'Ven', 
			'Sat'         : 'Sab',
			/******************************** sort variants ********************************/
			'sortnameDirsFirst' : 'per nome (cartelle in testa)', 
			'sortkindDirsFirst' : 'per tipo (cartelle in testa)', 
			'sortsizeDirsFirst' : 'per dimensione (cartelle in testa)', 
			'sortdateDirsFirst' : 'per data (cartelle in testa)', 
			'sortname'          : 'per nome', 
			'sortkind'          : 'per tipo', 
			'sortsize'          : 'per dimensione',
			'sortdate'          : 'per data',

			/********************************** messages **********************************/
			'confirmReq'      : 'Conferma richiesta',
			'confirmRm'       : 'Sei sicuro di voler rimuovere i file?<br />L\'operazione non è reversibile!',
			'confirmRepl'     : 'Sostituire i file ?',
			'apllyAll'        : 'Applica a tutti',
			'name'            : 'Nome',
			'size'            : 'Dimensione',
			'perms'           : 'Permessi',
			'modify'          : 'Modificato il',
			'kind'            : 'Tipo',
			'read'            : 'lettura',
			'write'           : 'scrittura',
			'noaccess'        : 'nessun accesso',
			'and'             : 'e',
			'unknown'         : 'sconosciuto',
			'selectall'       : 'Seleziona tutti i file',
			'selectfiles'     : 'Seleziona file',
			'selectffile'     : 'Seleziona il primo file',
			'selectlfile'     : 'Seleziona l\'ultimo file',
			'viewlist'        : 'Visualizza Elenco',
			'viewicons'       : 'Visualizza Icone',
			'places'          : 'Places',
			'calc'            : 'Calcola', 
			'path'            : 'Percorso',
			'aliasfor'        : 'Alias per',
			'locked'          : 'Bloccato',
			'dim'             : 'Dimensioni',
			'files'           : 'File',
			'folders'         : 'Cartelle',
			'items'           : 'Oggetti',
			'yes'             : 'si',
			'no'              : 'no',
			'link'            : 'Collegamento',
			'searcresult'     : 'Risultati ricerca',  
			'selected'        : 'oggetti selezionati',
			'about'           : 'About',
			'shortcuts'       : 'Scorciatoie',
			'help'            : 'Help',
			'webfm'           : 'Web file manager',
			'ver'             : 'Versione',
			'protocol'        : 'versione protocollo',
			'homepage'        : 'Home del progetto',
			'docs'            : 'Documentazione',
			'github'          : 'Seguici su Github',
			'twitter'         : 'Seguici su Twitter',
			'facebook'        : 'Seguici su Facebook',
			'team'            : 'Team',
			'chiefdev'        : 'sviluppatore capo',
			'developer'       : 'sviluppatore',
			'contributor'     : 'collaboratore',
			'maintainer'      : 'maintainer',
			'translator'      : 'traduttore',
			'icons'           : 'Icone',
			'dontforget'      : 'e non dimenticate di portare l\'asciugamano',
			'shortcutsof'     : 'Scorciatoie disabilitate',
			'dropFiles'       : 'Trascina i file qui',
			'or'              : 'o',
			'selectForUpload' : 'Seleziona file da caricare',
			'moveFiles'       : 'Sposta file',
			'copyFiles'       : 'Copia file',
			'rmFromPlaces'    : 'Rimuovi da places',
			'untitled folder' : 'cartella senza titolo',
			'untitled file.txt' : 'file senza titolo.txt',
			'aspectRatio'     : 'Proporzioni',
			'scale'           : 'Scala',
			'width'           : 'Larghezza',
			'height'          : 'Altezza',
			'mode'            : 'Modalità',
			'resize'          : 'Ridimensione',
			'crop'            : 'Ritaglia',
			'rotate'          : 'Ruota',
			'rotate-cw'       : 'Ruota di 90° in senso orario',
			'rotate-ccw'      : 'Ruota di 90° in senso antiorario',
			'degree'          : 'Gradi',

			/********************************** mimetypes **********************************/
			'kindUnknown'     : 'Sconosciuto',
			'kindFolder'      : 'Cartella',
			'kindAlias'       : 'Alias',
			'kindAliasBroken' : 'Alias guasto',
			// applications
			'kindApp'         : 'Applicazione',
			'kindPostscript'  : 'Documento Postscript',
			'kindMsOffice'    : 'Documento Microsoft Office',
			'kindMsWord'      : 'Documento Microsoft Word',
			'kindMsExcel'     : 'Documento Microsoft Excel',
			'kindMsPP'        : 'Presentazione Microsoft Powerpoint',
			'kindOO'          : 'Documento Open Office',
			'kindAppFlash'    : 'Applicazione Flash',
			'kindPDF'         : 'Documento PDF',
			'kindTorrent'     : 'File Bittorrent',
			'kind7z'          : 'Archivio 7z',
			'kindTAR'         : 'Archivio TAR',
			'kindGZIP'        : 'Archivio GZIP',
			'kindBZIP'        : 'Archivio BZIP',
			'kindZIP'         : 'Archivio ZIP',
			'kindRAR'         : 'Archivio RAR',
			'kindJAR'         : 'File Java JAR',
			'kindTTF'         : 'Font True Type',
			'kindOTF'         : 'Font Open Type',
			'kindRPM'         : 'Pacchetto RPM',
			// texts
			'kindText'        : 'Documento di testo',
			'kindTextPlain'   : 'Testo Semplice',
			'kindPHP'         : 'File PHP',
			'kindCSS'         : 'File CSS (Cascading Style Sheet)',
			'kindHTML'        : 'Documento HTML',
			'kindJS'          : 'File Javascript',
			'kindRTF'         : 'File RTF (Rich Text Format)',
			'kindC'           : 'File C',
			'kindCHeader'     : 'File C (header)',
			'kindCPP'         : 'File C++',
			'kindCPPHeader'   : 'File C++ (header)',
			'kindShell'       : 'Script Unix shell',
			'kindPython'      : 'File Python',
			'kindJava'        : 'File Java',
			'kindRuby'        : 'File Ruby',
			'kindPerl'        : 'File Perl',
			'kindSQL'         : 'File SQL',
			'kindXML'         : 'File XML',
			'kindAWK'         : 'File AWK',
			'kindCSV'         : 'File CSV (Comma separated values)',
			'kindDOCBOOK'     : 'File Docbook XML',
			// images
			'kindImage'       : 'Immagine',
			'kindBMP'         : 'Immagine BMP',
			'kindJPEG'        : 'Immagine JPEG',
			'kindGIF'         : 'Immagine GIF',
			'kindPNG'         : 'Immagine PNG',
			'kindTIFF'        : 'Immagine TIFF',
			'kindTGA'         : 'Immagine TGA',
			'kindPSD'         : 'Immagine Adobe Photoshop',
			'kindXBITMAP'     : 'Immagine X bitmap',
			'kindPXM'         : 'Immagine Pixelmator',
			// media
			'kindAudio'       : 'File Audio',
			'kindAudioMPEG'   : 'Audio MPEG',
			'kindAudioMPEG4'  : 'Audio MPEG-4',
			'kindAudioMIDI'   : 'Audio MIDI',
			'kindAudioOGG'    : 'Audio Ogg Vorbis',
			'kindAudioWAV'    : 'Audio WAV',
			'AudioPlaylist'   : 'Playlist MP3',
			'kindVideo'       : 'File Video',
			'kindVideoDV'     : 'Filmato DV',
			'kindVideoMPEG'   : 'Filmato MPEG',
			'kindVideoMPEG4'  : 'Filmato MPEG-4',
			'kindVideoAVI'    : 'Filmato AVI',
			'kindVideoMOV'    : 'Filmato Quick Time',
			'kindVideoWM'     : 'Filmato Windows Media',
			'kindVideoFlash'  : 'Filmato Flash',
			'kindVideoMKV'    : 'Filmato Matroska',
			'kindVideoOGG'    : 'Filmato Ogg'
		}
	}
};/**
 * Japanese translation
 * @author Tomoaki Yoshida <info@yoshida-studio.jp>
 * @author Naoki Sawada <hypweb@gmail.com>
 * @version 2013-07-03
 */
if (elFinder && elFinder.prototype && typeof(elFinder.prototype.i18) == 'object') {
	elFinder.prototype.i18.jp = {
		translator : 'Tomoaki Yoshida &lt;info@yoshida-studio.jp&gt;, Naoki Sawada &lt;hypweb@gmail.com&gt;',
		language   : 'Japanese',
		direction  : 'ltr',
		dateFormat : 'Y/m/d h:i A', // 2012/04/11 05:27 PM
		fancyDateFormat : '$1 h:i A', // will produce smth like: 今日 12:25 PM
		messages   : {
			
			/********************************** errors **********************************/
			'error'                : 'エラー',
			'errUnknown'           : '不明なエラーです',
			'errUnknownCmd'        : '不明なコマンドです',
			'errJqui'              : '無効なjQuery UI コンフィグレーションです。セレクタブルコンポーネント、ドラッガブルコンポーネント、ドロッパブルコンポーネントがあるかを確認して下さい',
			'errNode'              : 'elFinderはDOM Elementが必要です',
			'errURL'               : '無効なelFinder コンフィグレーションです! URLを設定してください',
			'errAccess'            : 'アクセスが拒否されました',
			'errConnect'           : 'バックエンドとの接続ができません',
			'errAbort'             : '接続が中断されました',
			'errTimeout'           : '接続がタイムアウトしました.',
			'errNotFound'          : 'バックエンドが見つかりません',
			'errResponse'          : '無効なバックエンドコンフィグレーションです',
			'errConf'              : '無効なバックエンドコンフィグレーションです',
			'errJSON'              : 'PHP JSON モジュールがインストールされていません',
			'errNoVolumes'         : '読み込み可能なボリュームが入手できません',
			'errCmdParams'         : 'コマンド "$1"のパラメーターが無効です',
			'errDataNotJSON'       : 'JSONデータではありません',
			'errDataEmpty'         : '空のデータです',
			'errCmdReq'            : 'バックエンドリクエストがコマンド名を要求しています',
			'errOpen'              : '"$1"を開くことができません',
			'errNotFolder'         : 'オブジェクトがフォルダーではありません',
			'errNotFile'           : 'オブジェクトがファイルではありません',
			'errRead'              : '"$1"を読むことができません',
			'errWrite'             : '"$1"に書きこむことができません',
			'errPerm'              : '権限がありません',
			'errLocked'            : '"$1" はロックされているので名前の変更、移動、削除ができません',
			'errExists'            : '"$1"というファイル名はすでに存在しています',
			'errInvName'           : '無効なファイル名です',
			'errFolderNotFound'    : 'フォルダーが見つかりません',
			'errFileNotFound'      : 'ファイルが見つかりません',
			'errTrgFolderNotFound' : 'ターゲットとするフォルダー "$1" が見つかりません',
			'errPopup'             : 'ポップアップウィンドウが開けません。ファイルを開くにはブラウザの設定を変更してください',
			'errMkdir'             : '"$1"フォルダーを作成することができません',
			'errMkfile'            : '"$1"ファイルを作成することができません',
			'errRename'            : '"$1"の名前を変更することができません',
			'errCopyFrom'          : '"$1"からのファイルコピーが許可されていません',
			'errCopyTo'            : '"$1"へのファイルコピーが許可されていません',
			'errUpload'            : 'アップロードエラー',  // old name - errUploadCommon
			'errUploadFile'        : '"$1"がアップロードできません', // old name - errUpload
			'errUploadNoFiles'     : 'アップロードされたファイルがありません',
			'errUploadTotalSize'   : 'データが許容サイズを超えています', // old name - errMaxSize
			'errUploadFileSize'    : 'ファイルが許容サイズを超えています', //  old name - errFileMaxSize
			'errUploadMime'        : '許可されていないファイル形式です',
			'errUploadTransfer'    : '"$1" 転送エラーです', 
			'errNotReplace'        : 'アイテム "$1" は、すでにこの場所にありますがアイテムのタイプが違うので置き換えることはできません', // new
			'errReplace'           : '"$1"を置き換えることができません',
			'errSave'              : '"$1"を保存することができません',
			'errCopy'              : '"$1"をコピーすることができません',
			'errMove'              : '"$1"を移動することができません',
			'errCopyInItself'      : '"$1"をそれ自身の中にコピーすることはできません',
			'errRm'                : '"$1"を削除することができません',
			'errRmSrc'             : 'Unable remove source file(s).',
			'errExtract'           : '"$1"を解凍することができません',
			'errArchive'           : 'アーカイブを作成することができません',
			'errArcType'           : 'サポート外のアーカイブ形式です',
			'errNoArchive'         : 'アーカイブでないかサポートされていないアーカイブ形式です',
			'errCmdNoSupport'      : 'サポートされていないコマンドです',
			'errReplByChild'       : 'フォルダ "$1" に含まれてるアイテムを置き換えることはできません',
			'errArcSymlinks'       : 'シンボリックリンクまたは許容されないファイル名を含むアーカイブはセキュリティ上、解凍できません', // edited 25.06.2012
			'errArcMaxSize'        : 'アーカイブが許容されたサイズを超えています',
			'errResize'            : '"$1"をリサイズできません',
			'errResizeDegree'      : 'イメージの回転角度が不正です',  // added 7.3.2013
			'errResizeRotate'      : 'イメージの回転ができません',  // added 7.3.2013
			'errResizeSize'        : '指定されたイメージサイズが不正です',  // added 7.3.2013
			'errResizeNoChange'    : 'イメージサイズなどの変更がありません',  // added 7.3.2013
			'errUsupportType'      : 'このファイルタイプはサポートされません',
			'errNotUTF8Content'    : 'ファイル "$1" には UTF-8 以外の文字が含まれているので編集できません',  // added 9.11.2011
			'errNetMount'          : '"$1"をマウントできません', // added 17.04.2012
			'errNetMountNoDriver'  : 'サポートされていないプロトコルです',     // added 17.04.2012
			'errNetMountFailed'    : 'マウントに失敗しました',         // added 17.04.2012
			'errNetMountHostReq'   : 'ホスト名は必須です', // added 18.04.2012
			'errSessionExpires'    : 'アクションがなかったため、セッションが期限切れになりました',
			'errCreatingTempDir'   : '一時ディレクトリを作成できません："$1"',
			'errFtpDownloadFile'   : 'FTP からファイルをダウンロードできません："$1"',
			'errFtpUploadFile'     : 'FTP へファイルをアップロードできません："$1"',
			'errFtpMkdir'          : 'FTP にリモートディレクトリを作成できません："$1"',
			'errArchiveExec'       : 'ファイルのアーカイブ中にエラーが発生しました："$1"',
			'errExtractExec'       : 'ファイルの抽出中にエラーが発生しました："$1"',
			
			/******************************* commands names ********************************/
			'cmdarchive'   : 'アーカイブ作成',
			'cmdback'      : '戻る',
			'cmdcopy'      : 'コピー',
			'cmdcut'       : 'カット',
			'cmddownload'  : 'ダウンロード',
			'cmdduplicate' : '複製',
			'cmdedit'      : 'ファイル編集',
			'cmdextract'   : 'アーカイブを解凍',
			'cmdforward'   : '進む',
			'cmdgetfile'   : 'ファイル選択',
			'cmdhelp'      : 'このソフトウェアについて',
			'cmdhome'      : 'ホーム',
			'cmdinfo'      : '情報',
			'cmdmkdir'     : '新規フォルダー',
			'cmdmkfile'    : '新規テキストファイル',
			'cmdopen'      : '開く',
			'cmdpaste'     : 'ペースト',
			'cmdquicklook' : 'プレビュー',
			'cmdreload'    : 'リロード',
			'cmdrename'    : 'リネーム',
			'cmdrm'        : '削除',
			'cmdsearch'    : 'ファイルを探す',
			'cmdup'        : '親ディレクトリーへ移動',
			'cmdupload'    : 'ファイルアップロード',
			'cmdview'      : 'ビュー',
			'cmdresize'    : 'リサイズと回転',
			'cmdsort'      : 'ソート',
			'cmdnetmount'  : 'ネットワークボリュームをマウント', // added 18.04.2012
			
			/*********************************** buttons ***********************************/ 
			'btnClose'  : '閉じる',
			'btnSave'   : '保存',
			'btnRm'     : '削除',
			'btnApply'  : '適用',
			'btnCancel' : 'キャンセル',
			'btnNo'     : 'いいえ',
			'btnYes'    : 'はい',
			'btnMount'  : 'マウント',  // added 18.04.2012
			/******************************** notifications ********************************/
			'ntfopen'     : 'フォルダーを開いています',
			'ntffile'     : 'ファイルを開いています',
			'ntfreload'   : 'フォルダーを再読込しています',
			'ntfmkdir'    : 'ディレクトリーを作成しています',
			'ntfmkfile'   : 'ファイルを作成しています',
			'ntfrm'       : 'ファイルを削除しています',
			'ntfcopy'     : 'ファイルをコピーしています',
			'ntfmove'     : 'ファイルを移動しています',
			'ntfprepare'  : 'ファイルコピーを準備しています',
			'ntfrename'   : 'ファイル名を変更しています',
			'ntfupload'   : 'ファイルをアップロードしています',
			'ntfdownload' : 'ファイルをダウンロードしています',
			'ntfsave'     : 'ファイルを保存しています',
			'ntfarchive'  : 'アーカイブ作成しています',
			'ntfextract'  : 'アーカイブを解凍しています',
			'ntfsearch'   : 'ファイル検索中',
			'ntfresize'   : 'リサイズしています',
			'ntfsmth'     : '処理をしています',
      		'ntfloadimg'  : 'イメージを読み込んでいます',
      		'ntfnetmount' : 'ネットワークボリュームをマウントしています', // added 18.04.2012
			'ntfdim'      : '画像サイズを取得しています', // added 20.05.2013
			'ntfreaddir'  : 'ホルダ情報を読み取っています', // added 01.07.2013
			
			/************************************ dates **********************************/
			'dateUnknown' : '不明',
			'Today'       : '今日',
			'Yesterday'   : '昨日',
			'Jan'         : '1月',
			'Feb'         : '2月',
			'Mar'         : '3月',
			'Apr'         : '4月',
			'May'         : '5月',
			'Jun'         : '6月',
			'Jul'         : '7月',
			'Aug'         : '8月',
			'Sep'         : '9月',
			'Oct'         : '10月',
			'Nov'         : '11月',
			'Dec'         : '12月',

			/******************************** sort variants ********************************/
			'sortname'          : '名前順', 
			'sortkind'          : '種類順', 
			'sortsize'          : 'サイズ順',
			'sortdate'          : '日付順',
			'sortFoldersFirst'  : 'フォルダ優先', // added 21.06.2012

			/********************************** messages **********************************/
			'confirmReq'      : '処理を実行しますか？',
			'confirmRm'       : '本当にファイルを削除しますか?<br/>この操作は取り消せません！',
			'confirmRepl'     : '古いファイルを新しいファイルで上書きしますか？',
			'apllyAll'        : '全てに適用します',
			'name'            : '名前',
			'size'            : 'サイズ',
			'perms'           : '権限',
			'modify'          : '更新',
			'kind'            : '種類',
			'read'            : '読み取り',
			'write'           : '書き込み',
			'noaccess'        : 'アクセス禁止',
			'and'             : ',',
			'unknown'         : '不明',
			'selectall'       : '全てのファイルを選択',
			'selectfiles'     : 'ファイル選択',
			'selectffile'     : '最初のファイルを選択',
			'selectlfile'     : '最後のファイルを選択',
			'viewlist'        : 'リスト形式で見る',
			'viewicons'       : 'アイコン形式で見る',
			'places'          : 'Places',
			'calc'            : '計算', 
			'path'            : 'パス',
			'aliasfor'        : 'エイリアス',
			'locked'          : 'ロックされています',
			'dim'             : 'サイズ',
			'files'           : 'ファイル',
			'folders'         : 'フォルダー',
			'items'           : 'アイテム',
			'yes'             : 'はい',
			'no'              : 'いいえ',
			'link'            : 'リンク',
			'searcresult'     : '検索結果',  
			'selected'        : '選択されたアイテム',
			'about'           : 'アバウト',
			'shortcuts'       : 'ショートカット',
			'help'            : 'ヘルプ',
			'webfm'           : 'ウェブファイルマネージャー',
			'ver'             : 'バージョン',
			'protocolver'     : 'プロトコルバージョン',
			'homepage'        : 'プロジェクトホーム',
			'docs'            : 'ドキュメンテーション',
			'github'          : 'Github でフォーク',
			'twitter'         : 'Twitter でフォロー',
			'facebook'        : 'Facebookグループ に参加',
			'team'            : 'チーム',
			'chiefdev'        : 'チーフデベロッパー',
			'developer'       : 'デベロッパー',
			'contributor'     : 'コントリビュータ',
			'maintainer'      : 'メインテナー',
			'translator'      : '翻訳者',
			'icons'           : 'アイコン',
			'dontforget'      : 'タオル忘れちゃだめよー。',
			'shortcutsof'     : 'ショートカットは利用できません',
			'dropFiles'       : 'ここにファイルをドロップ',
			'dropFilesBrowser': 'ブラウザからファイルをドロップまたは貼り付け', // added 30.05.2012
			'or'              : 'または',
			'selectForUpload' : 'アップロードするファイルを選択',
			'moveFiles'       : 'ファイルを移動',
			'copyFiles'       : 'ファイルをコピー',
			'rmFromPlaces'    : 'ここから削除',
			'aspectRatio'     : '縦横比維持',
			'scale'           : '表示縮尺',
			'width'           : '幅',
			'height'          : '高さ',
			'resize'          : 'リサイズ',
			'crop'            : '切り抜き',
			'rotate'          : '回転',
			'rotate-cw'       : '90度左回転',
			'rotate-ccw'      : '90度右回転',
			'degree'          : '度',
			'netMountDialogTitle' : 'ネットワークボリュームのマウント', // added 18.04.2012
			'protocol'            : 'プロトコル', // added 18.04.2012
			'host'                : 'ホスト名', // added 18.04.2012
			'port'                : 'ポート', // added 18.04.2012
			'user'                : 'ユーザー名', // added 18.04.2012
			'pass'                : 'パスワード', // added 18.04.2012

			/********************************** mimetypes **********************************/
			'kindUnknown'     : '不明',
			'kindFolder'      : 'フォルダー',
			'kindAlias'       : '別名',
			'kindAliasBroken' : '宛先不明の別名',
			// applications
			'kindApp'         : 'アプリケーション',
			'kindPostscript'  : 'Postscript ドキュメント',
			'kindMsOffice'    : 'Microsoft Office ドキュメント',
			'kindMsWord'      : 'Microsoft Word ドキュメント',
			'kindMsExcel'     : 'Microsoft Excel ドキュメント',
			'kindMsPP'        : 'Microsoft Powerpoint プレゼンテーション',
			'kindOO'          : 'Open Office ドキュメント',
			'kindAppFlash'    : 'Flash アプリケーション',
			'kindPDF'         : 'PDF',
			'kindTorrent'     : 'Bittorrent ファイル',
			'kind7z'          : '7z アーカイブ',
			'kindTAR'         : 'TAR アーカイブ',
			'kindGZIP'        : 'GZIP アーカイブ',
			'kindBZIP'        : 'BZIP アーカイブ',
			'kindZIP'         : 'ZIP アーカイブ',
			'kindRAR'         : 'RAR アーカイブ',
			'kindJAR'         : 'Java JAR ファイル',
			'kindTTF'         : 'True Type フォント',
			'kindOTF'         : 'Open Type フォント',
			'kindRPM'         : 'RPM パッケージ',
			// texts
			'kindText'        : 'Text ドキュメント',
			'kindTextPlain'   : 'プレインテキスト',
			'kindPHP'         : 'PHP ソース',
			'kindCSS'         : 'Cascading style sheet',
			'kindHTML'        : 'HTML ドキュメント',
			'kindJS'          : 'Javascript ソース',
			'kindRTF'         : 'Rich Text フォーマット',
			'kindC'           : 'C ソース',
			'kindCHeader'     : 'C ヘッダーソース',
			'kindCPP'         : 'C++ ソース',
			'kindCPPHeader'   : 'C++ ヘッダーソース',
			'kindShell'       : 'Unix shell スクリプト',
			'kindPython'      : 'Python ソース',
			'kindJava'        : 'Java ソース',
			'kindRuby'        : 'Ruby ソース',
			'kindPerl'        : 'Perl スクリプト',
			'kindSQL'         : 'SQL ソース',
			'kindXML'         : 'XML ドキュメント',
			'kindAWK'         : 'AWK ソース',
			'kindCSV'         : 'CSV',
			'kindDOCBOOK'     : 'Docbook XML ドキュメント',
			// images
			'kindImage'       : 'イメージ',
			'kindBMP'         : 'BMP イメージ',
			'kindJPEG'        : 'JPEG イメージ',
			'kindGIF'         : 'GIF イメージ',
			'kindPNG'         : 'PNG イメージ',
			'kindTIFF'        : 'TIFF イメージ',
			'kindTGA'         : 'TGA イメージ',
			'kindPSD'         : 'Adobe Photoshop イメージ',
			'kindXBITMAP'     : 'X bitmap イメージ',
			'kindPXM'         : 'Pixelmator イメージ',
			// media
			'kindAudio'       : 'オーディオメディア',
			'kindAudioMPEG'   : 'MPEG オーディオ',
			'kindAudioMPEG4'  : 'MPEG-4 オーディオ',
			'kindAudioMIDI'   : 'MIDI オーディオ',
			'kindAudioOGG'    : 'Ogg Vorbis オーディオ',
			'kindAudioWAV'    : 'WAV オーディオ',
			'AudioPlaylist'   : 'MP3 プレイリスト',
			'kindVideo'       : 'ビデオメディア',
			'kindVideoDV'     : 'DV ムービー',
			'kindVideoMPEG'   : 'MPEG ムービー',
			'kindVideoMPEG4'  : 'MPEG-4 ムービー',
			'kindVideoAVI'    : 'AVI ムービー',
			'kindVideoMOV'    : 'Quick Time ムービー',
			'kindVideoWM'     : 'Windows Media ムービー',
			'kindVideoFlash'  : 'Flash ムービー',
			'kindVideoMKV'    : 'Matroska ムービー',
			'kindVideoOGG'    : 'Ogg ムービー'
		}
	};
}

;/**
 * Korean translation
 * @author Hwang Ahreum 황아름 <luckmagic@naver.com>
 * @version 2012-06-27
 */
if (elFinder && elFinder.prototype && typeof(elFinder.prototype.i18) == 'object') {
	elFinder.prototype.i18.ko = {
		translator : 'Hwang Ahreum; &lt;luckmagic@naver.com&gt;',
		language   : 'Korea-한국어',
		direction  : 'ltr',
		dateFormat : 'd.m.Y H:i',
		fancyDateFormat : '$1 H:i',
		messages   : {

			/********************************** errors **********************************/
			'error'                : '에러',
			'errUnknown'           : '알 수 없는 에러',
			'errUnknownCmd'        : '알 수 없는 명령어',
			'errJqui'              : 'jQuery UI 환경설정이 올바르지 않습니다. 선택,드래그앤드롭 컴포넌트가 포함되어야합니다',
			'errNode'              : 'elFinder를 생성하기 위해서는 DOM Element를 요구합니다',
			'errURL'               : 'elFinder 환경설정이 올바르지 않습니다! URL 옵션이 설정되지 않았습니다',
			'errAccess'            : '액세스 할 수 없습니다',
			'errConnect'           : 'Backend에 연결할 수 없습니다',
			'errAbort'             : '연결 실패',
			'errTimeout'           : '연결시간 초과',
			'errNotFound'          : 'Backend를 찾을 수 없습니다',
			'errResponse'          : 'Backend가 응답하지 않습니다',
			'errConf'              : 'Backend 환경설정이 올바르지 않습니다',
			'errJSON'              : 'PHP JSON 모듈이 설치되지 않았습니다',
			'errNoVolumes'         : '읽기 가능한 볼률이 없습니다',
			'errCmdParams'         : ' "$1" 명령어는 잘못된 인수입니다',
			'errDataNotJSON'       : '데이터는 JSON이 아닙니다',
			'errDataEmpty'         : '빈 데이터 입니다',
			'errCmdReq'            : 'Backend는 필요한 명령어 이름을 요청합니다',
			'errOpen'              : ' "$1" 열 수 없습니다',
			'errNotFolder'         : '폴더가 아닙니다',
			'errNotFile'           : '파일이 아닙니다',
			'errRead'              : ' "$1" 읽을 수 없습니다',
			'errWrite'             : ' "$1" 쓸 수 없습니다',
			'errPerm'              : '권한이 없습니다',
			'errLocked'            : ' "$1" 잠겨 있습니다, 이동,삭제가 불가능합니다',
			'errExists'            : ' "$1" 존재합니다',
			'errInvName'           : '이름에 올바르지 않은 문자가 포함되었습니다',
			'errFolderNotFound'    : '폴더를 찾을 수 없습니다',
			'errFileNotFound'      : '파일을 찾을 수 없습니다',
			'errTrgFolderNotFound' : ' "$1" 폴더를 찾을 수 없습니다',
			'errPopup'             : '브라우저에서 팝업을 차단하였습니다.팝업을 허용하려면 브라우저 옵션을 변경하세요',
			'errMkdir'             : ' "$1" 폴더를 생성할 수 없습니다',
			'errMkfile'            : ' "$1" 파일을 생성할 수 없습니다',
			'errRename'            : ' "$1" 이름을 변경할 수 없습니다',
			'errCopyFrom'          : '볼률 "$1" 로부터 파일을 복사할 수 없습니다',
			'errCopyTo'            : '볼률 "$1" 에 파일을 복사할 수 없습니다',
			'errUploadCommon'      : '업로드 에러',
			'errUpload'            : ' "$1" 업로드할 수 없습니다',
			'errUploadNoFiles'     : '업로드할 파일이 없습니다',
			'errMaxSize'           : '데이터가 허용된 최대크기를 초과하였습니다',
			'errFileMaxSize'       : '파일이 허용된 최대크기를 초과하였습니다',
			'errUploadMime'        : '잘못된 파일형식입니다',
			'errUploadTransfer'    : ' "$1" 전송 에러', 
			'errSave'              : ' "$1" 저장할 수 없습니다',
			'errCopy'              : ' "$1" 복사할 수 없습니다',
			'errMove'              : ' "$1" 이동할 수 없습니다',
			'errCopyInItself'      : ' "$1" 이곳에 복사 할 수 없습니다',
			'errRm'                : ' "$1" 이름을 변경할 수 없습니다',
			'errExtract'           : ' "$1" 에 압축을 풀 수 없습니다',
			'errArchive'           : '압축파일을 생성할 수 없습니다',
			'errArcType'           : '지원하지 않는 압축파일 형식입니다',
			'errNoArchive'         : '압축파일이 아니거나 지원하지 않는 압축파일 형식입니다',
			'errCmdNoSupport'      : '이 명령어는 Backend를 지원하지 않습니다',
			'errReplByChild'       : ' "$1" 폴더에 덮어쓸수 없습니다',
			'errArcSymlinks'       : '보안을 위해 시스템 호출을 포함한 압축파일인지를 분석합니다',
			'errArcMaxSize'        : '압축파일이 허용된 최대크기를 초과하였습니다',
			'errResize'            : ' "$1" 크기 변경을 할 수 없습니다',
			'errUsupportType'      : '지원하지 않는 파일 형식',

			/******************************* commands names ********************************/
			'cmdarchive'   : '압축파일생성',
			'cmdback'      : '뒤로',
			'cmdcopy'      : '복사',
			'cmdcut'       : '자르기',
			'cmddownload'  : '다운로드',
			'cmdduplicate' : '사본',
			'cmdedit'      : '편집',
			'cmdextract'   : '압축풀기',
			'cmdforward'   : '앞으로',
			'cmdgetfile'   : '선택',
			'cmdhelp'      : '이 소프트웨어는',
			'cmdhome'      : '홈',
			'cmdinfo'      : '파일정보',
			'cmdmkdir'     : '새 폴더',
			'cmdmkfile'    : '새 텍스트파일',
			'cmdopen'      : '열기',
			'cmdpaste'     : '붙여넣기',
			'cmdquicklook' : '미리보기',
			'cmdreload'    : '새로고침',
			'cmdrename'    : '이름바꾸기',
			'cmdrm'        : '삭제',
			'cmdsearch'    : '파일찾기',
			'cmdup'        : '상위폴더',
			'cmdupload'    : '업로드',
			'cmdview'      : '보기',
			'cmdresize'    : '이미지 사이즈변경',
			'cmdsort'      : '정렬',

			/*********************************** buttons ***********************************/ 
			'btnClose'  : '닫기',
			'btnSave'   : '저장',
			'btnRm'     : '삭제',
			'btnApply'  : '적용',
			'btnCancel' : '취소',
			'btnNo'     : '아니오',
			'btnYes'    : '예',

			/******************************** notifications ********************************/
			'ntfopen'     : '폴더 열기',
			'ntffile'     : '파일 열기',
			'ntfreload'   : '새로고침',
			'ntfmkdir'    : '폴더 생성',
			'ntfmkfile'   : '파일 생성',
			'ntfrm'       : '삭제',
			'ntfcopy'     : '복사',
			'ntfmove'     : '이동',
			'ntfprepare'  : '복사 준비',
			'ntfrename'   : '이름바꾸기',
			'ntfupload'   : '업로드',
			'ntfdownload' : '다운로드',
			'ntfsave'     : '저장하기',
			'ntfarchive'  : '압축파일만들기',
			'ntfextract'  : '압축풀기',
			'ntfsearch'   : '검색',
			'ntfsmth'     : '작업중 >_<',
			'ntfloadimg'  : '이미지 불러오기',

			/************************************ dates **********************************/
			'dateUnknown' : '알수없음',
			'Today'       : '오늘',
			'Yesterday'   : '내일',
			'Jan'         : '1월',
			'Feb'         : '2월',
			'Mar'         : '3월',
			'Apr'         : '4월',
			'May'         : '5월',
			'Jun'         : '6월',
			'Jul'         : '7월',
			'Aug'         : '8월',
			'Sep'         : '9월',
			'Oct'         : '10월',
			'Nov'         : '11월',
			'Dec'         : '12월',
			'January'     : '1월',
			'February'    : '2월',
			'March'       : '3월',
			'April'       : '4월',
			'May'         : '5월',
			'June'        : '6월',
			'July'        : '7월',
			'August'      : '8월',
			'September'   : '9월',
			'October'     : '10월',
			'November'    : '11월',
			'December'    : '12월',
			'Sunday'      : '일요일', 
			'Monday'      : '월요일', 
			'Tuesday'     : '화요일', 
			'Wednesday'   : '수요일', 
			'Thursday'    : '목요일', 
			'Friday'      : '금요일', 
			'Saturday'    : '토요일',
			'Sun'         : '일', 
			'Mon'         : '월', 
			'Tue'         : '화', 
			'Wed'         : '수', 
			'Thu'         : '목', 
			'Fri'         : '금', 
			'Sat'         : '토',
			/******************************** sort variants ********************************/
			'sortnameDirsFirst' : '이름 (폴더 먼저)', 
			'sortkindDirsFirst' : '종류 (폴더 먼저)', 
			'sortsizeDirsFirst' : '크기 (폴더 먼저)', 
			'sortdateDirsFirst' : '날짜 (폴더 먼저)', 
			'sortname'          : '이름', 
			'sortkind'          : '종류', 
			'sortsize'          : '크기',
			'sortdate'          : '날짜',

			/********************************** messages **********************************/
			'confirmReq'      : '확인',
			'confirmRm'       : '이 파일을 정말 삭제 하겠습니까?<br/>실행 후 되돌릴 수 없습니다!',
			'confirmRepl'     : '파일을 덮어쓰겠습니까?',
			'apllyAll'        : '모두 적용',
			'name'            : '이름',
			'size'            : '크기',
			'perms'           : '권한',
			'modify'          : '수정된 시간',
			'kind'            : '종류',
			'read'            : '읽기',
			'write'           : '쓰기',
			'noaccess'        : '액세스 불가',
			'and'             : '와',
			'unknown'         : '알 수 없음',
			'selectall'       : '모든 파일 선택',
			'selectfiles'     : '파일 선택',
			'selectffile'     : '첫번째 파일 선택',
			'selectlfile'     : '마지막 파일 선택',
			'viewlist'        : '리스트 보기',
			'viewicons'       : '아이콘 보기',
			'places'          : '위치',
			'calc'            : '계산', 
			'path'            : '경로',
			'aliasfor'        : '별명',
			'locked'          : '잠금',
			'dim'             : '크기',
			'files'           : '파일',
			'folders'         : '폴더',
			'items'           : '아이템',
			'yes'             : '예',
			'no'              : '아니오',
			'link'            : '링크',
			'searcresult'     : '검색 결과',  
			'selected'        : '아이템 선택',
			'about'           : 'About',
			'shortcuts'       : '단축아이콘',
			'help'            : '도움말',
			'webfm'           : '웹 파일매니저',
			'ver'             : '버전',
			'protocol'        : '프로토콜 버전',
			'homepage'        : '홈페이지',
			'docs'            : '문서',
			'github'          : 'Fork us on Github',
			'twitter'         : '트위터따라가기',
			'facebook'        : '페이스북 가입하기',
			'team'            : '팀',
			'chiefdev'        : '개발팀장',
			'developer'       : '개발자',
			'contributor'     : '공헌자',
			'maintainer'      : '관리자',
			'translator'      : '번역',
			'icons'           : '아이콘',
			'dontforget'      : 'and don\'t forget to take your towel',
			'shortcutsof'     : '단축아이콘 사용불가',
			'dropFiles'       : '여기로 이동하기',
			'or'              : '또는',
			'selectForUpload' : '업로드 파일 선택',
			'moveFiles'       : '파일 이동',
			'copyFiles'       : '파일 복사',
			'rmFromPlaces'    : '현재 폴더에서 삭제하기',
			'untitled folder' : '새 폴더',
			'untitled file.txt' : '새 텍스트.txt',
			'aspectRatio'     : '화면비율',
			'scale'           : '크기',
			'width'           : '가로',
			'height'          : '세로',
			'mode'            : '모드',
			'resize'          : '사이즈 변경',
			'crop'            : '자르기',
			'rotate'          : '회전',
			'rotate-cw'       : '반시계방향 90도 회전',
			'rotate-ccw'      : '시계방향 90도 회전',
			'degree'          : '각도',

			/********************************** mimetypes **********************************/
			'kindUnknown'     : '알수없음',
			'kindFolder'      : '폴더',
			'kindAlias'       : 'Alias',
			'kindAliasBroken' : '손상된 Alias',
			// applications
			'kindApp'         : '응용프로그램',
			'kindPostscript'  : 'Postscript 문서',
			'kindMsOffice'    : 'Microsoft Office 문서',
			'kindMsWord'      : 'Microsoft Word 문서',
			'kindMsExcel'     : 'Microsoft Excel 문서',
			'kindMsPP'        : 'Microsoft Powerpoint',
			'kindOO'          : 'Office 문서 열기',
			'kindAppFlash'    : '플래쉬',
			'kindPDF'         : 'PDF(PDF)',
			'kindTorrent'     : 'Bittorrent 파일',
			'kind7z'          : '7z 압축파일',
			'kindTAR'         : 'TAR 압축파일',
			'kindGZIP'        : 'GZIP 압축파일',
			'kindBZIP'        : 'BZIP 압축파일',
			'kindZIP'         : 'ZIP 압축파일',
			'kindRAR'         : 'RAR 압축파일',
			'kindJAR'         : 'Java JAR 파일',
			'kindTTF'         : '트루타입 글꼴',
			'kindOTF'         : '오픈타입 글꼴',
			'kindRPM'         : 'RPM 패키지',
			// texts
			'kindText'        : 'Text 문서',
			'kindTextPlain'   : '보통 텍스트',
			'kindPHP'         : 'PHP 소스',
			'kindCSS'         : 'CSS 문서',
			'kindHTML'        : 'HTML 문서',
			'kindJS'          : '자바스크립트 소스',
			'kindRTF'         : 'RTF 형식',
			'kindC'           : 'C 소스',
			'kindCHeader'     : 'C 헤더소스',
			'kindCPP'         : 'C++ 소스',
			'kindCPPHeader'   : 'C++ 헤더소스',
			'kindShell'       : 'Unix shell 스크립트',
			'kindPython'      : 'Python 소스',
			'kindJava'        : 'Java 소스',
			'kindRuby'        : 'Ruby 소스',
			'kindPerl'        : 'Perl 스크립트',
			'kindSQL'         : 'SQL 소스',
			'kindXML'         : 'XML 문서',
			'kindAWK'         : 'AWK 소스',
			'kindCSV'         : 'CSV 형식',
			'kindDOCBOOK'     : 'XML 닥북 문서',
			// images
			'kindImage'       : '이미지',
			'kindBMP'         : 'BMP 이미지',
			'kindJPEG'        : 'JPEG 이미지',
			'kindGIF'         : 'GIF 이미지',
			'kindPNG'         : 'PNG 이미지',
			'kindTIFF'        : 'TIFF 이미지',
			'kindTGA'         : 'TGA 이미지',
			'kindPSD'         : 'Adobe Photoshop 이미지',
			'kindXBITMAP'     : 'X bitmap 이미지',
			'kindPXM'         : 'Pixelmator 이미지',
			// media
			'kindAudio'       : '오디오 미디어',
			'kindAudioMPEG'   : 'MPEG 오디오',
			'kindAudioMPEG4'  : 'MPEG-4 오디오',
			'kindAudioMIDI'   : 'MIDI 오디오',
			'kindAudioOGG'    : 'Ogg Vorbis 오디오',
			'kindAudioWAV'    : 'WAV 오디오',
			'AudioPlaylist'   : 'MP3 플레이 리스트',
			'kindVideo'       : 'Video 미디어',
			'kindVideoDV'     : 'DV 동영상',
			'kindVideoMPEG'   : 'MPEG 동영상',
			'kindVideoMPEG4'  : 'MPEG-4 동영상',
			'kindVideoAVI'    : 'AVI 동영상',
			'kindVideoMOV'    : '퀵타임 동영상',
			'kindVideoWM'     : '윈도우 미디어 플레이어 동영상',
			'kindVideoFlash'  : '플래쉬 동영상',
			'kindVideoMKV'    : 'Matroska 동영상',
			'kindVideoOGG'    : 'Ogg 동영상'
		}
	}
};/**
 * Dutch translation
 * @author Barry vd. Heuvel <barry@fruitcakestudio.nl>
 * @version 2012-04-02
 */
if (elFinder && elFinder.prototype && typeof(elFinder.prototype.i18) == 'object') {
	elFinder.prototype.i18.nl = {
		translator : 'Barry vd. Heuvel &lt;barry@fruitcakestudio.nl&gt;',
		language   : 'Nederlands',
		direction  : 'ltr',
		dateFormat : 'd-m-Y H:i',
		fancyDateFormat : '$1 H:i',
		messages   : {
			
			/********************************** errors **********************************/
			'error'                : 'Fout',
			'errUnknown'           : 'Onbekend fout.',
			'errUnknownCmd'        : 'Onbekend commando.',
			'errJqui'              : 'Ongeldige jQuery UI configuratie. Selectable, draggable en droppable componenten moeten aanwezig zijn.',
			'errNode'              : 'Voor elFinder moet een DOM Element gemaakt worden.',
			'errURL'               : 'Ongeldige elFinder configuratie! URL optie is niet ingesteld.',
			'errAccess'            : 'Toegang geweigerd.',
			'errConnect'           : 'Kan geen verbinding met de backend maken.',
			'errAbort'             : 'Verbinding afgebroken.',
			'errTimeout'           : 'Verbinding time-out.',
			'errNotFound'          : 'Backend niet gevonden.',
			'errResponse'          : 'Ongeldige reactie van de backend.',
			'errConf'              : 'Ongeldige backend configuratie.',
			'errJSON'              : 'PHP JSON module niet geïnstalleerd.',
			'errNoVolumes'         : 'Leesbaar volume is niet beschikbaar.',
			'errCmdParams'         : 'Ongeldige parameters voor commando "$1".',
			'errDataNotJSON'       : 'Data is niet JSON.',
			'errDataEmpty'         : 'Data is leeg.',
			'errCmdReq'            : 'Backend verzoek heeft een commando naam nodig.',
			'errOpen'              : 'Kan "$1" niet openen.',
			'errNotFolder'         : 'Object is geen map.',
			'errNotFile'           : 'Object is geen bestand.',
			'errRead'              : 'Kan "$1" niet lezen.',
			'errWrite'             : 'Kan niet schrijven in "$1".',
			'errPerm'              : 'Toegang geweigerd.',
			'errLocked'            : '"$1" is vergrendeld en kan niet hernoemd, verplaats of verwijderd worden.',
			'errExists'            : 'Bestand "$1" bestaat al.',
			'errInvName'           : 'Ongeldige bestandsnaam.',
			'errFolderNotFound'    : 'Map niet gevonden.',
			'errFileNotFound'      : 'Bestand niet gevonden.',
			'errTrgFolderNotFound' : 'Doelmap"$1" niet gevonden.',
			'errPopup'             : 'De browser heeft voorkomen dat de pop-up is geopend. Pas de browser instellingen aan om de popup te kunnen openen.',
			'errMkdir'             : 'Kan map "$1" niet aanmaken.',
			'errMkfile'            : 'Kan bestand "$1" niet aanmaken.',
			'errRename'            : 'Kan "$1" niet hernoemen.',
			'errCopyFrom'          : 'Bestanden kopiëren van "$1" is niet toegestaan.',
			'errCopyTo'            : 'Bestanden kopiëren naar "$1" is niet toegestaan.',
			'errUploadCommon'      : 'Upload fout.',
			'errUpload'            : 'Kan "$1" niet uploaden.',
			'errUploadNoFiles'     : 'Geen bestanden gevonden om te uploaden.',
			'errMaxSize'           : 'Data overschrijdt de maximale grootte.',
			'errFileMaxSize'       : 'Bestand overschrijdt de maximale grootte.',
			'errUploadMime'        : 'Bestandstype niet toegestaan.',
			'errUploadTransfer'    : '"$1" overdrachtsfout.', 
			'errSave'              : 'Kan "$1" niet opslaan.',
			'errCopy'              : 'Kan "$1" niet kopiëren.',
			'errMove'              : 'Kan "$1" niet verplaatsen.',
			'errCopyInItself'      : 'Kan "$1" niet in zichzelf kopiëren.',
			'errRm'                : 'Kan "$1" niet verwijderen.',
			'errExtract'           : 'Kan de bestanden van "$1" niet uitpakken.',
			'errArchive'           : 'Kan het archief niet maken.',
			'errArcType'           : 'Archief type is niet ondersteund.',
			'errNoArchive'         : 'Bestand is geen archief of geen ondersteund archief type.',
			'errCmdNoSupport'      : 'Backend ondersteund dit commando niet.',
			'errReplByChild'       : 'De map "$1" kan niet vervangen worden door een item uit die map.',
			'errArcSymlinks'       : 'Om veiligheidsredenen kan een bestand met symlinks of bestanden met niet toegestane namen niet worden uitgepakt .',
			'errArcMaxSize'        : 'Archief overschrijdt de maximale bestandsgrootte.',
			'errResize'            : 'Kan het formaat van "$1" niet wijzigen.',
			'errUsupportType'      : 'Bestandstype wordt niet ondersteund.',
			'errNotUTF8Content'    : 'Bestand "$1" is niet in UTF-8 and kan niet aangepast worden.', 
			'errNetMount'          : 'Kan "$1" niet mounten.',   
			'errNetMountNoDriver'  : 'Niet ondersteund protocol.',   
			'errNetMountFailed'    : 'Mount mislukt.',    
			'errNetMountHostReq'   : 'Host is verplicht.',
			'errSessionExpires'    : 'Uw sessie is verlopen vanwege inactiviteit.',
			'errCreatingTempDir'   : 'Kan de tijdelijke map niet aanmaken: "$1" ',
			'errFtpDownloadFile'   : 'Kan het bestand niet downloaden vanaf FTP: "$1"',
			'errFtpUploadFile'     : 'Kan het bestand niet uploaden naar FTP: "$1"',
			'errFtpMkdir'          : 'Kan het externe map niet aanmaken op de FTP-server: "$1"',
			'errArchiveExec'       : 'Er is een fout opgetreden bij het archivering van de bestanden: "$1" ',
			'errExtractExec'       : 'Er is een fout opgetreden bij het uitpakken van de bestanden: "$1" ',
			'errUploadFile'        : 'Kan "$1" niet uploaden',
			
			/******************************* commands names ********************************/
			'cmdarchive'   : 'Maak archief',
			'cmdback'      : 'Vorige',
			'cmdcopy'      : 'Kopieer',
			'cmdcut'       : 'Knip',
			'cmddownload'  : 'Download',
			'cmdduplicate' : 'Dupliceer',
			'cmdedit'      : 'Pas bestand aan',
			'cmdextract'   : 'Bestanden uit archief uitpakken',
			'cmdforward'   : 'Volgende',
			'cmdgetfile'   : 'Kies bestanden',
			'cmdhelp'      : 'Over deze software',
			'cmdhome'      : 'Home',
			'cmdinfo'      : 'Bekijk info',
			'cmdmkdir'     : 'Nieuwe map',
			'cmdmkfile'    : 'Nieuw tekstbestand',
			'cmdopen'      : 'Open',
			'cmdpaste'     : 'Plak',
			'cmdquicklook' : 'Voorbeeld',
			'cmdreload'    : 'Vernieuwen',
			'cmdrename'    : 'Naam wijzigen',
			'cmdrm'        : 'Verwijder',
			'cmdsearch'    : 'Zoek bestanden',
			'cmdup'        : 'Ga een map hoger',
			'cmdupload'    : 'Upload bestanden',
			'cmdview'      : 'Bekijk',
			'cmdresize'    : 'Formaat wijzigen',
			'cmdsort'      : 'Sorteren',
			'cmdnetmount'  : 'Mount netwerk volume',
			
			/*********************************** buttons ***********************************/ 
			'btnClose'  : 'Sluit',
			'btnSave'   : 'Opslaan',
			'btnRm'     : 'Verwijder',
			'btnApply'  : 'Toepassen',
			'btnCancel' : 'Annuleren',
			'btnNo'     : 'Nee',
			'btnYes'    : 'Ja',
			'btnMount'  : 'Mount',
			
			/******************************** notifications ********************************/
			'ntfopen'     : 'Bezig met openen van map',
			'ntffile'     : 'Bezig met openen bestand',
			'ntfreload'   : 'Herladen map inhoud',
			'ntfmkdir'    : 'Bezig met map maken',
			'ntfmkfile'   : 'Bezig met Bestanden maken',
			'ntfrm'       : 'Verwijderen bestanden',
			'ntfcopy'     : 'Kopieer bestanden',
			'ntfmove'     : 'Verplaats bestanden',
			'ntfprepare'  : 'Voorbereiden kopiëren',
			'ntfrename'   : 'Hernoem bestanden',
			'ntfupload'   : 'Bestanden uploaden actief',
			'ntfdownload' : 'Bestanden downloaden actief',
			'ntfsave'     : 'Bestanden opslaan',
			'ntfarchive'  : 'Archief aan het maken',
			'ntfextract'  : 'Bestanden uitpakken actief',
			'ntfsearch'   : 'Zoeken naar bestanden',
			'ntfsmth'     : 'Iets aan het doen',
			'ntfloadimg'  : 'Laden van plaatje',
			'ntfnetmount' : 'Verhogen netwerk volume',
			
			/************************************ dates **********************************/
			'dateUnknown' : 'onbekend',
			'Today'       : 'Vandaag',
			'Yesterday'   : 'Gisteren',
			'Jan'         : 'Jan',
			'Feb'         : 'Feb',
			'Mar'         : 'Mar',
			'Apr'         : 'Apr',
			'May'         : 'Mei',
			'Jun'         : 'Jun',
			'Jul'         : 'Jul',
			'Aug'         : 'Aug',
			'Sep'         : 'Sep',
			'Oct'         : 'Okt',
			'Nov'         : 'Nov',
			'Dec'         : 'Dec',
			'January'     : 'Januari',
			'February'    : 'Februari',
			'March'       : 'Maart',
			'April'       : 'April',
			'May'         : 'Mei',
			'June'        : 'Juni',
			'July'        : 'Juli',
			'August'      : 'Augustus',
			'September'   : 'September',
			'October'     : 'Oktober',
			'November'    : 'November',
			'December'    : 'December',
			'Sunday'      : 'Zondag', 
			'Monday'      : 'Maandag', 
			'Tuesday'     : 'Dinsdag', 
			'Wednesday'   : 'Woensdag', 
			'Thursday'    : 'Donderdag', 
			'Friday'      : 'Vrijdag', 
			'Saturday'    : 'Zaterdag',
			'Sun'         : 'Zo', 
			'Mon'         : 'Ma', 
			'Tue'         : 'Di', 
			'Wed'         : 'Wo', 
			'Thu'         : 'Do', 
			'Fri'         : 'Vr', 
			'Sat'         : 'Za',
			
			/******************************** sort variants ********************************/
			'sortname'          : 'op naam', 
			'sortkind'          : 'op type', 
			'sortsize'          : 'op grootte',
			'sortdate'          : 'op datum',
			'sortFoldersFirst'  : 'Mappen eerst',

			/********************************** messages **********************************/
			'confirmReq'      : 'Bevestiging nodig',
			'confirmRm'       : 'Weet u zeker dat u deze bestanden wil verwijderen?<br/>Deze actie kan niet ongedaan gemaakt worden!',
			'confirmRepl'     : 'Oud bestand vervangen door het nieuwe bestand?',
			'apllyAll'        : 'Toepassen op alles',
			'name'            : 'Naam',
			'size'            : 'Grootte',
			'perms'           : 'Rechten',
			'modify'          : 'Aangepast',
			'kind'            : 'Type',
			'read'            : 'lees',
			'write'           : 'schrijf',
			'noaccess'        : 'geen toegang',
			'and'             : 'en',
			'unknown'         : 'onbekend',
			'selectall'       : 'Selecteer alle bestanden',
			'selectfiles'     : 'Selecteer bestand(en)',
			'selectffile'     : 'Selecteer eerste bestand',
			'selectlfile'     : 'Selecteer laatste bestand',
			'viewlist'        : 'Lijst weergave',
			'viewicons'       : 'Icoon weergave',
			'places'          : 'Plaatsen',
			'calc'            : 'Bereken', 
			'path'            : 'Pad',
			'aliasfor'        : 'Alias voor',
			'locked'          : 'Vergrendeld',
			'dim'             : 'Dimensies',
			'files'           : 'Bestanden',
			'folders'         : 'Mappen',
			'items'           : 'Items',
			'yes'             : 'ja',
			'no'              : 'nee',
			'link'            : 'Link',
			'searcresult'     : 'Zoek resultaten',  
			'selected'        : 'geselecteerde items',
			'about'           : 'Over',
			'shortcuts'       : 'Snelkoppelingen',
			'help'            : 'Help',
			'webfm'           : 'Web bestandsmanager',
			'ver'             : 'Versie',
			'protocolver'     : 'protocol versie',
			'homepage'        : 'Project home',
			'docs'            : 'Documentatie',
			'github'          : 'Fork ons op Github',
			'twitter'         : 'Volg ons op twitter',
			'facebook'        : 'Wordt lid op facebook',
			'team'            : 'Team',
			'chiefdev'        : 'Hoofd ontwikkelaar',
			'developer'       : 'ontwikkelaar',
			'contributor'     : 'bijdrager',
			'maintainer'      : 'onderhouder',
			'translator'      : 'vertaler',
			'icons'           : 'Iconen',
			'dontforget'      : 'En vergeet je handdoek niet!',
			'shortcutsof'     : 'Snelkoppelingen uitgeschakeld',
			'dropFiles'       : 'Sleep hier uw bestanden heen',
			'or'              : 'of',
			'selectForUpload' : 'Selecteer bestanden om te uploaden',
			'moveFiles'       : 'Verplaats bestanden',
			'copyFiles'       : 'Kopieer bestanden',
			'rmFromPlaces'    : 'Verwijder uit Plaatsen',
			'untitled folder' : 'Nieuwe map',
			'untitled file.txt' : 'nieuw bestand.txt',
			'aspectRatio'     : 'Aspect ratio',
			'scale'           : 'Schaal',
			'width'           : 'Breedte',
			'height'          : 'Hoogte',
			'mode'		  : 'Modus',
			'resize'	  : 'Verkleinen', //Or: Vergroten/verkleinen
			'crop'		  : 'Bijsnijden',
			'rotate'	  : 'Draaien',
			'rotate-cw'	  : 'Draai 90 graden rechtsom',
			'rotate-ccw'	  : 'Draai 90 graden linksom',
			'degree'	  : '°',
			'netMountDialogTitle' : 'Mount network volume',
			'protocol'        : 'Protocol', 
			'host'            : 'Host', 
			'port'            : 'Poort',
			'user'            : 'Gebruikersnaams', 
			'pass'            : 'Wachtwoord', 
			
			/********************************** mimetypes **********************************/
			'kindUnknown'     : 'Onbekend',
			'kindFolder'      : 'Map',
			'kindAlias'       : 'Alias',
			'kindAliasBroken' : 'Kapot alias',
			// applications
			'kindApp'         : 'Applicatie',
			'kindPostscript'  : 'Postscript document',
			'kindMsOffice'    : 'Microsoft Office document',
			'kindMsWord'      : 'Microsoft Word document',
			'kindMsExcel'     : 'Microsoft Excel document',
			'kindMsPP'        : 'Microsoft Powerpoint presentation',
			'kindOO'          : 'Open Office document',
			'kindAppFlash'    : 'Flash applicatie',
			'kindPDF'         : 'Portable Document Format (PDF)',
			'kindTorrent'     : 'Bittorrent bestand',
			'kind7z'          : '7z archief',
			'kindTAR'         : 'TAR archief',
			'kindGZIP'        : 'GZIP archief',
			'kindBZIP'        : 'BZIP archief',
			'kindZIP'         : 'ZIP archief',
			'kindRAR'         : 'RAR archief',
			'kindJAR'         : 'Java JAR bestand',
			'kindTTF'         : 'True Type font',
			'kindOTF'         : 'Open Type font',
			'kindRPM'         : 'RPM package',
			// texts
			'kindText'        : 'Tekst bestand',
			'kindTextPlain'   : 'Tekst',
			'kindPHP'         : 'PHP bronbestand',
			'kindCSS'         : 'Cascading style sheet',
			'kindHTML'        : 'HTML document',
			'kindJS'          : 'Javascript bronbestand',
			'kindRTF'         : 'Rich Text Format',
			'kindC'           : 'C bronbestand',
			'kindCHeader'     : 'C header bronbestand',
			'kindCPP'         : 'C++ bronbestand',
			'kindCPPHeader'   : 'C++ header bronbestand',
			'kindShell'       : 'Unix shell script',
			'kindPython'      : 'Python bronbestand',
			'kindJava'        : 'Java bronbestand',
			'kindRuby'        : 'Ruby bronbestand',
			'kindPerl'        : 'Perl bronbestand',
			'kindSQL'         : 'SQL bronbestand',
			'kindXML'         : 'XML document',
			'kindAWK'         : 'AWK bronbestand',
			'kindCSV'         : 'Komma gescheiden waardes',
			'kindDOCBOOK'     : 'Docbook XML document',
			// images
			'kindImage'       : 'Afbeelding',
			'kindBMP'         : 'BMP afbeelding',
			'kindJPEG'        : 'JPEG afbeelding',
			'kindGIF'         : 'GIF afbeelding',
			'kindPNG'         : 'PNG afbeelding',
			'kindTIFF'        : 'TIFF afbeelding',
			'kindTGA'         : 'TGA afbeelding',
			'kindPSD'         : 'Adobe Photoshop afbeelding',
			'kindXBITMAP'     : 'X bitmap afbeelding',
			'kindPXM'         : 'Pixelmator afbeelding',
			// media
			'kindAudio'       : 'Audio media',
			'kindAudioMPEG'   : 'MPEG audio',
			'kindAudioMPEG4'  : 'MPEG-4 audio',
			'kindAudioMIDI'   : 'MIDI audio',
			'kindAudioOGG'    : 'Ogg Vorbis audio',
			'kindAudioWAV'    : 'WAV audio',
			'AudioPlaylist'   : 'MP3 playlist',
			'kindVideo'       : 'Video media',
			'kindVideoDV'     : 'DV video',
			'kindVideoMPEG'   : 'MPEG video',
			'kindVideoMPEG4'  : 'MPEG-4 video',
			'kindVideoAVI'    : 'AVI video',
			'kindVideoMOV'    : 'Quick Time video',
			'kindVideoWM'     : 'Windows Media video',
			'kindVideoFlash'  : 'Flash video',
			'kindVideoMKV'    : 'Matroska video',
			'kindVideoOGG'    : 'Ogg video'
		}
	}
}

;/**
 * Norwegian translation
 * @author Stian Jacobsen <stian@promonorge.no>
 * @version 2012-07-03
 */
if (elFinder && elFinder.prototype && typeof(elFinder.prototype.i18) == 'object') {
	elFinder.prototype.i18.no = {
		translator : 'Stian Jacobsen &lt;stian@promonorge.no&gt;',
		language   : 'Norwegian Bokmål',
		direction  : 'ltr',
		messages   : {
			
			/********************************** errors **********************************/
			'error'                : 'Feil',
			'errUnknown'           : 'Ukjent feil.',
			'errUnknownCmd'        : 'Ukjent kommando.',
			'errJqui'              : 'Ugyldig jQuery UI konfigurasjon. Selectable, draggable og droppable komponentene må være inkludert.',
			'errNode'              : 'elFinder påkrever at DOM Elementer kan opprettes.',
			'errURL'               : 'Ugyldig elFinder konfigurasjon! URL-valget er ikke satt.',
			'errAccess'            : 'Ingen adgang.',
			'errConnect'           : 'Kunne ikke koble til.',
			'errAbort'             : 'Tilkoblingen avbrutt.',
			'errTimeout'           : 'Tilkoblingen tidsavbrudd.',
			'errNotFound'          : 'Backend ble ikke funnet',
			'errResponse'          : 'Ugyldig backend respons.',
			'errConf'              : 'Ugyldig backend konfigurasjon.',
			'errJSON'              : 'PHP JSON modul er ikke installert.',
			'errNoVolumes'         : 'Lesbar volum er ikke tilgjennelig.',
			'errCmdParams'         : 'Ugyldig parameter for kommando "$1".',
			'errDataNotJSON'       : 'Innhold er ikke JSON.',
			'errDataEmpty'         : 'Innholdet er tomt.',
			'errCmdReq'            : 'Backend spørringen påkrever kommando.',
			'errOpen'              : 'Kunne ikke åpne "$1".',
			'errNotFolder'         : 'Objektet er ikke en mappe.',
			'errNotFile'           : 'Objektet er ikke en fil.',
			'errRead'              : 'Kunne ikke lese "$1".',
			'errWrite'             : 'Kunne ikke skrive til "$1".',
			'errPerm'              : 'Du har ikke rettigheter.',
			'errLocked'            : '"$1" er låst og kan ikke flyttes, slettes eller endres',
			'errExists'            : 'Filen "$1" finnes allerede.',
			'errInvName'           : 'Ugyldig filnavn.',
			'errFolderNotFound'    : 'Mappen finnes ikke.',
			'errFileNotFound'      : 'Filen finnes ikke.',
			'errTrgFolderNotFound' : 'Målmappen "$1" ble ikke funnet.',
			'errPopup'             : 'Nettleseren din blokkerte et pop-up vindu. For å åpne filen må du aktivere pop-up i din nettlesers innstillinger.',
			'errMkdir'             : 'Kunne ikke opprette mappen "$1".',
			'errMkfile'            : 'Kunne ikke opprette filen "$1".',
			'errRename'            : 'Kunne ikke gi nytt navn til "$1".',
			'errCopyFrom'          : 'Kopiere filer fra "$1" er ikke tillatt.',
			'errCopyTo'            : 'Kopiere filer til "$1" er ikke tillatt.',
			'errUploadCommon'      : 'Feil under opplasting.',
			'errUpload'            : 'Kunne ikke laste opp "$1".',
			'errUploadNoFiles'     : 'Ingen filer funnet til opplasting.',
			'errMaxSize'           : 'Innholdet overgår maksimum tillatt størrelse.',
			'errFileMaxSize'       : 'Filen vergår maksimum tillatt størrelse.',
			'errUploadMime'        : 'Filtypen ikke tillatt.',
			'errUploadTransfer'    : '"$1" overførings feil.', 
			'errSave'              : 'Kunne ikke lagre "$1".',
			'errCopy'              : 'Kunne ikke kopiere "$1".',
			'errMove'              : 'Kunne ikke flytte "$1".',
			'errCopyInItself'      : 'Kunne ikke kopiere "$1" til seg selv.',
			'errRm'                : 'Kunne ikke slette "$1".',
			'errExtract'           : 'Kunne ikke pakke ut filer fra "$1".',
			'errArchive'           : 'Kunne ikke opprette arkiv.',
			'errArcType'           : 'akriv-typen er ikke støttet.',
			'errNoArchive'         : 'Filen er ikke et arkiv eller et arkiv som ikke er støttet.',
			'errCmdNoSupport'      : 'Backend støtter ikke denne kommandoen.',
			
			/******************************* commands names ********************************/
			'cmdarchive'   : 'Opprett arkiv',
			'cmdback'      : 'Tilbake',
			'cmdcopy'      : 'Kopier',
			'cmdcut'       : 'Klipp ut',
			'cmddownload'  : 'Last ned',
			'cmdduplicate' : 'Dupliser',
			'cmdedit'      : 'Rediger fil',
			'cmdextract'   : 'Pakk ut filer fra arkiv',
			'cmdforward'   : 'Frem',
			'cmdgetfile'   : 'Velg filer',
			'cmdhelp'      : 'Om',
			'cmdhome'      : 'Hjem',
			'cmdinfo'      : 'Vis info',
			'cmdmkdir'     : 'Ny mappe',
			'cmdmkfile'    : 'Ny tekst-fil',
			'cmdopen'      : 'Åpne',
			'cmdpaste'     : 'Lim inn',
			'cmdquicklook' : 'Forhåndsvis',
			'cmdreload'    : 'Last inn på nytt',
			'cmdrename'    : 'Gi nytt navn',
			'cmdrm'        : 'Slett',
			'cmdsearch'    : 'Find filer',
			'cmdup'        : 'Opp et nivå',
			'cmdupload'    : 'Last opp filer',
			'cmdview'      : 'Vis',
			
			/*********************************** buttons ***********************************/ 
			'btnClose'  : 'Lukk',
			'btnSave'   : 'Lagre',
			'btnRm'     : 'Slett',
			'btnCancel' : 'Avbryt',
			'btnNo'     : 'Nei',
			'btnYes'    : 'Ja',
			
			/******************************** notifications ********************************/
			'ntfopen'     : 'Åpne mappe',
			'ntffile'     : 'Åpne fil',
			'ntfreload'   : 'Last inn mappen på nytt',
			'ntfmkdir'    : 'Oppretter mappe',
			'ntfmkfile'   : 'Oppretter filer',
			'ntfrm'       : 'Sletter filer',
			'ntfcopy'     : 'Kopierer filer',
			'ntfmove'     : 'Flytter filer',
			'ntfprepare'  : 'Gjør klar til kopiering av filer',
			'ntfrename'   : 'Gir nytt navn til filer',
			'ntfupload'   : 'Laster opp filer',
			'ntfdownload' : 'Laster ned filer',
			'ntfsave'     : 'Lagrer filer',
			'ntfarchive'  : 'Oppretter arkiv',
			'ntfextract'  : 'Pakker ut filer fra arkiv',
			'ntfsearch'   : 'Søker i filer',
			'ntfsmth'     : 'Gjør noe... >_<',
			
			/************************************ dates **********************************/
			'dateUnknown' : 'Ukjent',
			'Today'       : 'I dag',
			'Yesterday'   : 'I går',
			'Jan'         : 'Jan',
			'Feb'         : 'Feb',
			'Mar'         : 'Mar',
			'Apr'         : 'Apr',
			'May'         : 'Mai',
			'Jun'         : 'Jun',
			'Jul'         : 'Jul',
			'Aug'         : 'Aug',
			'Sep'         : 'Sep',
			'Oct'         : 'Okt',
			'Nov'         : 'Nov',
			'Dec'         : 'Des',

			/********************************** messages **********************************/
			'confirmReq'      : 'Bekreftelse nødvendig',
			'confirmRm'       : 'Er du sikker på at du ønsker å slette filene?',
			'confirmRepl'     : 'Erstatt fil?',
			'apllyAll'        : 'Apply to all',
			'name'            : 'Navn',
			'size'            : 'Størrelse',
			'perms'           : 'Rettigheter',
			'modify'          : 'Endret',
			'kind'            : 'Type',
			'read'            : 'les',
			'write'           : 'skriv',
			'noaccess'        : 'ingen adgang',
			'and'             : 'og',
			'unknown'         : 'ukjent',
			'selectall'       : 'Velg alle filene',
			'selectfiles'     : 'Velg fil(er)',
			'selectffile'     : 'Velg første fil',
			'selectlfile'     : 'Velg siste fil',
			'viewlist'        : 'Listevisning',
			'viewicons'       : 'Ikoner',
			'places'          : 'Områder',
			'calc'            : 'Beregn', 
			'path'            : 'Bane',
			'aliasfor'        : 'Alias for',
			'locked'          : 'Låst',
			'dim'             : 'Størrelser',
			'files'           : 'Filer',
			'folders'         : 'Mapper',
			'items'           : 'objekter',
			'yes'             : 'ja',
			'no'              : 'nei',
			'link'            : 'Link',
			'searcresult'     : 'Søkeresultater',  
			'selected'        : 'valgte filer',
			'about'           : 'Om',
			'shortcuts'       : 'Snarveier',
			'help'            : 'Hjelp',
			'webfm'           : 'Web-filbehandler',
			'ver'             : 'Versjon',
			'protocolver'     : 'protokol versjon',
			'homepage'        : 'Project home',
			'docs'            : 'dokumentasjon',
			'github'          : 'Fork us on Github',
			'twitter'         : 'Follow us on twitter',
			'facebook'        : 'Join us on facebook',
			'team'            : 'Team',
			'chiefdev'        : 'chief developer',
			'developer'       : 'developer',
			'contributor'     : 'contributor',
			'maintainer'      : 'maintainer',
			'translator'      : 'translator',
			'icons'           : 'Ikoner',
			'dontforget'      : 'and don\'t forget to bring a towel',
			'shortcutsof'     : 'Snarveier avslått',
			'dropFiles'       : 'Slipp filer her',
			'or'              : 'eller',
			'selectForUpload' : 'Velg filer til opplasting',
			'moveFiles'       : 'Flytt filer',
			'copyFiles'       : 'Kopier filer',
			
			/********************************** mimetypes **********************************/
			'kindUnknown'     : 'Ukjent',
			'kindFolder'      : 'Mappe',
			'kindAlias'       : 'Snarvei',
			'kindAliasBroken' : 'Ugyldig snarvei',
			// applications
			'kindApp'         : 'Programfil',
			'kindPostscript'  : 'Postscript dokument',
			'kindMsOffice'    : 'Microsoft Office dokument',
			'kindMsWord'      : 'Microsoft Word dokument',
			'kindMsExcel'     : 'Microsoft Excel dokument',
			'kindMsPP'        : 'Microsoft Powerpoint presentation',
			'kindOO'          : 'Open Office dokument',
			'kindAppFlash'    : 'Flash',
			'kindPDF'         : 'Portabelt dokument (PDF)',
			'kindTorrent'     : 'Bittorrent file',
			'kind7z'          : '7z arkiv',
			'kindTAR'         : 'TAR arkiv',
			'kindGZIP'        : 'GZIP arkiv',
			'kindBZIP'        : 'BZIP arkiv',
			'kindZIP'         : 'ZIP arkiv',
			'kindRAR'         : 'RAR ar',
			'kindJAR'         : 'Java JAR file',
			'kindTTF'         : 'True Type font',
			'kindOTF'         : 'Open Type font',
			'kindRPM'         : 'RPM package',
			// texts
			'kindText'        : 'Tekst dokument',
			'kindTextPlain'   : 'Plain text',
			'kindPHP'         : 'PHP kilde',
			'kindCSS'         : 'Cascading style sheet',
			'kindHTML'        : 'HTML dokument',
			'kindJS'          : 'Javascript',
			'kindRTF'         : 'Rikt Tekst Format',
			'kindC'           : 'C kilde',
			'kindCHeader'     : 'C header kilde',
			'kindCPP'         : 'C++ kilde',
			'kindCPPHeader'   : 'C++ header kilde',
			'kindShell'       : 'Unix shell script',
			'kindPython'      : 'Python kilde',
			'kindJava'        : 'Java kilde',
			'kindRuby'        : 'Ruby kilde',
			'kindPerl'        : 'Perl script',
			'kindSQL'         : 'SQL skilde',
			'kindXML'         : 'XML dokument',
			'kindAWK'         : 'AWK kilde',
			'kindCSV'         : 'Comma separated values',
			'kindDOCBOOK'     : 'Docbook XML dokument',
			// Images
			'kindimage'       : 'Bilde',
			'kindBMP'         : 'BMP bilde',
			'kindJPEG'        : 'JPEG bilde',
			'kindGIF'         : 'GIF bilde',
			'kindPNG'         : 'PNG bilde',
			'kindTIFF'        : 'TIFF bilde',
			'kindTGA'         : 'TGA bilde',
			'kindPSD'         : 'Adobe Photoshop bilde',
			'kindXBITMAP'     : 'X bitmap bilde',
			'kindPXM'         : 'Pixelmator bilde',
			// media
			'kindAudio'       : 'Audio media',
			'kindAudioMPEG'   : 'MPEG audio',
			'kindAudioMPEG4'  : 'MPEG-4 audio',
			'kindAudioMIDI'   : 'MIDI audio',
			'kindAudioOGG'    : 'Ogg Vorbis audio',
			'kindAudioWAV'    : 'WAV audio',
			'AudioPlaylist'   : 'MP3 spilleliste',
			'kindVideo'       : 'Video media',
			'kindVideoDV'     : 'DV film',
			'kindVideoMPEG'   : 'MPEG film',
			'kindVideoMPEG4'  : 'MPEG-4 film',
			'kindVideoAVI'    : 'AVI film',
			'kindVideoMOV'    : 'Quick Time film',
			'kindVideoWM'     : 'Windows Media film',
			'kindVideoFlash'  : 'Flash film',
			'kindVideoMKV'    : 'Matroska film',
			'kindVideoOGG'    : 'Ogg film'
		}
	}
};/**
 * Polish translation
 * @author Marcin Mikołajczyk <marcin@pjwstk.edu.pl>
 * @author Wojciech Jabłoński <www.jablonski@gmail.com>
 * @version 2013-01-28
 */
if (elFinder && elFinder.prototype && typeof(elFinder.prototype.i18) == 'object') {
	elFinder.prototype.i18.pl = {
		translator : 'Marcin Mikołajczyk &lt;marcin@pjwstk.edu.pl&gt;, Wojciech Jabłoński &lt;www.jablonski@gmail.com&gt;',
		language   : 'Polski',
		direction  : 'ltr',
		dateFormat : 'd M Y H:i',
		fancyDateFormat : '$1 H:i',
		messages   : {

			/********************************** errors **********************************/
			'error'                : 'Błąd',
			'errUnknown'           : 'Nieznany błąd.',
			'errUnknownCmd'        : 'Nieznane polecenie.',
			'errJqui'              : 'Niepoprawna konfiguracja jQuery UI. Muszą być zawarte komponenty selectable, draggable i droppable.',
			'errNode'              : 'elFinder wymaga utworzenia obiektu DOM.',
			'errURL'               : 'Niepoprawna konfiguracja elFinder! Pole URL nie jest ustawione.',
			'errAccess'            : 'Dostęp zabroniony.',
			'errConnect'           : 'Błąd połączenia z backend.',
			'errAbort'             : 'Połączenie zostało przerwane.',
			'errTimeout'           : 'Upłynął czas oczekiwania na połączenie.',
			'errNotFound'          : 'Backend nie został znaleziony.',
			'errResponse'          : 'Nieprawidłowa odpowiedź backend.',
			'errConf'              : 'Niepoprawna konfiguracja backend.',
			'errJSON'              : 'Moduł PHP JSON nie jest zainstalowany.',
			'errNoVolumes'         : 'Brak możliwości odczytu katalogów.',
			'errCmdParams'         : 'Nieprawidłowe parametry dla polecenia "$1".',
			'errDataNotJSON'       : 'Dane nie są JSON.',
			'errDataEmpty'         : 'Dane są puste.',
			'errCmdReq'            : 'Backend wymaga podania nazwy polecenia.',
			'errOpen'              : 'Nie można otworzyć "$1".',
			'errNotFolder'         : 'Obiekt nie jest folderem.',
			'errNotFile'           : 'Obiekt nie jest plikiem.',
			'errRead'              : 'Nie można odczytać "$1".',
			'errWrite'             : 'Nie można zapisać do "$1".',
			'errPerm'              : 'Brak uprawnień.',
			'errLocked'            : '"$1" jest zablokowany i nie może zostać zmieniony, przeniesiony lub usunięty.',
			'errExists'            : 'Plik "$1" już istnieje.',
			'errInvName'           : 'Nieprawidłowa nazwa pliku.',
			'errFolderNotFound'    : 'Katalog nie został znaleziony.',
			'errFileNotFound'      : 'Plik nie został znaleziony.',
			'errTrgFolderNotFound' : 'Katalog docelowy "$1" nie został znaleziony.',
			'errPopup'             : 'Przeglądarka zablokowała otwarcie nowego okna. Aby otworzyć plik, zmień ustawienia przeglądarki.',
			'errMkdir'             : 'Nie można utworzyć katalogu "$1".',
			'errMkfile'            : 'Nie można utworzyć pliku "$1".',
			'errRename'            : 'Nie można zmienić nazwy "$1".',
			'errCopyFrom'          : 'Kopiowanie z katalogu "$1" nie jest możliwe.',
			'errCopyTo'            : 'Kopiowanie do katalogu "$1" nie jest możliwe.',
			'errUploadCommon'      : 'Błąd wysyłania.',
			'errUpload'            : 'Nie można wysłać "$1".',
			'errUploadNoFiles'     : 'Nie znaleziono plików do wysłania.',
			'errMaxSize'           : 'Przekroczono dopuszczalny rozmiar wysyłanych plików.',
			'errFileMaxSize'       : 'Plik przekracza dopuszczalny rozmiar.',
			'errUploadMime'        : 'Niedozwolony typ pliku.',
			'errUploadTransfer'    : 'Błąd przesyłania "$1".',
			'errSave'              : 'Nie można zapisać "$1".',
			'errCopy'              : 'Nie można skopiować "$1".',
			'errMove'              : 'Nie można przenieść "$1".',
			'errCopyInItself'      : 'Nie można skopiować "$1" w miejsce jego samego.',
			'errRm'                : 'Nie można usunąć "$1".',
			'errExtract'           : 'Nie można wypakować plików z "$1".',
			'errArchive'           : 'Nie można utworzyć archiwum.',
			'errArcType'           : 'Nieobsługiwany typ archiwum.',
			'errNoArchive'         : 'Plik nie jest prawidłowym typem archiwum.',
			'errCmdNoSupport'      : 'Backend nie obsługuje tego polecenia.',
			'errReplByChild'       : 'Nie można zastąpić katalogu "$1" elementem w nim zawartym',
			'errArcSymlinks'       : 'Ze względów bezpieczeństwa rozpakowywanie archiwów zawierających dowiązania symboliczne (symlinks) jest niedozwolone.',
			'errArcMaxSize'        : 'Archiwum przekracza maksymalny dopuszczalny rozmiar.',
			'errResize'            : 'Nie można zmienić rozmiaru "$1".',
			'errUsupportType'      : 'Nieobsługiwany typ pliku.',

			/******************************* commands names ********************************/
			'cmdarchive'   : 'Utwórz archiwum',
			'cmdback'      : 'Wstecz',
			'cmdcopy'      : 'Kopiuj',
			'cmdcut'       : 'Wytnij',
			'cmddownload'  : 'Pobierz',
			'cmdduplicate' : 'Duplikuj',
			'cmdedit'      : 'Edytuj plik',
			'cmdextract'   : 'Wypakuj pliki z archiwum',
			'cmdforward'   : 'Dalej',
			'cmdgetfile'   : 'Wybierz pliki',
			'cmdhelp'      : 'Informacje o programie',
			'cmdhome'      : 'Katalog główny',
			'cmdinfo'      : 'Właściwości',
			'cmdmkdir'     : 'Nowy folder',
			'cmdmkfile'    : 'Nowy plik tekstowy',
			'cmdopen'      : 'Otwórz',
			'cmdpaste'     : 'Wklej',
			'cmdquicklook' : 'Podgląd',
			'cmdreload'    : 'Odśwież',
			'cmdrename'    : 'Zmień nazwę',
			'cmdrm'        : 'Usuń',
			'cmdsearch'    : 'Wyszukaj pliki',
			'cmdup'        : 'W górę',
			'cmdupload'    : 'Wyślij pliki',
			'cmdview'      : 'Widok',
			'cmdresize'    : 'Zmień rozmiar obrazu',
			'cmdsort'      : 'Sortuj',

			/*********************************** buttons ***********************************/
			'btnClose'  : 'Zamknij',
			'btnSave'   : 'Zapisz',
			'btnRm'     : 'Usuń',
			'btnApply'  : 'Zastosuj',
			'btnCancel' : 'Anuluj',
			'btnNo'     : 'Nie',
			'btnYes'    : 'Tak',

			/******************************** notifications ********************************/
			'ntfopen'     : 'Otwórz folder',
			'ntffile'     : 'Otwórz plik',
			'ntfreload'   : 'Odśwież zawartość folderu',
			'ntfmkdir'    : 'Tworzenie katalogu',
			'ntfmkfile'   : 'Tworzenie plików',
			'ntfrm'       : 'Usuwanie plików',
			'ntfcopy'     : 'Kopiowanie plików',
			'ntfmove'     : 'Przenoszenie plików',
			'ntfprepare'  : 'Przygotowanie do kopiowania plików',
			'ntfrename'   : 'Zmiana nazw plików',
			'ntfupload'   : 'Wysyłanie plików',
			'ntfdownload' : 'Pobieranie plików',
			'ntfsave'     : 'Zapisywanie plików',
			'ntfarchive'  : 'Tworzenie archiwum',
			'ntfextract'  : 'Wypakowywanie plików z archiwum',
			'ntfsearch'   : 'Wyszukiwanie plików',
			'ntfsmth'     : 'Robienie czegoś >_<',
			'ntfloadimg'  : 'Ładowanie obrazu',

			/************************************ dates **********************************/
			'dateUnknown' : 'nieznana',
			'Today'       : 'Dzisiaj',
			'Yesterday'   : 'Wczoraj',
			'Jan'         : 'sty',
			'Feb'         : 'lut',
			'Mar'         : 'mar',
			'Apr'         : 'kwi',
			'May'         : 'maj',
			'Jun'         : 'cze',
			'Jul'         : 'lip',
			'Aug'         : 'sie',
			'Sep'         : 'wrz',
			'Oct'         : 'paź',
			'Nov'         : 'lis',
			'Dec'         : 'gru',
			'January'     : 'Styczeń',
			'February'    : 'Luty',
			'March'       : 'Marzec',
			'April'       : 'Kwiecień',
			'May'         : 'Maj',
			'June'        : 'Czerwiec',
			'July'        : 'Lipiec',
			'August'      : 'Sierpień',
			'September'   : 'Wrzesień',
			'October'     : 'Październik',
			'November'    : 'Listopad',
			'December'    : 'Grudzień',
			'Sunday'      : 'niedziela',
			'Monday'      : 'poniedziałek',
			'Tuesday'     : 'wtorek',
			'Wednesday'   : 'środa',
			'Thursday'    : 'czwartek',
			'Friday'      : 'piątek',
			'Saturday'    : 'sobota',
			'Sun'         : 'nie',
			'Mon'         : 'pon',
			'Tue'         : 'wto',
			'Wed'         : 'śro',
			'Thu'         : 'czw',
			'Fri'         : 'pią',
			'Sat'         : 'sob',

			/******************************** sort variants ********************************/
			'sortnameDirsFirst' : 'po nazwie (foldery pierwsze)', 
			'sortkindDirsFirst' : 'po typie (foldery pierwsze)', 
			'sortsizeDirsFirst' : 'po rozmiarze (foldery pierwsze)', 
			'sortdateDirsFirst' : 'po dacie (foldery pierwsze)', 
			'sortname'          : 'po nazwie', 
			'sortkind'          : 'po typie', 
			'sortsize'          : 'po rozmiarze',
			'sortdate'          : 'po dacie',

			/********************************** messages **********************************/
			'confirmReq'      : 'Wymagane potwierdzenie',
			'confirmRm'       : 'Czy na pewno chcesz usunąć pliki?<br/>Tej operacji nie można cofnąć!',
			'confirmRepl'     : 'Zastąpić stary plik nowym?',
			'apllyAll'        : 'Zastosuj do wszystkich',
			'name'            : 'Nazwa',
			'size'            : 'Rozmiar',
			'perms'           : 'Uprawnienia',
			'modify'          : 'Zmodyfikowany',
			'kind'            : 'Typ',
			'read'            : 'odczyt',
			'write'           : 'zapis',
			'noaccess'        : 'brak dostępu',
			'and'             : 'i',
			'unknown'         : 'nieznany',
			'selectall'       : 'Zaznacz wszystkie pliki',
			'selectfiles'     : 'Zaznacz plik(i)',
			'selectffile'     : 'Zaznacz pierwszy plik',
			'selectlfile'     : 'Zaznacz ostatni plik',
			'viewlist'        : 'Widok listy',
			'viewicons'       : 'Widok ikon',
			'places'          : 'Ulubione',
			'calc'            : 'Oblicz',
			'path'            : 'Ścieżka',
			'aliasfor'        : 'Alias do',
			'locked'          : 'Zablokowany',
			'dim'             : 'Wymiary',
			'files'           : 'Pliki',
			'folders'         : 'Foldery',
			'items'           : 'Elementy',
			'yes'             : 'tak',
			'no'              : 'nie',
			'link'            : 'Odnośnik',
			'searcresult'     : 'Wyniki wyszukiwania',
			'selected'        : 'Zaznaczonych obiektów',
			'about'           : 'Informacje o programie',
			'shortcuts'       : 'Skróty klawiaturowe',
			'help'            : 'Pomoc',
			'webfm'           : 'Menedżer plików sieciowych',
			'ver'             : 'Wersja',
			'protocol'        : 'wersja wydania',
			'homepage'        : 'Strona główna projektu',
			'docs'            : 'Dokumentacja',
			'github'          : 'Obserwuj rozwój projektu na Github',
			'twitter'         : 'Śledź nas na Twitterze',
			'facebook'        : 'Dołącz do nas na Facebooku',
			'team'            : 'Autorzy',
			'chiefdev'        : 'główny programista',
			'developer'       : 'programista',
			'contributor'     : 'współautor',
			'maintainer'      : 'koordynator',
			'translator'      : 'tłumacz',
			'icons'           : 'Ikony',
			'dontforget'      : 'i nie zapomnij zabrać ręcznika',
			'shortcutsof'     : 'Skróty klawiaturowe są wyłączone',
			'dropFiles'       : 'Upuść pliki tutaj',
			'or'              : 'lub',
			'selectForUpload' : 'Wybierz pliki do wysłania',
			'moveFiles'       : 'Przenieś pliki',
			'copyFiles'       : 'Kopiuj pliki',
			'rmFromPlaces'    : 'Usuń z ulubionych',
			'untitled folder' : 'nowy folder',
			'untitled file.txt' : 'nowy plik.txt',
			'aspectRatio'     : 'Zachowaj proporcje',
			'scale'           : 'Skala',
			'width'           : 'Szerokość',
			'height'          : 'Wysokość',
			'mode'            : 'Tryb',
			'resize'          : 'Zmień rozmiar',
			'crop'            : 'Przytnij',
			'rotate'          : 'Obróć',
			'rotate-cw'       : 'Obróć 90° w lewo',
			'rotate-ccw'      : 'Obróć 90° w prawo',
			'degree'          : '°',


			/********************************** mimetypes **********************************/
			'kindUnknown'     : 'Nieznany',
			'kindFolder'      : 'Folder',
			'kindAlias'       : 'Alias',
			'kindAliasBroken' : 'Utracony alias',
			// applications
			'kindApp'         : 'Aplikacja',
			'kindPostscript'  : 'Dokument Postscript',
			'kindMsOffice'    : 'Dokument Office',
			'kindMsWord'      : 'Dokument Word',
			'kindMsExcel'     : 'Dokument Excel',
			'kindMsPP'        : 'Prezentacja PowerPoint',
			'kindOO'          : 'Dokument OpenOffice',
			'kindAppFlash'    : 'Aplikacja Flash',
			'kindPDF'         : 'Dokument przenośny PDF',
			'kindTorrent'     : 'Plik BitTorrent',
			'kind7z'          : 'Archiwum 7z',
			'kindTAR'         : 'Archiwum TAR',
			'kindGZIP'        : 'Archiwum GZIP',
			'kindBZIP'        : 'Archiwum BZIP',
			'kindZIP'         : 'Archiwum ZIP',
			'kindRAR'         : 'Archiwum RAR',
			'kindJAR'         : 'Java JAR file',
			'kindTTF'         : 'Czcionka TrueType',
			'kindOTF'         : 'Czcionka OpenType',
			'kindRPM'         : 'Pakiet RPM',
			// texts
			'kindText'        : 'Dokument tekstowy',
			'kindTextPlain'   : 'Zwykły tekst',
			'kindPHP'         : 'Kod źródłowy PHP',
			'kindCSS'         : 'Kaskadowe arkusze stylów',
			'kindHTML'        : 'Dokument HTML',
			'kindJS'          : 'Kod źródłowy Javascript',
			'kindRTF'         : 'Tekst sformatowany RTF',
			'kindC'           : 'Kod źródłowy C',
			'kindCHeader'     : 'Plik nagłówka C',
			'kindCPP'         : 'Kod źródłowy C++',
			'kindCPPHeader'   : 'Plik nagłówka C++',
			'kindShell'       : 'Skrypt powłoki Unix',
			'kindPython'      : 'Kod źródłowy Python',
			'kindJava'        : 'Kod źródłowy Java',
			'kindRuby'        : 'Kod źródłowy Ruby',
			'kindPerl'        : 'Skrypt Perl',
			'kindSQL'         : 'Kod źródłowy SQL',
			'kindXML'         : 'Dokument XML',
			'kindAWK'         : 'Kod źródłowy AWK',
			'kindCSV'         : 'Tekst rozdzielany przecinkami CSV',
			'kindDOCBOOK'     : 'Dokument Docbook XML',
			// images
			'kindImage'       : 'Obraz',
			'kindBMP'         : 'Obraz BMP',
			'kindJPEG'        : 'Obraz JPEG',
			'kindGIF'         : 'Obraz GIF',
			'kindPNG'         : 'Obraz PNG',
			'kindTIFF'        : 'Obraz TIFF',
			'kindTGA'         : 'Obraz TGA',
			'kindPSD'         : 'Obraz Adobe Photoshop',
			'kindXBITMAP'     : 'Obraz X BitMap',
			'kindPXM'         : 'Obraz Pixelmator',
			// media
			'kindAudio'       : 'Plik dźwiękowy',
			'kindAudioMPEG'   : 'Plik dźwiękowy MPEG',
			'kindAudioMPEG4'  : 'Plik dźwiękowy MPEG-4',
			'kindAudioMIDI'   : 'Plik dźwiękowy MIDI',
			'kindAudioOGG'    : 'Plik dźwiękowy Ogg Vorbis',
			'kindAudioWAV'    : 'Plik dźwiękowy WAV',
			'AudioPlaylist'   : 'Lista odtwarzania MP3',
			'kindVideo'       : 'Plik wideo',
			'kindVideoDV'     : 'Plik wideo DV',
			'kindVideoMPEG'   : 'Plik wideo MPEG',
			'kindVideoMPEG4'  : 'Plik wideo MPEG-4',
			'kindVideoAVI'    : 'Plik wideo AVI',
			'kindVideoMOV'    : 'Plik wideo Quick Time',
			'kindVideoWM'     : 'Plik wideo Windows Media',
			'kindVideoFlash'  : 'Plik wideo Flash',
			'kindVideoMKV'    : 'Plik wideo Matroska',
			'kindVideoOGG'    : 'Plik wideo Ogg'
		}
	}
}
;/**
 * Brazilian Portuguese translation
 * @author Leandro Carvalho <contato@leandrowebdev.net>
 * @version 2013-01-22
 */
if (elFinder && elFinder.prototype && typeof(elFinder.prototype.i18) == 'object') {
	elFinder.prototype.i18.pt_BR = {
		translator : 'Leandro Carvalho &lt;contato@leandrowebdev.net&gt;',
		language   : 'Português',
		direction  : 'ltr',
		dateFormat : 'd M Y H:i', // Mar 13, 2012 05:27 PM
		fancyDateFormat : '$1 H:i', // will produce smth like: Today 12:25 PM
		messages   : {

			/********************************** errors **********************************/
			'error'                : 'Erro',
			'errUnknown'           : 'Erro desconhecido.',
			'errUnknownCmd'        : 'Comando desconhecido.',
			'errJqui'              : 'Configuração inválida do JQuery UI. Verifique os componentes selectable, draggable e droppable incluidos.',
			'errNode'              : 'elFinder requer um elemento DOM para ser criado.',
			'errURL'               : 'Configuração inválida do elFinder! Você deve setar a opção da URL.',
			'errAccess'            : 'Acesso negado.',
			'errConnect'           : 'Incapaz de conectar ao backend.',
			'errAbort'             : 'Conexão abortada.',
			'errTimeout'           : 'Connection timeout.',
			'errNotFound'          : 'Backend não encontrado.',
			'errResponse'          : 'Resposta inválida do backend.',
			'errConf'              : 'Configuração inválida do backend.',
			'errJSON'              : 'Módulo PHP JSON não está instalado.',
			'errNoVolumes'         : 'Não existe nenhum volume legível disponivel.',
			'errCmdParams'         : 'Parâmetro inválido para o comando "$1".',
			'errDataNotJSON'       : 'Dados não estão no formato JSON.',
			'errDataEmpty'         : 'Dados vazios.',
			'errCmdReq'            : 'Requisição do Backend requer nome de comando.',
			'errOpen'              : 'Incapaz de abrir "$1".',
			'errNotFolder'         : 'Objeto não é uma pasta.',
			'errNotFile'           : 'Objeto não é um arquivo.',
			'errRead'              : 'Incapaz de ler "$1".',
			'errWrite'             : 'Incapaz de escrever em "$1".',
			'errPerm'              : 'Permissão negada.',
			'errLocked'            : '"$1" está bloqueado e não pode ser renomeado, movido ou removido.',
			'errExists'            : 'O nome do arquivo "$1" já existe neste local.',
			'errInvName'           : 'Nome do arquivo inválido.',
			'errFolderNotFound'    : 'Pasta não encontrada.',
			'errFileNotFound'      : 'Arquivo não encontrado.',
			'errTrgFolderNotFound' : 'Pasta de destino "$1" não encontrada.',
			'errPopup'             : 'Navegador impediu abertura da janela popup, Para abrir o arquivo desabilite está  opção no navegador.',
			'errMkdir'             : 'Incapaz de criar a pasta "$1".',
			'errMkfile'            : 'Incapaz de criar o arquivo "$1".',
			'errRename'            : 'Incapaz de renomear "$1".',
			'errCopyFrom'          : 'Copia dos arquivos do volume "$1" não permitida.',
			'errCopyTo'            : 'Copia dos arquivos para o volume "$1" não permitida.',
			'errUploadCommon'      : 'Erro no upload.',
			'errUpload'            : 'Incapaz de fazer o upload de "$1".',
			'errUploadNoFiles'     : 'Não foi encontrado nenhum arquivo para upload.',
			'errMaxSize'           : 'Os dados excedem o tamanho máximo permitido.',
			'errFileMaxSize'       : 'Arquivo excede o tamanho máximo permitido.',
			'errUploadMime'        : 'Tipo de arquivo não permitido.',
			'errUploadTransfer'    : '"$1" erro na transferência.', 
			'errSave'              : 'Incapaz de salvar "$1".',
			'errCopy'              : 'Incapaz de copiar "$1".',
			'errMove'              : 'Incapaz de mover "$1".',
			'errCopyInItself'      : 'Incapaz de copiar "$1" nele mesmo.',
			'errRm'                : 'Incapaz de remover "$1".',
			'errExtract'           : 'Incapaz de extrair os arquivos de "$1".',
			'errArchive'           : 'Incapaz de criar o arquivo.',
			'errArcType'           : 'Tipo de arquivo não suportado.',
			'errNoArchive'         : 'Arquivo inválido ou é um tipo sem suporte.',
			'errCmdNoSupport'      : 'Backend não suporta este comando.',
			'errNotUTF8Content'    : 'Arquivo "$1" não está em UTF-8 e não pode ser editado.',  // added 9.11.2011
			'errNetMount'          : 'Habilitar montagem "$1".', // added 17.04.2012
			'errNetMountNoDriver'  : 'Protocolo não suportado.',     // added 17.04.2012
			'errNetMountFailed'    : 'Monagem falhou.',         // added 17.04.2012
			'errNetMountHostReq'   : 'Servidor requerido.', // added 18.04.2012
			'errSessionExpires'    : 'Sua sessão expirou por inatividade',
			'errCreatingTempDir'   : 'Não foi possível criar um diretório temporário: "$1"',
			'errFtpDownloadFile'   : 'Não foi possível fazer o download do arquivo do FTP: "$1"',
			'errFtpUploadFile'     : 'Não foi possível fazer o upload do arquivo para o FTP: "$1"',
			'errFtpMkdir'          : 'Não foi possível criar um diretório remoto no FTP: "$1"',
			'errArchiveExec'       : 'Erro no arquivamento: "$1"',
			'errExtractExec'       : 'Erro na extração dos arquivos: "$1"',
			'cmdsort'              : 'Ordenar',
			'sortkind'             : 'por tipo',
			'sortname'             : 'por nome',
			'sortsize'             : 'por tamanho',
			'sortdate'             : 'por data',
			'sortFoldersFirst'     : 'Pastas primeiro',
			'errUploadFile'        : 'Não foi possível fazer o upload "$1".',

			/******************************* commands names ********************************/
			'cmdarchive'   : 'Criar arquivo',
			'cmdback'      : 'Voltar',
			'cmdcopy'      : 'Copiar',
			'cmdcut'       : 'Cortar',
			'cmddownload'  : 'Baixar',
			'cmdduplicate' : 'Duplicar',
			'cmdedit'      : 'Editar arquivo',
			'cmdextract'   : 'Extrair arquivo de ficheiros',
			'cmdforward'   : 'Avançar',
			'cmdgetfile'   : 'Selecionar arquivos',
			'cmdhelp'      : 'Sobre este software',
			'cmdhome'      : 'Home',
			'cmdinfo'      : 'propriedades',
			'cmdmkdir'     : 'Nova pasta',
			'cmdmkfile'    : 'Novo arquivo de texto',
			'cmdopen'      : 'Abrir',
			'cmdpaste'     : 'Colar',
			'cmdquicklook' : 'Pré-vizualização',
			'cmdreload'    : 'Recarregar',
			'cmdrename'    : 'Renomear',
			'cmdrm'        : 'Deletar',
			'cmdsearch'    : 'Achar arquivos',
			'cmdup'        : 'Ir para o diretório pai',
			'cmdupload'    : 'Fazer upload de arquivo',
			'cmdview'      : 'Vizualizar',
			'cmdresize'    : 'Redimencionar & Rodar',
			'cmdsort'      : 'Ordenar',
			'cmdnetmount'  : 'Montar unidade de rede', // added 18.04.2012

			/*********************************** buttons ***********************************/ 
			'btnClose'  : 'Fechar',
			'btnSave'   : 'Salvar',
			'btnRm'     : 'Remover',
			'btnCancel' : 'Cancelar',
			'btnNo'     : 'Não',
			'btnYes'    : 'Sim',
			'btnMount'  : 'Montar',  // added 18.04.2012

			/******************************** notifications ********************************/
			'ntfopen'     : 'Abrir Pasta',
			'ntffile'     : 'Abrir arquivo',
			'ntfreload'   : 'Recarregar conteudo da pasta',
			'ntfmkdir'    : 'Criar diretório',
			'ntfmkfile'   : 'Criar arquivos',
			'ntfrm'       : 'Deletar arquivos',
			'ntfcopy'     : 'Copiar arquivos',
			'ntfmove'     : 'Mover arquivos',
			'ntfprepare'  : 'Preparando para copiar',
			'ntfrename'   : 'Renomear arquivos',
			'ntfupload'   : 'Subindo arquivos',
			'ntfdownload' : 'Baixando os arquivos',
			'ntfsave'     : 'Slvando os arquivos',
			'ntfarchive'  : 'Criando os arquivos',
			'ntfextract'  : 'Extraindo arquivos',
			'ntfsearch'   : 'Procurando arquivos',
			'ntfsmth'     : 'Fazendo alguma coisa',
			'ntfnetmount' : 'Montando unidade de rede', // added 18.04.2012


			/************************************ dates **********************************/
			'dateUnknown' : 'Desconhecido',
			'Today'       : 'Hoje',
			'Yesterday'   : 'Ontem',
			'Jan'         : 'Jan',
			'Feb'         : 'Fev',
			'Mar'         : 'Mar',
			'Apr'         : 'Abr',
			'May'         : 'Mai',
			'Jun'         : 'Jun',
			'Jul'         : 'Jul',
			'Aug'         : 'Ago',
			'Sep'         : 'Set',
			'Oct'         : 'Out',
			'Nov'         : 'Nov',
			'Dec'         : 'Dez',

			/******************************** sort variants ********************************/
			'sortname'          : 'por nome', 
			'sortkind'          : 'por tipo', 
			'sortsize'          : 'por tam.',
			'sortdate'          : 'por data',
			'sortFoldersFirst'  : 'Pastas primeiro',
			
			/********************************** messages **********************************/
			'confirmReq'      : 'Confirmação requerida',
			'confirmRm'       : 'Você tem certeza que quer remover os arquivos?<br />Isto não pode ser desfeito!',
			'confirmRepl'     : 'Substituir arquivo velho com este novo?',
			'apllyAll'        : 'Aplicar a todos',
			'name'            : 'Nome',
			'size'            : 'Tamanho',
			'perms'           : 'Permissões',
			'modify'          : 'Modificado',
			'kind'            : 'Tipo',
			'read'            : 'Ler',
			'write'           : 'Escrever',
			'noaccess'        : 'Inacessível',
			'and'             : 'e',
			'unknown'         : 'Desconhecido',
			'selectall'       : 'Selecionar todos arquivos',
			'selectfiles'     : 'Selecionar arquivo(s)',
			'selectffile'     : 'Selecionar primeiro arquivo',
			'selectlfile'     : 'Slecionar último arquivo',
			'viewlist'        : 'Exibir como lista',
			'viewicons'       : 'Exibir como ícones',
			'places'          : 'Lugares',
			'calc'            : 'Calcular', 
			'path'            : 'Caminho',
			'aliasfor'        : 'Alias para',
			'locked'          : 'Bloqueado',
			'dim'             : 'Dimesões',
			'files'           : 'Arquivos',
			'folders'         : 'Pastas',
			'items'           : 'Itens',
			'yes'             : 'sim',
			'no'              : 'não',
			'link'            : 'Link',
			'searcresult'     : 'resultados da pesquisa',  
			'selected'        : 'itens selecionados',
			'about'           : 'Sobre',
			'shortcuts'       : 'Atalhos',
			'help'            : 'Ajuda',
			'webfm'           : 'Gerenciador de arquivos web',
			'ver'             : 'Versão',
			'protocolver'     : 'Versão do protocolo',
			'homepage'        : 'Home do projeto',
			'docs'            : 'Documentação',
			'github'          : 'Fork us on Github',
			'twitter'         : 'Siga-nos no twitter',
			'facebook'        : 'Junte-se a nós no Facebook',
			'team'            : 'Time',
			'chiefdev'        : 'Desenvolvedor chefe',
			'developer'       : 'Desenvolvedor',
			'contributor'     : 'Contribuinte',
			'maintainer'      : 'Mantenedor',
			'translator'      : 'Tradutor',
			'icons'           : 'Ícones',
			'dontforget'      : 'e não se esqueça de levar sua toalha',
			'shortcutsof'     : 'Atalhos desabilitados',
			'dropFiles'       : 'Solte os arquivos aqui',
			'or'              : 'ou',
			'selectForUpload' : 'Selecione arquivos para upload',
			'moveFiles'       : 'Mover arquivos',
			'copyFiles'       : 'Copiar arquivos',
			'rmFromPlaces'    : 'Remover de Lugares',
			'aspectRatio'     : 'Manter aspecto',
			'scale'           : 'Tamanho',
			'width'           : 'Largura',
			'height'          : 'Altura',
			'resize'          : 'Redimencionar',
			'crop'            : 'Cortar',
			'rotate'          : 'Rotacionar',
			'rotate-cw'       : 'Girar 90 graus CW',
			'rotate-ccw'      : 'Girar 90 graus CCW',
			'degree'          : '°',
			'netMountDialogTitle' : 'Montar Unidade de rede', // added 18.04.2012
			'protocol'            : 'Protocolo', // added 18.04.2012
			'host'                : 'Servidor', // added 18.04.2012
			'port'                : 'Porta', // added 18.04.2012
			'user'                : 'Usuário', // added 18.04.2012
			'pass'                : 'Senha', // added 18.04.2012

			/********************************** mimetypes **********************************/
			'kindUnknown'     : 'Desconhecio',
			'kindFolder'      : 'Pasta',
			'kindAlias'       : 'Alias',
			'kindAliasBroken' : 'Alias inválido',
			// applications
			'kindApp'         : 'Aplicação',
			'kindPostscript'  : 'Documento Postscript',
			'kindMsOffice'    : 'Documento Microsoft Office',
			'kindMsWord'      : 'Documento Microsoft Word',
			'kindMsExcel'     : 'Documento Microsoft Excel',
			'kindMsPP'        : 'Apresentação Microsoft Powerpoint',
			'kindOO'          : 'Documento Open Office',
			'kindAppFlash'    : 'Aplicação Flash',
			'kindPDF'         : 'Portable Document Format (PDF)',
			'kindTorrent'     : 'Arquivo Bittorrent',
			'kind7z'          : 'Arquivo 7z',
			'kindTAR'         : 'Arquivo TAR',
			'kindGZIP'        : 'Arquivo GZIP',
			'kindBZIP'        : 'Arquivo BZIP',
			'kindZIP'         : 'Arquivo ZIP',
			'kindRAR'         : 'Arquivo RAR',
			'kindJAR'         : 'Arquivo JAR',
			'kindTTF'         : 'True Type font',
			'kindOTF'         : 'Open Type font',
			'kindRPM'         : 'Pacote RPM',
			// texts
			'kindText'        : 'Arquivo de texto',
			'kindTextPlain'   : 'Texto simples',
			'kindPHP'         : 'PHP',
			'kindCSS'         : 'CSS',
			'kindHTML'        : 'Documento HTML',
			'kindJS'          : 'Javascript',
			'kindRTF'         : 'Formato Rich Text',
			'kindC'           : 'C',
			'kindCHeader'     : 'C cabeçalho',
			'kindCPP'         : 'C++',
			'kindCPPHeader'   : 'C++ cabeçalho',
			'kindShell'       : 'Unix shell script',
			'kindPython'      : 'Python',
			'kindJava'        : 'Java',
			'kindRuby'        : 'Ruby',
			'kindPerl'        : 'Perl script',
			'kindSQL'         : 'SQL',
			'kindXML'         : 'Documento XML',
			'kindAWK'         : 'AWK',
			'kindCSV'         : 'Valores separados por vírgula',
			'kindDOCBOOK'     : 'Documento Docbook XML',
			// images
			'kindImage'       : 'Imagem',
			'kindBMP'         : 'Imagem BMP',
			'kindJPEG'        : 'Imagem JPEG',
			'kindGIF'         : 'Imagem GIF',
			'kindPNG'         : 'Imagem PNG',
			'kindTIFF'        : 'Imagem TIFF',
			'kindTGA'         : 'Imagem TGA',
			'kindPSD'         : 'Imagem Adobe Photoshop',
			'kindXBITMAP'     : 'Imagem X bitmap',
			'kindPXM'         : 'Imagem Pixelmator',
			// media
			'kindAudio'       : 'Audio media',
			'kindAudioMPEG'   : 'Audio MPEG',
			'kindAudioMPEG4'  : 'Audio MPEG-4',
			'kindAudioMIDI'   : 'Audio MIDI',
			'kindAudioOGG'    : 'Audio Ogg Vorbis',
			'kindAudioWAV'    : 'Audio WAV',
			'AudioPlaylist'   : 'MP3 playlist',
			'kindVideo'       : 'Video media',
			'kindVideoDV'     : 'DV filme',
			'kindVideoMPEG'   : 'Video MPEG',
			'kindVideoMPEG4'  : 'Video MPEG-4',
			'kindVideoAVI'    : 'Video AVI',
			'kindVideoMOV'    : 'Quick Time movie',
			'kindVideoWM'     : 'Video Windows Media',
			'kindVideoFlash'  : 'Video Flash',
			'kindVideoMKV'    : 'Video Matroska',
			'kindVideoOGG'    : 'Video Ogg'
		}
	}
}
;/**
 * Russian translation
 * @author Dmitry "dio" Levashov <dio@std42.ru>
 * @version 2011-07-15
 */
if (elFinder && elFinder.prototype && typeof(elFinder.prototype.i18) == 'object') {
	elFinder.prototype.i18.ru = {
		translator : 'Dmitry "dio" Levashov &lt;dio@std42.ru&gt;',
		language   : 'Русский язык',
		direction  : 'ltr',
		dateFormat : 'd M Y H:i',
		fancyDateFormat : '$1 H:i',
		messages   : {

			/********************************** errors **********************************/
			'error'                : 'Ошибка',
			'errUnknown'           : 'Неизвестная ошибка.',
			'errUnknownCmd'        : 'Неизвестная комманда.',
			'errJqui'              : 'Отсутствуют необходимые компоненты jQuery UI - selectable, draggable и droppable.',
			'errNode'              : 'Отсутствует DOM элемент для инициализации elFinder.',
			'errURL'               : 'Некорректная настройка. Необходимо указать URL сервера.',
			'errAccess'            : 'Доступ запрещен.',
			'errConnect'           : 'Не удалось соединиться с сервером.',
			'errAbort'             : 'Соединение прервано.',
			'errTimeout'           : 'Таймаут соединения.',
			'errNotFound'          : 'Сервер не найден.',
			'errResponse'          : 'Некорректный ответ сервера.',
			'errConf'              : 'Некорректная настройка сервера.',
			'errJSON'              : 'Модуль PHP JSON не установлен.',
			'errNoVolumes'         : 'Отсутствуют корневые директории достуные для чтения.',
			'errCmdParams'         : 'Некорректные параметры комманды "$1".',
			'errDataNotJSON'       : 'Данные не формате JSON.',
			'errDataEmpty'         : 'Данные отсутствуют.',
			'errCmdReq'            : 'Для запроса к серверу необходимо указать имя комманды.',
			'errOpen'              : 'Не удалось открыть "$1".',
			'errNotFolder'         : 'Объект не является папкой.',
			'errNotFile'           : 'Объект не является файлом.',
			'errRead'              : 'Ошибка чтения "$1".',
			'errWrite'             : 'Ошибка записи "$1".',
			'errPerm'              : 'Доступ запрещен.',
			'errLocked'            : '"$1" защищен и не может быть переименован, перемещен или удален.',
			'errExists'            : 'В папке уже существует объект с именем "$1".',
			'errInvName'           : 'Недопустимое имя файла.',
			'errFolderNotFound'    : 'Папка не найдена.',
			'errFileNotFound'      : 'Файл не найден.',
			'errTrgFolderNotFound' : 'Целевая папка "$1" не найдена.',
			'errPopup'             : 'Браузер заблокировал открытие нового окна. Чтобы окрыть файл, измените настройки браузера.',
			'errMkdir'             : 'Ошибка создания папки "$1".',
			'errMkfile'            : 'Ошибка создания файла "$1".',
			'errRename'            : 'Ошибка переименования "$1".',
			'errCopyFrom'          : 'Копирование из корневой директории "$1" запрещено.',
			'errCopyTo'            : 'Копирование в корневую директорию "$1" запрещено.',
			'errUploadCommon'      : 'Ошибка загрузки файлов.',
			'errUpload'            : 'Ошибка загрузки "$1".',
			'errUploadNoFiles'     : 'Отсутствуют загруженые файлы.',
			'errMaxSize'           : 'Превышен допустимый размер загружаемых файлов.',
			'errFileMaxSize'       : 'Размер файла превышает допустимый.',
			'errUploadMime'        : 'Недопустимый тип файла.',
			'errUploadTransfer'    : 'Ошибка передачи файла "$1".', 
			'errSave'              : 'Ошибка сохранения "$1".',
			'errCopy'              : 'Ошибка копирования "$1".',
			'errMove'              : 'Ошибка перемещения "$1".',
			'errCopyInItself'      : 'Невозможно скопировать "$1" в самого себя.',
			'errRm'                : 'Ошибка удаления "$1".',
			'errExtract'           : 'Ошибка извлечения файлов из архива "$1".',
			'errArchive'           : 'Ошибка создания архива.',
			'errArcType'           : 'Неподдерживаемый тип архива.',
			'errNoArchive'         : 'Файл не является архивом допустимого типа.',
			'errCmdNoSupport'      : 'Сервер не поддерживает эту комманду.',
			'errReplByChild'       : 'Невозможно заменить папку "$1" содержащимся в ней объектом.',
			'errArcSymlinks'       : 'По соображениям безопасности запрещена распаковка архивов, содержащих ссылки (symlinks) или файлы с недопустимыми именами.', // edited 24.06.2012
			'errArcMaxSize'        : 'Размер файлов в архиве превышает максимально разрешенный.',
			'errResize'            : 'Не удалось изменить размер "$1".',
			'errUsupportType'      : 'Неподдерживаемый тип файла.',
			'errNotUTF8Content'    : 'Файл "$1" содержит текст в кодировке отличной от UTF-8 и не может быть отредактирован.',  // added 9.11.2011
			'errNetMount'          : 'Не удалось подключить "$1".',    // added 17.04.2012
			'errNetMountNoDriver'  : 'Неподдерживаемый протокол.',     // added 17.04.2012
			'errNetMountFailed'    : 'Ошибка монтирования.',           // added 17.04.2012
			'errNetMountHostReq'   : 'Host required.', // added 18.04.2012
			'errSessionExpires'    : 'Сессия была завершена так как превышено время отсутствия активности',
			'errCreatingTempDir'   : 'Ошибка при создании временной директории: "$1"',
			'errFtpDownloadFile'   : 'Ошибка при скачивании файла с FTP: "$1"',
			'errFtpUploadFile'     : 'Ошибка при загрузке файла на FTP: "$1"',
			'errFtpMkdir'          : 'Ошибка при создании директории на FTP: "$1"',
			'errArchiveExec'       : 'Ошибка при выполнении архивации: "$1"',
			'errExtractExec'       : 'Ошибка при выполнении распаковки: "$1"',

			'errUploadFile'        : 'Невозможно загрузить файл "$1"',


			/******************************* commands names ********************************/
			'cmdarchive'   : 'Создать архив',
			'cmdback'      : 'Назад',
			'cmdcopy'      : 'Копировать',
			'cmdcut'       : 'Вырезать',
			'cmddownload'  : 'Скачать',
			'cmdduplicate' : 'Сделать копию',
			'cmdedit'      : 'Редактировать',
			'cmdextract'   : 'Распаковать архив',
			'cmdforward'   : 'Вперед',
			'cmdgetfile'   : 'Выбрать',
			'cmdhelp'      : 'О программе',
			'cmdhome'      : 'Домой',
			'cmdinfo'      : 'Свойства',
			'cmdmkdir'     : 'Новая папка',
			'cmdmkfile'    : 'Новый файл',
			'cmdopen'      : 'Открыть',
			'cmdpaste'     : 'Вставить',
			'cmdquicklook' : 'Быстрый просмотр',
			'cmdreload'    : 'Обновить',
			'cmdrename'    : 'Переименовать',
			'cmdrm'        : 'Удалить',
			'cmdsearch'    : 'Поиск',
			'cmdup'        : 'Наверх',
			'cmdupload'    : 'Загрузить файлы',
			'cmdview'      : 'Вид',
			'cmdresize'    : 'Размер изображения',
			'cmdsort'      : 'Сортировать',
			
			/*********************************** buttons ***********************************/ 
			'btnClose'  : 'Закрыть',
			'btnSave'   : 'Сохранить',
			'btnRm'     : 'Удалить',
			'btnCancel' : 'Отмена',
			'btnApply'  : 'Применить',
			'btnNo'     : 'Нет',
			'btnYes'    : 'Да',
			'btnMount'  : 'Подключить',  // added 18.04.2012
			/******************************** notifications ********************************/
			'ntfopen'     : 'Открытие папки',
			'ntffile'     : 'Открытие файла',
			'ntfreload'   : 'Обновление текущей папки',
			'ntfmkdir'    : 'Создание папки',
			'ntfmkfile'   : 'Создание файла',
			'ntfrm'       : 'Удаление файлов',
			'ntfcopy'     : 'Копирование файлов',
			'ntfmove'     : 'Перемещение файлов',
			'ntfprepare'  : 'Подготовка к копированию',
			'ntfrename'   : 'Переименование файлов',
			'ntfupload'   : 'Загрузка файлов',
			'ntfdownload' : 'Скачивание файлов',
			'ntfsave'     : 'Сохранение файлов',
			'ntfarchive'  : 'Создание архива',
			'ntfextract'  : 'Распаковка архива',
			'ntfsearch'   : 'Поиск файлов',
			'ntfsmth'     : 'Занят важным делом',
			'ntfnetmount' : 'Монтирую сетевой диск', // added 18.04.2012

			/************************************ dates **********************************/
			'dateUnknown' : 'Незвестно',
			'Today'       : 'Сегодня',
			'Yesterday'   : 'Вчера',
			'Jan'         : 'Янв',
			'Feb'         : 'Фев',
			'Mar'         : 'Мар',
			'Apr'         : 'Апр',
			'May'         : 'Май',
			'Jun'         : 'Июнь',
			'Jul'         : 'Июль',
			'Aug'         : 'Авг',
			'Sep'         : 'Сен',
			'Oct'         : 'Окт',
			'Nov'         : 'Ноя',
			'Dec'         : 'Дек',
			'January'     : 'Январь',
			'February'    : 'Февраль',
			'March'       : 'Март',
			'April'       : 'Апрель',
			'May'         : 'Май',
			'June'        : 'Июнь',
			'July'        : 'Июль',
			'August'      : 'Август',
			'September'   : 'Сентябрь',
			'October'     : 'Октябрь',
			'November'    : 'Ноябрь',
			'December'    : 'Декабрь',
			'Sunday'      : 'Воскресенье', 
			'Monday'      : 'Понедельник', 
			'Tuesday'     : 'Вторник', 
			'Wednesday'   : 'Среда', 
			'Thursday'    : 'Четверг', 
			'Friday'      : 'Пятница', 
			'Saturday'    : 'Суббота',
			'Sun'         : 'Вск', 
			'Mon'         : 'Пнд', 
			'Tue'         : 'Втр', 
			'Wed'         : 'Срд', 
			'Thu'         : 'Чтв', 
			'Fri'         : 'Птн', 
			'Sat'         : 'Сбт',

			/******************************** sort variants ********************************/
			'sortname'          : 'по имени', 
			'sortkind'          : 'по типу', 
			'sortsize'          : 'по размеру',
			'sortdate'          : 'по дате',
			'sortFoldersFirst'  : 'Папки в начале',

			/********************************** messages **********************************/
			'confirmReq'      : 'Необходимо подтверждение.',
			'confirmRm'       : 'Хотите удалить файлы?<br>Действие необратимо.',
			'confirmRepl'     : 'Заменить старый файл новым?',
			'apllyAll'        : 'для всех',
			'name'            : 'Имя файла',
			'size'            : 'Размер',
			'perms'           : 'Доступ',
			'modify'          : 'Изменен',
			'kind'            : 'Тип',
			'read'            : 'чтение',
			'write'           : 'запись',
			'noaccess'        : 'нет доступа',
			'and'             : 'и',
			'unknown'         : 'неизвестно',
			'selectall'       : 'Выбрать все файлы',
			'selectfiles'     : 'Выбрать файл(ы)',
			'selectffile'     : 'Выбрать первый файл',
			'selectlfile'     : 'Выбрать последний файл',
			'viewlist'        : 'В виде списка',
			'viewicons'       : 'В виде иконок',
			'places'          : 'Избранное',
			'calc'            : 'вычисляю', 
			'path'            : 'Путь',
			'aliasfor'        : 'Указывает на',
			'locked'          : 'Защита',
			'dim'             : 'Разрешение',
			'files'           : 'Файлы',
			'folders'         : 'Папки',
			'items'           : 'Объекты',
			'yes'             : 'да',
			'no'              : 'нет',
			'link'            : 'Ссылка',
			'searcresult'     : 'Результаты поиска',  
			'selected'        : 'выбрано',
			'about'           : 'О программе',
			'shortcuts'       : 'Горячие клавиши',
			'help'            : 'Помощь',
			'webfm'           : 'Файловый менеджер для web',
			'ver'             : 'Версия',
			'protocolver'     : 'версия протокола',
			'homepage'        : 'Сайт проекта',
			'docs'            : 'Документация',
			'github'          : 'Fork us on Github',
			'twitter'         : 'Follow us in twitter',
			'facebook'        : 'Join us on facebook',
			'team'            : 'Авторы',
			'chiefdev'        : 'ведущий разработчик',
			'developer'       : 'разработчик',
			'contributor'     : 'участник',
			'maintainer'      : 'сопровождение проекта',
			'translator'      : 'переводчик',
			'icons'           : 'Иконки',
			'dontforget'      : 'и не забудьте взять своё полотенце',
			'shortcutsof'     : 'Горячие клавиши отключены',
			'dropFiles'       : 'Бросить файлы',
			'or'              : 'или',
			'selectForUpload' : 'Выбрать файлы для загрузки',
			'moveFiles'       : 'Перемещение файлов',
			'copyFiles'       : 'Копирование файлов',
			'rmFromPlaces'    : 'Удалить из избранного',
			'untitled folder' : 'новая папка',
			'untitled file.txt' : 'новый файл.txt',
			'aspectRatio'     : 'Сохранять пропорции',
			'scale'           : 'Масштаб',
			'width'           : 'Ширина',
			'height'          : 'Высота',
			'resize'          : 'Размер',
			'crop'            : 'Кадрировать',
			'rotate'          : 'Поворот',
			'rotate-cw'       : 'Поворот на 90 градусов по часовой стрелке',
			'rotate-ccw'      : 'Поворот на 90 градусов против часовой стрелке',
			'degree'          : '°',
			'netMountDialogTitle' : 'Подключить сетевой диск', // added 18.04.2012
			'protocol'            : 'Протокол', // added 18.04.2012
			'host'                : 'Хост', // added 18.04.2012
			'port'                : 'Порт', // added 18.04.2012
			'user'                : 'Пользователь', // added 18.04.2012
			'pass'                : 'Пароль', // added 18.04.2012
			/********************************** mimetypes **********************************/
			'kindUnknown'     : 'Неизвестный',
			'kindFolder'      : 'Папка',
			'kindAlias'       : 'Ссылка',
			'kindAliasBroken' : 'Битая ссылка',
			// applications
			'kindApp'         : 'Приложение',
			'kindPostscript'  : 'Документ Postscript',
			'kindMsOffice'    : 'Документ Microsoft Office',
			'kindMsWord'      : 'Документ Microsoft Word',
			'kindMsExcel'     : 'Документ Microsoft Excel',
			'kindMsPP'        : 'Презентация Microsoft Powerpoint',
			'kindOO'          : 'Документ Open Office',
			'kindAppFlash'    : 'Приложение Flash',
			'kindPDF'         : 'Документ PDF',
			'kindTorrent'     : 'Файл Bittorrent',
			'kind7z'          : 'Архив 7z',
			'kindTAR'         : 'Архив TAR',
			'kindGZIP'        : 'Архив GZIP',
			'kindBZIP'        : 'Архив BZIP',
			'kindZIP'         : 'Архив ZIP',
			'kindRAR'         : 'Архив RAR',
			'kindJAR'         : 'Файл Java JAR',
			'kindTTF'         : 'Шрифт True Type',
			'kindOTF'         : 'Шрифт Open Type',
			'kindRPM'         : 'Пакет RPM',
			// texts
			'kindText'        : 'Текстовый документ',
			'kindTextPlain'   : 'Простой текст',
			'kindPHP'         : 'Исходник PHP',
			'kindCSS'         : 'Таблицы стилей CSS',
			'kindHTML'        : 'Документ HTML',
			'kindJS'          : 'Исходник Javascript',
			'kindRTF'         : 'Rich Text Format',
			'kindC'           : 'Исходник C',
			'kindCHeader'     : 'Заголовочный файл C',
			'kindCPP'         : 'Исходник C++',
			'kindCPPHeader'   : 'Заголовочный файл C++',
			'kindShell'       : 'Unix shell script',
			'kindPython'      : 'Исходник Python',
			'kindJava'        : 'Исходник Java',
			'kindRuby'        : 'Исходник Ruby',
			'kindPerl'        : 'Исходник Perl',
			'kindSQL'         : 'Исходник SQL',
			'kindXML'         : 'XML document',
			'kindAWK'         : 'Исходник AWK',
			'kindCSV'         : 'Текст с разделителями',
			'kindDOCBOOK'     : 'Документ Docbook XML',
			// images
			'kindImage'       : 'Изображение',
			'kindBMP'         : 'Изображение BMP',
			'kindJPEG'        : 'Изображение JPEG',
			'kindGIF'         : 'Изображение GIF',
			'kindPNG'         : 'Изображение PNG',
			'kindTIFF'        : 'Изображение TIFF',
			'kindTGA'         : 'Изображение TGA',
			'kindPSD'         : 'Изображение Adobe Photoshop',
			'kindXBITMAP'     : 'Изображение X bitmap',
			'kindPXM'         : 'Изображение Pixelmator',
			// media
			'kindAudio'       : 'Аудио файл',
			'kindAudioMPEG'   : 'Аудио MPEG',
			'kindAudioMPEG4'  : 'Аудио MPEG-4',
			'kindAudioMIDI'   : 'Аудио MIDI',
			'kindAudioOGG'    : 'Аудио Ogg Vorbis',
			'kindAudioWAV'    : 'Аудио WAV',
			'AudioPlaylist'   : 'Плейлист MP3',
			'kindVideo'       : 'Видео файл',
			'kindVideoDV'     : 'Видео DV',
			'kindVideoMPEG'   : 'Видео MPEG',
			'kindVideoMPEG4'  : 'Видео MPEG-4',
			'kindVideoAVI'    : 'Видео AVI',
			'kindVideoMOV'    : 'Видео Quick Time',
			'kindVideoWM'     : 'Видео Windows Media',
			'kindVideoFlash'  : 'Видео Flash',
			'kindVideoMKV'    : 'Видео Matroska',
			'kindVideoOGG'    : 'Видео Ogg'
			,'volume_files' : 'Файлы '
		}
	}
}


 

;/**
 * Slovak translation
 * @author Jakub Ďuraš <jkblmr@gmail.com>
 * @version 2013-7-24
 */
if (elFinder && elFinder.prototype && typeof(elFinder.prototype.i18) == 'object') {
	elFinder.prototype.i18.sk = {
		translator : 'Jakub Ďuraš &lt;jkblmr@gmail.com&gt;',
		language   : 'slovenčina',
		direction  : 'ltr',
		dateFormat : 'd.m.Y H:i',
		fancyDateFormat : '$1 H:i',
		messages   : {
			
			/********************************** errors **********************************/
			'error'                : 'Chyba',
			'errUnknown'           : 'Neznáma chyba.',
			'errUnknownCmd'        : 'Neznámy príkaz.',
			'errJqui'              : 'Nesprávna jQuery UI konfigurácia. Selectable, draggable a droppable musia byť načítané.',
			'errNode'              : 'elFinder vyžaduje vytvorenie DOM Elementu.',
			'errURL'               : 'Nesprávna elFinder konfigurácia! URL nie je definovaná.',
			'errAccess'            : 'Prístup zamietnutý.',
			'errConnect'           : 'Nepodarilo sa pripojiť do backendu.',
			'errAbort'             : 'Pripojenie zrušené.',
			'errTimeout'           : 'Vypršal limit pripojenia.',
			'errNotFound'          : 'Backend nenájdený.',
			'errResponse'          : 'Nesprávna backend odpoveď.',
			'errConf'              : 'Nesprávna backend konfigurácia.',
			'errJSON'              : 'Požadovaný PHP JSON modul nie je nainštalovaný.',
			'errNoVolumes'         : 'Nie je dostupné žiadne čitateľné médium.',
			'errCmdParams'         : 'Nesprávne parametre pre príkaz "$1".',
			'errDataNotJSON'       : 'Dáta nie sú formátu JSON.',
			'errDataEmpty'         : 'Prázdne dáta.',
			'errCmdReq'            : 'Backend požiadavka požaduje meno príkazu.',
			'errOpen'              : 'Nie je možné otvoriť súbor "$1".',
			'errNotFolder'         : 'Objekt nie je priečinok.',
			'errNotFile'           : 'Objekt nie je súbor.',
			'errRead'              : 'Nie je možné prečítať súbor "$1".',
			'errWrite'             : 'Nie je možné písať do súboru "$1".',
			'errPerm'              : 'Nepovolený prístup.',
			'errLocked'            : '"$1" je uzamknutý, a nemôže byť premenovaný, presunutý alebo odstránený.',
			'errExists'            : 'Súbor s menom "$1" už existuje.',
			'errInvName'           : 'Nesprávne meno súboru.',
			'errFolderNotFound'    : 'Priečinok nenájdený.',
			'errFileNotFound'      : 'Súbor nenájdený.',
			'errTrgFolderNotFound' : 'Zvolený priečinok "$1" nenájdený.',
			'errPopup'             : 'Prehliadač zablokoval otvorenie vyskakovacieho okna. Pre otvorenie súboru povoľte vyskakovacie okná.',
			'errMkdir'             : 'Nie je možné vytvoriť priečinok "$1".',
			'errMkfile'            : 'Nie je možné vytvoriť súbor "$1".',
			'errRename'            : 'Nie je možné premenovať "$1".',
			'errCopyFrom'          : 'Kopírovanie súborov z média "$1" nie je povolené.',
			'errCopyTo'            : 'Kopírovanie súborov na médium "$1" nie je povolené.',
			'errUploadCommon'      : 'Problém s nahrávaním.',
			'errUpload'            : 'Nie je možné nahrať "$1".',
			'errUploadNoFiles'     : 'Žiadne súbory neboli nájdené na nahranie.',
			'errMaxSize'           : 'Dáta prekračujú maximálnu povolenú veľkosť.',
			'errFileMaxSize'       : 'Súbor prekračuje maximálnu povolenú veľkosť.',
			'errUploadMime'        : 'Nepovolený typ súboru.',
			'errUploadTransfer'    : 'Problém s nahrávaním "$1".', 
			'errSave'              : 'Nie je možné uložiť "$1".',
			'errCopy'              : 'Nie je možné kopíropvať "$1".',
			'errMove'              : 'Nie je možné preniesť "$1".',
			'errCopyInItself'      : 'Nie je možné kopírovať "$1" do seba.',
			'errRm'                : 'Nie je možné vymazať "$1".',
			'errExtract'           : 'Nie je možné extrahovať súbory z "$1".',
			'errArchive'           : 'Nie je možné vytvoriť archív.',
			'errArcType'           : 'Nepodporovaný typ archívu.',
			'errNoArchive'         : 'Súbor nie je archívom, alebo je nepodporovaného typu.',
			'errCmdNoSupport'      : 'Backend nepodporuje tento príkaz.',
			'errReplByChild'       : 'Priečinok “$1” nemôže byť nahradený položkou, ktorú už obsahuje.',
			'errArcSymlinks'       : 'Z bezpečnostných dôvodov bolo zakázané extrahovanie archívov obsahujúcich symlinky, alebo súborov s nepovolenými menami.', // edited 24.06.2012
			'errArcMaxSize'        : 'Súbory archívu prekračujú maximálnu povolenú veľkosť.',
			'errResize'            : 'Nie je možné zmeniť veľkost "$1".',
			'errUsupportType'      : 'Nepodporovaný typ súboru.',
			'errNotUTF8Content'    : 'Súbor "$1" nemá obsah kódovaný v UTF-8, a nemôže byť upravený.',  // added 9.11.2011
			'errNetMount'          : 'Nie je možné pripojiť "$1".',     // added 17.04.2012
			'errNetMountNoDriver'  : 'Nepodporovaný protokol.',     // added 17.04.2012
			'errNetMountFailed'    : 'Pripájanie zlyhalo.',             // added 17.04.2012
			'errNetMountHostReq'   : 'Host je požadovaný.', // added 18.04.2012
			/******************************* commands names ********************************/
			'cmdarchive'   : 'Vytvoriť archív',
			'cmdback'      : 'Späť',
			'cmdcopy'      : 'Kopírovať',
			'cmdcut'       : 'Vystrihnúť',
			'cmddownload'  : 'Stiahnuť',
			'cmdduplicate' : 'Duplikovať',
			'cmdedit'      : 'Upraviť súbor',
			'cmdextract'   : 'Extrahovať súbory z archívu',
			'cmdforward'   : 'Ďalej',
			'cmdgetfile'   : 'Zvoliť súbory',
			'cmdhelp'      : 'O tomto softvéri',
			'cmdhome'      : 'Domov',
			'cmdinfo'      : 'Získať info',
			'cmdmkdir'     : 'Nový priečinok',
			'cmdmkfile'    : 'Nový textový súbor',
			'cmdopen'      : 'Otvoriť',
			'cmdpaste'     : 'Vložiť',
			'cmdquicklook' : 'Náhľad',
			'cmdreload'    : 'Obnoviť',
			'cmdrename'    : 'Premenovať',
			'cmdrm'        : 'Vymazať',
			'cmdsearch'    : 'Nájsť súbory',
			'cmdup'        : 'Prejsť do nadradeného priečinka',
			'cmdupload'    : 'Nahrať súbory',
			'cmdview'      : 'Pozrieť',
			'cmdresize'    : 'Zmeniť veľkosť obrázku',
			'cmdsort'      : 'Zoradiť',
			
			/*********************************** buttons ***********************************/ 
			'btnClose'  : 'Zavrieť',
			'btnSave'   : 'Uložiť',
			'btnRm'     : 'Vymazať',
			'btnApply'  : 'Použiť',
			'btnCancel' : 'Zrušiť',
			'btnNo'     : 'Nie',
			'btnYes'    : 'Áno',
			
			/******************************** notifications ********************************/
			'ntfopen'     : 'Otváranie priečinka',
			'ntffile'     : 'Otváranie súboru',
			'ntfreload'   : 'Znovu-načítanie obsahu priečinka',
			'ntfmkdir'    : 'Vytváranie priečinka',
			'ntfmkfile'   : 'Vytváranie súborov',
			'ntfrm'       : 'Vymazanie súborov',
			'ntfcopy'     : 'Kopírovanie súborov',
			'ntfmove'     : 'Premiestnenie súborov',
			'ntfprepare'  : 'Príprava na kopírovanie súborov',
			'ntfrename'   : 'Premenovanie súborov',
			'ntfupload'   : 'Upload súborov',
			'ntfdownload' : 'Download súborov',
			'ntfsave'     : 'Uloženie súborov',
			'ntfarchive'  : 'Vytváranie archívu',
			'ntfextract'  : 'Extrahovanie súborov z archívu',
			'ntfsearch'   : 'Prehľadávanie súborov',
			'ntfsmth'     : 'Nejaká činnosť >_<',
			'ntfloadimg'  : 'Nahrávanie obrázka',
			'ntfnetmount' : 'Pripájanie sieťového média', // added 18.04.2012
			
			/************************************ dates **********************************/
			'dateUnknown' : 'neznámy',
			'Today'       : 'Dnes',
			'Yesterday'   : 'Včera',
			'Jan'         : 'Jan',
			'Feb'         : 'Feb',
			'Mar'         : 'Mar',
			'Apr'         : 'Apr',
			'May'         : 'Maj',
			'Jun'         : 'Jun',
			'Jul'         : 'Júl',
			'Aug'         : 'Aug',
			'Sep'         : 'Sep',
			'Oct'         : 'Okt',
			'Nov'         : 'Nov',
			'Dec'         : 'Dec',
			'January'     : 'Január',
			'February'    : 'Február',
			'March'       : 'Marec',
			'April'       : 'Apríl',
			'May'         : 'Máj',
			'June'        : 'Jún',
			'July'        : 'Júl',
			'August'      : 'August',
			'September'   : 'September',
			'October'     : 'Október',
			'November'    : 'November',
			'December'    : 'December',
			'Sunday'      : 'Nedeľa', 
			'Monday'      : 'Pondelok', 
			'Tuesday'     : 'Utorok', 
			'Wednesday'   : 'Streda', 
			'Thursday'    : 'Štvrtok', 
			'Friday'      : 'Piatok', 
			'Saturday'    : 'Sobota',
			'Sun'         : 'Ned', 
			'Mon'         : 'Pon', 
			'Tue'         : 'Ut', 
			'Wed'         : 'Str', 
			'Thu'         : 'Štv', 
			'Fri'         : 'Pia', 
			'Sat'         : 'Sob',
			/******************************** sort variants ********************************/
			'sortname'          : 'podľa mena', 
			'sortkind'          : 'podľa druhu', 
			'sortsize'          : 'podľa veľkosti',
			'sortdate'          : 'podľa dátumu',
			'sortFoldersFirst'  : 'Najskôr Priečinky', // added 22.06.2012
			
			/********************************** messages **********************************/
			'confirmReq'      : 'Očakávam potvrdenie',
			'confirmRm'       : 'Určite chcete vymazať súbory?<br/>Nebude sa to dať vrátiť späť!',
			'confirmRepl'     : 'Nahradiť starý súbor novým?',
			'apllyAll'        : 'Použiť na všetky',
			'name'            : 'Meno',
			'size'            : 'Veľkosť',
			'perms'           : 'Povolenia',
			'modify'          : 'Zmenené',
			'kind'            : 'Druh',
			'read'            : 'čítať',
			'write'           : 'zapisovať',
			'noaccess'        : 'bez prístupu',
			'and'             : 'a',
			'unknown'         : 'neznámy',
			'selectall'       : 'Vybrať všetky súbory',
			'selectfiles'     : 'Vybrať súbor(y)',
			'selectffile'     : 'Vybrať prvý súbor',
			'selectlfile'     : 'Vybrať posledný súbor',
			'viewlist'        : 'Zoznam',
			'viewicons'       : 'Ikony',
			'places'          : 'Miesta',
			'calc'            : 'Prepočítavanie', 
			'path'            : 'Cesta',
			'aliasfor'        : 'Alias pre',
			'locked'          : 'Uzamknuté',
			'dim'             : 'Rozmery',
			'files'           : 'Súbory',
			'folders'         : 'Priečinky',
			'items'           : 'Položky',
			'yes'             : 'áno',
			'no'              : 'nie',
			'link'            : 'Odkaz',
			'searcresult'     : 'Výsledky hľadania',  
			'selected'        : 'zvolené položky',
			'about'           : 'O aplikácii',
			'shortcuts'       : 'Skratky',
			'help'            : 'Pomoc',
			'webfm'           : 'Webový správca súborov',
			'ver'             : 'Verzia',
			'protocolver'     : 'verzia protokolu',
			'homepage'        : 'Domovská stránka',
			'docs'            : 'Dokumentácia',
			'github'          : 'Pozri nás na Githube',
			'twitter'         : 'Nasleduj nás na Twitteri',
			'facebook'        : 'Pripoj sa k nám na Facebooku',
			'team'            : 'Tím',
			'chiefdev'        : 'Hlavný vývojár',
			'developer'       : 'vývojár',
			'contributor'     : 'prispievateľ',
			'maintainer'      : 'správca',
			'translator'      : 'prekladateľ',
			'icons'           : 'Ikony',
			'dontforget'      : 'and don\'t forget to take your towel',
			'shortcutsof'     : 'Skratky zakázané',
			'dropFiles'       : 'Sem pustite súbory',
			'or'              : 'alebo',
			'selectForUpload' : 'Zvoliť súbory na upload',
			'moveFiles'       : 'Premiestniť súbory',
			'copyFiles'       : 'Kopírovať súbory',
			'rmFromPlaces'    : 'Odstrániť z umiestnení',
			'untitled folder' : 'nepomenovaný priečinok',
			'untitled file.txt' : 'untitled file.txt',
			'aspectRatio'     : 'Pomer zobrazenia',
			'scale'           : 'Mierka',
			'width'           : 'Šírka',
			'height'          : 'Výška',
			'mode'            : 'Mód',
			'resize'          : 'Zmeniť veľkosť',
			'crop'            : 'Zrezať',
			'rotate'          : 'Otočiť',
			'rotate-cw'       : 'Otočiť o 90 stupňov (v smere h.r.)',
			'rotate-ccw'      : 'Otočiť o 90 stupňov (proti smeru)',
			'degree'          : 'Stupne',
			'netMountDialogTitle' : 'Pripojiť sieťové médium', // added 18.04.2012
			'protocol'            : 'Protokol', // added 18.04.2012
			'host'                : 'Host', // added 18.04.2012
			'port'                : 'Port', // added 18.04.2012
			'user'                : 'Užívateľ', // added 18.04.2012
			'pass'                : 'Heslo', // added 18.04.2012
			/********************************** mimetypes **********************************/
			'kindUnknown'     : 'Neznámy',
			'kindFolder'      : 'Priečinok',
			'kindAlias'       : 'Alias',
			'kindAliasBroken' : 'Porušený alias',
			// applications
			'kindApp'         : 'Aplikácia',
			'kindPostscript'  : 'Postscript dokument',
			'kindMsOffice'    : 'Microsoft Office dokument',
			'kindMsWord'      : 'Microsoft Word dokument',
			'kindMsExcel'     : 'Microsoft Excel dokument',
			'kindMsPP'        : 'Microsoft Powerpoint prezentácia',
			'kindOO'          : 'Open Office dokument',
			'kindAppFlash'    : 'Flashová aplikácia',
			'kindPDF'         : 'Portable Document Format (PDF)',
			'kindTorrent'     : 'Bittorrent súbor',
			'kind7z'          : '7z archív',
			'kindTAR'         : 'TAR archív',
			'kindGZIP'        : 'GZIP archív',
			'kindBZIP'        : 'BZIP archív',
			'kindZIP'         : 'ZIP archív',
			'kindRAR'         : 'RAR archív',
			'kindJAR'         : 'Java JAR súbor',
			'kindTTF'         : 'True Type font',
			'kindOTF'         : 'Open Type font',
			'kindRPM'         : 'RPM balík',
			// texts
			'kindText'        : 'Textový document',
			'kindTextPlain'   : 'Obyčajný text',
			'kindPHP'         : 'PHP zdrojový kód',
			'kindCSS'         : 'Cascading style sheet (CSS)',
			'kindHTML'        : 'HTML dokument',
			'kindJS'          : 'Javascript zdrojový kód',
			'kindRTF'         : 'Rich Text Format',
			'kindC'           : 'C zdrojový kód',
			'kindCHeader'     : 'C header zdrojový kód',
			'kindCPP'         : 'C++ zdrojový kód',
			'kindCPPHeader'   : 'C++ header zdrojový kód',
			'kindShell'       : 'Unix shell script',
			'kindPython'      : 'Python zdrojový kód',
			'kindJava'        : 'Java zdrojový kód',
			'kindRuby'        : 'Ruby zdrojový kód',
			'kindPerl'        : 'Perl zdrojový kód',
			'kindSQL'         : 'SQL zdrojový kód',
			'kindXML'         : 'XML dokument',
			'kindAWK'         : 'AWK zdrojový kód',
			'kindCSV'         : 'Čiarkou oddeľované hodnoty',
			'kindDOCBOOK'     : 'Docbook XML dokument',
			// images
			'kindImage'       : 'Obrázok',
			'kindBMP'         : 'BMP Obrázok',
			'kindJPEG'        : 'JPEG Obrázok',
			'kindGIF'         : 'GIF Obrázok',
			'kindPNG'         : 'PNG Obrázok',
			'kindTIFF'        : 'TIFF Obrázok',
			'kindTGA'         : 'TGA Obrázok',
			'kindPSD'         : 'Adobe Photoshop Obrázok',
			'kindXBITMAP'     : 'X bitmap Obrázok',
			'kindPXM'         : 'Pixelmator Obrázok',
			// media
			'kindAudio'       : 'Zvukový súbor',
			'kindAudioMPEG'   : 'MPEG zvuk',
			'kindAudioMPEG4'  : 'MPEG-4 zvuk',
			'kindAudioMIDI'   : 'MIDI zvuk',
			'kindAudioOGG'    : 'Ogg Vorbis zvuk',
			'kindAudioWAV'    : 'WAV zvuk',
			'AudioPlaylist'   : 'MP3 playlist',
			'kindVideo'       : 'Video súbor',
			'kindVideoDV'     : 'DV video',
			'kindVideoMPEG'   : 'MPEG video',
			'kindVideoMPEG4'  : 'MPEG-4 video',
			'kindVideoAVI'    : 'AVI video',
			'kindVideoMOV'    : 'Quick Time video',
			'kindVideoWM'     : 'Windows Media video',
			'kindVideoFlash'  : 'Flash video',
			'kindVideoMKV'    : 'Matroska video',
			'kindVideoOGG'    : 'Ogg video'
		}
	}
}

;/**
 * Slovenian translation
 * @author Damjan Rems <d_rems at yahoo.com>
 * @version 2012-09-07
 */
if (elFinder && elFinder.prototype && typeof(elFinder.prototype.i18) == 'object') {
	elFinder.prototype.i18.sl = {
		translator : 'Damjan Rems &lt;d_rems at yahoo.com&gt;',
		language   : 'Slovenščina',
		direction  : 'ltr',
		dateFormat : 'd.m.Y H:i',
		fancyDateFormat : '$1 H:i',
		messages   : {
			
			/********************************** errors **********************************/
			'error'                : 'Napaka',
			'errUnknown'           : 'Neznana napaka.',
			'errUnknownCmd'        : 'Neznan ukaz.',
			'errJqui'              : 'Napačna jQuery UI nastavitev. Selectable, draggable in droppable dodatki morajo biti vključeni.',
			'errNode'              : 'elFinder potrebuje "DOM Element".',
			'errURL'               : 'Napačna nastavitev elFinder-ja! Manjka URL nastavitev.',
			'errAccess'            : 'Dostop zavrnjen.',
			'errConnect'           : 'Ne morem se priključiti na "backend".',
			'errAbort'             : 'Povezava prekinjena (aborted).',
			'errTimeout'           : 'Povezava potekla (timeout).',
			'errNotFound'          : 'Nisem našel "backend-a".',
			'errResponse'          : 'Napačni "backend" odgovor.',
			'errConf'              : 'Napačna "backend" nastavitev.',
			'errJSON'              : 'JSON modul ni instaliran.',
			'errNoVolumes'         : 'Readable volumes not available.',
			'errCmdParams'         : 'Napačni parametri za ukaz "$1".',
			'errDataNotJSON'       : 'Podatki niso v JSON obliki.',
			'errDataEmpty'         : 'Ni podatkov oz. so prazni.',
			'errCmdReq'            : '"Backend" zahtevek potrebuje ime ukaza.',
			'errOpen'              : '"$1" ni možno odpreti.',
			'errNotFolder'         : 'Objekt ni mapa.',
			'errNotFile'           : 'Objekt ni datoteka.',
			'errRead'              : '"$1" ni možno brati.',
			'errWrite'             : 'Ne morem pisati v "$1".',
			'errPerm'              : 'Dostop zavrnjen.',
			'errLocked'            : '"$1" je zaklenjen(a) in je ni možno preimenovati, premakniti ali izbrisati.',
			'errExists'            : 'Datoteka z imenom "$1" že obstaja.',
			'errInvName'           : 'Napačno ime datoteke.',
			'errFolderNotFound'    : 'Mape nisem našel.',
			'errFileNotFound'      : 'Datoteke nisem našel.',
			'errTrgFolderNotFound' : 'Ciljna mapa "$1" ne obstaja.',
			'errPopup'             : 'Brskalnik je preprečil prikaz (popup) okna. Za vpogled datoteke omogočite nastavitev v vašem brskalniku.',
			'errMkdir'             : 'Ni možno dodati mape "$1".',
			'errMkfile'            : 'Ni možno dodati datoteke "$1".',
			'errRename'            : 'Ni možno preimenovati "$1".',
			'errCopyFrom'          : 'Kopiranje datotek iz "$1" ni dovoljeno.',
			'errCopyTo'            : 'Kopiranje datotek na "$1" ni dovoljeno.',
			'errUploadCommon'      : 'Napaka pri prenosu.',
			'errUpload'            : '"$1" ni možno naložiti (upload).',
			'errUploadNoFiles'     : 'Ni datotek za nalaganje (upload).',
			'errMaxSize'           : 'Podatki presegajo največjo dovoljeno velikost.',
			'errFileMaxSize'       : 'Datoteka presega največjo dovoljeno velikost.',
			'errUploadMime'        : 'Datoteke s to končnico niso dovoljene.',
			'errUploadTransfer'    : '"$1" napaka pri prenosu.', 
			'errSave'              : '"$1" ni možno shraniti.',
			'errCopy'              : '"$1" ni možno kopirati.',
			'errMove'              : '"$1" ni možno premakniti.',
			'errCopyInItself'      : '"$1" ni možno kopirati samo vase.',
			'errRm'                : '"$1" ni možno izbrisati.',
			'errExtract'           : 'Datotek iz "$1" ni možno odpakirati.',
			'errArchive'           : 'Napaka pri delanju arhiva.',
			'errArcType'           : 'Nepodprta vrsta arhiva.',
			'errNoArchive'         : 'Datoteka ni arhiv ali vrsta arhiva ni podprta.',
			'errCmdNoSupport'      : '"Backend" ne podpira tega ukaza.',
			'errReplByChild'       : 'Mape “$1” ni možno zamenjati z vsebino mape.',
			'errArcSymlinks'       : 'Zaradi varnostnih razlogov arhiva ki vsebuje "symlinks" ni možno odpakirati.',
			'errArcMaxSize'        : 'Datoteke v arhivu presegajo največjo dovoljeno velikost.',
			'errResize'            : '"$1" ni možno razširiti.',
			'errUsupportType'      : 'Nepodprta vrsta datoteke.',
			
			/******************************* commands names ********************************/
			'cmdarchive'   : 'Naredi arhiv',
			'cmdback'      : 'Nazaj',
			'cmdcopy'      : 'Kopiraj',
			'cmdcut'       : 'Izreži',
			'cmddownload'  : 'Poberi (download)',
			'cmdduplicate' : 'Podvoji',
			'cmdedit'      : 'Uredi datoteko',
			'cmdextract'   : 'Odpakiraj datoteke iz arhiva',
			'cmdforward'   : 'Naprej',
			'cmdgetfile'   : 'Izberi datoteke',
			'cmdhelp'      : 'Več o',
			'cmdhome'      : 'Domov',
			'cmdinfo'      : 'Lastnosti',
			'cmdmkdir'     : 'Nova mapa',
			'cmdmkfile'    : 'Nova datoteka',
			'cmdopen'      : 'Odpri',
			'cmdpaste'     : 'Prilepi',
			'cmdquicklook' : 'Hitri ogled',
			'cmdreload'    : 'Osveži',
			'cmdrename'    : 'Preimenuj',
			'cmdrm'        : 'Izbriši',
			'cmdsearch'    : 'Poišči datoteke',
			'cmdup'        : 'Mapa nazaj',
			'cmdupload'    : 'Naloži (upload)',
			'cmdview'      : 'Ogled',
			'cmdresize'    : 'Povečaj (pomanjšaj) sliko',
			'cmdsort'      : 'Razvrsti',
			
			/*********************************** buttons ***********************************/ 
			'btnClose'  : 'Zapri',
			'btnSave'   : 'Shrani',
			'btnRm'     : 'Izbriši',
			'btnApply'  : 'Uporabi',
			'btnCancel' : 'Prekliči',
			'btnNo'     : 'Ne',
			'btnYes'    : 'Da',
			
			/******************************** notifications ********************************/
			'ntfopen'     : 'Odpri mapo',
			'ntffile'     : 'Odpri datoteko',
			'ntfreload'   : 'Osveži vsebino mape',
			'ntfmkdir'    : 'Ustvarjam mapo',
			'ntfmkfile'   : 'Ustvarjam datoteke',
			'ntfrm'       : 'Brišem datoteke',
			'ntfcopy'     : 'Kopiram datoteke',
			'ntfmove'     : 'Premikam datoteke',
			'ntfprepare'  : 'Pripravljam se na kopiranje datotek',
			'ntfrename'   : 'Preimenujem datoteke',
			'ntfupload'   : 'Nalagam (upload) datoteke',
			'ntfdownload' : 'Pobiram (download) datoteke',
			'ntfsave'     : 'Shranjujem datoteke',
			'ntfarchive'  : 'Ustvarjam arhiv',
			'ntfextract'  : 'Razpakiram datoteke iz arhiva',
			'ntfsearch'   : 'Iščem datoteke',
			'ntfsmth'     : 'Počakaj delam >_<',
			'ntfloadimg'  : 'Nalagam sliko',
			
			/************************************ dates **********************************/
			'dateUnknown' : 'neznan',
			'Today'       : 'Danes',
			'Yesterday'   : 'Včeraj',
			'Jan'         : 'Jan',
			'Feb'         : 'Feb',
			'Mar'         : 'Mar',
			'Apr'         : 'Apr',
			'May'         : 'Maj',
			'Jun'         : 'Jun',
			'Jul'         : 'Jul',
			'Aug'         : 'Avg',
			'Sep'         : 'Sep',
			'Oct'         : 'Okt',
			'Nov'         : 'Nov',
			'Dec'         : 'Dec',
			'January'     : 'Januar',
			'February'    : 'Februar',
			'March'       : 'Marec',
			'April'       : 'April',
			'May'         : 'Maj',
			'June'        : 'Junij',
			'July'        : 'Julij',
			'August'      : 'Avgust',
			'September'   : 'September',
			'October'     : 'Oktober',
			'November'    : 'November',
			'December'    : 'December',
			'Sunday'      : 'Nedelja', 
			'Monday'      : 'Ponedeljek', 
			'Tuesday'     : 'Torek', 
			'Wednesday'   : 'Sreda', 
			'Thursday'    : 'Četrtek', 
			'Friday'      : 'Petek', 
			'Saturday'    : 'Sobota',
			'Sun'         : 'Ned', 
			'Mon'         : 'Pon', 
			'Tue'         : 'Tor', 
			'Wed'         : 'Sre', 
			'Thu'         : 'Čet', 
			'Fri'         : 'Pet', 
			'Sat'         : 'Sob',
			/******************************** sort variants ********************************/
			'sortnameDirsFirst' : 'po imenu (mape na začetku)', 
			'sortkindDirsFirst' : 'po vrsti (mape na začetku)', 
			'sortsizeDirsFirst' : 'po velikosti (mape na začetku)', 
			'sortdateDirsFirst' : 'po datumu (mape na začetku)', 
			'sortname'          : 'po imenu', 
			'sortkind'          : 'po vrsti', 
			'sortsize'          : 'po velikosti',
			'sortdate'          : 'po datumu',
			
			/********************************** messages **********************************/
			'confirmReq'      : 'Zahtevana je potrditev',
			'confirmRm'       : 'Ste prepričani, da želite izbrisati datoteko?<br/>POZOR! Tega ukaza ni možno preklicati!',
			'confirmRepl'     : 'Zamenjam staro datoteko z novo?',
			'apllyAll'        : 'Uporabi pri vseh',
			'name'            : 'Ime',
			'size'            : 'Velikost',
			'perms'           : 'Dovoljenja',
			'modify'          : 'Spremenjeno',
			'kind'            : 'Vrsta',
			'read'            : 'beri',
			'write'           : 'piši',
			'noaccess'        : 'ni dostopa',
			'and'             : 'in',
			'unknown'         : 'neznan',
			'selectall'       : 'Izberi vse datoteke',
			'selectfiles'     : 'Izberi datotek(o)e',
			'selectffile'     : 'Izberi prvo datoteko',
			'selectlfile'     : 'Izberi zadnjo datoteko',
			'viewlist'        : 'Seznam',
			'viewicons'       : 'Ikone',
			'places'          : 'Mesta (places)',
			'calc'            : 'Izračun', 
			'path'            : 'Pot do',
			'aliasfor'        : 'Sopomenka (alias) za',
			'locked'          : 'Zaklenjeno',
			'dim'             : 'Dimenzije',
			'files'           : 'Datoteke',
			'folders'         : 'Mape',
			'items'           : 'Predmeti',
			'yes'             : 'da',
			'no'              : 'ne',
			'link'            : 'Povezava',
			'searcresult'     : 'Rezultati iskanja',  
			'selected'        : 'izbrani predmeti',
			'about'           : 'Več o',
			'shortcuts'       : 'Bližnjice',
			'help'            : 'Pomoč',
			'webfm'           : 'Spletni upravitelj datotek',
			'ver'             : 'Verzija',
			'protocol'        : 'verzija protokola',
			'homepage'        : 'Domača stran',
			'docs'            : 'Dokumentacija',
			'github'          : 'Fork us on Github',
			'twitter'         : 'Sledi na twitterju',
			'facebook'        : 'Pridruži se nam na facebook-u',
			'team'            : 'Tim',
			'chiefdev'        : 'Glavni razvijalec',
			'developer'       : 'razvijalec',
			'contributor'     : 'contributor',
			'maintainer'      : 'vzdrževalec',
			'translator'      : 'prevajalec',
			'icons'           : 'Ikone',
			'dontforget'      : 'In ne pozabi na brisačo',
			'shortcutsof'     : 'Bližnjica onemogočena',
			'dropFiles'       : 'Datoteke spusti tukaj',
			'or'              : 'ali',
			'selectForUpload' : 'Izberi datoteke za nalaganje',
			'moveFiles'       : 'Premakni datoteke',
			'copyFiles'       : 'Kopiraj datoteke',
			'rmFromPlaces'    : 'Izbriši iz mesta (places)',
			'untitled folder' : 'mapa brez imena',
			'untitled file.txt' : 'brez imena file.txt',
			'aspectRatio'     : 'Razmerje slike',
			'scale'           : 'Razširi',
			'width'           : 'Širina',
			'height'          : 'Višina',
			'mode'            : 'Način (mode)',
			'resize'          : 'Povečaj',
			'crop'            : 'Obreži',
			'rotate'          : 'Zavrti',
			'rotate-cw'       : 'Zavrti 90 st. v smeri ure',
			'rotate-ccw'      : 'Zavrti 90 st. v obratni smeri ure',
			'degree'          : 'Stopnja',
			
			/********************************** mimetypes **********************************/
			'kindUnknown'     : 'Neznan',
			'kindFolder'      : 'Mapa',
			'kindAlias'       : 'Sopomenka (alias)',
			'kindAliasBroken' : 'Nedelujoča sopomenka (alias)',
			// applications
			'kindApp'         : 'Program',
			'kindPostscript'  : 'Postscript dokument',
			'kindMsOffice'    : 'Microsoft Office dokument',
			'kindMsWord'      : 'Microsoft Word dokument',
			'kindMsExcel'     : 'Microsoft Excel dokument',
			'kindMsPP'        : 'Microsoft Powerpoint predstavitev',
			'kindOO'          : 'Open Office dokument',
			'kindAppFlash'    : 'Flash program',
			'kindPDF'         : 'Portable Document Format (PDF)',
			'kindTorrent'     : 'Bittorrent datoteka',
			'kind7z'          : '7z arhiv',
			'kindTAR'         : 'TAR arhiv',
			'kindGZIP'        : 'GZIP arhiv',
			'kindBZIP'        : 'BZIP arhiv',
			'kindZIP'         : 'ZIP arhiv',
			'kindRAR'         : 'RAR arhiv',
			'kindJAR'         : 'Java JAR datoteka',
			'kindTTF'         : 'True Type font',
			'kindOTF'         : 'Open Type font',
			'kindRPM'         : 'RPM paket',
			// texts
			'kindText'        : 'Tekst dokument',
			'kindTextPlain'   : 'Samo tekst',
			'kindPHP'         : 'PHP koda',
			'kindCSS'         : 'Cascading style sheet (CSS)',
			'kindHTML'        : 'HTML dokument',
			'kindJS'          : 'Javascript koda',
			'kindRTF'         : 'Rich Text Format (RTF)',
			'kindC'           : 'C koda',
			'kindCHeader'     : 'C header koda',
			'kindCPP'         : 'C++ koda',
			'kindCPPHeader'   : 'C++ header koda',
			'kindShell'       : 'Unix shell skripta',
			'kindPython'      : 'Python kdoa',
			'kindJava'        : 'Java koda',
			'kindRuby'        : 'Ruby koda',
			'kindPerl'        : 'Perl skripta',
			'kindSQL'         : 'SQL koda',
			'kindXML'         : 'XML dokument',
			'kindAWK'         : 'AWK koda',
			'kindCSV'         : 'Besedilo ločeno z vejico (CSV)',
			'kindDOCBOOK'     : 'Docbook XML dokument',
			// images
			'kindImage'       : 'Slika',
			'kindBMP'         : 'BMP slika',
			'kindJPEG'        : 'JPEG slika',
			'kindGIF'         : 'GIF slika',
			'kindPNG'         : 'PNG slika',
			'kindTIFF'        : 'TIFF slika',
			'kindTGA'         : 'TGA slika',
			'kindPSD'         : 'Adobe Photoshop slika',
			'kindXBITMAP'     : 'X bitmap slika',
			'kindPXM'         : 'Pixelmator slika',
			// media
			'kindAudio'       : 'Avdio medija',
			'kindAudioMPEG'   : 'MPEG zvok',
			'kindAudioMPEG4'  : 'MPEG-4 zvok',
			'kindAudioMIDI'   : 'MIDI zvok',
			'kindAudioOGG'    : 'Ogg Vorbis zvok',
			'kindAudioWAV'    : 'WAV zvok',
			'AudioPlaylist'   : 'MP3 seznam',
			'kindVideo'       : 'Video medija',
			'kindVideoDV'     : 'DV film',
			'kindVideoMPEG'   : 'MPEG film',
			'kindVideoMPEG4'  : 'MPEG-4 film',
			'kindVideoAVI'    : 'AVI film',
			'kindVideoMOV'    : 'Quick Time film',
			'kindVideoWM'     : 'Windows Media film',
			'kindVideoFlash'  : 'Flash film',
			'kindVideoMKV'    : 'Matroska film',
			'kindVideoOGG'    : 'Ogg film'
		}
	}
}
;/**
 * Swedish translation
 * @author Gabriel Satzger <gabriel.satzger@sbg.se>
 * @version 2012-09-10
 */
if (elFinder && elFinder.prototype && typeof(elFinder.prototype.i18) == 'object') {
	elFinder.prototype.i18.sv = {
		translator : 'Gabriel Satzger &lt;gabriel.satzger@sbg.se&gt;',
		language   : 'Svenska',
		direction  : 'ltr',
		dateFormat : 'Y-m-d H:i',
		fancyDateFormat : '$1 H:i',
		messages   : {
			
			/********************************** errors **********************************/
			'error'                : 'Error',
			'errUnknown'           : 'Okänt error.',
			'errUnknownCmd'        : 'Okänt kommando.',
			'errJqui'              : 'Felaktig jQuery UI konfiguration. Komponenterna selectable, draggable och droppable måste vara inkluderade.',
			'errNode'              : 'elFinder kräver att DOM Elementen skapats.',
			'errURL'               : 'Felaktig elFinder konfiguration! URL parametern är inte satt.',
			'errAccess'            : 'Åtkomst nekad.',
			'errConnect'           : 'Kan inte ansluta till backend.',
			'errAbort'             : 'Anslutningen avbröts.',
			'errTimeout'           : 'Anslutningen löpte ut.',
			'errNotFound'          : 'Backend hittades inte.',
			'errResponse'          : 'Ogiltig backend svar.',
			'errConf'              : 'Ogiltig backend konfiguration.',
			'errJSON'              : 'PHP JSON modul är inte installerad.',
			'errNoVolumes'         : 'Läsbara volymer är inte tillgängliga.',
			'errCmdParams'         : 'Ogiltiga parametrar för kommandot "$1".',
			'errDataNotJSON'       : 'Datan är inte JSON.',
			'errDataEmpty'         : 'Datan är tom.',
			'errCmdReq'            : 'Backend begäran kräver kommandonamn.',
			'errOpen'              : 'Kan inte öppna "$1".',
			'errNotFolder'         : 'Objektet är inte en mapp.',
			'errNotFile'           : 'Objektet är inte en fil.',
			'errRead'              : 'Kan inte läsa "$1".',
			'errWrite'             : 'Kan inte skriva till "$1".',
			'errPerm'              : 'Tillstånd nekat.',
			'errLocked'            : '"$1" är låst och kan inte döpas om, flyttas eller tas bort.',
			'errExists'            : 'Fil med namn "$1" finns redan.',
			'errInvName'           : 'Ogiltigt filnamn.',
			'errFolderNotFound'    : 'Mappen hittades inte.',
			'errFileNotFound'      : 'Filen hittades inte.',
			'errTrgFolderNotFound' : 'Målmappen "$1" hittades inte.',
			'errPopup'             : 'Webbläsaren hindrade popup-fönstret att öppnas. Ändra i webbläsarens inställningar för att kunna öppna filen.',
			'errMkdir'             : 'Kan inte skapa mappen "$1".',
			'errMkfile'            : 'Kan inte skapa filen "$1".',
			'errRename'            : 'Kan inte döpa om "$1".',
			'errCopyFrom'          : 'Kopiera filer från volym "$1" tillåts inte.',
			'errCopyTo'            : 'Kopiera filer till volym "$1" tillåts inte.',
			'errUploadCommon'      : 'Error vid uppladdningen.',
			'errUpload'            : 'Kan inte ladda upp "$1".',
			'errUploadNoFiles'     : 'Inga filer hittades för uppladdning.',
			'errMaxSize'           : 'Data överskrider den högsta tillåtna storleken.',
			'errFileMaxSize'       : 'Filen överskrider den högsta tillåtna storleken.',
			'errUploadMime'        : 'Otillåten filtyp.',
			'errUploadTransfer'    : '"$1" överföringsfel.', 
			'errSave'              : 'Kan inte spara "$1".',
			'errCopy'              : 'Kan inte kopiera "$1".',
			'errMove'              : 'Kan inte flytta "$1".',
			'errCopyInItself'      : 'Kan inte flytta "$1" till sig själv.',
			'errRm'                : 'Kan inte ta bort "$1".',
			'errExtract'           : 'Kan inte packa upp filen från "$1".',
			'errArchive'           : 'Kan inte skapa arkiv.',
			'errArcType'           : 'Arkivtypen stöds inte.',
			'errNoArchive'         : 'Filen är inte av typen arkiv.',
			'errCmdNoSupport'      : 'Backend stöder inte detta kommando.',
			'errReplByChild'       : 'Mappen “$1” kan inte ersättas av ett objekt den innehåller.',
			'errArcSymlinks'       : 'Av säkerhetsskäl nekas arkivet att packas upp då det innehåller symboliska länkar eller filer med ej tillåtna namn.', // edited 24.06.2012
			'errArcMaxSize'        : 'Arkivfiler överskrider största tillåtna storlek.',
			'errResize'            : 'Kan inte ändra storlek "$1".',
			'errUsupportType'      : 'Filtypen stöds inte.',
			'errNotUTF8Content'    : 'Filen "$1" är inte i UTF-8 och kan inte redigeras.',  // added 9.11.2011
			'errNetMount'          : 'Kan inte koppla "$1".',     // added 17.04.2012
			'errNetMountNoDriver'  : 'Protokollet stöds inte.',     // added 17.04.2012
			'errNetMountFailed'    : 'Kopplingen misslyckades.',             // added 17.04.2012
			'errNetMountHostReq'   : 'Host krävs.', // added 18.04.2012
			/******************************* commands names ********************************/
			'cmdarchive'   : 'Skapa arkiv',
			'cmdback'      : 'Tillbaka',
			'cmdcopy'      : 'Kopiera',
			'cmdcut'       : 'Klipp ut',
			'cmddownload'  : 'Ladda ned',
			'cmdduplicate' : 'Duplicera',
			'cmdedit'      : 'Redigera fil',
			'cmdextract'   : 'Extrahera filer från arkiv',
			'cmdforward'   : 'Framåt',
			'cmdgetfile'   : 'Välj filer',
			'cmdhelp'      : 'Om denna programvara',
			'cmdhome'      : 'Hem',
			'cmdinfo'      : 'Visa info',
			'cmdmkdir'     : 'Ny mapp',
			'cmdmkfile'    : 'Ny textfil',
			'cmdopen'      : 'Öpna',
			'cmdpaste'     : 'Klistra in',
			'cmdquicklook' : 'Förhandsgranska',
			'cmdreload'    : 'Ladda om',
			'cmdrename'    : 'Döp om',
			'cmdrm'        : 'Radera',
			'cmdsearch'    : 'Hitta filer',
			'cmdup'        : 'Gå till överordnade katalog',
			'cmdupload'    : 'Ladda upp filer',
			'cmdview'      : 'Visa',
			'cmdresize'    : 'Ändra bildstorlek',
			'cmdsort'      : 'Sortera',
			
			/*********************************** buttons ***********************************/ 
			'btnClose'  : 'Stäng',
			'btnSave'   : 'Spara',
			'btnRm'     : 'Ta bort',
			'btnApply'  : 'Verkställ',
			'btnCancel' : 'Ångra',
			'btnNo'     : 'Nej',
			'btnYes'    : 'Ja',
			
			/******************************** notifications ********************************/
			'ntfopen'     : 'Öppnar mapp',
			'ntffile'     : 'Öppnar fil',
			'ntfreload'   : 'Laddar om mappinnehållet',
			'ntfmkdir'    : 'Skapar katalog',
			'ntfmkfile'   : 'Skapar fil',
			'ntfrm'       : 'Tar bort filer',
			'ntfcopy'     : 'Kopierar filer',
			'ntfmove'     : 'Flyttar filer',
			'ntfprepare'  : 'Förbereder att flytta filer',
			'ntfrename'   : 'Döper om filer',
			'ntfupload'   : 'Laddar upp filer',
			'ntfdownload' : 'Laddar ner filer',
			'ntfsave'     : 'Sparar filer',
			'ntfarchive'  : 'Skapar arkiv',
			'ntfextract'  : 'Extraherar filer från arkiv',
			'ntfsearch'   : 'Söker filer',
			'ntfsmth'     : 'Gör någonting >_<',
			'ntfloadimg'  : 'Laddar bild',
			'ntfnetmount' : 'kopplar nätverksvolym', // added 18.04.2012
			
			/************************************ dates **********************************/
			'dateUnknown' : 'okänt',
			'Today'       : 'Idag',
			'Yesterday'   : 'Igår',
			'Jan'         : 'Jan',
			'Feb'         : 'Feb',
			'Mar'         : 'Mar',
			'Apr'         : 'Apr',
			'May'         : 'Maj',
			'Jun'         : 'Jun',
			'Jul'         : 'Jul',
			'Aug'         : 'Aug',
			'Sep'         : 'Sep',
			'Oct'         : 'Okt',
			'Nov'         : 'Nov',
			'Dec'         : 'Dec',
			'January'     : 'Januari',
			'February'    : 'Februari',
			'March'       : 'Mars',
			'April'       : 'April',
			'May'         : 'Maj',
			'June'        : 'Juni',
			'July'        : 'Juli',
			'August'      : 'Augusti',
			'September'   : 'September',
			'October'     : 'Oktober',
			'November'    : 'November',
			'December'    : 'December',
			'Sunday'      : 'Söndag', 
			'Monday'      : 'Måndag', 
			'Tuesday'     : 'Tisdag', 
			'Wednesday'   : 'Onsdag', 
			'Thursday'    : 'Torsdag', 
			'Friday'      : 'Fredag', 
			'Saturday'    : 'Lördag',
			'Sun'         : 'Sön', 
			'Mon'         : 'Mån', 
			'Tue'         : 'Tis', 
			'Wed'         : 'Ons', 
			'Thu'         : 'Tor', 
			'Fri'         : 'Fre', 
			'Sat'         : 'Lör',
			
			/******************************** sort variants ********************************/
			'sortnameDirsFirst' : 'namn (mappar först)', 
			'sortkindDirsFirst' : 'efter sort (mappar först)', 
			'sortsizeDirsFirst' : 'efter storlek (mappar först)', 
			'sortdateDirsFirst' : 'efter datum (mappar först)',
			'sortname'          : 'efter namn', 
			'sortkind'          : 'efter sort', 
			'sortsize'          : 'efter storlek',
			'sortdate'          : 'efter datum',
			'sortFoldersFirst'  : 'Mappar först', // added 22.06.2012
			
			/********************************** messages **********************************/
			'confirmReq'      : 'Bekräftelse krävs',
			'confirmRm'       : 'Är du säker på att du vill ta bort filer? <br/> Detta kan inte ångras!',
			'confirmRepl'     : 'Ersätt den gamla filen med en ny?',
			'apllyAll'        : 'Använd för alla',
			'name'            : 'Namn',
			'size'            : 'Storlek',
			'perms'           : 'Rättigheter',
			'modify'          : 'Ändrad',
			'kind'            : 'Sort',
			'read'            : 'läs',
			'write'           : 'skriv',
			'noaccess'        : 'ingen åtkomst',
			'and'             : 'och',
			'unknown'         : 'okänd',
			'selectall'       : 'Välj alla filer',
			'selectfiles'     : 'Välj fil(er)',
			'selectffile'     : 'Välj första filen',
			'selectlfile'     : 'Välj sista filen',
			'viewlist'        : 'Listvy',
			'viewicons'       : 'Ikonvy',
			'places'          : 'Platser',
			'calc'            : 'Beräkna', 
			'path'            : 'Sökväg',
			'aliasfor'        : 'Alias för',
			'locked'          : 'Låst',
			'dim'             : 'Dimensioner',
			'files'           : 'Filer',
			'folders'         : 'Mappar',
			'items'           : 'Objekt',
			'yes'             : 'ja',
			'no'              : 'nej',
			'link'            : 'Länk',
			'searcresult'     : 'Sökresultat',  
			'selected'        : 'valda objekt',
			'about'           : 'Om',
			'shortcuts'       : 'Genväg',
			'help'            : 'Hjälp',
			'webfm'           : 'Webbfilhanterare',
			'ver'             : 'Version',
			'protocolver'     : 'protokolversion',
			'homepage'        : 'Projekt hemsida',
			'docs'            : 'Dokumentation',
			'github'          : 'Forka oss på Github',
			'twitter'         : 'Följ oss på twitter',
			'facebook'        : 'Följ oss på facebook',
			'team'            : 'Team',
			'chiefdev'        : 'senior utvecklare',
			'developer'       : 'utvecklare',
			'contributor'     : 'bidragsgivare',
			'maintainer'      : 'underhållare',
			'translator'      : 'översättare',
			'icons'           : 'Ikoner',
			'dontforget'      : 'och glöm inte att ta med din handduk',
			'shortcutsof'     : 'Genvägar avaktiverade',
			'dropFiles'       : 'Släpp filerna här',
			'or'              : 'eller',
			'selectForUpload' : 'Välj filer att ladda upp',
			'moveFiles'       : 'Flytta filer',
			'copyFiles'       : 'Kopiera filer',
			'rmFromPlaces'    : 'Ta bort från platser',
			'untitled folder' : 'namnlös mapp',
			'untitled file.txt' : 'namnlös fil.txt',
			'aspectRatio'     : 'Aspekt ratio',
			'scale'           : 'Skala',
			'width'           : 'Bredd',
			'height'          : 'Höjd',
			'mode'            : 'Läge',
			'resize'          : 'Ändra storlek',
			'crop'            : 'Beskär',
			'rotate'          : 'Rotera',
			'rotate-cw'       : 'Rotera 90 grader medurs',
			'rotate-ccw'      : 'Rotera 90 grader moturs',
			'degree'          : 'Grader',
			'netMountDialogTitle' : 'Koppla nätverksvolym', // added 18.04.2012
			'protocol'            : 'Protokol', // added 18.04.2012
			'host'                : 'Host', // added 18.04.2012
			'port'                : 'Port', // added 18.04.2012
			'user'                : 'användare', // added 18.04.2012
			'pass'                : 'Lösenord', // added 18.04.2012
			/********************************** mimetypes **********************************/
			'kindUnknown'     : 'Okänd',
			'kindFolder'      : 'Mapp',
			'kindAlias'       : 'Alias',
			'kindAliasBroken' : 'Trasigt alias',
			// applications
			'kindApp'         : 'Applikation',
			'kindPostscript'  : 'Postscript',
			'kindMsOffice'    : 'Microsoft Office',
			'kindMsWord'      : 'Microsoft Word',
			'kindMsExcel'     : 'Microsoft Excel',
			'kindMsPP'        : 'Microsoft Powerpoint',
			'kindOO'          : 'Open Office',
			'kindAppFlash'    : 'Flash',
			'kindPDF'         : 'Portable Document Format (PDF)',
			'kindTorrent'     : 'Bittorrent',
			'kind7z'          : '7z',
			'kindTAR'         : 'TAR',
			'kindGZIP'        : 'GZIP',
			'kindBZIP'        : 'BZIP',
			'kindZIP'         : 'ZIP',
			'kindRAR'         : 'RAR',
			'kindJAR'         : 'Java JAR',
			'kindTTF'         : 'True Type',
			'kindOTF'         : 'Open Type',
			'kindRPM'         : 'RPM',
			// texts
			'kindText'        : 'Text',
			'kindTextPlain'   : 'Plain',
			'kindPHP'         : 'PHP',
			'kindCSS'         : 'Cascading style sheet',
			'kindHTML'        : 'HTML',
			'kindJS'          : 'Javascript',
			'kindRTF'         : 'Rich Text Format',
			'kindC'           : 'C',
			'kindCHeader'     : 'C header',
			'kindCPP'         : 'C++',
			'kindCPPHeader'   : 'C++ header',
			'kindShell'       : 'Unix shell script',
			'kindPython'      : 'Python',
			'kindJava'        : 'Java',
			'kindRuby'        : 'Ruby',
			'kindPerl'        : 'Perl',
			'kindSQL'         : 'SQL',
			'kindXML'         : 'XML',
			'kindAWK'         : 'AWK',
			'kindCSV'         : 'CSV',
			'kindDOCBOOK'     : 'Docbook XML',
			// images
			'kindImage'       : 'Bild',
			'kindBMP'         : 'BMP',
			'kindJPEG'        : 'JPEG',
			'kindGIF'         : 'GIF',
			'kindPNG'         : 'PNG',
			'kindTIFF'        : 'TIFF',
			'kindTGA'         : 'TGA',
			'kindPSD'         : 'Adobe Photoshop',
			'kindXBITMAP'     : 'X bitmap',
			'kindPXM'         : 'Pixelmator',
			// media
			'kindAudio'       : 'Audio media',
			'kindAudioMPEG'   : 'MPEG audio',
			'kindAudioMPEG4'  : 'MPEG-4 audio',
			'kindAudioMIDI'   : 'MIDI audio',
			'kindAudioOGG'    : 'Ogg Vorbis audio',
			'kindAudioWAV'    : 'WAV audio',
			'AudioPlaylist'   : 'MP3 playlist',
			'kindVideo'       : 'Video media',
			'kindVideoDV'     : 'DV movie',
			'kindVideoMPEG'   : 'MPEG movie',
			'kindVideoMPEG4'  : 'MPEG-4 movie',
			'kindVideoAVI'    : 'AVI movie',
			'kindVideoMOV'    : 'Quick Time movie',
			'kindVideoWM'     : 'Windows Media movie',
			'kindVideoFlash'  : 'Flash movie',
			'kindVideoMKV'    : 'Matroska movie',
			'kindVideoOGG'    : 'Ogg movie'
		}
	}
}

;/**
 * Turkish translation
 * @author I.Taskinoglu & A.Kaya <alikaya@armsyazilim.com>
 * @version 2012-06-15
 */
if (elFinder && elFinder.prototype && typeof(elFinder.prototype.i18) == 'object') {
	elFinder.prototype.i18.tr = {
		translator : 'I.Taskinoglu & A.Kaya &lt;alikaya@armsyazilim.com&gt;',
		language   : 'Türkçe',
		direction  : 'ltr',
		dateFormat : 'd.m.Y H:i',
		fancyDateFormat : '$1 H:i',
		messages   : {

			/********************************** errors **********************************/
			'error'                : 'Hata',
			'errUnknown'           : 'Bilinmeyen hata.',
			'errUnknownCmd'        : 'Bilinmeyen komut.',
			'errJqui'              : 'Geçersiz Jquery configurasyonu.',
			'errNode'              : 'DOM objesine ihtiyaç duyuluyor.',
			'errURL'               : 'Geçersiz konfigurasyon, URL ayarlanmamış.',
			'errAccess'            : 'Erişim yok.',
			'errConnect'           : 'Sunucya bağlanamadı.',
			'errAbort'             : 'Bağlantı kesildi.',
			'errTimeout'           : 'Bağlantı zaman aşımına uğradı.',
			'errNotFound'          : 'Sunucu bulunamadı.',
			'errResponse'          : 'Sunucu cevabı geçersiz.',
			'errConf'              : 'Sunucu ayarları geçersiz.',
			'errJSON'              : 'PHP JSON modülü kurulmamış.',
			'errNoVolumes'         : 'Okunabilir fiziksel disk yok.',
			'errCmdParams'         : '"$1" komutu için geçersiz parametre.',
			'errDataNotJSON'       : 'Veri JSON formatında değil.',
			'errDataEmpty'         : 'Veri Boş.',
			'errCmdReq'            : 'Sunucu isteği komut gerektiriyor.',
			'errOpen'              : '"$1" açılamadı.',
			'errNotFolder'         : 'Obje Klasör değil.',
			'errNotFile'           : 'Obje Dosya değil.',
			'errRead'              : '"$1" okunamadı.',
			'errWrite'             : '"$1" Dosyasına yazılamadı.',
			'errPerm'              : 'Yetki Yok.',                        
			'errLocked'            : '"$1" dosyası kilitli olduğu için adı değiştirilemedi, taşındı veya silindi.',
			'errExists'            : '"$1" adlı dosya var zaten.',
			'errInvName'           : 'Geçersiz dosya adı.',
			'errFolderNotFound'    : 'Dizin bulunamadı.',
			'errFileNotFound'      : 'Dosya bulunamadı.',
			'errTrgFolderNotFound' : 'Hedef klasör ["$1"] bulunamadı.',
			'errPopup'             : 'İnternet gezgininiz açılır pencereleri engellememeli. Ayarlardan izin verip tekrar deneyin.',
			'errMkdir'             : '"$1" dizin oluşturulamadı.',
			'errMkfile'            : '"$1" dosya oluşturulamadı.',
			'errRename'            : '"$1" adı değiştirilemedi.',
			'errCopyFrom'          : '"$1" dizininden kopyalamaya izin verilmedi.',
			'errCopyTo'            : '"$1" dizinine kopyalamaya izin verilmedi.',
			'errUploadCommon'      : 'Dosya gönderme hatası.',
			'errUpload'            : '"$1" dosya gönderilemedi.',
			'errUploadNoFiles'     : 'Göndermek için dosya bulunamadı.',
			'errMaxSize'           : 'Data izin verilen boyuttan büyük.',
			'errFileMaxSize'       : 'Dosya izin verilen boyuttan büyük.',
			'errUploadMime'        : 'Dosya tipine izin verilmiyor.',
			'errUploadTransfer'    : '"$1" transfer hatası.', 
			'errSave'              : '"$1" kaydedilemez.',
			'errCopy'              : '"$1" kopylanamaz.',
			'errMove'              : '"$1" taşınamaz.',
			'errCopyInItself'      : '"$1" kendi içinde kopyalanamaz.',
			'errRm'                : '"$1" silinemedi.',
			'errExtract'           : '"$1" arşivi açılamadı.',
			'errArchive'           : 'Arşiv oluşturulamadı.',
			'errArcType'           : 'Desteklenmeyen Arşiv Tipi.',
			'errNoArchive'         : 'Dosya arşiv değil veya desteklenmiyor.',
			'errCmdNoSupport'      : 'Bu komut desteklenmiyor.',
			'errReplByChild'       : 'Klasör “$1” içerdiği dosyadan dolayı değiştirilemedi',
			'errArcSymlinks'       : 'Güvenlik nedeni ile arşiv açılamadı.',
			'errArcMaxSize'        : 'Arşiv dosyası izin verilen maksimum boyutun üstünde.',
			'errResize'            : 'Boyutlandırılamadı "$1".',
			'errUsupportType'      : 'Desteklenmeyen Dosya Tipi.',

			/******************************* commands names ********************************/
			'cmdarchive'   : 'Arşiv Oluştur',
			'cmdback'      : 'Geri',
			'cmdcopy'      : 'Kopyala',
			'cmdcut'       : 'Kes',
			'cmddownload'  : 'İndir',
			'cmdduplicate' : 'Dosyayı Çoğalt',
			'cmdedit'      : 'Dosyayı Düzenle',
			'cmdextract'   : 'Dosyaları Arşivden Çıkar',
			'cmdforward'   : 'İleri',
			'cmdgetfile'   : 'Dosyayı Seç',
			'cmdhelp'      : 'elFinde Hakkında',
			'cmdhome'      : 'Ana Sayfa',
			'cmdinfo'      : 'Bilgi',
			'cmdmkdir'     : 'Yeni Klasör',
			'cmdmkfile'    : 'Yeni Boş Dosya',
			'cmdopen'      : 'Aç',
			'cmdpaste'     : 'Yapıştır',
			'cmdquicklook' : 'Önizleme',
			'cmdreload'    : 'Yenile',
			'cmdrename'    : 'Adını Değiştir',
			'cmdrm'        : 'Sil',
			'cmdsearch'    : 'Dosya Ara',
			'cmdup'        : 'Üst Klasöre Git',
			'cmdupload'    : 'Dosya Gönder',
			'cmdview'      : 'Aç',
			'cmdresize'    : 'Resmi Yeniden Boyutlandır',
			'cmdsort'      : 'Sırala',

			/*********************************** buttons ***********************************/
			'btnClose'  : 'Kapat',
			'btnSave'   : 'Kaydet',
			'btnRm'     : 'Sil',
			'btnApply'  : 'Uygula',
			'btnCancel' : 'Vazgeç',
			'btnNo'     : 'Hayır',
			'btnYes'    : 'Evet',

			/******************************** notifications ********************************/
			'ntfopen'     : 'Klasör Aç',
			'ntffile'     : 'Dosya Aç',
			'ntfreload'   : 'Klasörü Yenile',
			'ntfmkdir'    : 'Klasör Oluşuturuluyor',
			'ntfmkfile'   : 'Dosya Oluşturuluyor',
			'ntfrm'       : 'Dosyaları Sil',
			'ntfcopy'     : 'Dosyaları Kopyala',
			'ntfmove'     : 'Dosyaları Taşı',
			'ntfprepare'  : 'Kopyalamak için Hazırla',
			'ntfrename'   : 'Dosyaları Adlandır',
			'ntfupload'   : 'Dosyalar Yükleniyor',
			'ntfdownload' : 'Dosyalar İndiriliyor',
			'ntfsave'     : 'Dosyalar Kaydediliyor',
			'ntfarchive'  : 'Arşiv Oluşturuluyor',
			'ntfextract'  : 'Dosyalar Arşivde Çıkarılıyor',
			'ntfsearch'   : 'Dosyalar Aranıyor',
			'ntfsmth'     : 'Birşeyler Yapılıyor >_<',
			'ntfloadimg'  : 'Resim Yükleniyor',

			/************************************ dates **********************************/
			'dateUnknown' : 'bilinmiyor',
			'Today'       : 'Bugün',
			'Yesterday'   : 'Dün',
			'Jan'         : 'Oca',
			'Feb'         : 'Şub',
			'Mar'         : 'Mar',
			'Apr'         : 'Nis',
			'May'         : 'May',
			'Jun'         : 'Haz',
			'Jul'         : 'Tem',
			'Aug'         : 'Ağu',
			'Sep'         : 'Eyl',
			'Oct'         : 'Ekm',
			'Nov'         : 'Kas',
			'Dec'         : 'Ara',
			'January'     : 'Ocak',
			'February'    : 'Şubat',
			'March'       : 'Mart',
			'April'       : 'Nisan',
			'May'         : 'Mayıs',
			'June'        : 'Haziran',
			'July'        : 'Temmuz',
			'August'      : 'Ağustos',
			'September'   : 'Eylül',
			'October'     : 'Ekim',
			'November'    : 'Kasım',
			'December'    : 'Aralık',
			'Sunday'      : 'Pazar', 
			'Monday'      : 'Pazartesi', 
			'Tuesday'     : 'Salı', 
			'Wednesday'   : 'Çarşamba', 
			'Thursday'    : 'Perşembe', 
			'Friday'      : 'Cuma', 
			'Saturday'    : 'Cumartesi',
			'Sun'         : 'Paz', 
			'Mon'         : 'Pzt', 
			'Tue'         : 'Sal', 
			'Wed'         : 'Çar', 
			'Thu'         : 'Per', 
			'Fri'         : 'Cum', 
			'Sat'         : 'Cmt',
			/******************************** sort variants ********************************/
			'sortnameDirsFirst' : 'Ada göre (önce klasörler)', 
			'sortkindDirsFirst' : 'Türe göre (önce klasörler)', 
			'sortsizeDirsFirst' : 'Boyuta göre (önce klasörler)', 
			'sortdateDirsFirst' : 'Tarihe göre (önce klasörler)', 
			'sortname'          : 'Ada göre', 
			'sortkind'          : 'Türe göre', 
			'sortsize'          : 'Boyuta göre',
			'sortdate'          : 'Tarihe göre',

			/********************************** messages **********************************/
			'confirmReq'      : 'Onay gerekli',
			'confirmRm'       : 'Dosyaları silmek istediğinize emin misiniz?<br/>Bu geri alınamaz!',
			'confirmRepl'     : 'Eski dosyaları yenileriyle değiştir?',
			'apllyAll'        : 'Tümünü uygula',
			'name'            : 'Ad',
			'size'            : 'Boyut',
			'perms'           : 'Yetkiler',
			'modify'          : 'Değiştildi',
			'kind'            : 'Tür',
			'read'            : 'oku',
			'write'           : 'yaz',
			'noaccess'        : 'yetki yok',
			'and'             : 've',
			'unknown'         : 'bilinmeyen',
			'selectall'       : 'Tüm Dosyaları Seç',
			'selectfiles'     : 'Dosyaları Seç',
			'selectffile'     : 'İlk Dosyayı Seç',
			'selectlfile'     : 'Son Dosyayı Seç',
			'viewlist'        : 'Liste görünümü',
			'viewicons'       : 'İkon görünümü',
			'places'          : 'Klasörler',
			'calc'            : 'Hesapla', 
			'path'            : 'Dizin',
			'aliasfor'        : 'Takma adı :',
			'locked'          : 'Kilitli',
			'dim'             : 'Ölçüler',
			'files'           : 'Dosyalar',
			'folders'         : 'Klasörler',
			'items'           : 'Nesneler',
			'yes'             : 'evet',
			'no'              : 'hayır',
			'link'            : 'Bağlantı',
			'searcresult'     : 'Arama Sonuçları',  
			'selected'        : 'Şeçili Nesne',
			'about'           : 'Hakkında',
			'shortcuts'       : 'Kısayollar',
			'help'            : 'Yardım',
			'webfm'           : 'Dosya Yöneticisi',
			'ver'             : 'Versiyon',
			'protocol'        : 'protocol versiyonu',
			'homepage'        : 'Proje Ana Sayfası',
			'docs'            : 'Yardım',
			'github'          : 'Fork us on Github',
			'twitter'         : 'twittwer da takip et',
			'facebook'        : 'facebookda bize katıl',
			'team'            : 'Takım',
			'chiefdev'        : 'Yapımcı',
			'developer'       : 'yapımcı',
			'contributor'     : 'katkı',
			'maintainer'      : 'geliştirici',
			'translator'      : 'çeviri',
			'icons'           : 'İkonlar',
			'dontforget'      : 'Ve... havlunuzu almayı unutmayın',
			'shortcutsof'     : 'Kısayollar Kapalı',
			'dropFiles'       : 'Dosyaları buraya sürükleyin',
			'or'              : 'veya',
			'selectForUpload' : 'Yüklenecek Dosyaları Seçin',
			'moveFiles'       : 'Dosyaları Taşı',
			'copyFiles'       : 'Dosyaları Kopyala',
			'rmFromPlaces'    : 'Klasörlerden Sil',
			'untitled folder' : 'basliksiz_klasor',
			'untitled file.txt' : 'basliksiz_dosya.txt',
			'aspectRatio'     : 'Oran',
			'scale'           : 'Ölçekle',
			'width'           : 'Genişlik',
			'height'          : 'Yükseklik',
			'mode'            : 'Mod',
			'resize'          : 'Boyutlandır',
			'crop'            : 'Kes',
			'rotate'          : 'Döndür',
			'rotate-cw'       : '90 Derece Sağa Döndür',
			'rotate-ccw'      : '90 Derece Sola Döndür',
			'degree'          : 'Açı',

			/********************************** mimetypes **********************************/
			'kindUnknown'     : 'Bilinmiyor',
			'kindFolder'      : 'Klasör',
			'kindAlias'       : 'Takma Ad',
			'kindAliasBroken' : 'Bozuk Takma Ad',
			// applications
			'kindApp'         : 'Uygulama',
			'kindPostscript'  : 'Postscript Döküman',
			'kindMsOffice'    : 'Microsoft Office Dökümanı',
			'kindMsWord'      : 'Microsoft Word Dökümanı',
			'kindMsExcel'     : 'Microsoft Excel Dökümanı',
			'kindMsPP'        : 'Microsoft Powerpoint Sunum',
			'kindOO'          : 'Open Office Dökümanı',
			'kindAppFlash'    : 'Flash Uygulaması',
			'kindPDF'         : 'Adobe Acrobat (PDF)',
			'kindTorrent'     : 'Bittorrent dosyası',
			'kind7z'          : '7z arşivi',
			'kindTAR'         : 'TAR arşivi',
			'kindGZIP'        : 'GZIP arşivi',
			'kindBZIP'        : 'BZIP arşivi',
			'kindZIP'         : 'ZIP arşivi',
			'kindRAR'         : 'RAR arşivi',
			'kindJAR'         : 'Java JAR dosyası',
			'kindTTF'         : 'True Type font',
			'kindOTF'         : 'Open Type font',
			'kindRPM'         : 'RPM paketi',
			// texts
			'kindText'        : 'Text Dökümanı',
			'kindTextPlain'   : 'Plain text',
			'kindPHP'         : 'PHP dosyası',
			'kindCSS'         : 'CSS dosyası',
			'kindHTML'        : 'HTML dosyası',
			'kindJS'          : 'Javascript dosyası',
			'kindRTF'         : 'RTF dosyası',
			'kindC'           : 'C dosyası',
			'kindCHeader'     : 'C başlık dosyası',
			'kindCPP'         : 'C++ dosyası',
			'kindCPPHeader'   : 'C++ başlık dosyası',
			'kindShell'       : 'Unix shell dosyası',
			'kindPython'      : 'Python dosyası',
			'kindJava'        : 'Java dosyası',
			'kindRuby'        : 'Ruby dosyası',
			'kindPerl'        : 'Perl dosyası',
			'kindSQL'         : 'SQL dosyası',
			'kindXML'         : 'XML dosyası',
			'kindAWK'         : 'AWK dosyası',
			'kindCSV'         : 'CSV dosyası',
			'kindDOCBOOK'     : 'Docbook XML dosyası',
			// images
			'kindImage'       : 'Resim',
			'kindBMP'         : 'BMP Resim',
			'kindJPEG'        : 'JPEG Resim',
			'kindGIF'         : 'GIF Resim',
			'kindPNG'         : 'PNG Resim',
			'kindTIFF'        : 'TIFF Resim',
			'kindTGA'         : 'TGA Resim',
			'kindPSD'         : 'Adobe Photoshop Resim',
			'kindXBITMAP'     : 'X bitmap Resim',
			'kindPXM'         : 'Pixelmator Resim',
			// media
			'kindAudio'       : 'Ses Dosyası',
			'kindAudioMPEG'   : 'MPEG ses',
			'kindAudioMPEG4'  : 'MPEG-4 ses',
			'kindAudioMIDI'   : 'MIDI ses',
			'kindAudioOGG'    : 'Ogg Vorbis ses',
			'kindAudioWAV'    : 'WAV ses',
			'AudioPlaylist'   : 'MP3 Çalma Listesi',
			'kindVideo'       : 'Video Dosyası',
			'kindVideoDV'     : 'DV video',
			'kindVideoMPEG'   : 'MPEG video',
			'kindVideoMPEG4'  : 'MPEG-4 video',
			'kindVideoAVI'    : 'AVI video',
			'kindVideoMOV'    : 'Quick Time video',
			'kindVideoWM'     : 'Windows Media video',
			'kindVideoFlash'  : 'Flash video',
			'kindVideoMKV'    : 'Matroska video',
			'kindVideoOGG'    : 'Ogg video'
		}
	}
};/**
 * Vietnamese translation
 * @author Chung Thủy f <chungthuyf@gmail.com>
 * @version 16-01-2013
 */
if (elFinder && elFinder.prototype && typeof(elFinder.prototype.i18) == 'object') {
  elFinder.prototype.i18.vi = {
    translator : 'Chung Thủy f &lt;chungthuyf@gmail.com&gt;',
    language : 'Ngôn ngữ Việt Nam',
    direction : 'ltr',
    dateFormat : 'd.m.Y H:i',
    fancyDateFormat : '$1 H:i',
    messages : {

        /********************************** errors **********************************/
        'error'                : 'Lỗi',
        'errUnknown'           : 'Lỗi không xác định được.',
        'errUnknownCmd'        : 'Lỗi không rõ lệnh.',
        'errJqui'              : 'Invalid jQuery UI configuration. Selectable, draggable and droppable components must be included.',
        'errNode'              : 'elFinder requires DOM Element to be created.',
        'errURL'               : 'Cấu elFinder không hợp lệ! URL không được thiết lập tùy chọn.',
        'errAccess'            : 'Truy cập bị từ chối.',
        'errConnect'           : 'Unable to connect to backend.',
        'errAbort'             : 'Kết nối bị hủy bỏ.',
        'errTimeout'           : 'Connection timeout.',
        'errNotFound'          : 'Backend not found.',
        'errResponse'          : 'Invalid backend response.',
        'errConf'              : 'Invalid backend configuration.',
        'errJSON'              : 'PHP JSON module not installed.',
        'errNoVolumes'         : 'Readable volumes not available.',
        'errCmdParams'         : 'Invalid parameters for command "$1".',
        'errDataNotJSON'       : 'Data is not JSON.',
        'errDataEmpty'         : 'Data is empty.',
        'errCmdReq'            : 'Backend request requires command name.',
        'errOpen'              : 'Unable to open "$1".',
        'errNotFolder'         : 'Object is not a folder.',
        'errNotFile'           : 'Object is not a file.',
        'errRead'              : 'Unable to read "$1".',
        'errWrite'             : 'Unable to write into "$1".',
        'errPerm'              : 'Permission denied.',
        'errLocked'            : '"$1" is locked and can not be renamed, moved or removed.',
        'errExists'            : 'File named "$1" already exists.',
        'errInvName'           : 'Invalid file name.',
        'errFolderNotFound'    : 'Folder not found.',
        'errFileNotFound'      : 'File not found.',
        'errTrgFolderNotFound' : 'Target folder "$1" not found.',
        'errPopup'             : 'Browser prevented opening popup window. To open file enable it in browser options.',
        'errMkdir'             : 'Unable to create folder "$1".',
        'errMkfile'            : 'Unable to create file "$1".',
        'errRename'            : 'Unable to rename "$1".',
        'errCopyFrom'          : 'Copying files from volume "$1" not allowed.',
        'errCopyTo'            : 'Copying files to volume "$1" not allowed.',
        'errUploadCommon'      : 'Upload error.',
        'errUpload'            : 'Unable to upload "$1".',
        'errUploadNoFiles'     : 'No files found for upload.',
        'errMaxSize'           : 'Data exceeds the maximum allowed size.',
        'errFileMaxSize'       : 'File exceeds maximum allowed size.',
        'errUploadMime'        : 'File type not allowed.',
        'errUploadTransfer'    : '"$1" transfer error.', 
        'errSave'              : 'Unable to save "$1".',
        'errCopy'              : 'Unable to copy "$1".',
        'errMove'              : 'Unable to move "$1".',
        'errCopyInItself'      : 'Unable to copy "$1" into itself.',
        'errRm'                : 'Unable to remove "$1".',
        'errExtract'           : 'Unable to extract files from "$1".',
        'errArchive'           : 'Unable to create archive.',
        'errArcType'           : 'Unsupported archive type.',
        'errNoArchive'         : 'File is not archive or has unsupported archive type.',
        'errCmdNoSupport'      : 'Backend does not support this command.',
        'errReplByChild'       : 'The folder “$1” can’t be replaced by an item it contains.',
        'errArcSymlinks'       : 'For security reason denied to unpack archives contains symlinks.',
        'errArcMaxSize'        : 'Archive files exceeds maximum allowed size.',
        'errResize'            : 'Unable to resize "$1".',
        'errUsupportType'      : 'Unsupported file type.',

        /******************************* commands names ********************************/
        'cmdarchive'   : 'Tạo tập tin nén',
        'cmdback'      : 'Trở lại',
        'cmdcopy'      : 'Sao chép',
        'cmdcut'       : 'Cắt',
        'cmddownload'  : 'Tải về',
        'cmdduplicate' : 'Bản sao',
        'cmdedit'      : 'Sửa tập tin',
        'cmdextract'   : 'Giải nén tập tin',
        'cmdforward'   : 'Trước',
        'cmdgetfile'   : 'Chọn tập tin',
        'cmdhelp'      : 'Giới thiệu phần mềm',
        'cmdhome'      : 'Home',
        'cmdinfo'      : 'Thông tin',
        'cmdmkdir'     : 'Thư mục',
        'cmdmkfile'    : 'Tạo tập tin Text',
        'cmdopen'      : 'Mở',
        'cmdpaste'     : 'Paste',
        'cmdquicklook' : 'Xem trước',
        'cmdreload'    : 'Nạp lại',
        'cmdrename'    : 'Đổi tên',
        'cmdrm'        : 'Xóa',
        'cmdsearch'    : 'Tìm tập tin',
        'cmdup'        : 'Go to parent directory',
        'cmdupload'    : 'Tải tập tin lên',
        'cmdview'      : 'Xem',
        'cmdresize'    : 'Resize image',
        'cmdsort'      : 'Sắp xếp',

        /*********************************** buttons ***********************************/ 
        'btnClose'  : 'Đóng',
        'btnSave'   : 'Lưu',
        'btnRm'     : 'Gỡ bỏ',
        'btnApply'  : 'Áp dụng',
        'btnCancel' : 'Hủy bỏ',
        'btnNo'     : 'Không',
        'btnYes'    : 'Đồng ý',

        /******************************** notifications ********************************/
        'ntfopen'     : 'Mở thư mục',
        'ntffile'     : 'Mở tập tin',
        'ntfreload'   : 'Nạp lại nội dung thư mục',
        'ntfmkdir'    : 'Tạo thư mục',
        'ntfmkfile'   : 'Tạo tập tin',
        'ntfrm'       : 'Xóa tập tin',
        'ntfcopy'     : 'Sao chép tập tin',
        'ntfmove'     : 'Di chuyển tập tin',
        'ntfprepare'  : 'Chuẩn bị để sao chép các tập tin',
        'ntfrename'   : 'Đổi tên tập tin',
        'ntfupload'   : 'Tải tập tin lên',
        'ntfdownload' : 'Tải tập tin',
        'ntfsave'     : 'Lưu tập tin',
        'ntfarchive'  : 'Tạo tập tin nén',
        'ntfextract'  : 'Giải nén tập tin',
        'ntfsearch'   : 'Tìm kiếm tập tin',
        'ntfsmth'     : 'Doing something >_<',
        'ntfloadimg'  : 'Đang tải hình ảnh',

        /************************************ dates **********************************/
        'dateUnknown' : 'Chưa biết',
        'Today'       : 'Hôm nay',
        'Yesterday'   : 'Yesterday',
        'Jan'         : 'Jan',
        'Feb'         : 'Feb',
        'Mar'         : 'Mar',
        'Apr'         : 'Apr',
        'May'         : 'May',
        'Jun'         : 'Jun',
        'Jul'         : 'Jul',
        'Aug'         : 'Aug',
        'Sep'         : 'Sep',
        'Oct'         : 'Oct',
        'Nov'         : 'Nov',
        'Dec'         : 'Dec',
        'January'     : 'January',
        'February'    : 'February',
        'March'       : 'March',
        'April'       : 'April',
        'May'         : 'May',
        'June'        : 'June',
        'July'        : 'July',
        'August'      : 'August',
        'September'   : 'September',
        'October'     : 'October',
        'November'    : 'November',
        'December'    : 'December',
        'Sunday'      : 'Sunday', 
        'Monday'      : 'Monday', 
        'Tuesday'     : 'Tuesday', 
        'Wednesday'   : 'Wednesday', 
        'Thursday'    : 'Thursday', 
        'Friday'      : 'Friday', 
        'Saturday'    : 'Saturday',
        'Sun'         : 'Sun', 
        'Mon'         : 'Mon', 
        'Tue'         : 'Tue', 
        'Wed'         : 'Wed', 
        'Thu'         : 'Thu', 
        'Fri'         : 'Fri', 
        'Sat'         : 'Sat',
        /******************************** sort variants ********************************/
        'sortnameDirsFirst' : 'by name (folders first)', 
        'sortkindDirsFirst' : 'by kind (folders first)', 
        'sortsizeDirsFirst' : 'by size (folders first)', 
        'sortdateDirsFirst' : 'by date (folders first)', 
        'sortname'          : 'by name', 
        'sortkind'          : 'by kind', 
        'sortsize'          : 'by size',
        'sortdate'          : 'by date',

        /********************************** messages **********************************/
        'confirmReq'      : 'Confirmation required',
        'confirmRm'       : 'Are you sure you want to remove files?<br/>This cannot be undone!',
        'confirmRepl'     : 'Replace old file with new one?',
        'apllyAll'        : 'Apply to all',
        'name'            : 'Name',
        'size'            : 'Size',
        'perms'           : 'Permissions',
        'modify'          : 'Modified',
        'kind'            : 'Kind',
        'read'            : 'read',
        'write'           : 'write',
        'noaccess'        : 'no access',
        'and'             : 'and',
        'unknown'         : 'unknown',
        'selectall'       : 'Select all files',
        'selectfiles'     : 'Select file(s)',
        'selectffile'     : 'Select first file',
        'selectlfile'     : 'Select last file',
        'viewlist'        : 'List view',
        'viewicons'       : 'Icons view',
        'places'          : 'Places',
        'calc'            : 'Calculate', 
        'path'            : 'Path',
        'aliasfor'        : 'Alias for',
        'locked'          : 'Locked',
        'dim'             : 'Dimensions',
        'files'           : 'Files',
        'folders'         : 'Folders',
        'items'           : 'Items',
        'yes'             : 'yes',
        'no'              : 'no',
        'link'            : 'Link',
        'searcresult'     : 'Search results',  
        'selected'        : 'selected items',
        'about'           : 'About',
        'shortcuts'       : 'Shortcuts',
        'help'            : 'Help',
        'webfm'           : 'Web file manager',
        'ver'             : 'Version',
        'protocol'        : 'protocol version',
        'homepage'        : 'Project home',
        'docs'            : 'Documentation',
        'github'          : 'Fork us on Github',
        'twitter'         : 'Follow us on twitter',
        'facebook'        : 'Join us on facebook',
        'team'            : 'Team',
        'chiefdev'        : 'chief developer',
        'developer'       : 'developer',
        'contributor'     : 'contributor',
        'maintainer'      : 'maintainer',
        'translator'      : 'translator',
        'icons'           : 'Icons',
        'dontforget'      : 'and don\'t forget to take your towel',
        'shortcutsof'     : 'Shortcuts disabled',
        'dropFiles'       : 'Drop files here',
        'or'              : 'or',
        'selectForUpload' : 'Select files to upload',
        'moveFiles'       : 'Move files',
        'copyFiles'       : 'Copy files',
        'rmFromPlaces'    : 'Remove from places',
        'untitled folder' : 'untitled folder',
        'untitled file.txt' : 'untitled file.txt',
        'aspectRatio'     : 'Aspect ratio',
        'scale'           : 'Scale',
        'width'           : 'Width',
        'height'          : 'Height',
        'mode'            : 'Mode',
        'resize'          : 'Resize',
        'crop'            : 'Crop',
        'rotate'          : 'Rotate',
        'rotate-cw'       : 'Rotate 90 degrees CW',
        'rotate-ccw'      : 'Rotate 90 degrees CCW',
        'degree'          : 'Degree',

        /********************************** mimetypes **********************************/
        'kindUnknown'     : 'Unknown',
        'kindFolder'      : 'Folder',
        'kindAlias'       : 'Alias',
        'kindAliasBroken' : 'Broken alias',
        // applications
        'kindApp'         : 'Application',
        'kindPostscript'  : 'Postscript document',
        'kindMsOffice'    : 'Microsoft Office document',
        'kindMsWord'      : 'Microsoft Word document',
        'kindMsExcel'     : 'Microsoft Excel document',
        'kindMsPP'        : 'Microsoft Powerpoint presentation',
        'kindOO'          : 'Open Office document',
        'kindAppFlash'    : 'Flash application',
        'kindPDF'         : 'Portable Document Format (PDF)',
        'kindTorrent'     : 'Bittorrent file',
        'kind7z'          : '7z archive',
        'kindTAR'         : 'TAR archive',
        'kindGZIP'        : 'GZIP archive',
        'kindBZIP'        : 'BZIP archive',
        'kindZIP'         : 'ZIP archive',
        'kindRAR'         : 'RAR archive',
        'kindJAR'         : 'Java JAR file',
        'kindTTF'         : 'True Type font',
        'kindOTF'         : 'Open Type font',
        'kindRPM'         : 'RPM package',
        // texts
        'kindText'        : 'Text document',
        'kindTextPlain'   : 'Plain text',
        'kindPHP'         : 'PHP source',
        'kindCSS'         : 'Cascading style sheet',
        'kindHTML'        : 'HTML document',
        'kindJS'          : 'Javascript source',
        'kindRTF'         : 'Rich Text Format',
        'kindC'           : 'C source',
        'kindCHeader'     : 'C header source',
        'kindCPP'         : 'C++ source',
        'kindCPPHeader'   : 'C++ header source',
        'kindShell'       : 'Unix shell script',
        'kindPython'      : 'Python source',
        'kindJava'        : 'Java source',
        'kindRuby'        : 'Ruby source',
        'kindPerl'        : 'Perl script',
        'kindSQL'         : 'SQL source',
        'kindXML'         : 'XML document',
        'kindAWK'         : 'AWK source',
        'kindCSV'         : 'Comma separated values',
        'kindDOCBOOK'     : 'Docbook XML document',
        // images
        'kindImage'       : 'Image',
        'kindBMP'         : 'BMP image',
        'kindJPEG'        : 'JPEG image',
        'kindGIF'         : 'GIF Image',
        'kindPNG'         : 'PNG Image',
        'kindTIFF'        : 'TIFF image',
        'kindTGA'         : 'TGA image',
        'kindPSD'         : 'Adobe Photoshop image',
        'kindXBITMAP'     : 'X bitmap image',
        'kindPXM'         : 'Pixelmator image',
        // media
        'kindAudio'       : 'Audio media',
        'kindAudioMPEG'   : 'MPEG audio',
        'kindAudioMPEG4'  : 'MPEG-4 audio',
        'kindAudioMIDI'   : 'MIDI audio',
        'kindAudioOGG'    : 'Ogg Vorbis audio',
        'kindAudioWAV'    : 'WAV audio',
        'AudioPlaylist'   : 'MP3 playlist',
        'kindVideo'       : 'Video media',
        'kindVideoDV'     : 'DV movie',
        'kindVideoMPEG'   : 'MPEG movie',
        'kindVideoMPEG4'  : 'MPEG-4 movie',
        'kindVideoAVI'    : 'AVI movie',
        'kindVideoMOV'    : 'Quick Time movie',
        'kindVideoWM'     : 'Windows Media movie',
        'kindVideoFlash'  : 'Flash movie',
        'kindVideoMKV'    : 'Matroska movie',
        'kindVideoOGG'    : 'Ogg movie'
    }
  }
}
;/**
 * Simplified Chinese translation
 * @author deerchao <deerchao@gmail.com>
 * @author Andy Hu <andyhu7@yahoo.com.hk>
 * @version 2013-01-29
 */
if (elFinder && elFinder.prototype && typeof(elFinder.prototype.i18) == 'object') {
	elFinder.prototype.i18.zh_CN = {
		translator : '翻译者 deerchao &lt;deerchao@gmail.com&gt;, Andy Hu &lt;andyhu7@yahoo.com.hk&gt;',
		language   : '简体中文',
		direction  : 'ltr',
		dateFormat : 'Y-m-d H:i',
		fancyDateFormat : '$1 H:i',
		messages   : {
			
			/********************************** errors **********************************/
			'error'                : '错误',
			'errUnknown'           : '未知的错误.',
			'errUnknownCmd'        : '未知的命令.',
			'errJqui'              : '无效的 jQuery UI 配置. 必须包含 Selectable, draggable 以及 droppable 组件.',
			'errNode'              : 'elFinder 需要能创建 DOM 元素.',
			'errURL'               : '无效的 elFinder 配置! URL 选项未配置.',
			'errAccess'            : '访问被拒绝.',
			'errConnect'           : '不能连接到后端.',
			'errAbort'             : '连接中止.',
			'errTimeout'           : '连接超时.',
			'errNotFound'          : '未找到后端.',
			'errResponse'          : '无效的后端响应.',
			'errConf'              : '无效的后端配置.',
			'errJSON'              : 'PHP JSON 模块未安装.',
			'errNoVolumes'         : '无可读的卷.',
			'errCmdParams'         : '无效的参数, 命令: "$1".',
			'errDataNotJSON'       : '响应不符合 JSON 格式.',
			'errDataEmpty'         : '响应为空.',
			'errCmdReq'            : '后端请求需要命令名称.',
			'errOpen'              : '无法打开 "$1".',
			'errNotFolder'         : '对象不是文件夹.',
			'errNotFile'           : '对象不是文件.',
			'errRead'              : '无法读取 "$1".',
			'errWrite'             : '无法写入 "$1".',
			'errPerm'              : '无权限.',
			'errLocked'            : '"$1" 被锁定,不能重命名, 移动或删除.',
			'errExists'            : '文件 "$1" 已经存在了.',
			'errInvName'           : '无效的文件名.',
			'errFolderNotFound'    : '未找到文件夹.',
			'errFileNotFound'      : '未找到文件.',
			'errTrgFolderNotFound' : '未找到目标文件夹 "$1".',
			'errPopup'             : '浏览器拦截了弹出窗口. 请在选项中允许弹出窗口.',
			'errMkdir'             : '不能创建文件夹 "$1".',
			'errMkfile'            : '不能创建文件 "$1".',
			'errRename'            : '不能重命名 "$1".',
			'errCopyFrom'          : '不允许从卷 "$1" 复制.',
			'errCopyTo'            : '不允许向卷 "$1" 复制.',
			'errUpload'            : '上传出错.',
			'errUploadFile'        : '无法上传 "$1".',
			'errUploadNoFiles'     : '未找到要上传的文件.',
			'errUploadTotalSize'   : '数据超过了允许的最大大小.',
			'errUploadFileSize'    : '文件超过了允许的最大大小.',
			'errUploadMime'        : '不允许的文件类型.',
			'errUploadTransfer'    : '"$1" 传输错误.', 
			'errNotReplace'        : '对象 "$1" 已经在此位置存在, 不能被其他对象替换.', // new
			'errReplace'           : '无法替换 "$1".',
			'errSave'              : '无法保存 "$1".',
			'errCopy'              : '无法复制 "$1".',
			'errMove'              : '无法移动 "$1".',
			'errCopyInItself'      : '不能移动 "$1" 到原有位置.',
			'errRm'                : '无法删除 "$1".',
			'errRmSrc'             : '不能删除源文件.',
			'errExtract'           : '无法从 "$1" 提取文件.',
			'errArchive'           : '无法创建压缩包.',
			'errArcType'           : '不支持的压缩格式.',
			'errNoArchive'         : '文件不是压缩包, 或者不支持该压缩格式.',
			'errCmdNoSupport'      : '后端不支持该命令.',
			'errReplByChild'       : '文件夹 “$1” 不能被它所包含的项目替换.',
			'errArcSymlinks'       : '出于安全上的考虑，不允许解压包含符号链接的压缩包.',
			'errArcMaxSize'        : '压缩包文件超过最大允许文件大小范围.',
			'errResize'            : '无法重新调整大小 "$1".',
			'errUsupportType'      : '不被支持的文件格式.',
			'errNotUTF8Content'    : '文件 "$1" 不是 UTF-8 格式, 不能编辑.',  // added 9.11.2011
			'errNetMount'          : '无法装载 "$1".', // added 17.04.2012
			'errNetMountNoDriver'  : '不支持该协议.',     // added 17.04.2012
			'errNetMountFailed'    : '装载失败.',         // added 17.04.2012
			'errNetMountHostReq'   : '需要指定主机.', // added 18.04.2012
			/******************************* commands names ********************************/
			'cmdarchive'   : '创建压缩包',
			'cmdback'      : '后退',
			'cmdcopy'      : '复制',
			'cmdcut'       : '剪切',
			'cmddownload'  : '下载',
			'cmdduplicate' : '创建复本',
			'cmdedit'      : '编辑文件',
			'cmdextract'   : '从压缩包提取文件',
			'cmdforward'   : '前进',
			'cmdgetfile'   : '选择文件',
			'cmdhelp'      : '关于本软件',
			'cmdhome'      : '首页',
			'cmdinfo'      : '查看信息',
			'cmdmkdir'     : '新建文件夹',
			'cmdmkfile'    : '新建文本文件',
			'cmdopen'      : '打开',
			'cmdpaste'     : '粘贴',
			'cmdquicklook' : '预览',
			'cmdreload'    : '刷新',
			'cmdrename'    : '重命名',
			'cmdrm'        : '删除',
			'cmdsearch'    : '查找文件',
			'cmdup'        : '转到上一级文件夹',
			'cmdupload'    : '上传文件',
			'cmdview'      : '查看',
			'cmdresize'    : '重新调整大小',
			'cmdsort'      : '排序',
			'cmdnetmount'  : '装载网络卷', // added 18.04.2012
			
			/*********************************** buttons ***********************************/ 
			'btnClose'  : '关闭',
			'btnSave'   : '保存',
			'btnRm'     : '删除',
			'btnApply'  : '应用',
			'btnCancel' : '取消',
			'btnNo'     : '否',
			'btnYes'    : '是',
			'btnMount'  : '装载',  // added 18.04.2012
			/******************************** notifications ********************************/
			'ntfopen'     : '打开文件夹',
			'ntffile'     : '打开文件',
			'ntfreload'   : '刷新文件夹内容',
			'ntfmkdir'    : '创建文件夹',
			'ntfmkfile'   : '创建文件',
			'ntfrm'       : '删除文件',
			'ntfcopy'     : '复制文件',
			'ntfmove'     : '移动文件',
			'ntfprepare'  : '准备复制文件',
			'ntfrename'   : '重命名文件',
			'ntfupload'   : '上传文件',
			'ntfdownload' : '下载文件',
			'ntfsave'     : '保存文件',
			'ntfarchive'  : '创建压缩包',
			'ntfextract'  : '从压缩包提取文件',
			'ntfsearch'   : '搜索文件',
			'ntfresize'   : '正在更改尺寸',
			'ntfsmth'     : '正在忙 >_<',
			'ntfloadimg'  : '正在加载图片',
      		'ntfnetmount' : '正在装载网络卷', // added 18.04.2012
			
			/************************************ dates **********************************/
			'dateUnknown' : '未知',
			'Today'       : '今天',
			'Yesterday'   : '昨天',
			'Jan'         : '一月',
			'Feb'         : '二月',
			'Mar'         : '三月',
			'Apr'         : '四月',
			'May'         : '五月',
			'Jun'         : '六月',
			'Jul'         : '七月',
			'Aug'         : '八月',
			'Sep'         : '九月',
			'Oct'         : '十月',
			'Nov'         : '十一月',
			'Dec'         : '十二月',
			'January'     : '一月',
			'February'    : '二月',
			'March'       : '三月',
			'April'       : '四月',
			'May'         : '五月',
			'June'        : '六月',
			'July'        : '七月',
			'August'      : '八月',
			'September'   : '九月',
			'October'     : '十月',
			'November'    : '十一月',
			'December'    : '十二月',
			'Sunday'      : '星期日',
			'Monday'      : '星期一',
			'Tuesday'     : '星期二',
			'Wednesday'   : '星期三',
			'Thursday'    : '星期四',
			'Friday'      : '星期五',
			'Saturday'    : '星期六',
			'Sun'         : '周日', 
			'Mon'         : '周一', 
			'Tue'         : '周二', 
			'Wed'         : '周三', 
			'Thu'         : '周四', 
			'Fri'         : '周五', 
			'Sat'         : '周六',
			/******************************** sort variants ********************************/
			'sortnameDirsFirst' : '按名称 (文件夹在最前)', 
			'sortkindDirsFirst' : '按类型 (文件夹在最前)', 
			'sortsizeDirsFirst' : '按大小 (文件夹在最前)', 
			'sortdateDirsFirst' : '按日期 (文件夹在最前)', 
			'sortname'          : '按名称', 
			'sortkind'          : '按类型', 
			'sortsize'          : '按大小',
			'sortdate'          : '按日期',

			/********************************** messages **********************************/
			'confirmReq'      : '请确认',
			'confirmRm'       : '确定要删除文件吗?<br/>该操作不可撤销!',
			'confirmRepl'     : '用新的文件替换原有文件?',
			'apllyAll'        : '全部应用',
			'name'            : '名称',
			'size'            : '大小',
			'perms'           : '权限',
			'modify'          : '修改于',
			'kind'            : '类别',
			'read'            : '读取',
			'write'           : '写入',
			'noaccess'        : '无权限',
			'and'             : '和',
			'unknown'         : '未知',
			'selectall'       : '选择所有文件',
			'selectfiles'     : '选择文件',
			'selectffile'     : '选择第一个文件',
			'selectlfile'     : '选择最后一个文件',
			'viewlist'        : '列表视图',
			'viewicons'       : '图标视图',
			'places'          : '位置',
			'calc'            : '计算', 
			'path'            : '路径',
			'aliasfor'        : '别名',
			'locked'          : '锁定',
			'dim'             : '尺寸',
			'files'           : '文件',
			'folders'         : '文件夹',
			'items'           : '项目',
			'yes'             : '是',
			'no'              : '否',
			'link'            : '链接',
			'searcresult'     : '搜索结果',  
			'selected'        : '选中的项目',
			'about'           : '关于',
			'shortcuts'       : '快捷键',
			'help'            : '帮助',
			'webfm'           : '网络文件管理器',
			'ver'             : '版本',
			'protocolver'     : '协议版本',
			'homepage'        : '项目主页',
			'docs'            : '文档',
			'github'          : 'Fork us on Github',
			'twitter'         : 'Follow us on twitter',
			'facebook'        : 'Join us on facebook',
			'team'            : '团队',
			'chiefdev'        : '首席开发',
			'developer'       : '开发',
			'contributor'     : '贡献',
			'maintainer'      : '维护',
			'translator'      : '翻译',
			'icons'           : '图标',
			'dontforget'      : '别忘了带上你擦汗的毛巾',
			'shortcutsof'     : '快捷键已禁用',
			'dropFiles'       : '把文件拖到这里',
			'or'              : '或者',
			'selectForUpload' : '选择要上传的文件',
			'moveFiles'       : '移动文件',
			'copyFiles'       : '复制文件',
			'rmFromPlaces'    : '从位置中删除',
			'untitled folder' : '未命名文件夹',
			'untitled file.txt' : '未命名文件.txt',
			'aspectRatio'     : '保持比例',
			'scale'           : '高宽比',
			'width'           : '宽',
			'height'          : '高',
			'mode'            : '模式',
			'resize'          : '重新调整大小',
			'crop'            : '裁切',
			'rotate'          : '旋转',
			'rotate-cw'       : '顺时针旋转90度',
			'rotate-ccw'      : '逆时针旋转90度',
			'degree'          : '度',
			'port'            : '端口', // added 18.04.2012
			'user'            : '用户', // added 18.04.2012
			'pass'            : '密码', // added 18.04.2012
			
			/********************************** mimetypes **********************************/
			'kindUnknown'     : '未知',
			'kindFolder'      : '文件夹',
			'kindAlias'       : '别名',
			'kindAliasBroken' : '错误的别名',
			// applications
			'kindApp'         : '程序',
			'kindPostscript'  : 'Postscript 文档',
			'kindMsOffice'    : 'Microsoft Office 文档',
			'kindMsWord'      : 'Microsoft Word 文档',
			'kindMsExcel'     : 'Microsoft Excel 文档',
			'kindMsPP'        : 'Microsoft Powerpoint 演示',
			'kindOO'          : 'Open Office 文档',
			'kindAppFlash'    : 'Flash 程序',
			'kindPDF'         : 'Portable Document Format (PDF)',
			'kindTorrent'     : 'Bittorrent 文件',
			'kind7z'          : '7z 压缩包',
			'kindTAR'         : 'TAR 压缩包',
			'kindGZIP'        : 'GZIP 压缩包',
			'kindBZIP'        : 'BZIP 压缩包',
			'kindZIP'         : 'ZIP 压缩包',
			'kindRAR'         : 'RAR 压缩包',
			'kindJAR'         : 'Java JAR 文件',
			'kindTTF'         : 'True Type 字体',
			'kindOTF'         : 'Open Type 字体',
			'kindRPM'         : 'RPM 包',
			// texts
			'kindText'        : '文本文件',
			'kindTextPlain'   : '纯文本',
			'kindPHP'         : 'PHP 源代码',
			'kindCSS'         : '层叠样式表(CSS)',
			'kindHTML'        : 'HTML 文档',
			'kindJS'          : 'Javascript 源代码',
			'kindRTF'         : '富文本格式(RTF)',
			'kindC'           : 'C 源代码',
			'kindCHeader'     : 'C 头文件',
			'kindCPP'         : 'C++ 源代码',
			'kindCPPHeader'   : 'C++ 头文件',
			'kindShell'       : 'Unix 外壳脚本',
			'kindPython'      : 'Python 源代码',
			'kindJava'        : 'Java 源代码',
			'kindRuby'        : 'Ruby 源代码',
			'kindPerl'        : 'Perl 源代码',
			'kindSQL'         : 'SQL 脚本',
			'kindXML'         : 'XML 文档',
			'kindAWK'         : 'AWK 源代码',
			'kindCSV'         : '逗号分隔值文件(CSV)',
			'kindDOCBOOK'     : 'Docbook XML 文档',
			// images
			'kindImage'       : '图片',
			'kindBMP'         : 'BMP 图片',
			'kindJPEG'        : 'JPEG 图片',
			'kindGIF'         : 'GIF 图片',
			'kindPNG'         : 'PNG 图片',
			'kindTIFF'        : 'TIFF 图片',
			'kindTGA'         : 'TGA 图片',
			'kindPSD'         : 'Adobe Photoshop 图片',
			'kindXBITMAP'     : 'X bitmap 图片',
			'kindPXM'         : 'Pixelmator 图片',
			// media
			'kindAudio'       : '音频',
			'kindAudioMPEG'   : 'MPEG 音频',
			'kindAudioMPEG4'  : 'MPEG-4 音频',
			'kindAudioMIDI'   : 'MIDI 音频',
			'kindAudioOGG'    : 'Ogg Vorbis 音频',
			'kindAudioWAV'    : 'WAV 音频',
			'AudioPlaylist'   : 'MP3 播放列表',
			'kindVideo'       : '视频',
			'kindVideoDV'     : 'DV 视频',
			'kindVideoMPEG'   : 'MPEG 视频',
			'kindVideoMPEG4'  : 'MPEG-4 视频',
			'kindVideoAVI'    : 'AVI 视频',
			'kindVideoMOV'    : 'Quick Time 视频',
			'kindVideoWM'     : 'Windows Media 视频',
			'kindVideoFlash'  : 'Flash 视频',
			'kindVideoMKV'    : 'Matroska 视频',
			'kindVideoOGG'    : 'Ogg 视频'
		}
	}
}
;/**
 * Traditional Chinese translation
 * @author Yuwei Chuang <ywchuang.tw@gmail.com>
 * @version 2013-05-07
 */
if (elFinder && elFinder.prototype && typeof(elFinder.prototype.i18) == 'object') {
	elFinder.prototype.i18.zh_TW = {
		translator : 'Yuwei Chuang &lt;ywchuang.tw@gmail.com&gt;',
		language   : '正體中文',
		direction  : 'ltr',
		dateFormat : 'M d, Y h:i A', // Mar 13, 2012 05:27 PM
		fancyDateFormat : '$1 H:i',
		messages   : {
			
			/********************************** errors **********************************/
			'error'                : '錯誤',
			'errUnknown'           : '未知的錯誤.',
			'errUnknownCmd'        : '未知的指令.',
			'errJqui'              : '無效的 jQuery UI 設定. 必須包含 Selectable, draggable 以及 droppable 元件.',
			'errNode'              : 'elFinder 需要能建立 DOM 元素.',
			'errURL'               : '無效的 elFinder 設定! 尚未設定 URL 選項.',
			'errAccess'            : '拒絕存取.',
			'errConnect'           : '無法連線至後端.',
			'errAbort'             : '連線中斷.',
			'errTimeout'           : '連線逾時.',
			'errNotFound'          : '後端不存在.',
			'errResponse'          : '無效的後端回復.',
			'errConf'              : '無效的後端設定.',
			'errJSON'              : '未安裝 PHP JSON 模組.',
			'errNoVolumes'         : '無可讀取的 volumes.',
			'errCmdParams'         : '無效的參數, 指令: "$1".',
			'errDataNotJSON'       : '資料不是 JSON 格式.',
			'errDataEmpty'         : '沒有資料.',
			'errCmdReq'            : '後端請求需要命令名稱.',
			'errOpen'              : '無法打開 "$1".',
			'errNotFolder'         : '非資料夾.',
			'errNotFile'           : '非檔案.',
			'errRead'              : '無法讀取 "$1".',
			'errWrite'             : '無法寫入 "$1".',
			'errPerm'              : '無權限.',
			'errLocked'            : '"$1" 被鎖定,不能重新命名, 移動或删除.',
			'errExists'            : '檔案 "$1" 已經存在了.',
			'errInvName'           : '無效的檔案名稱.',
			'errFolderNotFound'    : '未找到資料夾.',
			'errFileNotFound'      : '未找到檔案.',
			'errTrgFolderNotFound' : '未找到目標資料夾 "$1".',
			'errPopup'             : '連覽器攔截了彈跳視窗. 請在瀏覽器選項允許彈跳視窗.',
			'errMkdir'             : '不能建立資料夾 "$1".',
			'errMkfile'            : '不能建立檔案 "$1".',
			'errRename'            : '不能重新命名 "$1".',
			'errCopyFrom'          : '不允許從 volume "$1" 複製.',
			'errCopyTo'            : '不允複製到 volume "$1".',
			'errUpload'            : '上船錯誤.',
			'errUploadFile'        : '無法上傳 "$1".',
			'errUploadNoFiles'     : '未找到要上傳的檔案.',
			'errUploadTotalSize'   : '資料超過了最大允許大小.',
			'errUploadFileSize'    : '檔案超過了最大允許大小.',
			'errUploadMime'        : '不允許的檔案類型.',
			'errUploadTransfer'    : '"$1" 傳輸錯誤.', 
			'errNotReplace'        : '"$1" 已經存在此位置, 不能被其他的替换.', // new
			'errReplace'           : '無法替换 "$1".',
			'errSave'              : '無法保存 "$1".',
			'errCopy'              : '無法複製 "$1".',
			'errMove'              : '無法移動 "$1".',
			'errCopyInItself'      : '無法移動 "$1" 到原有位置.',
			'errRm'                : '無法删除 "$1".',
			'errRmSrc'             : '無法删除來源檔案.',
			'errExtract'           : '無法從 "$1" 解壓縮檔案.',
			'errArchive'           : '無法建立壓縮膽案.',
			'errArcType'           : '不支援的壓縮格式.',
			'errNoArchive'         : '檔案不是壓縮檔案, 或者不支援該壓缩格式.',
			'errCmdNoSupport'      : '後端不支援該指令.',
			'errReplByChild'       : '資料夾 “$1” 不能被它所包含的檔案(資料夾)替换.',
			'errArcSymlinks'       : '出于安全上的考量，禁止解壓縮檔案包含不允許的檔案名稱.',
			'errArcMaxSize'        : '壓縮檔案超過最大允許檔案大小範圍.',
			'errResize'            : '無法重新調整大小 "$1".',
			'errUsupportType'      : '不支援的檔案格式.',
			'errNotUTF8Content'    : '檔案 "$1" 不是 UTF-8 格式, 不能編輯.',  // added 9.11.2011
			'errNetMount'          : '無法掛載 "$1".', // added 17.04.2012
			'errNetMountNoDriver'  : '不支援該通訊協議.',     // added 17.04.2012
			'errNetMountFailed'    : '掛載失敗.',         // added 17.04.2012
			'errNetMountHostReq'   : '需要指定主機位置.', // added 18.04.2012
			/******************************* commands names ********************************/
			'cmdarchive'   : '建立壓縮檔案',
			'cmdback'      : '後退',
			'cmdcopy'      : '複製',
			'cmdcut'       : '剪下',
			'cmddownload'  : '下載',
			'cmdduplicate' : '建立副本',
			'cmdedit'      : '編輯檔案',
			'cmdextract'   : '從壓縮檔案解壓縮',
			'cmdforward'   : '前進',
			'cmdgetfile'   : '選擇檔案',
			'cmdhelp'      : '關於本軟體',
			'cmdhome'      : '首頁',
			'cmdinfo'      : '查看關於',
			'cmdmkdir'     : '建立資料夾',
			'cmdmkfile'    : '建立文字檔案',
			'cmdopen'      : '打開',
			'cmdpaste'     : '貼上',
			'cmdquicklook' : '預覽',
			'cmdreload'    : '更新',
			'cmdrename'    : '重新命名',
			'cmdrm'        : '删除',
			'cmdsearch'    : '搜尋檔案',
			'cmdup'        : '移到上一層資料夾',
			'cmdupload'    : '上傳檔案',
			'cmdview'      : '查看',
			'cmdresize'    : '重新調整大小',
			'cmdsort'      : '排序',
			'cmdnetmount'  : '掛載 net volume', // added 18.04.2012
			
			/*********************************** buttons ***********************************/ 
			'btnClose'  : '關閉',
			'btnSave'   : '儲存',
			'btnRm'     : '删除',
			'btnApply'  : '使用',
			'btnCancel' : '取消',
			'btnNo'     : '否',
			'btnYes'    : '是',
			'btnMount'  : '掛載',  // added 18.04.2012
			/******************************** notifications ********************************/
			'ntfopen'     : '打開資料夾',
			'ntffile'     : '打開檔案',
			'ntfreload'   : '更新資料夾内容',
			'ntfmkdir'    : '建立資料夾',
			'ntfmkfile'   : '建立檔案',
			'ntfrm'       : '删除檔案',
			'ntfcopy'     : '複製檔案',
			'ntfmove'     : '移動檔案',
			'ntfprepare'  : '準備複製檔案',
			'ntfrename'   : '重新命名檔案',
			'ntfupload'   : '上傳檔案',
			'ntfdownload' : '下載檔案',
			'ntfsave'     : '儲存檔案',
			'ntfarchive'  : '建立壓縮檔案',
			'ntfextract'  : '從壓縮檔案解壓縮',
			'ntfsearch'   : '搜尋檔案',
			'ntfresize'   : '正在更改尺寸',
			'ntfsmth'     : '正在忙 >_<',
			'ntfloadimg'  : '正在讀取圖片',
      		'ntfnetmount' : '正在掛載 net volume', // added 18.04.2012
			
			/************************************ dates **********************************/
			'dateUnknown' : '未知',
			'Today'       : '今天',
			'Yesterday'   : '昨天',
			'Jan'         : '一月',
			'Feb'         : '二月',
			'Mar'         : '三月',
			'Apr'         : '四月',
			'May'         : '五月',
			'Jun'         : '六月',
			'Jul'         : '七月',
			'Aug'         : '八月',
			'Sep'         : '九月',
			'Oct'         : '十月',
			'Nov'         : '十一月',
			'Dec'         : '十二月',
			'January'     : '一月',
			'February'    : '二月',
			'March'       : '三月',
			'April'       : '四月',
			'May'         : '五月',
			'June'        : '六月',
			'July'        : '七月',
			'August'      : '八月',
			'September'   : '九月',
			'October'     : '十月',
			'November'    : '十一月',
			'December'    : '十二月',
			'Sunday'      : '星期日',
			'Monday'      : '星期一',
			'Tuesday'     : '星期二',
			'Wednesday'   : '星期三',
			'Thursday'    : '星期四',
			'Friday'      : '星期五',
			'Saturday'    : '星期六',
			'Sun'         : '周日', 
			'Mon'         : '周一', 
			'Tue'         : '周二', 
			'Wed'         : '周三', 
			'Thu'         : '周四', 
			'Fri'         : '周五', 
			'Sat'         : '周六',
			/******************************** sort variants ********************************/
			'sortnameDirsFirst' : '按名稱 (資料夾在最前)', 
			'sortkindDirsFirst' : '按類型 (資料夾在最前)', 
			'sortsizeDirsFirst' : '按大小 (資料夾在最前)', 
			'sortdateDirsFirst' : '按日期 (資料夾在最前)', 
			'sortname'          : '按名稱', 
			'sortkind'          : '按類型', 
			'sortsize'          : '按大小',
			'sortdate'          : '按日期',

			/********************************** messages **********************************/
			'confirmReq'      : '請確認',
			'confirmRm'       : '確定要删除檔案嗎?<br/>該操作不可回復!',
			'confirmRepl'     : '用新的檔案替换原有檔案?',
			'apllyAll'        : '全部使用',
			'name'            : '名稱',
			'size'            : '大小',
			'perms'           : '權限',
			'modify'          : '修改于',
			'kind'            : '類別',
			'read'            : '讀取',
			'write'           : '寫入',
			'noaccess'        : '無權限',
			'and'             : '和',
			'unknown'         : '未知',
			'selectall'       : '選擇所有檔案',
			'selectfiles'     : '選擇檔案',
			'selectffile'     : '選擇第一個檔案',
			'selectlfile'     : '選擇最後一個檔案',
			'viewlist'        : '列表檢視',
			'viewicons'       : '圖示檢視',
			'places'          : '位置',
			'calc'            : '計算', 
			'path'            : '路徑',
			'aliasfor'        : '别名',
			'locked'          : '鎖定',
			'dim'             : '尺寸',
			'files'           : '檔案',
			'folders'         : '資料夾',
			'items'           : '項目',
			'yes'             : '是',
			'no'              : '否',
			'link'            : '連結',
			'searcresult'     : '搜尋结果',  
			'selected'        : '選取的項目',
			'about'           : '關於',
			'shortcuts'       : '快捷鍵',
			'help'            : '幫助',
			'webfm'           : '網路檔案總管',
			'ver'             : '版本',
			'protocolver'     : '協定版本',
			'homepage'        : '首頁',
			'docs'            : '文件',
			'github'          : 'Fork us on Github',
			'twitter'         : 'Follow us on twitter',
			'facebook'        : 'Join us on facebook',
			'team'            : '團隊',
			'chiefdev'        : '首席開發者',
			'developer'       : '開發者',
			'contributor'     : '貢獻者',
			'maintainer'      : '維護者',
			'translator'      : '翻譯',
			'icons'           : '圖示',
			'dontforget'      : '别忘了帶上你擦汗的毛巾',
			'shortcutsof'     : '快捷鍵已禁用',
			'dropFiles'       : '把檔案拖到此處',
			'or'              : '或者',
			'selectForUpload' : '選擇要上傳的檔案',
			'moveFiles'       : '移動檔案',
			'copyFiles'       : '複製檔案',
			'rmFromPlaces'    : '從位置中删除',
			'untitled folder' : '未命名資料夾',
			'untitled file.txt' : '未命名檔案.txt',
			'aspectRatio'     : '保持比例',
			'scale'           : '寬高比',
			'width'           : '寬',
			'height'          : '高',
			'mode'            : '模式',
			'resize'          : '重新調整大小',
			'crop'            : '裁切',
			'rotate'          : '旋轉',
			'rotate-cw'       : '順時針旋轉90度',
			'rotate-ccw'      : '逆時針旋轉90度',
			'degree'          : '度',
			'port'            : '接口', // added 18.04.2012
			'user'            : '使用者', // added 18.04.2012
			'pass'            : '密碼', // added 18.04.2012
			
			/********************************** mimetypes **********************************/
			'kindUnknown'     : '未知',
			'kindFolder'      : '資料夾',
			'kindAlias'       : '别名',
			'kindAliasBroken' : '錯誤的别名',
			// applications
			'kindApp'         : '應用程式',
			'kindPostscript'  : 'Postscript 文件',
			'kindMsOffice'    : 'Microsoft Office 文件',
			'kindMsWord'      : 'Microsoft Word 文件',
			'kindMsExcel'     : 'Microsoft Excel 文件',
			'kindMsPP'        : 'Microsoft Powerpoint 簡報',
			'kindOO'          : 'Open Office 文件',
			'kindAppFlash'    : 'Flash 應用程式',
			'kindPDF'         : 'Portable Document Format (PDF)',
			'kindTorrent'     : 'Bittorrent 檔案',
			'kind7z'          : '7z 壓縮檔案',
			'kindTAR'         : 'TAR 壓縮檔案',
			'kindGZIP'        : 'GZIP 壓縮檔案',
			'kindBZIP'        : 'BZIP 壓縮檔案',
			'kindZIP'         : 'ZIP 壓縮檔案',
			'kindRAR'         : 'RAR 壓縮檔案',
			'kindJAR'         : 'Java JAR 檔案',
			'kindTTF'         : 'True Type 字體',
			'kindOTF'         : 'Open Type 字體',
			'kindRPM'         : 'RPM 封裝',
			// texts
			'kindText'        : '文字檔案',
			'kindTextPlain'   : '純文字',
			'kindPHP'         : 'PHP 程式碼',
			'kindCSS'         : 'CSS',
			'kindHTML'        : 'HTML 文件',
			'kindJS'          : 'Javascript 程式碼',
			'kindRTF'         : '富文字格式(RTF)',
			'kindC'           : 'C 程式碼',
			'kindCHeader'     : 'C 標頭檔',
			'kindCPP'         : 'C++ 程式碼',
			'kindCPPHeader'   : 'C++ 標頭檔',
			'kindShell'       : 'Unix Shell 脚本',
			'kindPython'      : 'Python 程式碼',
			'kindJava'        : 'Java 程式碼',
			'kindRuby'        : 'Ruby 程式碼',
			'kindPerl'        : 'Perl 程式碼',
			'kindSQL'         : 'SQL 脚本',
			'kindXML'         : 'XML 文件',
			'kindAWK'         : 'AWK 程式碼',
			'kindCSV'         : '逗號分隔值檔案(CSV)',
			'kindDOCBOOK'     : 'Docbook XML 文件',
			// images
			'kindImage'       : '圖片',
			'kindBMP'         : 'BMP 圖片',
			'kindJPEG'        : 'JPEG 圖片',
			'kindGIF'         : 'GIF 圖片',
			'kindPNG'         : 'PNG 圖片',
			'kindTIFF'        : 'TIFF 圖片',
			'kindTGA'         : 'TGA 圖片',
			'kindPSD'         : 'Adobe Photoshop 圖片',
			'kindXBITMAP'     : 'X bitmap 圖片',
			'kindPXM'         : 'Pixelmator 圖片',
			// media
			'kindAudio'       : '聲音',
			'kindAudioMPEG'   : 'MPEG 聲音',
			'kindAudioMPEG4'  : 'MPEG-4 聲音',
			'kindAudioMIDI'   : 'MIDI 聲音',
			'kindAudioOGG'    : 'Ogg Vorbis 聲音',
			'kindAudioWAV'    : 'WAV 聲音',
			'AudioPlaylist'   : 'MP3 播放列表',
			'kindVideo'       : '影片',
			'kindVideoDV'     : 'DV 影片',
			'kindVideoMPEG'   : 'MPEG 影片',
			'kindVideoMPEG4'  : 'MPEG-4 影片',
			'kindVideoAVI'    : 'AVI 影片',
			'kindVideoMOV'    : 'Quick Time 影片',
			'kindVideoWM'     : 'Windows Media 影片',
			'kindVideoFlash'  : 'Flash 影片',
			'kindVideoMKV'    : 'Matroska 影片',
			'kindVideoOGG'    : 'Ogg 影片'
		}
	}
}
;"use strict";
/**
 * @class dialogelfinder - open elFinder in dialog window
 *
 * @param  Object  elFinder options with dialog options
 * @example
 * $(selector).dialogelfinder({
 *     // some elfinder options
 *     title          : 'My files', // dialog title, default = "Files"
 *     width          : 850,        // dialog width, default 840
 *     autoOpen       : false,      // if false - dialog will not be opened after init, default = true
 *     destroyOnClose : true        // destroy elFinder on close dialog, default = false
 * })
 * @author Dmitry (dio) Levashov
 **/
$.fn.dialogelfinder = function(opts) {
	var position = 'elfinderPosition',
		destroy  = 'elfinderDestroyOnClose';
	
	this.not('.elfinder').each(function() {

		
		var doc     = $(document),
			toolbar = $('<div class="ui-widget-header dialogelfinder-drag ui-corner-top">'+(opts.title || 'Files')+'</div>'),
			button  = $('<a href="#" class="dialogelfinder-drag-close ui-corner-all"><span class="ui-icon ui-icon-closethick"/></a>')
				.appendTo(toolbar)
				.click(function(e) {
					e.preventDefault();
					
					node.dialogelfinder('close');
				}),
			node    = $(this).addClass('dialogelfinder')
				.css('position', 'absolute')
				.hide()
				.appendTo('body')
				.draggable({ handle : '.dialogelfinder-drag',
					     containment : 'window' })
				.elfinder(opts)
				.prepend(toolbar),
			elfinder = node.elfinder('instance');
		
		
		node.width(parseInt(node.width()) || 840) // fix width if set to "auto"
			.data(destroy, !!opts.destroyOnClose)
			.find('.elfinder-toolbar').removeClass('ui-corner-top');
		
		opts.position && node.data(position, opts.position);
		
		opts.autoOpen !== false && $(this).dialogelfinder('open');

	});
	
	if (opts == 'open') {
		var node = $(this),
			pos  = node.data(position) || {
				top  : parseInt($(document).scrollTop() + ($(window).height() < node.height() ? 2 : ($(window).height() - node.height())/2)),
				left : parseInt($(document).scrollLeft() + ($(window).width() < node.width()  ? 2 : ($(window).width()  - node.width())/2))
			},
			zindex = 100;

		if (node.is(':hidden')) {
			
			$('body').find(':visible').each(function() {
				var $this = $(this), z;
				
				if (this !== node[0] && $this.css('position') == 'absolute' && (z = parseInt($this.zIndex())) > zindex) {
					zindex = z + 1;
				}
			});

			node.zIndex(zindex).css(pos).show().trigger('resize')

			setTimeout(function() {
				// fix resize icon position and make elfinder active
				node.trigger('resize').mousedown();
			}, 200);
		}
	} else if (opts == 'close') {
		var node = $(this);
			
		if (node.is(':visible')) {
			!!node.data(destroy)
				? node.elfinder('destroy').remove()
				: node.elfinder('close');
		}
	} else if (opts == 'instance') {
		return $(this).getElFinder();
	}

	return this;
}

;$.fn.elfinder = function(o) {
	
	if (o == 'instance') {
		return this.getElFinder();
	}
	
	return this.each(function() {
		
		var cmd = typeof(o) == 'string' ? o : '';
		if (!this.elfinder) {
			new elFinder(this, typeof(o) == 'object' ? o : {})
		}
		
		switch(cmd) {
			case 'close':
			case 'hide':
				this.elfinder.hide();
				break;
				
			case 'open':
			case 'show':
				this.elfinder.show();
				break;
				
			case'destroy':
				this.elfinder.destroy();
				break;
		}
		
	})
}

$.fn.getElFinder = function() {
	var instance;
	
	this.each(function() {
		if (this.elfinder) {
			instance = this.elfinder;
			return false;
		}
	});
	
	return instance;
}
;"use strict";
/**
 * elFinder transport to support old protocol.
 *
 * @example
 * $('selector').elfinder({
 *   .... 
 *   transport : new elFinderSupportVer1()
 * })
 *
 * @author Dmitry (dio) Levashov
 **/
window.elFinderSupportVer1 = function(upload) {
	var self = this;
	
	this.upload = upload || 'auto';
	
	this.init = function(fm) {
		this.fm = fm;
		this.fm.parseUploadData = function(text) {
			var data;

			if (!$.trim(text)) {
				return {error : ['errResponse', 'errDataEmpty']};
			}

			try {
				data = $.parseJSON(text);
			} catch (e) {
				return {error : ['errResponse', 'errDataNotJSON']}
			}
			
			return self.normalize('upload', data);
		}
	}
	
	
	this.send = function(opts) {
		var self = this,
			fm = this.fm,
			dfrd = $.Deferred(),
			cmd = opts.data.cmd,
			args = [],
			_opts = {},
			data,
			xhr;
			
		dfrd.abort = function() {
			xhr.state() == 'pending' && xhr.abort();
		}
		
		switch (cmd) {
			case 'open':
				opts.data.tree = 1;
				break;
			case 'parents':
			case 'tree':
				return dfrd.resolve({tree : []});
				break;
			case 'get':
				opts.data.cmd = 'read';
				opts.data.current = fm.file(opts.data.target).phash;
				break;
			case 'put':
				opts.data.cmd = 'edit';
				opts.data.current = fm.file(opts.data.target).phash;
				break;
			case 'archive':
			case 'rm':
				opts.data.current = fm.file(opts.data.targets[0]).phash;
				break;
			case 'extract':
			case 'rename':
			case 'resize':
				opts.data.current = fm.file(opts.data.target).phash;
				break;
			case 'duplicate':
				_opts = $.extend(true, {}, opts);

				$.each(opts.data.targets, function(i, hash) {
					$.ajax($.extend(_opts, {data : {cmd : 'duplicate', target : hash, current : fm.file(hash).phash}}))
						.error(function(error) {
							fm.error(fm.res('error', 'connect'));
						})
						.done(function(data) {
							data = self.normalize('duplicate', data);
							if (data.error) {
								fm.error(data.error);
							} else if (data.added) {
								fm.trigger('add', {added : data.added});
							}
						})
				});
				return dfrd.resolve({})
				break;
				
			case 'mkdir':
			case 'mkfile':
				opts.data.current = opts.data.target;
				break;
			case 'paste':
				opts.data.current = opts.data.dst
				break;
				
			case 'size':
				return dfrd.resolve({error : fm.res('error', 'cmdsupport')});
				break;
			case 'search':
				return dfrd.resolve({error : fm.res('error', 'cmdsupport')});
				break;
				
		}
		// cmd = opts.data.cmd
		
		xhr = $.ajax(opts)
			.fail(function(error) {
				dfrd.reject(error)
			})
			.done(function(raw) {
				data = self.normalize(cmd, raw);
				
				// cmd != 'open' && self.fm.log(data);
				
				if (cmd == 'paste' && !data.error) {
					fm.sync();
					dfrd.resolve({});
				} else {
					dfrd.resolve(data);
				}
			})
			
		return dfrd;
		
		return $.ajax(opts);
	}
	
	// fix old connectors errors messages as possible
	// this.errors = {
	// 	'Unknown command'                                  : 'Unknown command.',
	// 	'Invalid backend configuration'                    : 'Invalid backend configuration.',
	// 	'Access denied'                                    : 'Access denied.',
	// 	'PHP JSON module not installed'                    : 'PHP JSON module not installed.',
	// 	'File not found'                                   : 'File not found.',
	// 	'Invalid name'                                     : 'Invalid file name.',
	// 	'File or folder with the same name already exists' : 'File named "$1" already exists in this location.',
	// 	'Not allowed file type'                            : 'Not allowed file type.',
	// 	'File exceeds the maximum allowed filesize'        : 'File exceeds maximum allowed size.',
	// 	'Unable to copy into itself'                       : 'Unable to copy "$1" into itself.',
	// 	'Unable to create archive'                         : 'Unable to create archive.',
	// 	'Unable to extract files from archive'             : 'Unable to extract files from "$1".'
	// }
	
	this.normalize = function(cmd, data) {
		var self = this,
			files = {}, 
			filter = function(file) { return file && file.hash && file.name && file.mime ? file : null; },
			phash;

		if ((cmd == 'tmb' || cmd == 'get')) {
			return data;
		}
		
		// if (data.error) {
		// 	$.each(data.error, function(i, msg) {
		// 		if (self.errors[msg]) {
		// 			data.error[i] = self.errors[msg];
		// 		}
		// 	});
		// }
		
		if (cmd == 'upload' && data.error && data.cwd) {
			data.warning = $.extend({}, data.error);
			data.error = false;
		}
		
		
		if (data.error) {
			return data;
		}
		
		if (cmd == 'put') {

			phash = this.fm.file(data.target.hash).phash;
			return {changed : [this.normalizeFile(data.target, phash)]};
		}
		
		phash = data.cwd.hash;
		
		if (data.tree) {
			$.each(this.normalizeTree(data.tree), function(i, file) {
				files[file.hash] = file;
			});
		}
		
		$.each(data.cdc||[], function(i, file) {
			var hash = file.hash;

			if (files[hash]) {
				files[hash].date   = file.date;
				files[hash].locked = file.hash == phash ? true : file.rm === void(0) ? false : !file.rm;
			} else {
				files[hash] = self.normalizeFile(file, phash, data.tmb);
			}
		});
		
		if (!data.tree) {
			$.each(this.fm.files(), function(hash, file) {
				if (!files[hash] && file.phash != phash && file.mime == 'directory') {
					files[hash] = file;
				}
			});
		}
		
		if (cmd == 'open') {
			return {
					cwd     : files[phash] || this.normalizeFile(data.cwd),
					files   : $.map(files, function(f) { return f }),
					options : self.normalizeOptions(data),
					init    : !!data.params,
					debug   : data.debug
				};
		}
		
		
		
		return $.extend({
			current : data.cwd.hash,
			error   : data.error,
			warning : data.warning,
			options : {tmb : !!data.tmb}
		}, this.fm.diff($.map(files, filter)));
		
	}
	
	/**
	 * Convert old api tree into plain array of dirs
	 *
	 * @param  Object  root dir
	 * @return Array
	 */
	this.normalizeTree = function(root) {
		var self     = this,
			result   = [],
			traverse = function(dirs, phash) {
				var i, dir;
				
				for (i = 0; i < dirs.length; i++) {
					dir = dirs[i];
					result.push(self.normalizeFile(dir, phash))
					dir.dirs.length && traverse(dir.dirs, dir.hash);
				}
			};

		traverse([root]);

		return result;
	}
	
	/**
	 * Convert file info from old api format into new one
	 *
	 * @param  Object  file
	 * @param  String  parent dir hash
	 * @return Object
	 */
	this.normalizeFile = function(file, phash, tmb) {
		var mime = file.mime || 'directory',
			size = mime == 'directory' && !file.linkTo ? 0 : file.size,
			info = {
				url    : file.url,
				hash   : file.hash,
				phash  : phash,
				name   : file.name,
				mime   : mime,
				date   : file.date || 'unknown',
				size   : size,
				read   : file.read,
				write  : file.write,
				locked : !phash ? true : file.rm === void(0) ? false : !file.rm
			};
		
		if (file.mime == 'application/x-empty') {
			info.mime = 'text/plain';
		}
		if (file.linkTo) {
			info.alias = file.linkTo;
		}

		if (file.linkTo) {
			info.linkTo = file.linkTo;
		}
		
		if (file.tmb) {
			info.tmb = file.tmb;
		} else if (info.mime.indexOf('image/') === 0 && tmb) {
			info.tmb = 1;
			
		}

		if (file.dirs && file.dirs.length) {
			info.dirs = true;
		}
		if (file.dim) {
			info.dim = file.dim;
		}
		if (file.resize) {
			info.resize = file.resize;
		}
		return info;
	}
	
	this.normalizeOptions = function(data) {
		var opts = {
				path          : data.cwd.rel,
				disabled      : data.disabled || [],
				tmb           : !!data.tmb,
				copyOverwrite : true
			};
		
		if (data.params) {
			opts.api      = 1;
			opts.url      = data.params.url;
			opts.archivers = {
				create  : data.params.archives || [],
				extract : data.params.extract || []
			}
		}
		
		if (opts.path.indexOf('/') !== -1) {
			opts.separator = '/';
		} else if (opts.path.indexOf('\\') !== -1) {
			opts.separator = '\\';
		}
		return opts;
	}
	
	
}
;"use strict"
/**
 * @class  elFinder toolbar button widget.
 * If command has variants - create menu
 *
 * @author Dmitry (dio) Levashov
 **/
$.fn.elfinderbutton = function(cmd) {
	return this.each(function() {
		
		var c        = 'class',
			fm       = cmd.fm,
			disabled = fm.res(c, 'disabled'),
			active   = fm.res(c, 'active'),
			hover    = fm.res(c, 'hover'),
			item     = 'elfinder-button-menu-item',
			selected = 'elfinder-button-menu-item-selected',
			menu,
			button   = $(this).addClass('ui-state-default elfinder-button')
				.attr('title', cmd.title)
				.append('<span class="elfinder-button-icon elfinder-button-icon-'+cmd.name+'"/>')
				.hover(function(e) { !button.is('.'+disabled) && button[e.type == 'mouseleave' ? 'removeClass' : 'addClass'](hover) /**button.toggleClass(hover);*/ })
				.click(function(e) { 
					if (!button.is('.'+disabled)) {
						if (menu && cmd.variants.length > 1) {
							// close other menus
							menu.is(':hidden') && cmd.fm.getUI().click();
							e.stopPropagation();
							menu.slideToggle(100);
						} else {
							cmd.exec();
						}
						
					}
				}),
			hideMenu = function() {
				menu.hide();
			};
			
		// if command has variants create menu
		if ($.isArray(cmd.variants)) {
			button.addClass('elfinder-menubutton');
			
			menu = $('<div class="ui-widget ui-widget-content elfinder-button-menu ui-corner-all"/>')
				.hide()
				.appendTo(button)
				.zIndex(12+button.zIndex())
				.delegate('.'+item, 'hover', function() { $(this).toggleClass(hover) })
				.delegate('.'+item, 'click', function(e) {
					e.preventDefault();
					e.stopPropagation();
					button.removeClass(hover);
					cmd.exec(cmd.fm.selected(), $(this).data('value'));
				});

			cmd.fm.bind('disable select', hideMenu).getUI().click(hideMenu);
			
			cmd.change(function() {
				menu.html('');
				$.each(cmd.variants, function(i, variant) {
					menu.append($('<div class="'+item+'">'+variant[1]+'</div>').data('value', variant[0]).addClass(variant[0] == cmd.value ? selected : ''));
				});
			});
		}	
			
		cmd.change(function() {
			if (cmd.disabled()) {
				button.removeClass(active+' '+hover).addClass(disabled);
			} else {
				button.removeClass(disabled);
				button[cmd.active() ? 'addClass' : 'removeClass'](active);
			}
		})
		.change();
	});
}
;"use strict";
/**
 * @class  elFinder contextmenu
 *
 * @author Dmitry (dio) Levashov
 **/
$.fn.elfindercontextmenu = function(fm) {
	
	return this.each(function() {
		var menu = $(this).addClass('ui-helper-reset ui-widget ui-state-default ui-corner-all elfinder-contextmenu elfinder-contextmenu-'+fm.direction)
				.hide()
				.appendTo('body')
				.delegate('.elfinder-contextmenu-item', 'mouseenter mouseleave', function() {
					$(this).toggleClass('ui-state-hover')
				}),
			subpos  = fm.direction == 'ltr' ? 'left' : 'right',
			types = $.extend({}, fm.options.contextmenu),
			tpl     = '<div class="elfinder-contextmenu-item"><span class="elfinder-button-icon {icon} elfinder-contextmenu-icon"/><span>{label}</span></div>',
			item = function(label, icon, callback) {
				return $(tpl.replace('{icon}', icon ? 'elfinder-button-icon-'+icon : '').replace('{label}', label))
					.click(function(e) {
						e.stopPropagation();
						e.stopPropagation();
						callback();
					})
			},
			
			open = function(x, y) {
				var win        = $(window),
					width      = menu.outerWidth(),
					height     = menu.outerHeight(),
					wwidth     = win.width(),
					wheight    = win.height(),
					scrolltop  = win.scrollTop(),
					scrollleft = win.scrollLeft(),
					css        = {
						top  : (y + height < wheight ? y : y - height > 0 ? y - height : y) + scrolltop,
						left : (x + width  < wwidth  ? x : x - width) + scrollleft,
						'z-index' : 100 + fm.getUI('workzone').zIndex()
					};

				menu.css(css)
					.show();
				
				css = {'z-index' : css['z-index']+10};
				css[subpos] = parseInt(menu.width());
				menu.find('.elfinder-contextmenu-sub').css(css);
			},
			
			close = function() {
				menu.hide().empty();
			},
			
			create = function(type, targets) {
				var sep = false;
				
				
				
				$.each(types[type]||[], function(i, name) {
					var cmd, node, submenu;
					
					if (name == '|' && sep) {
						menu.append('<div class="elfinder-contextmenu-separator"/>');
						sep = false;
						return;
					}
					
					cmd = fm.command(name);

					if (cmd && cmd.getstate(targets) != -1) {
						if (cmd.variants) {
							if (!cmd.variants.length) {
								return;
							}
							node = item(cmd.title, cmd.name, function() {});
							
							submenu = $('<div class="ui-corner-all elfinder-contextmenu-sub"/>')
								.appendTo(node.append('<span class="elfinder-contextmenu-arrow"/>'));
								
							node.addClass('elfinder-contextmenu-group')
								.hover(function() {
									submenu.toggle()
								})
								
							$.each(cmd.variants, function(i, variant) {
								submenu.append(
									$('<div class="elfinder-contextmenu-item"><span>'+variant[1]+'</span></div>')
										.click(function(e) {
											e.stopPropagation();
											close();
											cmd.exec(targets, variant[0]);
										})
								);
							});
								
						} else {
							node = item(cmd.title, cmd.name, function() {
								close();
								cmd.exec(targets);
							})
							
						}
						
						menu.append(node)
						sep = true;
					}
				})
			},
			
			createFromRaw = function(raw) {
				$.each(raw, function(i, data) {
					var node;
					
					if (data.label && typeof data.callback == 'function') {
						node = item(data.label, data.icon, function() {
							close();
							data.callback();
						});
						menu.append(node);
					}
				})
			};
		
		fm.one('load', function() {
			fm.bind('contextmenu', function(e) {
				var data = e.data;

				close();

				if (data.type && data.targets) {
					create(data.type, data.targets);
				} else if (data.raw) {
					createFromRaw(data.raw);
				}

				menu.children().length && open(data.x, data.y);
			})
			.one('destroy', function() { menu.remove(); })
			.bind('disable select', close)
			.getUI().click(close);
		});
		
	});
	
}
;"use strict";
/**
 * elFinder current working directory ui.
 *
 * @author Dmitry (dio) Levashov
 **/
$.fn.elfindercwd = function(fm, options) {
	
	this.not('.elfinder-cwd').each(function() {
		// fm.time('cwdLoad');
		
		var 
			list = fm.viewType == 'list',

			undef = 'undefined',
			/**
			 * Select event full name
			 *
			 * @type String
			 **/
			evtSelect = 'select.'+fm.namespace,
			
			/**
			 * Unselect event full name
			 *
			 * @type String
			 **/
			evtUnselect = 'unselect.'+fm.namespace,
			
			/**
			 * Disable event full name
			 *
			 * @type String
			 **/
			evtDisable = 'disable.'+fm.namespace,
			
			/**
			 * Disable event full name
			 *
			 * @type String
			 **/
			evtEnable = 'enable.'+fm.namespace,
			
			c = 'class',
			/**
			 * File css class
			 *
			 * @type String
			 **/
			clFile       = fm.res(c, 'cwdfile'),
			
			/**
			 * Selected css class
			 *
			 * @type String
			 **/
			fileSelector = '.'+clFile,
			
			/**
			 * Selected css class
			 *
			 * @type String
			 **/
			clSelected = 'ui-selected',
			
			/**
			 * Disabled css class
			 *
			 * @type String
			 **/
			clDisabled = fm.res(c, 'disabled'),
			
			/**
			 * Draggable css class
			 *
			 * @type String
			 **/
			clDraggable = fm.res(c, 'draggable'),
			
			/**
			 * Droppable css class
			 *
			 * @type String
			 **/
			clDroppable = fm.res(c, 'droppable'),
			
			/**
			 * Hover css class
			 *
			 * @type String
			 **/
			clHover     = fm.res(c, 'hover'), 

			/**
			 * Hover css class
			 *
			 * @type String
			 **/
			clDropActive = fm.res(c, 'adroppable'),

			/**
			 * Css class for temporary nodes (for mkdir/mkfile) commands
			 *
			 * @type String
			 **/
			clTmp = clFile+'-tmp',

			/**
			 * Number of thumbnails to load in one request (new api only)
			 *
			 * @type Number
			 **/
			tmbNum = fm.options.loadTmbs > 0 ? fm.options.loadTmbs : 5,
			
			/**
			 * Current search query.
			 *
			 * @type String
			 */
			query = '',
			
			lastSearch = [],

			/**
			 * File templates
			 *
			 * @type Object
			 **/
			templates = {
				icon : '<div id="{hash}" class="'+clFile+' {permsclass} {dirclass} ui-corner-all" title="{tooltip}"><div class="elfinder-cwd-file-wrapper ui-corner-all"><div class="elfinder-cwd-icon {mime} ui-corner-all" unselectable="on" {style}/>{marker}</div><div class="elfinder-cwd-filename" title="{name}">{name}</div></div>',
				row  : '<tr id="{hash}" class="'+clFile+' {permsclass} {dirclass}" title="{tooltip}"><td><div class="elfinder-cwd-file-wrapper"><span class="elfinder-cwd-icon {mime}"/>{marker}<span class="elfinder-cwd-filename">{name}</span></div></td><td>{perms}</td><td>{date}</td><td>{size}</td><td>{kind}</td></tr>'
			},
			
			permsTpl = fm.res('tpl', 'perms'),
			
			symlinkTpl = fm.res('tpl', 'symlink'),
			
			/**
			 * Template placeholders replacement rules
			 *
			 * @type Object
			 **/
			replacement = {
				permsclass : function(f) {
					return fm.perms2class(f);
				},
				perms : function(f) {
					return fm.formatPermissions(f);
				},
				dirclass : function(f) {
					return f.mime == 'directory' ? 'directory' : '';
				},
				mime : function(f) {
					return fm.mime2class(f.mime);
				},
				size : function(f) {
					return fm.formatSize(f.size);
				},
				date : function(f) {
					return fm.formatDate(f);
				},
				kind : function(f) {
					return fm.mime2kind(f);
				},
				marker : function(f) {
					return (f.alias || f.mime == 'symlink-broken' ? symlinkTpl : '')+(!f.read || !f.write ? permsTpl : '');
				},
				tooltip : function(f) {
					var title = fm.formatDate(f) + (f.size > 0 ? ' ('+fm.formatSize(f.size)+')' : '');
					return f.tooltip? fm.escape(f.tooltip).replace(/"/g, '&quot;').replace(/\r/g, '&#13;') + '&#13;' + title : title;
				}
			},
			
			/**
			 * Return file html
			 *
			 * @param  Object  file info
			 * @return String
			 **/
			itemhtml = function(f) {
				f.name = fm.escape(f.name);
				return templates[list ? 'row' : 'icon']
						.replace(/\{([a-z]+)\}/g, function(s, e) { 
							return replacement[e] ? replacement[e](f) : (f[e] ? f[e] : ''); 
						});
			},
			
			/**
			 * Flag. Required for msie to avoid unselect files on dragstart
			 *
			 * @type Boolean
			 **/
			selectLock = false,
			
			/**
			 * Move selection to prev/next file
			 *
			 * @param String  move direction
			 * @param Boolean append to current selection
			 * @return void
			 * @rise select			
			 */
			select = function(keyCode, append) {
				var code     = $.ui.keyCode,
					prev     = keyCode == code.LEFT || keyCode == code.UP,
					sel      = cwd.find('[id].'+clSelected),
					selector = prev ? 'first:' : 'last',
					s, n, sib, top, left;

				function sibling(n, direction) {
					return n[direction+'All']('[id]:not(.'+clDisabled+'):not(.elfinder-cwd-parent):first');
				}
				
				if (sel.length) {
					s = sel.filter(prev ? ':first' : ':last');
					sib = sibling(s, prev ? 'prev' : 'next');
					
					if (!sib.length) {
						// there is no sibling on required side - do not move selection
						n = s;
					} else if (list || keyCode == code.LEFT || keyCode == code.RIGHT) {
						// find real prevoius file
						n = sib;
					} else {
						// find up/down side file in icons view
						top = s.position().top;
						left = s.position().left;

						n = s;
						if (prev) {
							do {
								n = n.prev('[id]');
							} while (n.length && !(n.position().top < top && n.position().left <= left));

							if (n.is('.'+clDisabled)) {
								n = sibling(n, 'next');
							}
						} else {
							do {
								n = n.next('[id]');
							} while (n.length && !(n.position().top > top && n.position().left >= left));
							
							if (n.is('.'+clDisabled)) {
								n = sibling(n, 'prev');
							}
							// there is row before last one - select last file
							if (!n.length) {
								sib = cwd.find('[id]:not(.'+clDisabled+'):last');
								if (sib.position().top > top) {
									n = sib;
								}
							}
						}
					}
					// !append && unselectAll();
				} else {
					// there are no selected file - select first/last one
					n = cwd.find('[id]:not(.'+clDisabled+'):not(.elfinder-cwd-parent):'+(prev ? 'last' : 'first'));
				}
				
				if (n && n.length && !n.is('.elfinder-cwd-parent')) {
					if (append) {
						// append new files to selected
						n = s.add(s[prev ? 'prevUntil' : 'nextUntil']('#'+n.attr('id'))).add(n);
					} else {
						// unselect selected files
						sel.trigger(evtUnselect);
					}
					// select file(s)
					n.trigger(evtSelect);
					// set its visible
					scrollToView(n.filter(prev ? ':first' : ':last'));
					// update cache/view
					trigger();
				}
			},
			
			selectedFiles = [],
			
			selectFile = function(hash) {
				cwd.find('#'+hash).trigger(evtSelect);
			},
			
			selectAll = function() {
				var phash = fm.cwd().hash;

				cwd.find('[id]:not(.'+clSelected+'):not(.elfinder-cwd-parent)').trigger(evtSelect); 
				selectedFiles = $.map(fm.files(), function(f) { return f.phash == phash ? f.hash : null ;});
				trigger();
			},
			
			/**
			 * Unselect all files
			 *
			 * @return void
			 */
			unselectAll = function() {
				selectedFiles = [];
				cwd.find('[id].'+clSelected).trigger(evtUnselect); 
				trigger();
			},
			
			/**
			 * Return selected files hashes list
			 *
			 * @return Array
			 */
			selected = function() {
				return selectedFiles;
			},
			
			/**
			 * Fire elfinder "select" event and pass selected files to it
			 *
			 * @return void
			 */
			trigger = function() {
				fm.trigger('select', {selected : selectedFiles});
			},
			
			/**
			 * Scroll file to set it visible
			 *
			 * @param DOMElement  file/dir node
			 * @return void
			 */
			scrollToView = function(o) {
				var ftop    = o.position().top,
					fheight = o.outerHeight(true),
					wtop    = wrapper.scrollTop(),
					wheight = wrapper.innerHeight();

				if (ftop + fheight > wtop + wheight) {
					wrapper.scrollTop(parseInt(ftop + fheight - wheight));
				} else if (ftop < wtop) {
					wrapper.scrollTop(ftop);
				}
			},
			
			/**
			 * Files we get from server but not show yet
			 *
			 * @type Array
			 **/
			buffer = [],
			
			/**
			 * Return index of elements with required hash in buffer 
			 *
			 * @param String  file hash
			 * @return Number
			 */
			index = function(hash) {
				var l = buffer.length;
				
				while (l--) {
					if (buffer[l].hash == hash) {
						return l;
					}
				}
				return -1;
			},
			
			/**
			 * Scroll event name
			 *
			 * @type String
			 **/
			scrollEvent = 'scroll.'+fm.namespace,

			/**
			 * Cwd scroll event handler.
			 * Lazy load - append to cwd not shown files
			 *
			 * @return void
			 */
			render = function() {
				var html  = [],  
					dirs  = false, 
					ltmb  = [],
					atmb  = {},
					last  = cwd.find('[id]:last'),
					top   = !last.length,
					place = list ? cwd.children('table').children('tbody') : cwd,
					files;

				if (!buffer.length) {
					return wrapper.unbind(scrollEvent);
				}
				
				while ((!last.length || last.position().top <= wrapper.height() + wrapper.scrollTop() + fm.options.showThreshold)
					&& (files = buffer.splice(0, fm.options.showFiles)).length) {
					
					html = $.map(files, function(f) {
						if (f.hash && f.name) {
							if (f.mime == 'directory') {
								dirs = true;
							}
							if (f.tmb) {
								f.tmb === 1 ? ltmb.push(f.hash) : (atmb[f.hash] = f.tmb);
							} 
							return itemhtml(f);
						}
						return null;
					});

					place.append(html.join(''));
					last = cwd.find('[id]:last');
					// scroll top on dir load to avoid scroll after page reload
					top && cwd.scrollTop(0);
					
				}

				// load/attach thumbnails
				attachThumbnails(atmb);
				ltmb.length && loadThumbnails(ltmb);

				// make directory droppable
				dirs && makeDroppable();
				
				if (selectedFiles.length) {
					place.find('[id]:not(.'+clSelected+'):not(.elfinder-cwd-parent)').each(function() {
						var id = this.id;
						
						$.inArray(id, selectedFiles) !== -1 && $(this).trigger(evtSelect);
					});
				}
				
			},
			
			/**
			 * Droppable options for cwd.
			 * Do not add class on childs file over
			 *
			 * @type Object
			 */
			droppable = $.extend({}, fm.droppable, {
				over : function(e, ui) { 
					var hash = fm.cwd().hash;
					$.each(ui.helper.data('files'), function(i, h) {
						if (fm.file(h).phash == hash) {
							cwd.removeClass(clDropActive);
							return false;
						}
					});
				}
			}),
			
			/**
			 * Make directory droppable
			 *
			 * @return void
			 */
			makeDroppable = function() {
				setTimeout(function() {
					cwd.find('.directory:not(.'+clDroppable+',.elfinder-na,.elfinder-ro)').droppable(fm.droppable);
				}, 20);
			},
			
			/**
			 * Preload required thumbnails and on load add css to files.
			 * Return false if required file is not visible yet (in buffer) -
			 * required for old api to stop loading thumbnails.
			 *
			 * @param  Object  file hash -> thumbnail map
			 * @return Boolean
			 */
			attachThumbnails = function(images) {
				var url = fm.option('tmbUrl'),
					ret = true, 
					ndx;
				
				$.each(images, function(hash, tmb) {
					var node = cwd.find('#'+hash);

					if (node.length) {

						(function(node, tmb) {
							$('<img/>')
								.load(function() { node.find('.elfinder-cwd-icon').css('background', "url('"+tmb+"') center center no-repeat"); })
								.attr('src', tmb);
						})(node, url+tmb);
					} else {
						ret = false;
						if ((ndx = index(hash)) != -1) {
							buffer[ndx].tmb = tmb;
						}
					}
				});
				return ret;
			},
			
			/**
			 * Load thumbnails from backend.
			 *
			 * @param  Array|Boolean  files hashes list for new api | true for old api
			 * @return void
			 */
			loadThumbnails = function(files) {
				var tmbs = [];
				
				if (fm.oldAPI) {
					fm.request({
						data : {cmd : 'tmb', current : fm.cwd().hash},
						preventFail : true
						})
						.done(function(data) {
							if (attachThumbnails(data.images||[]) && data.tmb) {
								loadThumbnails();
							}
						});
					return;
				} 

				tmbs = tmbs = files.splice(0, tmbNum);
				if (tmbs.length) {
					fm.request({
						data : {cmd : 'tmb', targets : tmbs},
						preventFail : true
					})
					.done(function(data) {
						if (attachThumbnails(data.images||[])) {
							loadThumbnails(files);
						}
					});
				}
			},
			
			/**
			 * Add new files to cwd/buffer
			 *
			 * @param  Array  new files
			 * @return void
			 */
			add = function(files) {
				var place    = list ? cwd.find('tbody') : cwd,
					l        = files.length, 
					ltmb     = [],
					atmb     = {},
					dirs     = false,
					findNode = function(file) {
						var pointer = cwd.find('[id]:first'), file2;

						while (pointer.length) {
							file2 = fm.file(pointer.attr('id'));
							if (!pointer.is('.elfinder-cwd-parent') && file2 && fm.compare(file, file2) < 0) {
								return pointer;
							}
							pointer = pointer.next('[id]');
						}
					},
					findIndex = function(file) {
						var l = buffer.length, i;
						
						for (i =0; i < l; i++) {
							if (fm.compare(file, buffer[i]) < 0) {
								return i;
							}
						}
						return l || -1;
					},
					file, hash, node, ndx;

				
				while (l--) {
					file = files[l];
					hash = file.hash;
					
					if (cwd.find('#'+hash).length) {
						continue;
					}
					
					if ((node = findNode(file)) && node.length) {
						node.before(itemhtml(file)); 
					} else if ((ndx = findIndex(file)) >= 0) {
						buffer.splice(ndx, 0, file);
					} else {
						place.append(itemhtml(file));
					}
					
					if (cwd.find('#'+hash).length) {
						if (file.mime == 'directory') {
							dirs = true;
						} else if (file.tmb) {
							file.tmb === 1 ? ltmb.push(hash) : (atmb[hash] = file.tmb);
						}
					}
				}
				
				attachThumbnails(atmb);
				ltmb.length && loadThumbnails(ltmb);
				dirs && makeDroppable();
			},
			
			/**
			 * Remove files from cwd/buffer
			 *
			 * @param  Array  files hashes
			 * @return void
			 */
			remove = function(files) {
				var l = files.length, hash, n, ndx;
				
				while (l--) {
					hash = files[l];
					if ((n = cwd.find('#'+hash)).length) {
						try {
							n.detach();
						} catch(e) {
							fm.debug('error', e);
						}
					} else if ((ndx = index(hash)) != -1) {
						buffer.splice(ndx, 1);
					}
				}
			},
			
			msg = {
				name : fm.i18n('name'),
				perm : fm.i18n('perms'),
				mod  : fm.i18n('modify'),
				size : fm.i18n('size'),
				kind : fm.i18n('kind')
			},
			
			/**
			 * Update directory content
			 *
			 * @param  Array  files
			 * @return void
			 */
			content = function(files, any) {
				var phash = fm.cwd().hash; 
				// console.log(files)
				
				unselectAll();
				
				try {
					// to avoid problem with draggable
					cwd.children('table,'+fileSelector).remove();
				} catch (e) {
					cwd.html('');
				}

				cwd.removeClass('elfinder-cwd-view-icons elfinder-cwd-view-list')
					.addClass('elfinder-cwd-view-'+(list ? 'list' :'icons'));

				wrapper[list ? 'addClass' : 'removeClass']('elfinder-cwd-wrapper-list');

				list && cwd.html('<table><thead><tr class="ui-state-default"><td >'+msg.name+'</td><td>'+msg.perm+'</td><td>'+msg.mod+'</td><td>'+msg.size+'</td><td>'+msg.kind+'</td></tr></thead><tbody/></table>');
		
				buffer = $.map(files, function(f) { return any || f.phash == phash ? f : null; });
				
				buffer = fm.sortFiles(buffer);
		
				wrapper.bind(scrollEvent, render).trigger(scrollEvent);
		
				phash = fm.cwd().phash;
				
				if (options.oldSchool && phash && !query) {
					var parent = $.extend(true, {}, fm.file(phash), {name : '..', mime : 'directory'});
					parent = $(itemhtml(parent))
						.addClass('elfinder-cwd-parent')
						.bind('mousedown click mouseup touchstart touchmove touchend dblclick mouseenter', function(e) {
						//.bind('mousedown click mouseup dblclick mouseenter', function(e) {
							e.preventDefault();
							e.stopPropagation();
						})
						.dblclick(function() {
							fm.exec('open', this.id);
						});

					(list ? cwd.find('tbody') : cwd).prepend(parent);
				}
				
			},
			
			/**
			 * CWD node itself
			 *
			 * @type JQuery
			 **/
			cwd = $(this)
				.addClass('ui-helper-clearfix elfinder-cwd')
				.attr('unselectable', 'on')
				// fix ui.selectable bugs and add shift+click support 
				.delegate(fileSelector, 'click.'+fm.namespace, function(e) {
					var p    = this.id ? $(this) : $(this).parents('[id]:first'), 
						prev = p.prevAll('.'+clSelected+':first'),
						next = p.nextAll('.'+clSelected+':first'),
						pl   = prev.length,
						nl   = next.length,
						sib;

					e.stopImmediatePropagation();

					if (e.shiftKey && (pl || nl)) {
						sib = pl ? p.prevUntil('#'+prev.attr('id')) : p.nextUntil('#'+next.attr('id'));
						sib.add(p).trigger(evtSelect);
					} else if (e.ctrlKey || e.metaKey) {
						p.trigger(p.is('.'+clSelected) ? evtUnselect : evtSelect);
					} else {
						if ($(this).data('touching') && p.is('.'+clSelected)) {
							$(this).data('touching', null);
							fm.dblclick({file : this.id});
							unselectAll();
						} else {
							unselectAll();
							p.trigger(evtSelect);
						}
					}

					trigger();
				})
				// call fm.open()
				.delegate(fileSelector, 'dblclick.'+fm.namespace, function(e) {
					fm.dblclick({file : this.id});
				})
				// for touch device
				.delegate(fileSelector, 'touchstart.'+fm.namespace, function(e) {
					$(this).data('touching', true);
					var p = this.id ? $(this) : $(this).parents('[id]:first'),
					  sel = p.prevAll('.'+clSelected+':first').length +
					        p.nextAll('.'+clSelected+':first').length;
					$(this).data('longtap', setTimeout(function(){
						// long tap
						p.trigger(p.is('.'+clSelected) ? evtUnselect : evtSelect);
						trigger();
						if (sel == 0 && p.is('.'+clSelected)) {
							p.trigger('click');
							trigger();
						} 
					}, 500));
				})
				.delegate(fileSelector, 'touchmove.'+fm.namespace+' touchend.'+fm.namespace, function(e) {
					clearTimeout($(this).data('longtap'));
				})
				// attach draggable
				.delegate(fileSelector, 'mouseenter.'+fm.namespace, function(e) {
					var $this = $(this),
						target = list ? $this : $this.children();

					if (!$this.is('.'+clTmp) && !target.is('.'+clDraggable+',.'+clDisabled)) {
						target.draggable(fm.draggable);
					}
				})
				// add hover class to selected file
				.delegate(fileSelector, evtSelect, function(e) {
					var $this = $(this), 
						id    = $this.attr('id');
					
					if (!selectLock && !$this.is('.'+clDisabled)) {
						$this.addClass(clSelected).children().addClass(clHover);
						if ($.inArray(id, selectedFiles) === -1) {
							selectedFiles.push(id);
						}
					}
				})
				// remove hover class from unselected file
				.delegate(fileSelector, evtUnselect, function(e) {
					var $this = $(this), 
						id    = $this.attr('id'),
						ndx;
					
					if (!selectLock) {
						$(this).removeClass(clSelected).children().removeClass(clHover);
						ndx = $.inArray(id, selectedFiles);
						if (ndx !== -1) {
							selectedFiles.splice(ndx, 1);
						}
					}
					
				})
				// disable files wich removing or moving
				.delegate(fileSelector, evtDisable, function() {
					var $this  = $(this).removeClass(clSelected).addClass(clDisabled), 
						target = (list ? $this : $this.children()).removeClass(clHover);
					
					$this.is('.'+clDroppable) && $this.droppable('disable');
					target.is('.'+clDraggable) && target.draggable('disable');
					!list && target.removeClass(clDisabled);
				})
				// if any files was not removed/moved - unlock its
				.delegate(fileSelector, evtEnable, function() {
					var $this  = $(this).removeClass(clDisabled), 
						target = list ? $this : $this.children();
					
					$this.is('.'+clDroppable) && $this.droppable('enable');	
					target.is('.'+clDraggable) && target.draggable('enable');
				})
				.delegate(fileSelector, 'scrolltoview', function() {
					scrollToView($(this));
				})
				.delegate(fileSelector, 'mouseenter.'+fm.namespace+' mouseleave.'+fm.namespace, function(e) {
					fm.trigger('hover', {hash : $(this).attr('id'), type : e.type});
					$(this).toggleClass('ui-state-hover');
				})
				.bind('contextmenu.'+fm.namespace, function(e) {
					var file = $(e.target).closest('.'+clFile);
					
					if (file.length) {
						e.stopPropagation();
						e.preventDefault();
						if (!file.is('.'+clDisabled)) {
							if (!file.is('.'+clSelected)) {
								// cwd.trigger('unselectall');
								unselectAll();
								file.trigger(evtSelect);
								trigger();
							}
							fm.trigger('contextmenu', {
								'type'    : 'files',
								'targets' : fm.selected(),
								'x'       : e.clientX,
								'y'       : e.clientY
							});

						}
						
					}
					// e.preventDefault();
					
					
				})
				// unselect all on cwd click
				.bind('click.'+fm.namespace, function(e) {
					!e.shiftKey && !e.ctrlKey && !e.metaKey && unselectAll();
				})
				
				// make files selectable
				.selectable({
					filter     : fileSelector,
					stop       : trigger,
					delay      : 250,
					selected   : function(e, ui) { $(ui.selected).trigger(evtSelect); },
					unselected : function(e, ui) { $(ui.unselected).trigger(evtUnselect); }
				})
				// make cwd itself droppable for folders from nav panel
				.droppable(droppable)
				// prepend fake file/dir
				.bind('create.'+fm.namespace, function(e, file) {
					var parent = list ? cwd.find('tbody') : cwd,
						p = parent.find('.elfinder-cwd-parent'),
						file = $(itemhtml(file)).addClass(clTmp);
						
					unselectAll();

					if (p.length) {
						p.after(file);
					} else {
						parent.prepend(file);
					}
					
					cwd.scrollTop(0);
				})
				// unselect all selected files
				.bind('unselectall', unselectAll)
				.bind('selectfile', function(e, id) {
					cwd.find('#'+id).trigger(evtSelect);
					trigger();
				}),
			wrapper = $('<div class="elfinder-cwd-wrapper"/>')
				.bind('contextmenu', function(e) {
					e.preventDefault();
					fm.trigger('contextmenu', {
						'type'    : 'cwd',
						'targets' : [fm.cwd().hash],
						'x'       : e.clientX,
						'y'       : e.clientY
					});
					
				}),
			
			resize = function() {
				var h = 0;

				wrapper.siblings('.elfinder-panel:visible').each(function() {
					h += $(this).outerHeight(true);
				});

				wrapper.height(wz.height() - h);
			},
			
			// elfinder node
			parent = $(this).parent().resize(resize),
			// workzone node
			wz = parent.children('.elfinder-workzone').append(wrapper.append(this))
			;
			
		
		if (fm.dragUpload) {
			wrapper[0].addEventListener('dragenter', function(e) {
				e.preventDefault();
				e.stopPropagation();
				
				wrapper.addClass(clDropActive);
			}, false);

			wrapper[0].addEventListener('dragleave', function(e) {
				e.preventDefault();
				e.stopPropagation();
				e.target == cwd[0] && wrapper.removeClass(clDropActive);
			}, false);

			wrapper[0].addEventListener('dragover', function(e) {
				e.preventDefault();
				e.stopPropagation();
			}, false);

			wrapper[0].addEventListener('drop', function(e) {
			  	e.preventDefault();
				wrapper.removeClass(clDropActive);
				var file = false;
				var type = '';
				var data = null;
				try{
					data = e.dataTransfer.getData('text/html');
				} catch(e) {}
				if (data) {
					file = [ data ];
					type = 'html';
				} else if (data = e.dataTransfer.getData('text')) {
					file = [ data ];
					type = 'text';
				} else if (e.dataTransfer && e.dataTransfer.items &&  e.dataTransfer.items.length) {
					file = e.dataTransfer;
					type = 'data';
				} else if (e.dataTransfer && e.dataTransfer.files &&  e.dataTransfer.files.length) {
					file = e.dataTransfer.files;
					type = 'files';
				}
				if (file) {
					fm.exec('upload', {files : file, type : type});
				}
			}, false);
		}

		fm
			.bind('open', function(e) {
				content(e.data.files);
			})
			.bind('search', function(e) {
				lastSearch = e.data.files;
				content(lastSearch, true);
			})
			.bind('searchend', function() {
				lastSearch = [];
				if (query) {
					query = '';
					content(fm.files());
				}
			})
			.bind('searchstart', function(e) {
				query = e.data.query;
			})
			.bind('sortchange', function() {
				content(query ? lastSearch : fm.files(), !!query);
			})
			.bind('viewchange', function() {
				var sel = fm.selected(),
					l   = fm.storage('view') == 'list';
				
				if (l != list) {
					list = l;
					content(fm.files());

					$.each(sel, function(i, h) {
						selectFile(h);
					});
					trigger();
				}
				resize();
			})
			.add(function(e) {
				var phash = fm.cwd().hash,
					files = query
						? $.map(e.data.added || [], function(f) { return f.name.indexOf(query) === -1 ? null : f ;})
						: $.map(e.data.added || [], function(f) { return f.phash == phash ? f : null; })
						;
				add(files);
			})
			.change(function(e) {
				var phash = fm.cwd().hash,
					sel   = fm.selected(),
					files;

				if (query) {
					$.each(e.data.changed || [], function(i, file) {
						remove([file.hash]);
						if (file.name.indexOf(query) !== -1) {
							add([file]);
							$.inArray(file.hash, sel) !== -1 && selectFile(file.hash);
						}
					});
				} else {
					$.each($.map(e.data.changed || [], function(f) { return f.phash == phash ? f : null; }), function(i, file) {
						remove([file.hash]);
						add([file]);
						$.inArray(file.hash, sel) !== -1 && selectFile(file.hash);
					});
				}
				
				trigger();
			})
			.remove(function(e) {
				remove(e.data.removed || []);
				trigger();
			})
			// fix cwd height if it less then wrapper
			.bind('open add search searchend', function() {
				cwd.css('height', 'auto');

				if (cwd.outerHeight(true) < wrapper.height()) {
					cwd.height(wrapper.height() - (cwd.outerHeight(true) - cwd.height()) - 2);
				} 
			})
			// select dragged file if no selected, disable selectable
			.dragstart(function(e) {
				var target = $(e.data.target),
					oe     = e.data.originalEvent;

				if (target.is(fileSelector)) {
					
					if (!target.is('.'+clSelected)) {
						!(oe.ctrlKey || oe.metaKey || oe.shiftKey) && unselectAll();
						target.trigger(evtSelect);
						trigger();
					}
					cwd.droppable('disable');
				}
				
				cwd.selectable('disable').removeClass(clDisabled);
				selectLock = true;
			})
			// enable selectable
			.dragstop(function() {
				cwd.selectable('enable').droppable('enable');
				selectLock = false;
			})
			.bind('lockfiles unlockfiles', function(e) {
				var event = e.type == 'lockfiles' ? evtDisable : evtEnable,
					files = e.data.files || [], 
					l     = files.length;
				
				while (l--) {
					cwd.find('#'+files[l]).trigger(event);
				}
				trigger();
			})
			// select new files after some actions
			.bind('mkdir mkfile duplicate upload rename archive extract', function(e) {
				var phash = fm.cwd().hash, files;
				
				unselectAll();

				$.each(e.data.added || [], function(i, file) { 
					file && file.phash == phash && selectFile(file.hash);
				});
				trigger();
			})
			.shortcut({
				pattern     :'ctrl+a', 
				description : 'selectall',
				callback    : selectAll
			})
			.shortcut({
				pattern     : 'left right up down shift+left shift+right shift+up shift+down',
				description : 'selectfiles',
				type        : 'keydown' , //fm.UA.Firefox || fm.UA.Opera ? 'keypress' : 'keydown',
				callback    : function(e) { select(e.keyCode, e.shiftKey); }
			})
			.shortcut({
				pattern     : 'home',
				description : 'selectffile',
				callback    : function(e) { 
					unselectAll();
					scrollToView(cwd.find('[id]:first').trigger(evtSelect));
					trigger();
				}
			})
			.shortcut({
				pattern     : 'end',
				description : 'selectlfile',
				callback    : function(e) { 
					unselectAll();
					scrollToView(cwd.find('[id]:last').trigger(evtSelect)) ;
					trigger();
				}
			});
		
	});
	
	// fm.timeEnd('cwdLoad')
	
	return this;
}
;"use strict";
/**
 * @class  elFinder dialog
 *
 * @author Dmitry (dio) Levashov
 **/
$.fn.elfinderdialog = function(opts) {
	var dialog;
	
	if (typeof(opts) == 'string' && (dialog = this.closest('.ui-dialog')).length) {
		if (opts == 'open') {
			dialog.css('display') == 'none' && dialog.fadeIn(120, function() {
				dialog.trigger('open');
			});
		} else if (opts == 'close') {
			dialog.css('display') != 'none' && dialog.hide().trigger('close');
		} else if (opts == 'destroy') {
			dialog.hide().remove();
		} else if (opts == 'toTop') {
			dialog.trigger('totop');
		}
	}
	
	opts = $.extend({}, $.fn.elfinderdialog.defaults, opts);

	this.filter(':not(.ui-dialog-content)').each(function() {
		var self       = $(this).addClass('ui-dialog-content ui-widget-content'),
			parent     = self.parent(),
			clactive   = 'elfinder-dialog-active',
			cldialog   = 'elfinder-dialog',
			clnotify   = 'elfinder-dialog-notify',
			clhover    = 'ui-state-hover',
			id         = parseInt(Math.random()*1000000),
			overlay    = parent.children('.elfinder-overlay'),
			buttonset  = $('<div class="ui-dialog-buttonset"/>'),
			buttonpane = $('<div class=" ui-helper-clearfix ui-dialog-buttonpane ui-widget-content"/>')
				.append(buttonset),
			
			dialog = $('<div class="ui-dialog ui-widget ui-widget-content ui-corner-all ui-draggable std42-dialog  '+cldialog+' '+opts.cssClass+'"/>')
				.hide()
				.append(self)
				.appendTo(parent)
				.draggable({ handle : '.ui-dialog-titlebar',
					     containment : 'document' })
				.css({
					width  : opts.width,
					height : opts.height
				})
				.mousedown(function(e) {
					e.stopPropagation();
					
					$(document).mousedown();

					if (!dialog.is('.'+clactive)) {
						parent.find('.'+cldialog+':visible').removeClass(clactive);
						dialog.addClass(clactive).zIndex(maxZIndex() + 1);
					}
				})
				.bind('open', function() {
					dialog.trigger('totop');
					typeof(opts.open) == 'function' && $.proxy(opts.open, self[0])();

					if (!dialog.is('.'+clnotify)) {
						
						parent.find('.'+cldialog+':visible').not('.'+clnotify).each(function() {
							var d     = $(this),
								top   = parseInt(d.css('top')),
								left  = parseInt(d.css('left')),
								_top  = parseInt(dialog.css('top')),
								_left = parseInt(dialog.css('left'))
								;

							if (d[0] != dialog[0] && (top == _top || left == _left)) {
								dialog.css({
									top  : (top+10)+'px',
									left : (left+10)+'px'
								});
							}
						});
					} 
				})
				.bind('close', function() {
					var dialogs = parent.find('.elfinder-dialog:visible'),
						z = maxZIndex();
					
					$(this).data('modal') && overlay.elfinderoverlay('hide');
					
					// get focus to next dialog
					if (dialogs.length) {
						dialogs.each(function() {
							var d = $(this);
							if (d.zIndex() >= z) {
								d.trigger('totop');
								return false;
							}
						})
					} else {
						// return focus to parent
						setTimeout(function() {
							parent.mousedown().click();
						}, 10);
						
					}
					
					if (typeof(opts.close) == 'function') {
						$.proxy(opts.close, self[0])();
					} else if (opts.destroyOnClose) {
						dialog.hide().remove();
					}
				})
				.bind('totop', function() {
					$(this).mousedown().find('.ui-button:first').focus().end().find(':text:first').focus();
					$(this).data('modal') && overlay.elfinderoverlay('show');
					overlay.zIndex($(this).zIndex());
				})
				.data({modal: opts.modal}),
				maxZIndex = function() {
					var z = parent.zIndex() + 10;
					parent.find('.'+cldialog+':visible').each(function() {
						var _z;
						if (this != dialog[0]) {
							_z = $(this).zIndex();
							if (_z > z) {
								z = _z;
							}
						}
					})
					return z;
				},
				top
			;
		
		if (!opts.position) {
			top = parseInt((parent.height() - dialog.outerHeight())/2 - 42);
			opts.position = {
				top  : (top > 0 ? top : 0)+'px',
				left : parseInt((parent.width() - dialog.outerWidth())/2)+'px'
			}
		} 
			
		dialog.css(opts.position);

		if (opts.closeOnEscape) {
			$(document).bind('keyup.'+id, function(e) {
				if (e.keyCode == $.ui.keyCode.ESCAPE && dialog.is('.'+clactive)) {
					self.elfinderdialog('close');
					$(document).unbind('keyup.'+id);
				}
			})
		}
		dialog.prepend(
			$('<div class="ui-dialog-titlebar ui-widget-header ui-corner-all ui-helper-clearfix">'+opts.title+'</div>')
				.prepend($('<a href="#" class="ui-dialog-titlebar-close ui-corner-all"><span class="ui-icon ui-icon-closethick"/></a>')
					.mousedown(function(e) {
						e.preventDefault();
						self.elfinderdialog('close');
					}))

		);
			
		
			
		$.each(opts.buttons, function(name, cb) {
			var button = $('<button type="button" class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only"><span class="ui-button-text">'+name+'</span></button>')
				.click($.proxy(cb, self[0]))
				.hover(function(e) { $(this)[e.type == 'mouseenter' ? 'focus' : 'blur']() })
				.focus(function() { $(this).addClass(clhover) })
				.blur(function() { $(this).removeClass(clhover) })
				.keydown(function(e) { 
					var next;
					
					if (e.keyCode == $.ui.keyCode.ENTER) {
						$(this).click();
					}  else if (e.keyCode == $.ui.keyCode.TAB) {
						next = $(this).next('.ui-button');
						next.length ? next.focus() : $(this).parent().children('.ui-button:first').focus()
					}
				})
			buttonset.append(button);
		})
			
		buttonset.children().length && dialog.append(buttonpane);
		if (opts.resizable && $.fn.resizable) {
			dialog.resizable({
					minWidth   : opts.minWidth,
					minHeight  : opts.minHeight,
					alsoResize : this
				});
		} 
			
		typeof(opts.create) == 'function' && $.proxy(opts.create, this)();
			
		opts.autoOpen && self.elfinderdialog('open');

	});
	
	return this;
}

$.fn.elfinderdialog.defaults = {
	cssClass  : '',
	title     : '',
	modal     : false,
	resizable : true,
	autoOpen  : true,
	closeOnEscape : true,
	destroyOnClose : false,
	buttons   : {},
	position  : null,
	width     : 320,
	height    : 'auto',
	minWidth  : 200,
	minHeight : 110
};/**
 * @class elfindernav - elFinder container for diretories tree and places
 *
 * @author Dmitry (dio) Levashov
 **/
$.fn.elfindernavbar = function(fm, opts) {

	this.not('.elfinder-navbar').each(function() {
		var nav    = $(this).addClass('ui-state-default elfinder-navbar'),
			parent = nav.parent()
				.resize(function() {
					nav.height(wz.height() - delta);
				}),
			wz     = parent.children('.elfinder-workzone').append(nav),
			delta  = nav.outerHeight() - nav.height(),
			ltr    = fm.direction == 'ltr',
			handle;

		
		if ($.fn.resizable) {
			handle = nav.resizable({
					handles : ltr ? 'e' : 'w',
					minWidth : opts.minWidth || 150,
					maxWidth : opts.maxWidth || 500
				})
				.bind('resize scroll', function() {
					var offset = (fm.UA.Opera && nav.scrollLeft())? 20 : 2;
					handle.css({
						top  : parseInt(nav.scrollTop())+'px',
						left : ltr ? 'auto' : parseInt(nav.scrollLeft() + offset),
						right: ltr ? parseInt(nav.scrollLeft() - offset) * -1 : 'auto'
					});
				})
				.find('.ui-resizable-handle').zIndex(nav.zIndex() + 10);

			if (!ltr) {
				nav.resize(function() {
					nav.css('left', null).css('right', 0);
				});
			}

			fm.one('open', function() {
				setTimeout(function() {
					nav.trigger('resize');
				}, 150);
			});
		}
	});
	
	return this;
};
;
$.fn.elfinderoverlay = function(opts) {
	
	this.filter(':not(.elfinder-overlay)').each(function() {
		opts = $.extend({}, opts);
		$(this).addClass('ui-widget-overlay elfinder-overlay')
			.hide()
			.mousedown(function(e) {
				e.preventDefault();
				e.stopPropagation();
			})
			.data({
				cnt  : 0,
				show : typeof(opts.show) == 'function' ? opts.show : function() { },
				hide : typeof(opts.hide) == 'function' ? opts.hide : function() { }
			});
	});
	
	if (opts == 'show') {
		var o    = this.eq(0),
			cnt  = o.data('cnt') + 1,
			show = o.data('show');

		o.data('cnt', cnt);

		if (o.is(':hidden')) {
			o.zIndex(o.parent().zIndex()+1);
			o.show();
			show();
		}
	} 
	
	if (opts == 'hide') {
		var o    = this.eq(0),
			cnt  = o.data('cnt') - 1,
			hide = o.data('hide');
		
		o.data('cnt', cnt);
			
		if (cnt == 0 && o.is(':visible')) {
			o.hide();
			hide();        
		}
	}
	
	return this;
};$.fn.elfinderpanel = function(fm) {
	
	return this.each(function() {
		var panel = $(this).addClass('elfinder-panel ui-state-default ui-corner-all'),
			margin = 'margin-'+(fm.direction == 'ltr' ? 'left' : 'right');
		
		fm.one('load', function(e) {
			var navbar = fm.getUI('navbar');
			
			panel.css(margin, parseInt(navbar.outerWidth(true)));
			navbar.bind('resize', function() {
				panel.is(':visible') && panel.css(margin, parseInt(navbar.outerWidth(true)))
			})
		})
	})
};"use strict";
/**
 * @class elFinder ui
 * Display current folder path in statusbar.
 * Click on folder name in path - open folder
 *
 * @author Dmitry (dio) Levashov
 **/
$.fn.elfinderpath = function(fm) {
	return this.each(function() {
		var path = $(this).addClass('elfinder-path').html('&nbsp;')
				.delegate('a', 'click', function(e) {
					var hash = $(this).attr('href').substr(1);

					e.preventDefault();
					hash != fm.cwd().hash && fm.exec('open', hash);
				})
				.prependTo(fm.getUI('statusbar').show())

			fm.bind('open searchend', function() {
				var dirs = [];

				$.each(fm.parents(fm.cwd().hash), function(i, hash) {
					dirs.push('<a href="#'+hash+'">'+fm.escape(fm.file(hash).name)+'</a>');
				});

				path.html(dirs.join(fm.option('separator')));
			})
			.bind('search', function() {
				path.html(fm.i18n('searcresult'));
			});
	});
};"use strict";
/**
 * @class elFinder places/favorites ui
 *
 * @author Dmitry (dio) Levashov
 **/
$.fn.elfinderplaces = function(fm, opts) {
	return this.each(function() {
		var dirs      = [],
			c         = 'class',
			navdir    = fm.res(c, 'navdir'),
			collapsed = fm.res(c, 'navcollapse'),
			expanded  = fm.res(c, 'navexpand'),
			hover     = fm.res(c, 'hover'),
			clroot    = fm.res(c, 'treeroot'),
			tpl       = fm.res('tpl', 'navdir'),
			ptpl      = fm.res('tpl', 'perms'),
			spinner   = $(fm.res('tpl', 'navspinner')),
			/**
			 * Convert places dir node into dir hash
			 *
			 * @param  String  directory id
			 * @return String
			 **/
			id2hash   = function(id) { return id.substr(6);	},
			/**
			 * Convert places dir node into dir hash
			 *
			 * @param  String  directory id
			 * @return String
			 **/
			hash2id   = function(hash) { return 'place-'+hash; },
			
			/**
			 * Save current places state
			 *
			 * @return void
			 **/
			save      = function() { fm.storage('places', dirs.join(',')); },
			/**
			 * Return node for given dir object
			 *
			 * @param  Object  directory object
			 * @return jQuery
			 **/
			create    = function(dir) {
				return $(tpl.replace(/\{id\}/, hash2id(dir.hash))
						.replace(/\{name\}/, fm.escape(dir.name))
						.replace(/\{cssclass\}/, fm.perms2class(dir))
						.replace(/\{permissions\}/, !dir.read || !dir.write ? ptpl : '')
						.replace(/\{symlink\}/, ''));
			},
			/**
			 * Add new node into places
			 *
			 * @param  Object  directory object
			 * @return void
			 **/
			add = function(dir) {
				var node = create(dir);

				if (subtree.children().length) {
					$.each(subtree.children(), function() {
						var current =  $(this);
						
						if (dir.name.localeCompare(current.children('.'+navdir).text()) < 0) {
							return !node.insertBefore(current);
						}
					});
				} 
				
				dirs.push(dir.hash);
				!node.parent().length && subtree.append(node);
				root.addClass(collapsed);
				node.draggable({
					appendTo : 'body',
					revert   : false,
					helper   : function() {
						var dir = $(this);
							
						dir.children().removeClass('ui-state-hover');
						
						return $('<div class="elfinder-place-drag elfinder-'+fm.direction+'"/>')
								.append(dir.clone())
								.data('hash', id2hash(dir.children(':first').attr('id')));

					},
					start    : function() { $(this).hide(); },
					stop     : function(e, ui) {
						var top    = places.offset().top,
							left   = places.offset().left,
							width  = places.width(),
							height = places.height(),
							x      = e.clientX,
							y      = e.clientY;
						
						if (x > left && x < left+width && y > top && y < y+height) {
							$(this).show();
						} else {
							remove(ui.helper.data('hash'));
							save();
						}
					}
				});
			}, 
			/**
			 * Remove dir from places
			 *
			 * @param  String  directory id
			 * @return void
			 **/
			remove = function(hash) {
				var ndx = $.inArray(hash, dirs);

				if (ndx !== -1) {
					dirs.splice(ndx, 1);
					subtree.find('#'+hash2id(hash)).parent().remove();
					!subtree.children().length && root.removeClass(collapsed+' '+expanded);
				}
			},
			/**
			 * Remove all dir from places
			 *
			 * @return void
			 **/
			clear = function() {
				subtree.empty();
				root.removeClass(collapsed+' '+expanded);
			},
			/**
			 * Node - wrapper for places root
			 *
			 * @type jQuery
			 **/
			wrapper = create({
					hash  : 'root-'+fm.namespace, 
					name  : fm.i18n(opts.name, 'places'),
					read  : true,
					write : true
				}),
			/**
			 * Places root node
			 *
			 * @type jQuery
			 **/
			root = wrapper.children('.'+navdir)
				.addClass(clroot)
				.click(function() {
					if (root.is('.'+collapsed)) {
						places.toggleClass(expanded);
						subtree.slideToggle();
						fm.storage('placesState', places.is('.'+expanded)? 1 : 0);
					}
				}),
			/**
			 * Container for dirs
			 *
			 * @type jQuery
			 **/
			subtree = wrapper.children('.'+fm.res(c, 'navsubtree')),
			/**
			 * Main places container
			 *
			 * @type jQuery
			 **/
			places = $(this).addClass(fm.res(c, 'tree')+' elfinder-places ui-corner-all')
				.hide()
				.append(wrapper)
				.appendTo(fm.getUI('navbar'))
				.delegate('.'+navdir, 'hover', function() {
					$(this).toggleClass('ui-state-hover');
				})
				.delegate('.'+navdir, 'click', function(e) {
					fm.exec('open', $(this).attr('id').substr(6));
				})
				.delegate('.'+navdir+':not(.'+clroot+')', 'contextmenu', function(e) {
					var hash = $(this).attr('id').substr(6);
					
					e.preventDefault();
					
					fm.trigger('contextmenu', {
						raw : [{
							label    : fm.i18n('rmFromPlaces'),
							icon     : 'rm',
							callback : function() { remove(hash); save(); }
						}],
						'x'       : e.clientX,
						'y'       : e.clientY
					})
					
				})
				.droppable({
					tolerance  : 'pointer',
					accept     : '.elfinder-cwd-file-wrapper,.elfinder-tree-dir,.elfinder-cwd-file',
					hoverClass : fm.res('class', 'adroppable'),
					drop       : function(e, ui) {
						var resolve = true;
						
						$.each(ui.helper.data('files'), function(i, hash) {
							var dir = fm.file(hash);
							
							if (dir && dir.mime == 'directory' && $.inArray(dir.hash, dirs) === -1) {
								add(dir);
							} else {
								resolve = false;
							}
						})
						save();
						resolve && ui.helper.hide();
					}
				});
	

		// on fm load - show places and load files from backend
		fm.one('load', function() {
			if (fm.oldAPI) {
				return;
			}
			
			places.show().parent().show();

			dirs = $.map(fm.storage('places').split(','), function(hash) { return hash || null});
			
			if (dirs.length) {
				root.prepend(spinner);
				
				fm.request({
					data : {cmd : 'info', targets : dirs},
					preventDefault : true
				})
				.done(function(data) {
					dirs = [];
					$.each(data.files, function(i, file) {
						file.mime == 'directory' && add(file);
					});
					save();
					if (fm.storage('placesState') > 0) {
						root.click();
					}
				})
				.always(function() {
					spinner.remove();
				})
			}
			

			fm.remove(function(e) {
				$.each(e.data.removed, function(i, hash) {
					remove(hash);
				});
				save();
			})
			.change(function(e) {
				$.each(e.data.changed, function(i, file) {
					if ($.inArray(file.hash, dirs) !== -1) {
						remove(file.hash);
						file.mime == 'directory' && add(file);
					}
				});
				save();
			})
			.bind('sync', function() {
				if (dirs.length) {
					root.prepend(spinner);

					fm.request({
						data : {cmd : 'info', targets : dirs},
						preventDefault : true
					})
					.done(function(data) {
						$.each(data.files || [], function(i, file) {
							if ($.inArray(file.hash, dirs) === -1) {
								remove(file.hash);
							}
						});
						save();
					})
					.always(function() {
						spinner.remove();
					});
				}
			})
			
		})
		
	});
};"use strict"
/**
 * @class  elFinder toolbar search button widget.
 *
 * @author Dmitry (dio) Levashov
 **/
$.fn.elfindersearchbutton = function(cmd) {
	return this.each(function() {
		var result = false,
			button = $(this).hide().addClass('ui-widget-content elfinder-button '+cmd.fm.res('class', 'searchbtn')+''),
			search = function() {
				var val = $.trim(input.val());
				if (val) {
					cmd.exec(val).done(function() {
						result = true;
						input.focus();
					});
					
				} else {
					cmd.fm.trigger('searchend')
				}
			},
			abort = function() {
				input.val('');
				if (result) {
					result = false;
					cmd.fm.trigger('searchend');
				}
			},
			input  = $('<input type="text" size="42"/>')
				.appendTo(button)
				// to avoid fm shortcuts on arrows
				.keypress(function(e) {
					e.stopPropagation();
				})
				.keydown(function(e) {
					e.stopPropagation();
					
					e.keyCode == 13 && search();
					
					if (e.keyCode== 27) {
						e.preventDefault();
						abort();
					}
				});
		
		$('<span class="ui-icon ui-icon-search" title="'+cmd.title+'"/>')
			.appendTo(button)
			.click(search);
		
		$('<span class="ui-icon ui-icon-close"/>')
			.appendTo(button)
			.click(abort)
		
		// wait when button will be added to DOM
		setTimeout(function() {
			button.parent().detach();
			cmd.fm.getUI('toolbar').prepend(button.show());
			// position icons for ie7
			if (cmd.fm.UA.ltIE7) {
				var icon = button.children(cmd.fm.direction == 'ltr' ? '.ui-icon-close' : '.ui-icon-search');
				icon.css({
					right : '',
					left  : parseInt(button.width())-icon.outerWidth(true)
				});
			}
		}, 200);
		
		cmd.fm
			.error(function() {
				input.unbind('keydown');
			})
			.select(function() {
				input.blur();
			})
			.bind('searchend', function() {
				input.val('');
			})
			.viewchange(abort)
			.shortcut({
				pattern     : 'ctrl+f f3',
				description : cmd.title,
				callback    : function() { input.select().focus(); }
			});

	});
};"use strict"
/**
 * @class  elFinder toolbar button menu with sort variants.
 *
 * @author Dmitry (dio) Levashov
 **/
$.fn.elfindersortbutton = function(cmd) {
	
	return this.each(function() {
		var fm       = cmd.fm,
			name     = cmd.name,
			c        = 'class',
			disabled = fm.res(c, 'disabled'),
			hover    = fm.res(c, 'hover'),
			item     = 'elfinder-button-menu-item',
			selected = item+'-selected',
			asc      = selected+'-asc',
			desc     = selected+'-desc',
			button   = $(this).addClass('ui-state-default elfinder-button elfinder-menubutton elfiner-button-'+name)
				.attr('title', cmd.title)
				.append('<span class="elfinder-button-icon elfinder-button-icon-'+name+'"/>')
				.hover(function(e) { !button.is('.'+disabled) && button.toggleClass(hover); })
				.click(function(e) {
					if (!button.is('.'+disabled)) {
						e.stopPropagation();
						menu.is(':hidden') && cmd.fm.getUI().click();
						menu.slideToggle(100);
					}
				}),
			menu = $('<div class="ui-widget ui-widget-content elfinder-button-menu ui-corner-all"/>')
				.hide()
				.appendTo(button)
				.zIndex(12+button.zIndex())
				.delegate('.'+item, 'hover', function() { $(this).toggleClass(hover) })
				.delegate('.'+item, 'click', function(e) {
					e.preventDefault();
					e.stopPropagation();
					hide();
				}),
			update = function() {
				menu.children(':not(:last)').removeClass(selected+' '+asc+' '+desc)
					.filter('[rel="'+fm.sortType+'"]')
					.addClass(selected+' '+(fm.sortOrder == 'asc' ? asc : desc));

				menu.children(':last').toggleClass(selected, fm.sortStickFolders);
			},
			hide = function() { menu.hide(); };
			
			
		$.each(fm.sortRules, function(name, value) {
			menu.append($('<div class="'+item+'" rel="'+name+'"><span class="ui-icon ui-icon-arrowthick-1-n"/><span class="ui-icon ui-icon-arrowthick-1-s"/>'+fm.i18n('sort'+name)+'</div>').data('type', name));
		});
		
		menu.children().click(function(e) {
			var type = $(this).attr('rel');
			
			cmd.exec([], {
				type  : type, 
				order : type == fm.sortType ? fm.sortOrder == 'asc' ? 'desc' : 'asc' : fm.sortOrder, 
				stick : fm.sortStickFolders
			});
		})
		
		$('<div class="'+item+' '+item+'-separated"><span class="ui-icon ui-icon-check"/>'+fm.i18n('sortFoldersFirst')+'</div>')
			.appendTo(menu)
			.click(function() {
				cmd.exec([], {type : fm.sortType, order : fm.sortOrder, stick : !fm.sortStickFolders});
			});		
		
		fm.bind('disable select', hide).getUI().click(hide);
			
		fm.bind('sortchange', update)
		
		if (menu.children().length > 1) {
			cmd.change(function() {
					button.toggleClass(disabled, cmd.disabled());
					update();
				})
				.change();
			
		} else {
			button.addClass(disabled);
		}

	});
	
}


;"use strict";
/**
 * @class elFinder ui
 * Display number of files/selected files and its size in statusbar
 *
 * @author Dmitry (dio) Levashov
 **/
$.fn.elfinderstat = function(fm) {
	return this.each(function() {
		var size       = $(this).addClass('elfinder-stat-size'),
			sel        = $('<div class="elfinder-stat-selected"/>'),
			titlesize  = fm.i18n('size').toLowerCase(),
			titleitems = fm.i18n('items').toLowerCase(),
			titlesel   = fm.i18n('selected'),
			setstat    = function(files, cwd) {
				var c = 0, 
					s = 0;

				$.each(files, function(i, file) {
					if (!cwd || file.phash == cwd) {
						c++;
						s += parseInt(file.size)||0;
					}
				})
				size.html(titleitems+': '+c+', '+titlesize+': '+fm.formatSize(s));
			};

		fm.getUI('statusbar').prepend(size).append(sel).show();
		
		fm
		.bind('open reload add remove change searchend', function() {
			setstat(fm.files(), fm.cwd().hash)
		})
		.search(function(e) {
			setstat(e.data.files);
		})
		.select(function() {
			var s = 0,
				c = 0,
				files = fm.selectedFiles();

			if (files.length == 1) {
				s = files[0].size;
				sel.html(fm.escape(files[0].name)+(s > 0 ? ', '+fm.formatSize(s) : ''));
				
				return;
			}

			$.each(files, function(i, file) {
				c++;
				s += parseInt(file.size)||0;
			});

			sel.html(c ? titlesel+': '+c+', '+titlesize+': '+fm.formatSize(s) : '&nbsp;');
		})

		;
	})
};"use strict";
/**
 * @class  elFinder toolbar
 *
 * @author Dmitry (dio) Levashov
 **/
$.fn.elfindertoolbar = function(fm, opts) {
	this.not('.elfinder-toolbar').each(function() {
		var commands = fm._commands,
			self     = $(this).addClass('ui-helper-clearfix ui-widget-header ui-corner-top elfinder-toolbar'),
			panels   = opts || [],
			l        = panels.length,
			i, cmd, panel, button;
		
		self.prev().length && self.parent().prepend(this);

		while (l--) {
			if (panels[l]) {
				panel = $('<div class="ui-widget-content ui-corner-all elfinder-buttonset"/>');
				i = panels[l].length;
				while (i--) {
					if ((cmd = commands[panels[l][i]])) {
						button = 'elfinder'+cmd.options.ui;
						$.fn[button] && panel.prepend($('<div/>')[button](cmd));
					}
				}
				
				panel.children().length && self.prepend(panel);
				panel.children(':gt(0)').before('<span class="ui-widget-content elfinder-toolbar-button-separator"/>');

			}
		}
		
		self.children().length && self.show();
	});
	
	return this;
};"use strict";
/**
 * @class  elFinder folders tree
 *
 * @author Dmitry (dio) Levashov
 **/
$.fn.elfindertree = function(fm, opts) {
	var treeclass = fm.res('class', 'tree');
	
	this.not('.'+treeclass).each(function() {

		var c = 'class',
			
			/**
			 * Root directory class name
			 *
			 * @type String
			 */
			root      = fm.res(c, 'treeroot'),

			/**
			 * Open root dir if not opened yet
			 *
			 * @type Boolean
			 */
			openRoot  = opts.openRootOnLoad,

			/**
			 * Subtree class name
			 *
			 * @type String
			 */
			subtree   = fm.res(c, 'navsubtree'),
			
			/**
			 * Directory class name
			 *
			 * @type String
			 */
			navdir    = fm.res(c, 'treedir'),
			
			/**
			 * Collapsed arrow class name
			 *
			 * @type String
			 */
			collapsed = fm.res(c, 'navcollapse'),
			
			/**
			 * Expanded arrow class name
			 *
			 * @type String
			 */
			expanded  = fm.res(c, 'navexpand'),
			
			/**
			 * Class name to mark arrow for directory with already loaded children
			 *
			 * @type String
			 */
			loaded    = 'elfinder-subtree-loaded',
			
			/**
			 * Arraw class name
			 *
			 * @type String
			 */
			arrow = fm.res(c, 'navarrow'),
			
			/**
			 * Current directory class name
			 *
			 * @type String
			 */
			active    = fm.res(c, 'active'),
			
			/**
			 * Droppable dirs dropover class
			 *
			 * @type String
			 */
			dropover = fm.res(c, 'adroppable'),
			
			/**
			 * Hover class name
			 *
			 * @type String
			 */
			hover    = fm.res(c, 'hover'),
			
			/**
			 * Disabled dir class name
			 *
			 * @type String
			 */
			disabled = fm.res(c, 'disabled'),
			
			/**
			 * Draggable dir class name
			 *
			 * @type String
			 */
			draggable = fm.res(c, 'draggable'),
			
			/**
			 * Droppable dir  class name
			 *
			 * @type String
			 */
			droppable = fm.res(c, 'droppable'),
			
			insideNavbar = function(x) {
				var left = navbar.offset().left;
					
				return left <= x && x <= left + navbar.width();
			},
			
			drop = fm.droppable.drop,
			
			/**
			 * Droppable options
			 *
			 * @type Object
			 */
			droppableopts = $.extend(true, {}, fm.droppable, {
				// show subfolders on dropover
				over : function(e) { 
					var link = $(this),
						cl   = hover+' '+dropover;

					if (insideNavbar(e.clientX)) {
						link.addClass(cl)
						if (link.is('.'+collapsed+':not(.'+expanded+')')) {
							setTimeout(function() {
								link.is('.'+dropover) && link.children('.'+arrow).click();
							}, 500);
						}
					} else {
						link.removeClass(cl);
					}
				},
				out : function() { $(this).removeClass(hover+' '+dropover); },
				drop : function(e, ui) { insideNavbar(e.clientX) && drop.call(this, e, ui); }
			}),
			
			spinner = $(fm.res('tpl', 'navspinner')),
			
			/**
			 * Directory html template
			 *
			 * @type String
			 */
			tpl = fm.res('tpl', 'navdir'),
			
			/**
			 * Permissions marker html template
			 *
			 * @type String
			 */
			ptpl = fm.res('tpl', 'perms'),
			
			/**
			 * Symlink marker html template
			 *
			 * @type String
			 */
			stpl = fm.res('tpl', 'symlink'),
			
			/**
			 * Html template replacement methods
			 *
			 * @type Object
			 */
			replace = {
				id          : function(dir) { return fm.navHash2Id(dir.hash) },
				cssclass    : function(dir) { return (dir.phash ? '' : root)+' '+navdir+' '+fm.perms2class(dir)+' '+(dir.dirs && !dir.link ? collapsed : ''); },
				permissions : function(dir) { return !dir.read || !dir.write ? ptpl : ''; },
				symlink     : function(dir) { return dir.alias ? stpl : ''; }
			},
			
			/**
			 * Return html for given dir
			 *
			 * @param  Object  directory
			 * @return String
			 */
			itemhtml = function(dir) {
				dir.name = fm.escape(dir.i18 || dir.name);
				
				return tpl.replace(/(?:\{([a-z]+)\})/ig, function(m, key) {
					return dir[key] || (replace[key] ? replace[key](dir) : '');
				});
			},
			
			/**
			 * Return only dirs from files list
			 *
			 * @param  Array  files list
			 * @return Array
			 */
			filter = function(files) {
				return $.map(files||[], function(f) { return f.mime == 'directory' ? f : null });
			},
			
			/**
			 * Find parent subtree for required directory
			 *
			 * @param  String  dir hash
			 * @return jQuery
			 */
			findSubtree = function(hash) {
				return hash ? tree.find('#'+fm.navHash2Id(hash)).next('.'+subtree) : tree;
			},
			
			/**
			 * Find directory (wrapper) in required node
			 * before which we can insert new directory
			 *
			 * @param  jQuery  parent directory
			 * @param  Object  new directory
			 * @return jQuery
			 */
			findSibling = function(subtree, dir) {
				var node = subtree.children(':first'),
					info;

				while (node.length) {
					info = fm.file(fm.navId2Hash(node.children('[id]').attr('id')));
					
					if ((info = fm.file(fm.navId2Hash(node.children('[id]').attr('id')))) 
					&& dir.name.toLowerCase().localeCompare(info.name.toLowerCase()) < 0) {
						return node;
					}
					node = node.next();
				}
				return $('');
			},
			
			/**
			 * Add new dirs in tree
			 *
			 * @param  Array  dirs list
			 * @return void
			 */
			updateTree = function(dirs) {
				var length  = dirs.length,
					orphans = [],
					i = dirs.length, 
					dir, html, parent, sibling;

				var firstVol = true; // check for netmount volume
				while (i--) {
					dir = dirs[i];

					if (tree.find('#'+fm.navHash2Id(dir.hash)).length) {
						continue;
					}
					
					if ((parent = findSubtree(dir.phash)).length) {
						html = itemhtml(dir);
						if (dir.phash && (sibling = findSibling(parent, dir)).length) {
							sibling.before(html);
						} else {
							parent[firstVol || dir.phash ? 'append' : 'prepend'](html);
							firstVol = false;
						}
					} else {
						orphans.push(dir);
					}
				}

				if (orphans.length && orphans.length < length) {
					return updateTree(orphans);
				} 
				
				setTimeout(function() {
					updateDroppable();
				}, 10);
				
			},
			
			/**
			 * Mark current directory as active
			 * If current directory is not in tree - load it and its parents
			 *
			 * @param {Boolean} do not recursive call
			 * @return void
			 */
			sync = function(stopRec) {
				var cwd     = fm.cwd().hash,
					current = tree.find('#'+fm.navHash2Id(cwd)), 
					rootNode, dir;
				
				if (openRoot) {
					rootNode = tree.find('#'+fm.navHash2Id(fm.root()));
					rootNode.is('.'+loaded) && rootNode.addClass(expanded).next('.'+subtree).show();
					openRoot = false;
				}
				
				if (!current.is('.'+active)) {
					tree.find('.'+navdir+'.'+active).removeClass(active);
					current.addClass(active);
				}

				if (opts.syncTree) {
					if (current.length) {
						return current.parentsUntil('.'+root).filter('.'+subtree).show().prev('.'+navdir).addClass(expanded);
					}
					if (fm.newAPI) {
						dir = fm.file(cwd);
						if (dir && dir.phash && tree.find('#'+fm.navHash2Id(dir.phash)).length) {
							updateTree([dir]);
							return sync();
						}
						fm.request({
							data : {cmd : 'parents', target : cwd},
							preventFail : true
						})
						.done(function(data) {
							var dirs = filter(data.tree);
							updateTree(dirs);
							updateArrows(dirs, loaded);
							cwd == fm.cwd().hash && sync(true);
						})
						;
					}
					
				}
			},
			
			/**
			 * Make writable and not root dirs droppable
			 *
			 * @return void
			 */
			updateDroppable = function() {
				tree.find('.'+navdir+':not(.'+droppable+',.elfinder-ro,.elfinder-na)').droppable(droppableopts);
			},
			
			/**
			 * Check required folders for subfolders and update arrow classes
			 *
			 * @param  Array  folders to check
			 * @param  String css class 
			 * @return void
			 */
			updateArrows = function(dirs, cls) {
				var sel = cls == loaded
						? '.'+collapsed+':not(.'+loaded+')'
						: ':not(.'+collapsed+')';
				
						
				//tree.find('.'+subtree+':has(*)').prev(':not(.'+collapsed+')').addClass(collapsed)

				$.each(dirs, function(i, dir) {
					tree.find('#'+fm.navHash2Id(dir.phash)+sel)
						.filter(function() { return $(this).next('.'+subtree).children().length > 0 })
						.addClass(cls);
				})
			},
			
			
			
			/**
			 * Navigation tree
			 *
			 * @type JQuery
			 */
			tree = $(this).addClass(treeclass)
				// make dirs draggable and toggle hover class
				.delegate('.'+navdir, 'hover', function(e) {
					var link  = $(this), 
						enter = e.type == 'mouseenter';
					
					if (!link.is('.'+dropover+' ,.'+disabled)) {
						enter && !link.is('.'+root+',.'+draggable+',.elfinder-na,.elfinder-wo') && link.draggable(fm.draggable);
						link.toggleClass(hover, enter);
					}
				})
				// add/remove dropover css class
				.delegate('.'+navdir, 'dropover dropout drop', function(e) {
					$(this)[e.type == 'dropover' ? 'addClass' : 'removeClass'](dropover+' '+hover);
				})
				// open dir or open subfolders in tree
				.delegate('.'+navdir, 'click', function(e) {
					var link = $(this),
						hash = fm.navId2Hash(link.attr('id')),
						file = fm.file(hash);
				
					fm.trigger('searchend');
				
					if (hash != fm.cwd().hash && !link.is('.'+disabled)) {
						fm.exec('open', file.thash || hash);
					} else if (link.is('.'+collapsed)) {
						link.children('.'+arrow).click();
					}
				})
				// toggle subfolders in tree
				.delegate('.'+navdir+'.'+collapsed+' .'+arrow, 'click', function(e) {
					var arrow = $(this),
						link  = arrow.parent('.'+navdir),
						stree = link.next('.'+subtree);

					e.stopPropagation();

					if (link.is('.'+loaded)) {
						link.toggleClass(expanded);
						stree.slideToggle()
					} else {
						spinner.insertBefore(arrow);
						link.removeClass(collapsed);

						fm.request({cmd : 'tree', target : fm.navId2Hash(link.attr('id'))})
							.done(function(data) { 
								updateTree(filter(data.tree)); 
								
								if (stree.children().length) {
									link.addClass(collapsed+' '+expanded);
									stree.slideDown();
								} 
								sync();
							})
							.always(function(data) {
								spinner.remove();
								link.addClass(loaded);
							});
					}
				})
				.delegate('.'+navdir, 'contextmenu', function(e) {
					e.preventDefault();

					fm.trigger('contextmenu', {
						'type'    : 'navbar',
						'targets' : [fm.navId2Hash($(this).attr('id'))],
						'x'       : e.clientX,
						'y'       : e.clientY
					});
				}),
			// move tree into navbar
			navbar = fm.getUI('navbar').append(tree).show()
				
			;

		fm.open(function(e) {
			var data = e.data,
				dirs = filter(data.files);

			data.init && tree.empty();

			if (dirs.length) {
				updateTree(dirs);
				updateArrows(dirs, loaded);
			} 
			sync();
		})
		// add new dirs
		.add(function(e) {
			var dirs = filter(e.data.added);

			if (dirs.length) {
				updateTree(dirs);
				updateArrows(dirs, collapsed);
			}
		})
		// update changed dirs
		.change(function(e) {
			var dirs = filter(e.data.changed),
				l    = dirs.length,
				dir, node, tmp, realParent, reqParent, realSibling, reqSibling, isExpanded, isLoaded;
			
			while (l--) {
				dir = dirs[l];
				if ((node = tree.find('#'+fm.navHash2Id(dir.hash))).length) {
					if (dir.phash) {
						realParent  = node.closest('.'+subtree);
						reqParent   = findSubtree(dir.phash);
						realSibling = node.parent().next();
						reqSibling  = findSibling(reqParent, dir);
						
						if (!reqParent.length) {
							continue;
						}
						
						if (reqParent[0] !== realParent[0] || realSibling.get(0) !== reqSibling.get(0)) {
							reqSibling.length ? reqSibling.before(node) : reqParent.append(node);
						}
					}
					isExpanded = node.is('.'+expanded);
					isLoaded   = node.is('.'+loaded);
					tmp        = $(itemhtml(dir));
					node.replaceWith(tmp.children('.'+navdir));
					
					if (dir.dirs 
					&& (isExpanded || isLoaded) 
					&& (node = tree.find('#'+fm.navHash2Id(dir.hash))) 
					&& node.next('.'+subtree).children().length) {
						isExpanded && node.addClass(expanded);
						isLoaded && node.addClass(loaded);
					}
				}
			}

			sync();
			updateDroppable();
		})
		// remove dirs
		.remove(function(e) {
			var dirs = e.data.removed,
				l    = dirs.length,
				node, stree;
			
			while (l--) {
				if ((node = tree.find('#'+fm.navHash2Id(dirs[l]))).length) {
					stree = node.closest('.'+subtree);
					node.parent().detach();
					if (!stree.children().length) {
						stree.hide().prev('.'+navdir).removeClass(collapsed+' '+expanded+' '+loaded);
					}
				}
			}
		})
		// add/remove active class for current dir
		.bind('search searchend', function(e) {
			tree.find('#'+fm.navHash2Id(fm.cwd().hash))[e.type == 'search' ? 'removeClass' : 'addClass'](active);
		})
		// lock/unlock dirs while moving
		.bind('lockfiles unlockfiles', function(e) {
			var lock = e.type == 'lockfiles',
				act  = lock ? 'disable' : 'enable',
				dirs = $.map(e.data.files||[], function(h) {  
					var dir = fm.file(h);
					return dir && dir.mime == 'directory' ? h : null;
				})
				
			$.each(dirs, function(i, hash) {
				var dir = tree.find('#'+fm.navHash2Id(hash));
				
				if (dir.length) {
					dir.is('.'+draggable) && dir.draggable(act);
					dir.is('.'+droppable) && dir.droppable(active);
					dir[lock ? 'addClass' : 'removeClass'](disabled);
				}
			});
		})

	});
	
	return this;
}
;"use strict";
/**
 * @class  elFinder toolbar's button tor upload file
 *
 * @author Dmitry (dio) Levashov
 **/
$.fn.elfinderuploadbutton = function(cmd) {
	return this.each(function() {
		var button = $(this).elfinderbutton(cmd)
				.unbind('click'), 
			form = $('<form/>').appendTo(button),
			input = $('<input type="file" multiple="true"/>')
				.change(function() {
					var _input = $(this);
					if (_input.val()) {
						cmd.exec({input : _input.remove()[0]});
						input.clone(true).appendTo(form);
					} 
				});

		form.append(input.clone(true));
				
		cmd.change(function() {
			form[cmd.disabled() ? 'hide' : 'show']();
		})
		.change();
	});
}
;"use strict"
/**
 * @class  elFinder toolbar button to switch current directory view.
 *
 * @author Dmitry (dio) Levashov
 **/
$.fn.elfinderviewbutton = function(cmd) {
	return this.each(function() {
		var button = $(this).elfinderbutton(cmd),
			icon   = button.children('.elfinder-button-icon');

		cmd.change(function() {
			var icons = cmd.value == 'icons';

			icon.toggleClass('elfinder-button-icon-view-list', icons);
			button.attr('title', cmd.fm.i18n(icons ? 'viewlist' : 'viewicons'));
		});
	});
};"use strict";
/**
 * @class elfinderworkzone - elFinder container for nav and current directory
 * @author Dmitry (dio) Levashov
 **/
$.fn.elfinderworkzone = function(fm) {
	var cl = 'elfinder-workzone';
	
	this.not('.'+cl).each(function() {
		var wz     = $(this).addClass(cl),
			wdelta = wz.outerHeight(true) - wz.height(),
			parent = wz.parent();
			
		parent.add(window).bind('resize', function() {
				var height = parent.height();

				parent.children(':visible:not(.'+cl+')').each(function() {
					var ch = $(this);

					if (ch.css('position') != 'absolute') {
						height -= ch.outerHeight(true);
					}
				});
				
				wz.height(height - wdelta);
			});
	});
	return this;
}


;/*! jQuery UI - v1.10.4 - 2014-03-02
* http://jqueryui.com
* Includes: jquery.ui.core.js, jquery.ui.widget.js, jquery.ui.mouse.js, jquery.ui.position.js, jquery.ui.draggable.js, jquery.ui.droppable.js, jquery.ui.resizable.js, jquery.ui.selectable.js, jquery.ui.sortable.js, jquery.ui.accordion.js, jquery.ui.autocomplete.js, jquery.ui.button.js, jquery.ui.datepicker.js, jquery.ui.dialog.js, jquery.ui.menu.js, jquery.ui.progressbar.js, jquery.ui.slider.js, jquery.ui.spinner.js, jquery.ui.tabs.js, jquery.ui.tooltip.js, jquery.ui.effect.js, jquery.ui.effect-blind.js, jquery.ui.effect-bounce.js, jquery.ui.effect-clip.js, jquery.ui.effect-drop.js, jquery.ui.effect-explode.js, jquery.ui.effect-fade.js, jquery.ui.effect-fold.js, jquery.ui.effect-highlight.js, jquery.ui.effect-pulsate.js, jquery.ui.effect-scale.js, jquery.ui.effect-shake.js, jquery.ui.effect-slide.js, jquery.ui.effect-transfer.js
* Copyright 2014 jQuery Foundation and other contributors; Licensed MIT */

(function(e,t){function i(t,i){var s,a,o,r=t.nodeName.toLowerCase();return"area"===r?(s=t.parentNode,a=s.name,t.href&&a&&"map"===s.nodeName.toLowerCase()?(o=e("img[usemap=#"+a+"]")[0],!!o&&n(o)):!1):(/input|select|textarea|button|object/.test(r)?!t.disabled:"a"===r?t.href||i:i)&&n(t)}function n(t){return e.expr.filters.visible(t)&&!e(t).parents().addBack().filter(function(){return"hidden"===e.css(this,"visibility")}).length}var s=0,a=/^ui-id-\d+$/;e.ui=e.ui||{},e.extend(e.ui,{version:"1.10.4",keyCode:{BACKSPACE:8,COMMA:188,DELETE:46,DOWN:40,END:35,ENTER:13,ESCAPE:27,HOME:36,LEFT:37,NUMPAD_ADD:107,NUMPAD_DECIMAL:110,NUMPAD_DIVIDE:111,NUMPAD_ENTER:108,NUMPAD_MULTIPLY:106,NUMPAD_SUBTRACT:109,PAGE_DOWN:34,PAGE_UP:33,PERIOD:190,RIGHT:39,SPACE:32,TAB:9,UP:38}}),e.fn.extend({focus:function(t){return function(i,n){return"number"==typeof i?this.each(function(){var t=this;setTimeout(function(){e(t).focus(),n&&n.call(t)},i)}):t.apply(this,arguments)}}(e.fn.focus),scrollParent:function(){var t;return t=e.ui.ie&&/(static|relative)/.test(this.css("position"))||/absolute/.test(this.css("position"))?this.parents().filter(function(){return/(relative|absolute|fixed)/.test(e.css(this,"position"))&&/(auto|scroll)/.test(e.css(this,"overflow")+e.css(this,"overflow-y")+e.css(this,"overflow-x"))}).eq(0):this.parents().filter(function(){return/(auto|scroll)/.test(e.css(this,"overflow")+e.css(this,"overflow-y")+e.css(this,"overflow-x"))}).eq(0),/fixed/.test(this.css("position"))||!t.length?e(document):t},zIndex:function(i){if(i!==t)return this.css("zIndex",i);if(this.length)for(var n,s,a=e(this[0]);a.length&&a[0]!==document;){if(n=a.css("position"),("absolute"===n||"relative"===n||"fixed"===n)&&(s=parseInt(a.css("zIndex"),10),!isNaN(s)&&0!==s))return s;a=a.parent()}return 0},uniqueId:function(){return this.each(function(){this.id||(this.id="ui-id-"+ ++s)})},removeUniqueId:function(){return this.each(function(){a.test(this.id)&&e(this).removeAttr("id")})}}),e.extend(e.expr[":"],{data:e.expr.createPseudo?e.expr.createPseudo(function(t){return function(i){return!!e.data(i,t)}}):function(t,i,n){return!!e.data(t,n[3])},focusable:function(t){return i(t,!isNaN(e.attr(t,"tabindex")))},tabbable:function(t){var n=e.attr(t,"tabindex"),s=isNaN(n);return(s||n>=0)&&i(t,!s)}}),e("<a>").outerWidth(1).jquery||e.each(["Width","Height"],function(i,n){function s(t,i,n,s){return e.each(a,function(){i-=parseFloat(e.css(t,"padding"+this))||0,n&&(i-=parseFloat(e.css(t,"border"+this+"Width"))||0),s&&(i-=parseFloat(e.css(t,"margin"+this))||0)}),i}var a="Width"===n?["Left","Right"]:["Top","Bottom"],o=n.toLowerCase(),r={innerWidth:e.fn.innerWidth,innerHeight:e.fn.innerHeight,outerWidth:e.fn.outerWidth,outerHeight:e.fn.outerHeight};e.fn["inner"+n]=function(i){return i===t?r["inner"+n].call(this):this.each(function(){e(this).css(o,s(this,i)+"px")})},e.fn["outer"+n]=function(t,i){return"number"!=typeof t?r["outer"+n].call(this,t):this.each(function(){e(this).css(o,s(this,t,!0,i)+"px")})}}),e.fn.addBack||(e.fn.addBack=function(e){return this.add(null==e?this.prevObject:this.prevObject.filter(e))}),e("<a>").data("a-b","a").removeData("a-b").data("a-b")&&(e.fn.removeData=function(t){return function(i){return arguments.length?t.call(this,e.camelCase(i)):t.call(this)}}(e.fn.removeData)),e.ui.ie=!!/msie [\w.]+/.exec(navigator.userAgent.toLowerCase()),e.support.selectstart="onselectstart"in document.createElement("div"),e.fn.extend({disableSelection:function(){return this.bind((e.support.selectstart?"selectstart":"mousedown")+".ui-disableSelection",function(e){e.preventDefault()})},enableSelection:function(){return this.unbind(".ui-disableSelection")}}),e.extend(e.ui,{plugin:{add:function(t,i,n){var s,a=e.ui[t].prototype;for(s in n)a.plugins[s]=a.plugins[s]||[],a.plugins[s].push([i,n[s]])},call:function(e,t,i){var n,s=e.plugins[t];if(s&&e.element[0].parentNode&&11!==e.element[0].parentNode.nodeType)for(n=0;s.length>n;n++)e.options[s[n][0]]&&s[n][1].apply(e.element,i)}},hasScroll:function(t,i){if("hidden"===e(t).css("overflow"))return!1;var n=i&&"left"===i?"scrollLeft":"scrollTop",s=!1;return t[n]>0?!0:(t[n]=1,s=t[n]>0,t[n]=0,s)}})})(jQuery);(function(t,e){var i=0,s=Array.prototype.slice,n=t.cleanData;t.cleanData=function(e){for(var i,s=0;null!=(i=e[s]);s++)try{t(i).triggerHandler("remove")}catch(o){}n(e)},t.widget=function(i,s,n){var o,a,r,h,l={},c=i.split(".")[0];i=i.split(".")[1],o=c+"-"+i,n||(n=s,s=t.Widget),t.expr[":"][o.toLowerCase()]=function(e){return!!t.data(e,o)},t[c]=t[c]||{},a=t[c][i],r=t[c][i]=function(t,i){return this._createWidget?(arguments.length&&this._createWidget(t,i),e):new r(t,i)},t.extend(r,a,{version:n.version,_proto:t.extend({},n),_childConstructors:[]}),h=new s,h.options=t.widget.extend({},h.options),t.each(n,function(i,n){return t.isFunction(n)?(l[i]=function(){var t=function(){return s.prototype[i].apply(this,arguments)},e=function(t){return s.prototype[i].apply(this,t)};return function(){var i,s=this._super,o=this._superApply;return this._super=t,this._superApply=e,i=n.apply(this,arguments),this._super=s,this._superApply=o,i}}(),e):(l[i]=n,e)}),r.prototype=t.widget.extend(h,{widgetEventPrefix:a?h.widgetEventPrefix||i:i},l,{constructor:r,namespace:c,widgetName:i,widgetFullName:o}),a?(t.each(a._childConstructors,function(e,i){var s=i.prototype;t.widget(s.namespace+"."+s.widgetName,r,i._proto)}),delete a._childConstructors):s._childConstructors.push(r),t.widget.bridge(i,r)},t.widget.extend=function(i){for(var n,o,a=s.call(arguments,1),r=0,h=a.length;h>r;r++)for(n in a[r])o=a[r][n],a[r].hasOwnProperty(n)&&o!==e&&(i[n]=t.isPlainObject(o)?t.isPlainObject(i[n])?t.widget.extend({},i[n],o):t.widget.extend({},o):o);return i},t.widget.bridge=function(i,n){var o=n.prototype.widgetFullName||i;t.fn[i]=function(a){var r="string"==typeof a,h=s.call(arguments,1),l=this;return a=!r&&h.length?t.widget.extend.apply(null,[a].concat(h)):a,r?this.each(function(){var s,n=t.data(this,o);return n?t.isFunction(n[a])&&"_"!==a.charAt(0)?(s=n[a].apply(n,h),s!==n&&s!==e?(l=s&&s.jquery?l.pushStack(s.get()):s,!1):e):t.error("no such method '"+a+"' for "+i+" widget instance"):t.error("cannot call methods on "+i+" prior to initialization; "+"attempted to call method '"+a+"'")}):this.each(function(){var e=t.data(this,o);e?e.option(a||{})._init():t.data(this,o,new n(a,this))}),l}},t.Widget=function(){},t.Widget._childConstructors=[],t.Widget.prototype={widgetName:"widget",widgetEventPrefix:"",defaultElement:"<div>",options:{disabled:!1,create:null},_createWidget:function(e,s){s=t(s||this.defaultElement||this)[0],this.element=t(s),this.uuid=i++,this.eventNamespace="."+this.widgetName+this.uuid,this.options=t.widget.extend({},this.options,this._getCreateOptions(),e),this.bindings=t(),this.hoverable=t(),this.focusable=t(),s!==this&&(t.data(s,this.widgetFullName,this),this._on(!0,this.element,{remove:function(t){t.target===s&&this.destroy()}}),this.document=t(s.style?s.ownerDocument:s.document||s),this.window=t(this.document[0].defaultView||this.document[0].parentWindow)),this._create(),this._trigger("create",null,this._getCreateEventData()),this._init()},_getCreateOptions:t.noop,_getCreateEventData:t.noop,_create:t.noop,_init:t.noop,destroy:function(){this._destroy(),this.element.unbind(this.eventNamespace).removeData(this.widgetName).removeData(this.widgetFullName).removeData(t.camelCase(this.widgetFullName)),this.widget().unbind(this.eventNamespace).removeAttr("aria-disabled").removeClass(this.widgetFullName+"-disabled "+"ui-state-disabled"),this.bindings.unbind(this.eventNamespace),this.hoverable.removeClass("ui-state-hover"),this.focusable.removeClass("ui-state-focus")},_destroy:t.noop,widget:function(){return this.element},option:function(i,s){var n,o,a,r=i;if(0===arguments.length)return t.widget.extend({},this.options);if("string"==typeof i)if(r={},n=i.split("."),i=n.shift(),n.length){for(o=r[i]=t.widget.extend({},this.options[i]),a=0;n.length-1>a;a++)o[n[a]]=o[n[a]]||{},o=o[n[a]];if(i=n.pop(),1===arguments.length)return o[i]===e?null:o[i];o[i]=s}else{if(1===arguments.length)return this.options[i]===e?null:this.options[i];r[i]=s}return this._setOptions(r),this},_setOptions:function(t){var e;for(e in t)this._setOption(e,t[e]);return this},_setOption:function(t,e){return this.options[t]=e,"disabled"===t&&(this.widget().toggleClass(this.widgetFullName+"-disabled ui-state-disabled",!!e).attr("aria-disabled",e),this.hoverable.removeClass("ui-state-hover"),this.focusable.removeClass("ui-state-focus")),this},enable:function(){return this._setOption("disabled",!1)},disable:function(){return this._setOption("disabled",!0)},_on:function(i,s,n){var o,a=this;"boolean"!=typeof i&&(n=s,s=i,i=!1),n?(s=o=t(s),this.bindings=this.bindings.add(s)):(n=s,s=this.element,o=this.widget()),t.each(n,function(n,r){function h(){return i||a.options.disabled!==!0&&!t(this).hasClass("ui-state-disabled")?("string"==typeof r?a[r]:r).apply(a,arguments):e}"string"!=typeof r&&(h.guid=r.guid=r.guid||h.guid||t.guid++);var l=n.match(/^(\w+)\s*(.*)$/),c=l[1]+a.eventNamespace,u=l[2];u?o.delegate(u,c,h):s.bind(c,h)})},_off:function(t,e){e=(e||"").split(" ").join(this.eventNamespace+" ")+this.eventNamespace,t.unbind(e).undelegate(e)},_delay:function(t,e){function i(){return("string"==typeof t?s[t]:t).apply(s,arguments)}var s=this;return setTimeout(i,e||0)},_hoverable:function(e){this.hoverable=this.hoverable.add(e),this._on(e,{mouseenter:function(e){t(e.currentTarget).addClass("ui-state-hover")},mouseleave:function(e){t(e.currentTarget).removeClass("ui-state-hover")}})},_focusable:function(e){this.focusable=this.focusable.add(e),this._on(e,{focusin:function(e){t(e.currentTarget).addClass("ui-state-focus")},focusout:function(e){t(e.currentTarget).removeClass("ui-state-focus")}})},_trigger:function(e,i,s){var n,o,a=this.options[e];if(s=s||{},i=t.Event(i),i.type=(e===this.widgetEventPrefix?e:this.widgetEventPrefix+e).toLowerCase(),i.target=this.element[0],o=i.originalEvent)for(n in o)n in i||(i[n]=o[n]);return this.element.trigger(i,s),!(t.isFunction(a)&&a.apply(this.element[0],[i].concat(s))===!1||i.isDefaultPrevented())}},t.each({show:"fadeIn",hide:"fadeOut"},function(e,i){t.Widget.prototype["_"+e]=function(s,n,o){"string"==typeof n&&(n={effect:n});var a,r=n?n===!0||"number"==typeof n?i:n.effect||i:e;n=n||{},"number"==typeof n&&(n={duration:n}),a=!t.isEmptyObject(n),n.complete=o,n.delay&&s.delay(n.delay),a&&t.effects&&t.effects.effect[r]?s[e](n):r!==e&&s[r]?s[r](n.duration,n.easing,o):s.queue(function(i){t(this)[e](),o&&o.call(s[0]),i()})}})})(jQuery);(function(t){var e=!1;t(document).mouseup(function(){e=!1}),t.widget("ui.mouse",{version:"1.10.4",options:{cancel:"input,textarea,button,select,option",distance:1,delay:0},_mouseInit:function(){var e=this;this.element.bind("mousedown."+this.widgetName,function(t){return e._mouseDown(t)}).bind("click."+this.widgetName,function(i){return!0===t.data(i.target,e.widgetName+".preventClickEvent")?(t.removeData(i.target,e.widgetName+".preventClickEvent"),i.stopImmediatePropagation(),!1):undefined}),this.started=!1},_mouseDestroy:function(){this.element.unbind("."+this.widgetName),this._mouseMoveDelegate&&t(document).unbind("mousemove."+this.widgetName,this._mouseMoveDelegate).unbind("mouseup."+this.widgetName,this._mouseUpDelegate)},_mouseDown:function(i){if(!e){this._mouseStarted&&this._mouseUp(i),this._mouseDownEvent=i;var s=this,n=1===i.which,a="string"==typeof this.options.cancel&&i.target.nodeName?t(i.target).closest(this.options.cancel).length:!1;return n&&!a&&this._mouseCapture(i)?(this.mouseDelayMet=!this.options.delay,this.mouseDelayMet||(this._mouseDelayTimer=setTimeout(function(){s.mouseDelayMet=!0},this.options.delay)),this._mouseDistanceMet(i)&&this._mouseDelayMet(i)&&(this._mouseStarted=this._mouseStart(i)!==!1,!this._mouseStarted)?(i.preventDefault(),!0):(!0===t.data(i.target,this.widgetName+".preventClickEvent")&&t.removeData(i.target,this.widgetName+".preventClickEvent"),this._mouseMoveDelegate=function(t){return s._mouseMove(t)},this._mouseUpDelegate=function(t){return s._mouseUp(t)},t(document).bind("mousemove."+this.widgetName,this._mouseMoveDelegate).bind("mouseup."+this.widgetName,this._mouseUpDelegate),i.preventDefault(),e=!0,!0)):!0}},_mouseMove:function(e){return t.ui.ie&&(!document.documentMode||9>document.documentMode)&&!e.button?this._mouseUp(e):this._mouseStarted?(this._mouseDrag(e),e.preventDefault()):(this._mouseDistanceMet(e)&&this._mouseDelayMet(e)&&(this._mouseStarted=this._mouseStart(this._mouseDownEvent,e)!==!1,this._mouseStarted?this._mouseDrag(e):this._mouseUp(e)),!this._mouseStarted)},_mouseUp:function(e){return t(document).unbind("mousemove."+this.widgetName,this._mouseMoveDelegate).unbind("mouseup."+this.widgetName,this._mouseUpDelegate),this._mouseStarted&&(this._mouseStarted=!1,e.target===this._mouseDownEvent.target&&t.data(e.target,this.widgetName+".preventClickEvent",!0),this._mouseStop(e)),!1},_mouseDistanceMet:function(t){return Math.max(Math.abs(this._mouseDownEvent.pageX-t.pageX),Math.abs(this._mouseDownEvent.pageY-t.pageY))>=this.options.distance},_mouseDelayMet:function(){return this.mouseDelayMet},_mouseStart:function(){},_mouseDrag:function(){},_mouseStop:function(){},_mouseCapture:function(){return!0}})})(jQuery);(function(t,e){function i(t,e,i){return[parseFloat(t[0])*(p.test(t[0])?e/100:1),parseFloat(t[1])*(p.test(t[1])?i/100:1)]}function s(e,i){return parseInt(t.css(e,i),10)||0}function n(e){var i=e[0];return 9===i.nodeType?{width:e.width(),height:e.height(),offset:{top:0,left:0}}:t.isWindow(i)?{width:e.width(),height:e.height(),offset:{top:e.scrollTop(),left:e.scrollLeft()}}:i.preventDefault?{width:0,height:0,offset:{top:i.pageY,left:i.pageX}}:{width:e.outerWidth(),height:e.outerHeight(),offset:e.offset()}}t.ui=t.ui||{};var a,o=Math.max,r=Math.abs,l=Math.round,h=/left|center|right/,c=/top|center|bottom/,u=/[\+\-]\d+(\.[\d]+)?%?/,d=/^\w+/,p=/%$/,f=t.fn.position;t.position={scrollbarWidth:function(){if(a!==e)return a;var i,s,n=t("<div style='display:block;position:absolute;width:50px;height:50px;overflow:hidden;'><div style='height:100px;width:auto;'></div></div>"),o=n.children()[0];return t("body").append(n),i=o.offsetWidth,n.css("overflow","scroll"),s=o.offsetWidth,i===s&&(s=n[0].clientWidth),n.remove(),a=i-s},getScrollInfo:function(e){var i=e.isWindow||e.isDocument?"":e.element.css("overflow-x"),s=e.isWindow||e.isDocument?"":e.element.css("overflow-y"),n="scroll"===i||"auto"===i&&e.width<e.element[0].scrollWidth,a="scroll"===s||"auto"===s&&e.height<e.element[0].scrollHeight;return{width:a?t.position.scrollbarWidth():0,height:n?t.position.scrollbarWidth():0}},getWithinInfo:function(e){var i=t(e||window),s=t.isWindow(i[0]),n=!!i[0]&&9===i[0].nodeType;return{element:i,isWindow:s,isDocument:n,offset:i.offset()||{left:0,top:0},scrollLeft:i.scrollLeft(),scrollTop:i.scrollTop(),width:s?i.width():i.outerWidth(),height:s?i.height():i.outerHeight()}}},t.fn.position=function(e){if(!e||!e.of)return f.apply(this,arguments);e=t.extend({},e);var a,p,g,m,v,_,b=t(e.of),y=t.position.getWithinInfo(e.within),k=t.position.getScrollInfo(y),w=(e.collision||"flip").split(" "),D={};return _=n(b),b[0].preventDefault&&(e.at="left top"),p=_.width,g=_.height,m=_.offset,v=t.extend({},m),t.each(["my","at"],function(){var t,i,s=(e[this]||"").split(" ");1===s.length&&(s=h.test(s[0])?s.concat(["center"]):c.test(s[0])?["center"].concat(s):["center","center"]),s[0]=h.test(s[0])?s[0]:"center",s[1]=c.test(s[1])?s[1]:"center",t=u.exec(s[0]),i=u.exec(s[1]),D[this]=[t?t[0]:0,i?i[0]:0],e[this]=[d.exec(s[0])[0],d.exec(s[1])[0]]}),1===w.length&&(w[1]=w[0]),"right"===e.at[0]?v.left+=p:"center"===e.at[0]&&(v.left+=p/2),"bottom"===e.at[1]?v.top+=g:"center"===e.at[1]&&(v.top+=g/2),a=i(D.at,p,g),v.left+=a[0],v.top+=a[1],this.each(function(){var n,h,c=t(this),u=c.outerWidth(),d=c.outerHeight(),f=s(this,"marginLeft"),_=s(this,"marginTop"),x=u+f+s(this,"marginRight")+k.width,C=d+_+s(this,"marginBottom")+k.height,M=t.extend({},v),T=i(D.my,c.outerWidth(),c.outerHeight());"right"===e.my[0]?M.left-=u:"center"===e.my[0]&&(M.left-=u/2),"bottom"===e.my[1]?M.top-=d:"center"===e.my[1]&&(M.top-=d/2),M.left+=T[0],M.top+=T[1],t.support.offsetFractions||(M.left=l(M.left),M.top=l(M.top)),n={marginLeft:f,marginTop:_},t.each(["left","top"],function(i,s){t.ui.position[w[i]]&&t.ui.position[w[i]][s](M,{targetWidth:p,targetHeight:g,elemWidth:u,elemHeight:d,collisionPosition:n,collisionWidth:x,collisionHeight:C,offset:[a[0]+T[0],a[1]+T[1]],my:e.my,at:e.at,within:y,elem:c})}),e.using&&(h=function(t){var i=m.left-M.left,s=i+p-u,n=m.top-M.top,a=n+g-d,l={target:{element:b,left:m.left,top:m.top,width:p,height:g},element:{element:c,left:M.left,top:M.top,width:u,height:d},horizontal:0>s?"left":i>0?"right":"center",vertical:0>a?"top":n>0?"bottom":"middle"};u>p&&p>r(i+s)&&(l.horizontal="center"),d>g&&g>r(n+a)&&(l.vertical="middle"),l.important=o(r(i),r(s))>o(r(n),r(a))?"horizontal":"vertical",e.using.call(this,t,l)}),c.offset(t.extend(M,{using:h}))})},t.ui.position={fit:{left:function(t,e){var i,s=e.within,n=s.isWindow?s.scrollLeft:s.offset.left,a=s.width,r=t.left-e.collisionPosition.marginLeft,l=n-r,h=r+e.collisionWidth-a-n;e.collisionWidth>a?l>0&&0>=h?(i=t.left+l+e.collisionWidth-a-n,t.left+=l-i):t.left=h>0&&0>=l?n:l>h?n+a-e.collisionWidth:n:l>0?t.left+=l:h>0?t.left-=h:t.left=o(t.left-r,t.left)},top:function(t,e){var i,s=e.within,n=s.isWindow?s.scrollTop:s.offset.top,a=e.within.height,r=t.top-e.collisionPosition.marginTop,l=n-r,h=r+e.collisionHeight-a-n;e.collisionHeight>a?l>0&&0>=h?(i=t.top+l+e.collisionHeight-a-n,t.top+=l-i):t.top=h>0&&0>=l?n:l>h?n+a-e.collisionHeight:n:l>0?t.top+=l:h>0?t.top-=h:t.top=o(t.top-r,t.top)}},flip:{left:function(t,e){var i,s,n=e.within,a=n.offset.left+n.scrollLeft,o=n.width,l=n.isWindow?n.scrollLeft:n.offset.left,h=t.left-e.collisionPosition.marginLeft,c=h-l,u=h+e.collisionWidth-o-l,d="left"===e.my[0]?-e.elemWidth:"right"===e.my[0]?e.elemWidth:0,p="left"===e.at[0]?e.targetWidth:"right"===e.at[0]?-e.targetWidth:0,f=-2*e.offset[0];0>c?(i=t.left+d+p+f+e.collisionWidth-o-a,(0>i||r(c)>i)&&(t.left+=d+p+f)):u>0&&(s=t.left-e.collisionPosition.marginLeft+d+p+f-l,(s>0||u>r(s))&&(t.left+=d+p+f))},top:function(t,e){var i,s,n=e.within,a=n.offset.top+n.scrollTop,o=n.height,l=n.isWindow?n.scrollTop:n.offset.top,h=t.top-e.collisionPosition.marginTop,c=h-l,u=h+e.collisionHeight-o-l,d="top"===e.my[1],p=d?-e.elemHeight:"bottom"===e.my[1]?e.elemHeight:0,f="top"===e.at[1]?e.targetHeight:"bottom"===e.at[1]?-e.targetHeight:0,g=-2*e.offset[1];0>c?(s=t.top+p+f+g+e.collisionHeight-o-a,t.top+p+f+g>c&&(0>s||r(c)>s)&&(t.top+=p+f+g)):u>0&&(i=t.top-e.collisionPosition.marginTop+p+f+g-l,t.top+p+f+g>u&&(i>0||u>r(i))&&(t.top+=p+f+g))}},flipfit:{left:function(){t.ui.position.flip.left.apply(this,arguments),t.ui.position.fit.left.apply(this,arguments)},top:function(){t.ui.position.flip.top.apply(this,arguments),t.ui.position.fit.top.apply(this,arguments)}}},function(){var e,i,s,n,a,o=document.getElementsByTagName("body")[0],r=document.createElement("div");e=document.createElement(o?"div":"body"),s={visibility:"hidden",width:0,height:0,border:0,margin:0,background:"none"},o&&t.extend(s,{position:"absolute",left:"-1000px",top:"-1000px"});for(a in s)e.style[a]=s[a];e.appendChild(r),i=o||document.documentElement,i.insertBefore(e,i.firstChild),r.style.cssText="position: absolute; left: 10.7432222px;",n=t(r).offset().left,t.support.offsetFractions=n>10&&11>n,e.innerHTML="",i.removeChild(e)}()})(jQuery);(function(t){t.widget("ui.draggable",t.ui.mouse,{version:"1.10.4",widgetEventPrefix:"drag",options:{addClasses:!0,appendTo:"parent",axis:!1,connectToSortable:!1,containment:!1,cursor:"auto",cursorAt:!1,grid:!1,handle:!1,helper:"original",iframeFix:!1,opacity:!1,refreshPositions:!1,revert:!1,revertDuration:500,scope:"default",scroll:!0,scrollSensitivity:20,scrollSpeed:20,snap:!1,snapMode:"both",snapTolerance:20,stack:!1,zIndex:!1,drag:null,start:null,stop:null},_create:function(){"original"!==this.options.helper||/^(?:r|a|f)/.test(this.element.css("position"))||(this.element[0].style.position="relative"),this.options.addClasses&&this.element.addClass("ui-draggable"),this.options.disabled&&this.element.addClass("ui-draggable-disabled"),this._mouseInit()},_destroy:function(){this.element.removeClass("ui-draggable ui-draggable-dragging ui-draggable-disabled"),this._mouseDestroy()},_mouseCapture:function(e){var i=this.options;return this.helper||i.disabled||t(e.target).closest(".ui-resizable-handle").length>0?!1:(this.handle=this._getHandle(e),this.handle?(t(i.iframeFix===!0?"iframe":i.iframeFix).each(function(){t("<div class='ui-draggable-iframeFix' style='background: #fff;'></div>").css({width:this.offsetWidth+"px",height:this.offsetHeight+"px",position:"absolute",opacity:"0.001",zIndex:1e3}).css(t(this).offset()).appendTo("body")}),!0):!1)},_mouseStart:function(e){var i=this.options;return this.helper=this._createHelper(e),this.helper.addClass("ui-draggable-dragging"),this._cacheHelperProportions(),t.ui.ddmanager&&(t.ui.ddmanager.current=this),this._cacheMargins(),this.cssPosition=this.helper.css("position"),this.scrollParent=this.helper.scrollParent(),this.offsetParent=this.helper.offsetParent(),this.offsetParentCssPosition=this.offsetParent.css("position"),this.offset=this.positionAbs=this.element.offset(),this.offset={top:this.offset.top-this.margins.top,left:this.offset.left-this.margins.left},this.offset.scroll=!1,t.extend(this.offset,{click:{left:e.pageX-this.offset.left,top:e.pageY-this.offset.top},parent:this._getParentOffset(),relative:this._getRelativeOffset()}),this.originalPosition=this.position=this._generatePosition(e),this.originalPageX=e.pageX,this.originalPageY=e.pageY,i.cursorAt&&this._adjustOffsetFromHelper(i.cursorAt),this._setContainment(),this._trigger("start",e)===!1?(this._clear(),!1):(this._cacheHelperProportions(),t.ui.ddmanager&&!i.dropBehaviour&&t.ui.ddmanager.prepareOffsets(this,e),this._mouseDrag(e,!0),t.ui.ddmanager&&t.ui.ddmanager.dragStart(this,e),!0)},_mouseDrag:function(e,i){if("fixed"===this.offsetParentCssPosition&&(this.offset.parent=this._getParentOffset()),this.position=this._generatePosition(e),this.positionAbs=this._convertPositionTo("absolute"),!i){var s=this._uiHash();if(this._trigger("drag",e,s)===!1)return this._mouseUp({}),!1;this.position=s.position}return this.options.axis&&"y"===this.options.axis||(this.helper[0].style.left=this.position.left+"px"),this.options.axis&&"x"===this.options.axis||(this.helper[0].style.top=this.position.top+"px"),t.ui.ddmanager&&t.ui.ddmanager.drag(this,e),!1},_mouseStop:function(e){var i=this,s=!1;return t.ui.ddmanager&&!this.options.dropBehaviour&&(s=t.ui.ddmanager.drop(this,e)),this.dropped&&(s=this.dropped,this.dropped=!1),"original"!==this.options.helper||t.contains(this.element[0].ownerDocument,this.element[0])?("invalid"===this.options.revert&&!s||"valid"===this.options.revert&&s||this.options.revert===!0||t.isFunction(this.options.revert)&&this.options.revert.call(this.element,s)?t(this.helper).animate(this.originalPosition,parseInt(this.options.revertDuration,10),function(){i._trigger("stop",e)!==!1&&i._clear()}):this._trigger("stop",e)!==!1&&this._clear(),!1):!1},_mouseUp:function(e){return t("div.ui-draggable-iframeFix").each(function(){this.parentNode.removeChild(this)}),t.ui.ddmanager&&t.ui.ddmanager.dragStop(this,e),t.ui.mouse.prototype._mouseUp.call(this,e)},cancel:function(){return this.helper.is(".ui-draggable-dragging")?this._mouseUp({}):this._clear(),this},_getHandle:function(e){return this.options.handle?!!t(e.target).closest(this.element.find(this.options.handle)).length:!0},_createHelper:function(e){var i=this.options,s=t.isFunction(i.helper)?t(i.helper.apply(this.element[0],[e])):"clone"===i.helper?this.element.clone().removeAttr("id"):this.element;return s.parents("body").length||s.appendTo("parent"===i.appendTo?this.element[0].parentNode:i.appendTo),s[0]===this.element[0]||/(fixed|absolute)/.test(s.css("position"))||s.css("position","absolute"),s},_adjustOffsetFromHelper:function(e){"string"==typeof e&&(e=e.split(" ")),t.isArray(e)&&(e={left:+e[0],top:+e[1]||0}),"left"in e&&(this.offset.click.left=e.left+this.margins.left),"right"in e&&(this.offset.click.left=this.helperProportions.width-e.right+this.margins.left),"top"in e&&(this.offset.click.top=e.top+this.margins.top),"bottom"in e&&(this.offset.click.top=this.helperProportions.height-e.bottom+this.margins.top)},_getParentOffset:function(){var e=this.offsetParent.offset();return"absolute"===this.cssPosition&&this.scrollParent[0]!==document&&t.contains(this.scrollParent[0],this.offsetParent[0])&&(e.left+=this.scrollParent.scrollLeft(),e.top+=this.scrollParent.scrollTop()),(this.offsetParent[0]===document.body||this.offsetParent[0].tagName&&"html"===this.offsetParent[0].tagName.toLowerCase()&&t.ui.ie)&&(e={top:0,left:0}),{top:e.top+(parseInt(this.offsetParent.css("borderTopWidth"),10)||0),left:e.left+(parseInt(this.offsetParent.css("borderLeftWidth"),10)||0)}},_getRelativeOffset:function(){if("relative"===this.cssPosition){var t=this.element.position();return{top:t.top-(parseInt(this.helper.css("top"),10)||0)+this.scrollParent.scrollTop(),left:t.left-(parseInt(this.helper.css("left"),10)||0)+this.scrollParent.scrollLeft()}}return{top:0,left:0}},_cacheMargins:function(){this.margins={left:parseInt(this.element.css("marginLeft"),10)||0,top:parseInt(this.element.css("marginTop"),10)||0,right:parseInt(this.element.css("marginRight"),10)||0,bottom:parseInt(this.element.css("marginBottom"),10)||0}},_cacheHelperProportions:function(){this.helperProportions={width:this.helper.outerWidth(),height:this.helper.outerHeight()}},_setContainment:function(){var e,i,s,n=this.options;return n.containment?"window"===n.containment?(this.containment=[t(window).scrollLeft()-this.offset.relative.left-this.offset.parent.left,t(window).scrollTop()-this.offset.relative.top-this.offset.parent.top,t(window).scrollLeft()+t(window).width()-this.helperProportions.width-this.margins.left,t(window).scrollTop()+(t(window).height()||document.body.parentNode.scrollHeight)-this.helperProportions.height-this.margins.top],undefined):"document"===n.containment?(this.containment=[0,0,t(document).width()-this.helperProportions.width-this.margins.left,(t(document).height()||document.body.parentNode.scrollHeight)-this.helperProportions.height-this.margins.top],undefined):n.containment.constructor===Array?(this.containment=n.containment,undefined):("parent"===n.containment&&(n.containment=this.helper[0].parentNode),i=t(n.containment),s=i[0],s&&(e="hidden"!==i.css("overflow"),this.containment=[(parseInt(i.css("borderLeftWidth"),10)||0)+(parseInt(i.css("paddingLeft"),10)||0),(parseInt(i.css("borderTopWidth"),10)||0)+(parseInt(i.css("paddingTop"),10)||0),(e?Math.max(s.scrollWidth,s.offsetWidth):s.offsetWidth)-(parseInt(i.css("borderRightWidth"),10)||0)-(parseInt(i.css("paddingRight"),10)||0)-this.helperProportions.width-this.margins.left-this.margins.right,(e?Math.max(s.scrollHeight,s.offsetHeight):s.offsetHeight)-(parseInt(i.css("borderBottomWidth"),10)||0)-(parseInt(i.css("paddingBottom"),10)||0)-this.helperProportions.height-this.margins.top-this.margins.bottom],this.relative_container=i),undefined):(this.containment=null,undefined)},_convertPositionTo:function(e,i){i||(i=this.position);var s="absolute"===e?1:-1,n="absolute"!==this.cssPosition||this.scrollParent[0]!==document&&t.contains(this.scrollParent[0],this.offsetParent[0])?this.scrollParent:this.offsetParent;return this.offset.scroll||(this.offset.scroll={top:n.scrollTop(),left:n.scrollLeft()}),{top:i.top+this.offset.relative.top*s+this.offset.parent.top*s-("fixed"===this.cssPosition?-this.scrollParent.scrollTop():this.offset.scroll.top)*s,left:i.left+this.offset.relative.left*s+this.offset.parent.left*s-("fixed"===this.cssPosition?-this.scrollParent.scrollLeft():this.offset.scroll.left)*s}},_generatePosition:function(e){var i,s,n,a,o=this.options,r="absolute"!==this.cssPosition||this.scrollParent[0]!==document&&t.contains(this.scrollParent[0],this.offsetParent[0])?this.scrollParent:this.offsetParent,l=e.pageX,h=e.pageY;return this.offset.scroll||(this.offset.scroll={top:r.scrollTop(),left:r.scrollLeft()}),this.originalPosition&&(this.containment&&(this.relative_container?(s=this.relative_container.offset(),i=[this.containment[0]+s.left,this.containment[1]+s.top,this.containment[2]+s.left,this.containment[3]+s.top]):i=this.containment,e.pageX-this.offset.click.left<i[0]&&(l=i[0]+this.offset.click.left),e.pageY-this.offset.click.top<i[1]&&(h=i[1]+this.offset.click.top),e.pageX-this.offset.click.left>i[2]&&(l=i[2]+this.offset.click.left),e.pageY-this.offset.click.top>i[3]&&(h=i[3]+this.offset.click.top)),o.grid&&(n=o.grid[1]?this.originalPageY+Math.round((h-this.originalPageY)/o.grid[1])*o.grid[1]:this.originalPageY,h=i?n-this.offset.click.top>=i[1]||n-this.offset.click.top>i[3]?n:n-this.offset.click.top>=i[1]?n-o.grid[1]:n+o.grid[1]:n,a=o.grid[0]?this.originalPageX+Math.round((l-this.originalPageX)/o.grid[0])*o.grid[0]:this.originalPageX,l=i?a-this.offset.click.left>=i[0]||a-this.offset.click.left>i[2]?a:a-this.offset.click.left>=i[0]?a-o.grid[0]:a+o.grid[0]:a)),{top:h-this.offset.click.top-this.offset.relative.top-this.offset.parent.top+("fixed"===this.cssPosition?-this.scrollParent.scrollTop():this.offset.scroll.top),left:l-this.offset.click.left-this.offset.relative.left-this.offset.parent.left+("fixed"===this.cssPosition?-this.scrollParent.scrollLeft():this.offset.scroll.left)}},_clear:function(){this.helper.removeClass("ui-draggable-dragging"),this.helper[0]===this.element[0]||this.cancelHelperRemoval||this.helper.remove(),this.helper=null,this.cancelHelperRemoval=!1},_trigger:function(e,i,s){return s=s||this._uiHash(),t.ui.plugin.call(this,e,[i,s]),"drag"===e&&(this.positionAbs=this._convertPositionTo("absolute")),t.Widget.prototype._trigger.call(this,e,i,s)},plugins:{},_uiHash:function(){return{helper:this.helper,position:this.position,originalPosition:this.originalPosition,offset:this.positionAbs}}}),t.ui.plugin.add("draggable","connectToSortable",{start:function(e,i){var s=t(this).data("ui-draggable"),n=s.options,a=t.extend({},i,{item:s.element});s.sortables=[],t(n.connectToSortable).each(function(){var i=t.data(this,"ui-sortable");i&&!i.options.disabled&&(s.sortables.push({instance:i,shouldRevert:i.options.revert}),i.refreshPositions(),i._trigger("activate",e,a))})},stop:function(e,i){var s=t(this).data("ui-draggable"),n=t.extend({},i,{item:s.element});t.each(s.sortables,function(){this.instance.isOver?(this.instance.isOver=0,s.cancelHelperRemoval=!0,this.instance.cancelHelperRemoval=!1,this.shouldRevert&&(this.instance.options.revert=this.shouldRevert),this.instance._mouseStop(e),this.instance.options.helper=this.instance.options._helper,"original"===s.options.helper&&this.instance.currentItem.css({top:"auto",left:"auto"})):(this.instance.cancelHelperRemoval=!1,this.instance._trigger("deactivate",e,n))})},drag:function(e,i){var s=t(this).data("ui-draggable"),n=this;t.each(s.sortables,function(){var a=!1,o=this;this.instance.positionAbs=s.positionAbs,this.instance.helperProportions=s.helperProportions,this.instance.offset.click=s.offset.click,this.instance._intersectsWith(this.instance.containerCache)&&(a=!0,t.each(s.sortables,function(){return this.instance.positionAbs=s.positionAbs,this.instance.helperProportions=s.helperProportions,this.instance.offset.click=s.offset.click,this!==o&&this.instance._intersectsWith(this.instance.containerCache)&&t.contains(o.instance.element[0],this.instance.element[0])&&(a=!1),a})),a?(this.instance.isOver||(this.instance.isOver=1,this.instance.currentItem=t(n).clone().removeAttr("id").appendTo(this.instance.element).data("ui-sortable-item",!0),this.instance.options._helper=this.instance.options.helper,this.instance.options.helper=function(){return i.helper[0]},e.target=this.instance.currentItem[0],this.instance._mouseCapture(e,!0),this.instance._mouseStart(e,!0,!0),this.instance.offset.click.top=s.offset.click.top,this.instance.offset.click.left=s.offset.click.left,this.instance.offset.parent.left-=s.offset.parent.left-this.instance.offset.parent.left,this.instance.offset.parent.top-=s.offset.parent.top-this.instance.offset.parent.top,s._trigger("toSortable",e),s.dropped=this.instance.element,s.currentItem=s.element,this.instance.fromOutside=s),this.instance.currentItem&&this.instance._mouseDrag(e)):this.instance.isOver&&(this.instance.isOver=0,this.instance.cancelHelperRemoval=!0,this.instance.options.revert=!1,this.instance._trigger("out",e,this.instance._uiHash(this.instance)),this.instance._mouseStop(e,!0),this.instance.options.helper=this.instance.options._helper,this.instance.currentItem.remove(),this.instance.placeholder&&this.instance.placeholder.remove(),s._trigger("fromSortable",e),s.dropped=!1)})}}),t.ui.plugin.add("draggable","cursor",{start:function(){var e=t("body"),i=t(this).data("ui-draggable").options;e.css("cursor")&&(i._cursor=e.css("cursor")),e.css("cursor",i.cursor)},stop:function(){var e=t(this).data("ui-draggable").options;e._cursor&&t("body").css("cursor",e._cursor)}}),t.ui.plugin.add("draggable","opacity",{start:function(e,i){var s=t(i.helper),n=t(this).data("ui-draggable").options;s.css("opacity")&&(n._opacity=s.css("opacity")),s.css("opacity",n.opacity)},stop:function(e,i){var s=t(this).data("ui-draggable").options;s._opacity&&t(i.helper).css("opacity",s._opacity)}}),t.ui.plugin.add("draggable","scroll",{start:function(){var e=t(this).data("ui-draggable");e.scrollParent[0]!==document&&"HTML"!==e.scrollParent[0].tagName&&(e.overflowOffset=e.scrollParent.offset())},drag:function(e){var i=t(this).data("ui-draggable"),s=i.options,n=!1;i.scrollParent[0]!==document&&"HTML"!==i.scrollParent[0].tagName?(s.axis&&"x"===s.axis||(i.overflowOffset.top+i.scrollParent[0].offsetHeight-e.pageY<s.scrollSensitivity?i.scrollParent[0].scrollTop=n=i.scrollParent[0].scrollTop+s.scrollSpeed:e.pageY-i.overflowOffset.top<s.scrollSensitivity&&(i.scrollParent[0].scrollTop=n=i.scrollParent[0].scrollTop-s.scrollSpeed)),s.axis&&"y"===s.axis||(i.overflowOffset.left+i.scrollParent[0].offsetWidth-e.pageX<s.scrollSensitivity?i.scrollParent[0].scrollLeft=n=i.scrollParent[0].scrollLeft+s.scrollSpeed:e.pageX-i.overflowOffset.left<s.scrollSensitivity&&(i.scrollParent[0].scrollLeft=n=i.scrollParent[0].scrollLeft-s.scrollSpeed))):(s.axis&&"x"===s.axis||(e.pageY-t(document).scrollTop()<s.scrollSensitivity?n=t(document).scrollTop(t(document).scrollTop()-s.scrollSpeed):t(window).height()-(e.pageY-t(document).scrollTop())<s.scrollSensitivity&&(n=t(document).scrollTop(t(document).scrollTop()+s.scrollSpeed))),s.axis&&"y"===s.axis||(e.pageX-t(document).scrollLeft()<s.scrollSensitivity?n=t(document).scrollLeft(t(document).scrollLeft()-s.scrollSpeed):t(window).width()-(e.pageX-t(document).scrollLeft())<s.scrollSensitivity&&(n=t(document).scrollLeft(t(document).scrollLeft()+s.scrollSpeed)))),n!==!1&&t.ui.ddmanager&&!s.dropBehaviour&&t.ui.ddmanager.prepareOffsets(i,e)}}),t.ui.plugin.add("draggable","snap",{start:function(){var e=t(this).data("ui-draggable"),i=e.options;e.snapElements=[],t(i.snap.constructor!==String?i.snap.items||":data(ui-draggable)":i.snap).each(function(){var i=t(this),s=i.offset();this!==e.element[0]&&e.snapElements.push({item:this,width:i.outerWidth(),height:i.outerHeight(),top:s.top,left:s.left})})},drag:function(e,i){var s,n,a,o,r,l,h,c,u,d,p=t(this).data("ui-draggable"),g=p.options,f=g.snapTolerance,m=i.offset.left,_=m+p.helperProportions.width,v=i.offset.top,b=v+p.helperProportions.height;for(u=p.snapElements.length-1;u>=0;u--)r=p.snapElements[u].left,l=r+p.snapElements[u].width,h=p.snapElements[u].top,c=h+p.snapElements[u].height,r-f>_||m>l+f||h-f>b||v>c+f||!t.contains(p.snapElements[u].item.ownerDocument,p.snapElements[u].item)?(p.snapElements[u].snapping&&p.options.snap.release&&p.options.snap.release.call(p.element,e,t.extend(p._uiHash(),{snapItem:p.snapElements[u].item})),p.snapElements[u].snapping=!1):("inner"!==g.snapMode&&(s=f>=Math.abs(h-b),n=f>=Math.abs(c-v),a=f>=Math.abs(r-_),o=f>=Math.abs(l-m),s&&(i.position.top=p._convertPositionTo("relative",{top:h-p.helperProportions.height,left:0}).top-p.margins.top),n&&(i.position.top=p._convertPositionTo("relative",{top:c,left:0}).top-p.margins.top),a&&(i.position.left=p._convertPositionTo("relative",{top:0,left:r-p.helperProportions.width}).left-p.margins.left),o&&(i.position.left=p._convertPositionTo("relative",{top:0,left:l}).left-p.margins.left)),d=s||n||a||o,"outer"!==g.snapMode&&(s=f>=Math.abs(h-v),n=f>=Math.abs(c-b),a=f>=Math.abs(r-m),o=f>=Math.abs(l-_),s&&(i.position.top=p._convertPositionTo("relative",{top:h,left:0}).top-p.margins.top),n&&(i.position.top=p._convertPositionTo("relative",{top:c-p.helperProportions.height,left:0}).top-p.margins.top),a&&(i.position.left=p._convertPositionTo("relative",{top:0,left:r}).left-p.margins.left),o&&(i.position.left=p._convertPositionTo("relative",{top:0,left:l-p.helperProportions.width}).left-p.margins.left)),!p.snapElements[u].snapping&&(s||n||a||o||d)&&p.options.snap.snap&&p.options.snap.snap.call(p.element,e,t.extend(p._uiHash(),{snapItem:p.snapElements[u].item})),p.snapElements[u].snapping=s||n||a||o||d)}}),t.ui.plugin.add("draggable","stack",{start:function(){var e,i=this.data("ui-draggable").options,s=t.makeArray(t(i.stack)).sort(function(e,i){return(parseInt(t(e).css("zIndex"),10)||0)-(parseInt(t(i).css("zIndex"),10)||0)});s.length&&(e=parseInt(t(s[0]).css("zIndex"),10)||0,t(s).each(function(i){t(this).css("zIndex",e+i)}),this.css("zIndex",e+s.length))}}),t.ui.plugin.add("draggable","zIndex",{start:function(e,i){var s=t(i.helper),n=t(this).data("ui-draggable").options;s.css("zIndex")&&(n._zIndex=s.css("zIndex")),s.css("zIndex",n.zIndex)},stop:function(e,i){var s=t(this).data("ui-draggable").options;s._zIndex&&t(i.helper).css("zIndex",s._zIndex)}})})(jQuery);(function(t){function e(t,e,i){return t>e&&e+i>t}t.widget("ui.droppable",{version:"1.10.4",widgetEventPrefix:"drop",options:{accept:"*",activeClass:!1,addClasses:!0,greedy:!1,hoverClass:!1,scope:"default",tolerance:"intersect",activate:null,deactivate:null,drop:null,out:null,over:null},_create:function(){var e,i=this.options,s=i.accept;this.isover=!1,this.isout=!0,this.accept=t.isFunction(s)?s:function(t){return t.is(s)},this.proportions=function(){return arguments.length?(e=arguments[0],undefined):e?e:e={width:this.element[0].offsetWidth,height:this.element[0].offsetHeight}},t.ui.ddmanager.droppables[i.scope]=t.ui.ddmanager.droppables[i.scope]||[],t.ui.ddmanager.droppables[i.scope].push(this),i.addClasses&&this.element.addClass("ui-droppable")},_destroy:function(){for(var e=0,i=t.ui.ddmanager.droppables[this.options.scope];i.length>e;e++)i[e]===this&&i.splice(e,1);this.element.removeClass("ui-droppable ui-droppable-disabled")},_setOption:function(e,i){"accept"===e&&(this.accept=t.isFunction(i)?i:function(t){return t.is(i)}),t.Widget.prototype._setOption.apply(this,arguments)},_activate:function(e){var i=t.ui.ddmanager.current;this.options.activeClass&&this.element.addClass(this.options.activeClass),i&&this._trigger("activate",e,this.ui(i))},_deactivate:function(e){var i=t.ui.ddmanager.current;this.options.activeClass&&this.element.removeClass(this.options.activeClass),i&&this._trigger("deactivate",e,this.ui(i))},_over:function(e){var i=t.ui.ddmanager.current;i&&(i.currentItem||i.element)[0]!==this.element[0]&&this.accept.call(this.element[0],i.currentItem||i.element)&&(this.options.hoverClass&&this.element.addClass(this.options.hoverClass),this._trigger("over",e,this.ui(i)))},_out:function(e){var i=t.ui.ddmanager.current;i&&(i.currentItem||i.element)[0]!==this.element[0]&&this.accept.call(this.element[0],i.currentItem||i.element)&&(this.options.hoverClass&&this.element.removeClass(this.options.hoverClass),this._trigger("out",e,this.ui(i)))},_drop:function(e,i){var s=i||t.ui.ddmanager.current,n=!1;return s&&(s.currentItem||s.element)[0]!==this.element[0]?(this.element.find(":data(ui-droppable)").not(".ui-draggable-dragging").each(function(){var e=t.data(this,"ui-droppable");return e.options.greedy&&!e.options.disabled&&e.options.scope===s.options.scope&&e.accept.call(e.element[0],s.currentItem||s.element)&&t.ui.intersect(s,t.extend(e,{offset:e.element.offset()}),e.options.tolerance)?(n=!0,!1):undefined}),n?!1:this.accept.call(this.element[0],s.currentItem||s.element)?(this.options.activeClass&&this.element.removeClass(this.options.activeClass),this.options.hoverClass&&this.element.removeClass(this.options.hoverClass),this._trigger("drop",e,this.ui(s)),this.element):!1):!1},ui:function(t){return{draggable:t.currentItem||t.element,helper:t.helper,position:t.position,offset:t.positionAbs}}}),t.ui.intersect=function(t,i,s){if(!i.offset)return!1;var n,a,o=(t.positionAbs||t.position.absolute).left,r=(t.positionAbs||t.position.absolute).top,l=o+t.helperProportions.width,h=r+t.helperProportions.height,c=i.offset.left,u=i.offset.top,d=c+i.proportions().width,p=u+i.proportions().height;switch(s){case"fit":return o>=c&&d>=l&&r>=u&&p>=h;case"intersect":return o+t.helperProportions.width/2>c&&d>l-t.helperProportions.width/2&&r+t.helperProportions.height/2>u&&p>h-t.helperProportions.height/2;case"pointer":return n=(t.positionAbs||t.position.absolute).left+(t.clickOffset||t.offset.click).left,a=(t.positionAbs||t.position.absolute).top+(t.clickOffset||t.offset.click).top,e(a,u,i.proportions().height)&&e(n,c,i.proportions().width);case"touch":return(r>=u&&p>=r||h>=u&&p>=h||u>r&&h>p)&&(o>=c&&d>=o||l>=c&&d>=l||c>o&&l>d);default:return!1}},t.ui.ddmanager={current:null,droppables:{"default":[]},prepareOffsets:function(e,i){var s,n,a=t.ui.ddmanager.droppables[e.options.scope]||[],o=i?i.type:null,r=(e.currentItem||e.element).find(":data(ui-droppable)").addBack();t:for(s=0;a.length>s;s++)if(!(a[s].options.disabled||e&&!a[s].accept.call(a[s].element[0],e.currentItem||e.element))){for(n=0;r.length>n;n++)if(r[n]===a[s].element[0]){a[s].proportions().height=0;continue t}a[s].visible="none"!==a[s].element.css("display"),a[s].visible&&("mousedown"===o&&a[s]._activate.call(a[s],i),a[s].offset=a[s].element.offset(),a[s].proportions({width:a[s].element[0].offsetWidth,height:a[s].element[0].offsetHeight}))}},drop:function(e,i){var s=!1;return t.each((t.ui.ddmanager.droppables[e.options.scope]||[]).slice(),function(){this.options&&(!this.options.disabled&&this.visible&&t.ui.intersect(e,this,this.options.tolerance)&&(s=this._drop.call(this,i)||s),!this.options.disabled&&this.visible&&this.accept.call(this.element[0],e.currentItem||e.element)&&(this.isout=!0,this.isover=!1,this._deactivate.call(this,i)))}),s},dragStart:function(e,i){e.element.parentsUntil("body").bind("scroll.droppable",function(){e.options.refreshPositions||t.ui.ddmanager.prepareOffsets(e,i)})},drag:function(e,i){e.options.refreshPositions&&t.ui.ddmanager.prepareOffsets(e,i),t.each(t.ui.ddmanager.droppables[e.options.scope]||[],function(){if(!this.options.disabled&&!this.greedyChild&&this.visible){var s,n,a,o=t.ui.intersect(e,this,this.options.tolerance),r=!o&&this.isover?"isout":o&&!this.isover?"isover":null;r&&(this.options.greedy&&(n=this.options.scope,a=this.element.parents(":data(ui-droppable)").filter(function(){return t.data(this,"ui-droppable").options.scope===n}),a.length&&(s=t.data(a[0],"ui-droppable"),s.greedyChild="isover"===r)),s&&"isover"===r&&(s.isover=!1,s.isout=!0,s._out.call(s,i)),this[r]=!0,this["isout"===r?"isover":"isout"]=!1,this["isover"===r?"_over":"_out"].call(this,i),s&&"isout"===r&&(s.isout=!1,s.isover=!0,s._over.call(s,i)))}})},dragStop:function(e,i){e.element.parentsUntil("body").unbind("scroll.droppable"),e.options.refreshPositions||t.ui.ddmanager.prepareOffsets(e,i)}}})(jQuery);(function(t){function e(t){return parseInt(t,10)||0}function i(t){return!isNaN(parseInt(t,10))}t.widget("ui.resizable",t.ui.mouse,{version:"1.10.4",widgetEventPrefix:"resize",options:{alsoResize:!1,animate:!1,animateDuration:"slow",animateEasing:"swing",aspectRatio:!1,autoHide:!1,containment:!1,ghost:!1,grid:!1,handles:"e,s,se",helper:!1,maxHeight:null,maxWidth:null,minHeight:10,minWidth:10,zIndex:90,resize:null,start:null,stop:null},_create:function(){var e,i,s,n,a,o=this,r=this.options;if(this.element.addClass("ui-resizable"),t.extend(this,{_aspectRatio:!!r.aspectRatio,aspectRatio:r.aspectRatio,originalElement:this.element,_proportionallyResizeElements:[],_helper:r.helper||r.ghost||r.animate?r.helper||"ui-resizable-helper":null}),this.element[0].nodeName.match(/canvas|textarea|input|select|button|img/i)&&(this.element.wrap(t("<div class='ui-wrapper' style='overflow: hidden;'></div>").css({position:this.element.css("position"),width:this.element.outerWidth(),height:this.element.outerHeight(),top:this.element.css("top"),left:this.element.css("left")})),this.element=this.element.parent().data("ui-resizable",this.element.data("ui-resizable")),this.elementIsWrapper=!0,this.element.css({marginLeft:this.originalElement.css("marginLeft"),marginTop:this.originalElement.css("marginTop"),marginRight:this.originalElement.css("marginRight"),marginBottom:this.originalElement.css("marginBottom")}),this.originalElement.css({marginLeft:0,marginTop:0,marginRight:0,marginBottom:0}),this.originalResizeStyle=this.originalElement.css("resize"),this.originalElement.css("resize","none"),this._proportionallyResizeElements.push(this.originalElement.css({position:"static",zoom:1,display:"block"})),this.originalElement.css({margin:this.originalElement.css("margin")}),this._proportionallyResize()),this.handles=r.handles||(t(".ui-resizable-handle",this.element).length?{n:".ui-resizable-n",e:".ui-resizable-e",s:".ui-resizable-s",w:".ui-resizable-w",se:".ui-resizable-se",sw:".ui-resizable-sw",ne:".ui-resizable-ne",nw:".ui-resizable-nw"}:"e,s,se"),this.handles.constructor===String)for("all"===this.handles&&(this.handles="n,e,s,w,se,sw,ne,nw"),e=this.handles.split(","),this.handles={},i=0;e.length>i;i++)s=t.trim(e[i]),a="ui-resizable-"+s,n=t("<div class='ui-resizable-handle "+a+"'></div>"),n.css({zIndex:r.zIndex}),"se"===s&&n.addClass("ui-icon ui-icon-gripsmall-diagonal-se"),this.handles[s]=".ui-resizable-"+s,this.element.append(n);this._renderAxis=function(e){var i,s,n,a;e=e||this.element;for(i in this.handles)this.handles[i].constructor===String&&(this.handles[i]=t(this.handles[i],this.element).show()),this.elementIsWrapper&&this.originalElement[0].nodeName.match(/textarea|input|select|button/i)&&(s=t(this.handles[i],this.element),a=/sw|ne|nw|se|n|s/.test(i)?s.outerHeight():s.outerWidth(),n=["padding",/ne|nw|n/.test(i)?"Top":/se|sw|s/.test(i)?"Bottom":/^e$/.test(i)?"Right":"Left"].join(""),e.css(n,a),this._proportionallyResize()),t(this.handles[i]).length},this._renderAxis(this.element),this._handles=t(".ui-resizable-handle",this.element).disableSelection(),this._handles.mouseover(function(){o.resizing||(this.className&&(n=this.className.match(/ui-resizable-(se|sw|ne|nw|n|e|s|w)/i)),o.axis=n&&n[1]?n[1]:"se")}),r.autoHide&&(this._handles.hide(),t(this.element).addClass("ui-resizable-autohide").mouseenter(function(){r.disabled||(t(this).removeClass("ui-resizable-autohide"),o._handles.show())}).mouseleave(function(){r.disabled||o.resizing||(t(this).addClass("ui-resizable-autohide"),o._handles.hide())})),this._mouseInit()},_destroy:function(){this._mouseDestroy();var e,i=function(e){t(e).removeClass("ui-resizable ui-resizable-disabled ui-resizable-resizing").removeData("resizable").removeData("ui-resizable").unbind(".resizable").find(".ui-resizable-handle").remove()};return this.elementIsWrapper&&(i(this.element),e=this.element,this.originalElement.css({position:e.css("position"),width:e.outerWidth(),height:e.outerHeight(),top:e.css("top"),left:e.css("left")}).insertAfter(e),e.remove()),this.originalElement.css("resize",this.originalResizeStyle),i(this.originalElement),this},_mouseCapture:function(e){var i,s,n=!1;for(i in this.handles)s=t(this.handles[i])[0],(s===e.target||t.contains(s,e.target))&&(n=!0);return!this.options.disabled&&n},_mouseStart:function(i){var s,n,a,o=this.options,r=this.element.position(),h=this.element;return this.resizing=!0,/absolute/.test(h.css("position"))?h.css({position:"absolute",top:h.css("top"),left:h.css("left")}):h.is(".ui-draggable")&&h.css({position:"absolute",top:r.top,left:r.left}),this._renderProxy(),s=e(this.helper.css("left")),n=e(this.helper.css("top")),o.containment&&(s+=t(o.containment).scrollLeft()||0,n+=t(o.containment).scrollTop()||0),this.offset=this.helper.offset(),this.position={left:s,top:n},this.size=this._helper?{width:this.helper.width(),height:this.helper.height()}:{width:h.width(),height:h.height()},this.originalSize=this._helper?{width:h.outerWidth(),height:h.outerHeight()}:{width:h.width(),height:h.height()},this.originalPosition={left:s,top:n},this.sizeDiff={width:h.outerWidth()-h.width(),height:h.outerHeight()-h.height()},this.originalMousePosition={left:i.pageX,top:i.pageY},this.aspectRatio="number"==typeof o.aspectRatio?o.aspectRatio:this.originalSize.width/this.originalSize.height||1,a=t(".ui-resizable-"+this.axis).css("cursor"),t("body").css("cursor","auto"===a?this.axis+"-resize":a),h.addClass("ui-resizable-resizing"),this._propagate("start",i),!0},_mouseDrag:function(e){var i,s=this.helper,n={},a=this.originalMousePosition,o=this.axis,r=this.position.top,h=this.position.left,l=this.size.width,c=this.size.height,u=e.pageX-a.left||0,d=e.pageY-a.top||0,p=this._change[o];return p?(i=p.apply(this,[e,u,d]),this._updateVirtualBoundaries(e.shiftKey),(this._aspectRatio||e.shiftKey)&&(i=this._updateRatio(i,e)),i=this._respectSize(i,e),this._updateCache(i),this._propagate("resize",e),this.position.top!==r&&(n.top=this.position.top+"px"),this.position.left!==h&&(n.left=this.position.left+"px"),this.size.width!==l&&(n.width=this.size.width+"px"),this.size.height!==c&&(n.height=this.size.height+"px"),s.css(n),!this._helper&&this._proportionallyResizeElements.length&&this._proportionallyResize(),t.isEmptyObject(n)||this._trigger("resize",e,this.ui()),!1):!1},_mouseStop:function(e){this.resizing=!1;var i,s,n,a,o,r,h,l=this.options,c=this;return this._helper&&(i=this._proportionallyResizeElements,s=i.length&&/textarea/i.test(i[0].nodeName),n=s&&t.ui.hasScroll(i[0],"left")?0:c.sizeDiff.height,a=s?0:c.sizeDiff.width,o={width:c.helper.width()-a,height:c.helper.height()-n},r=parseInt(c.element.css("left"),10)+(c.position.left-c.originalPosition.left)||null,h=parseInt(c.element.css("top"),10)+(c.position.top-c.originalPosition.top)||null,l.animate||this.element.css(t.extend(o,{top:h,left:r})),c.helper.height(c.size.height),c.helper.width(c.size.width),this._helper&&!l.animate&&this._proportionallyResize()),t("body").css("cursor","auto"),this.element.removeClass("ui-resizable-resizing"),this._propagate("stop",e),this._helper&&this.helper.remove(),!1},_updateVirtualBoundaries:function(t){var e,s,n,a,o,r=this.options;o={minWidth:i(r.minWidth)?r.minWidth:0,maxWidth:i(r.maxWidth)?r.maxWidth:1/0,minHeight:i(r.minHeight)?r.minHeight:0,maxHeight:i(r.maxHeight)?r.maxHeight:1/0},(this._aspectRatio||t)&&(e=o.minHeight*this.aspectRatio,n=o.minWidth/this.aspectRatio,s=o.maxHeight*this.aspectRatio,a=o.maxWidth/this.aspectRatio,e>o.minWidth&&(o.minWidth=e),n>o.minHeight&&(o.minHeight=n),o.maxWidth>s&&(o.maxWidth=s),o.maxHeight>a&&(o.maxHeight=a)),this._vBoundaries=o},_updateCache:function(t){this.offset=this.helper.offset(),i(t.left)&&(this.position.left=t.left),i(t.top)&&(this.position.top=t.top),i(t.height)&&(this.size.height=t.height),i(t.width)&&(this.size.width=t.width)},_updateRatio:function(t){var e=this.position,s=this.size,n=this.axis;return i(t.height)?t.width=t.height*this.aspectRatio:i(t.width)&&(t.height=t.width/this.aspectRatio),"sw"===n&&(t.left=e.left+(s.width-t.width),t.top=null),"nw"===n&&(t.top=e.top+(s.height-t.height),t.left=e.left+(s.width-t.width)),t},_respectSize:function(t){var e=this._vBoundaries,s=this.axis,n=i(t.width)&&e.maxWidth&&e.maxWidth<t.width,a=i(t.height)&&e.maxHeight&&e.maxHeight<t.height,o=i(t.width)&&e.minWidth&&e.minWidth>t.width,r=i(t.height)&&e.minHeight&&e.minHeight>t.height,h=this.originalPosition.left+this.originalSize.width,l=this.position.top+this.size.height,c=/sw|nw|w/.test(s),u=/nw|ne|n/.test(s);return o&&(t.width=e.minWidth),r&&(t.height=e.minHeight),n&&(t.width=e.maxWidth),a&&(t.height=e.maxHeight),o&&c&&(t.left=h-e.minWidth),n&&c&&(t.left=h-e.maxWidth),r&&u&&(t.top=l-e.minHeight),a&&u&&(t.top=l-e.maxHeight),t.width||t.height||t.left||!t.top?t.width||t.height||t.top||!t.left||(t.left=null):t.top=null,t},_proportionallyResize:function(){if(this._proportionallyResizeElements.length){var t,e,i,s,n,a=this.helper||this.element;for(t=0;this._proportionallyResizeElements.length>t;t++){if(n=this._proportionallyResizeElements[t],!this.borderDif)for(this.borderDif=[],i=[n.css("borderTopWidth"),n.css("borderRightWidth"),n.css("borderBottomWidth"),n.css("borderLeftWidth")],s=[n.css("paddingTop"),n.css("paddingRight"),n.css("paddingBottom"),n.css("paddingLeft")],e=0;i.length>e;e++)this.borderDif[e]=(parseInt(i[e],10)||0)+(parseInt(s[e],10)||0);n.css({height:a.height()-this.borderDif[0]-this.borderDif[2]||0,width:a.width()-this.borderDif[1]-this.borderDif[3]||0})}}},_renderProxy:function(){var e=this.element,i=this.options;this.elementOffset=e.offset(),this._helper?(this.helper=this.helper||t("<div style='overflow:hidden;'></div>"),this.helper.addClass(this._helper).css({width:this.element.outerWidth()-1,height:this.element.outerHeight()-1,position:"absolute",left:this.elementOffset.left+"px",top:this.elementOffset.top+"px",zIndex:++i.zIndex}),this.helper.appendTo("body").disableSelection()):this.helper=this.element},_change:{e:function(t,e){return{width:this.originalSize.width+e}},w:function(t,e){var i=this.originalSize,s=this.originalPosition;return{left:s.left+e,width:i.width-e}},n:function(t,e,i){var s=this.originalSize,n=this.originalPosition;return{top:n.top+i,height:s.height-i}},s:function(t,e,i){return{height:this.originalSize.height+i}},se:function(e,i,s){return t.extend(this._change.s.apply(this,arguments),this._change.e.apply(this,[e,i,s]))},sw:function(e,i,s){return t.extend(this._change.s.apply(this,arguments),this._change.w.apply(this,[e,i,s]))},ne:function(e,i,s){return t.extend(this._change.n.apply(this,arguments),this._change.e.apply(this,[e,i,s]))},nw:function(e,i,s){return t.extend(this._change.n.apply(this,arguments),this._change.w.apply(this,[e,i,s]))}},_propagate:function(e,i){t.ui.plugin.call(this,e,[i,this.ui()]),"resize"!==e&&this._trigger(e,i,this.ui())},plugins:{},ui:function(){return{originalElement:this.originalElement,element:this.element,helper:this.helper,position:this.position,size:this.size,originalSize:this.originalSize,originalPosition:this.originalPosition}}}),t.ui.plugin.add("resizable","animate",{stop:function(e){var i=t(this).data("ui-resizable"),s=i.options,n=i._proportionallyResizeElements,a=n.length&&/textarea/i.test(n[0].nodeName),o=a&&t.ui.hasScroll(n[0],"left")?0:i.sizeDiff.height,r=a?0:i.sizeDiff.width,h={width:i.size.width-r,height:i.size.height-o},l=parseInt(i.element.css("left"),10)+(i.position.left-i.originalPosition.left)||null,c=parseInt(i.element.css("top"),10)+(i.position.top-i.originalPosition.top)||null;i.element.animate(t.extend(h,c&&l?{top:c,left:l}:{}),{duration:s.animateDuration,easing:s.animateEasing,step:function(){var s={width:parseInt(i.element.css("width"),10),height:parseInt(i.element.css("height"),10),top:parseInt(i.element.css("top"),10),left:parseInt(i.element.css("left"),10)};n&&n.length&&t(n[0]).css({width:s.width,height:s.height}),i._updateCache(s),i._propagate("resize",e)}})}}),t.ui.plugin.add("resizable","containment",{start:function(){var i,s,n,a,o,r,h,l=t(this).data("ui-resizable"),c=l.options,u=l.element,d=c.containment,p=d instanceof t?d.get(0):/parent/.test(d)?u.parent().get(0):d;p&&(l.containerElement=t(p),/document/.test(d)||d===document?(l.containerOffset={left:0,top:0},l.containerPosition={left:0,top:0},l.parentData={element:t(document),left:0,top:0,width:t(document).width(),height:t(document).height()||document.body.parentNode.scrollHeight}):(i=t(p),s=[],t(["Top","Right","Left","Bottom"]).each(function(t,n){s[t]=e(i.css("padding"+n))}),l.containerOffset=i.offset(),l.containerPosition=i.position(),l.containerSize={height:i.innerHeight()-s[3],width:i.innerWidth()-s[1]},n=l.containerOffset,a=l.containerSize.height,o=l.containerSize.width,r=t.ui.hasScroll(p,"left")?p.scrollWidth:o,h=t.ui.hasScroll(p)?p.scrollHeight:a,l.parentData={element:p,left:n.left,top:n.top,width:r,height:h}))},resize:function(e){var i,s,n,a,o=t(this).data("ui-resizable"),r=o.options,h=o.containerOffset,l=o.position,c=o._aspectRatio||e.shiftKey,u={top:0,left:0},d=o.containerElement;d[0]!==document&&/static/.test(d.css("position"))&&(u=h),l.left<(o._helper?h.left:0)&&(o.size.width=o.size.width+(o._helper?o.position.left-h.left:o.position.left-u.left),c&&(o.size.height=o.size.width/o.aspectRatio),o.position.left=r.helper?h.left:0),l.top<(o._helper?h.top:0)&&(o.size.height=o.size.height+(o._helper?o.position.top-h.top:o.position.top),c&&(o.size.width=o.size.height*o.aspectRatio),o.position.top=o._helper?h.top:0),o.offset.left=o.parentData.left+o.position.left,o.offset.top=o.parentData.top+o.position.top,i=Math.abs((o._helper?o.offset.left-u.left:o.offset.left-u.left)+o.sizeDiff.width),s=Math.abs((o._helper?o.offset.top-u.top:o.offset.top-h.top)+o.sizeDiff.height),n=o.containerElement.get(0)===o.element.parent().get(0),a=/relative|absolute/.test(o.containerElement.css("position")),n&&a&&(i-=Math.abs(o.parentData.left)),i+o.size.width>=o.parentData.width&&(o.size.width=o.parentData.width-i,c&&(o.size.height=o.size.width/o.aspectRatio)),s+o.size.height>=o.parentData.height&&(o.size.height=o.parentData.height-s,c&&(o.size.width=o.size.height*o.aspectRatio))},stop:function(){var e=t(this).data("ui-resizable"),i=e.options,s=e.containerOffset,n=e.containerPosition,a=e.containerElement,o=t(e.helper),r=o.offset(),h=o.outerWidth()-e.sizeDiff.width,l=o.outerHeight()-e.sizeDiff.height;e._helper&&!i.animate&&/relative/.test(a.css("position"))&&t(this).css({left:r.left-n.left-s.left,width:h,height:l}),e._helper&&!i.animate&&/static/.test(a.css("position"))&&t(this).css({left:r.left-n.left-s.left,width:h,height:l})}}),t.ui.plugin.add("resizable","alsoResize",{start:function(){var e=t(this).data("ui-resizable"),i=e.options,s=function(e){t(e).each(function(){var e=t(this);e.data("ui-resizable-alsoresize",{width:parseInt(e.width(),10),height:parseInt(e.height(),10),left:parseInt(e.css("left"),10),top:parseInt(e.css("top"),10)})})};"object"!=typeof i.alsoResize||i.alsoResize.parentNode?s(i.alsoResize):i.alsoResize.length?(i.alsoResize=i.alsoResize[0],s(i.alsoResize)):t.each(i.alsoResize,function(t){s(t)})},resize:function(e,i){var s=t(this).data("ui-resizable"),n=s.options,a=s.originalSize,o=s.originalPosition,r={height:s.size.height-a.height||0,width:s.size.width-a.width||0,top:s.position.top-o.top||0,left:s.position.left-o.left||0},h=function(e,s){t(e).each(function(){var e=t(this),n=t(this).data("ui-resizable-alsoresize"),a={},o=s&&s.length?s:e.parents(i.originalElement[0]).length?["width","height"]:["width","height","top","left"];t.each(o,function(t,e){var i=(n[e]||0)+(r[e]||0);i&&i>=0&&(a[e]=i||null)}),e.css(a)})};"object"!=typeof n.alsoResize||n.alsoResize.nodeType?h(n.alsoResize):t.each(n.alsoResize,function(t,e){h(t,e)})},stop:function(){t(this).removeData("resizable-alsoresize")}}),t.ui.plugin.add("resizable","ghost",{start:function(){var e=t(this).data("ui-resizable"),i=e.options,s=e.size;e.ghost=e.originalElement.clone(),e.ghost.css({opacity:.25,display:"block",position:"relative",height:s.height,width:s.width,margin:0,left:0,top:0}).addClass("ui-resizable-ghost").addClass("string"==typeof i.ghost?i.ghost:""),e.ghost.appendTo(e.helper)},resize:function(){var e=t(this).data("ui-resizable");e.ghost&&e.ghost.css({position:"relative",height:e.size.height,width:e.size.width})},stop:function(){var e=t(this).data("ui-resizable");e.ghost&&e.helper&&e.helper.get(0).removeChild(e.ghost.get(0))}}),t.ui.plugin.add("resizable","grid",{resize:function(){var e=t(this).data("ui-resizable"),i=e.options,s=e.size,n=e.originalSize,a=e.originalPosition,o=e.axis,r="number"==typeof i.grid?[i.grid,i.grid]:i.grid,h=r[0]||1,l=r[1]||1,c=Math.round((s.width-n.width)/h)*h,u=Math.round((s.height-n.height)/l)*l,d=n.width+c,p=n.height+u,f=i.maxWidth&&d>i.maxWidth,g=i.maxHeight&&p>i.maxHeight,m=i.minWidth&&i.minWidth>d,v=i.minHeight&&i.minHeight>p;i.grid=r,m&&(d+=h),v&&(p+=l),f&&(d-=h),g&&(p-=l),/^(se|s|e)$/.test(o)?(e.size.width=d,e.size.height=p):/^(ne)$/.test(o)?(e.size.width=d,e.size.height=p,e.position.top=a.top-u):/^(sw)$/.test(o)?(e.size.width=d,e.size.height=p,e.position.left=a.left-c):(p-l>0?(e.size.height=p,e.position.top=a.top-u):(e.size.height=l,e.position.top=a.top+n.height-l),d-h>0?(e.size.width=d,e.position.left=a.left-c):(e.size.width=h,e.position.left=a.left+n.width-h))}})})(jQuery);(function(t){t.widget("ui.selectable",t.ui.mouse,{version:"1.10.4",options:{appendTo:"body",autoRefresh:!0,distance:0,filter:"*",tolerance:"touch",selected:null,selecting:null,start:null,stop:null,unselected:null,unselecting:null},_create:function(){var e,i=this;this.element.addClass("ui-selectable"),this.dragged=!1,this.refresh=function(){e=t(i.options.filter,i.element[0]),e.addClass("ui-selectee"),e.each(function(){var e=t(this),i=e.offset();t.data(this,"selectable-item",{element:this,$element:e,left:i.left,top:i.top,right:i.left+e.outerWidth(),bottom:i.top+e.outerHeight(),startselected:!1,selected:e.hasClass("ui-selected"),selecting:e.hasClass("ui-selecting"),unselecting:e.hasClass("ui-unselecting")})})},this.refresh(),this.selectees=e.addClass("ui-selectee"),this._mouseInit(),this.helper=t("<div class='ui-selectable-helper'></div>")},_destroy:function(){this.selectees.removeClass("ui-selectee").removeData("selectable-item"),this.element.removeClass("ui-selectable ui-selectable-disabled"),this._mouseDestroy()},_mouseStart:function(e){var i=this,s=this.options;this.opos=[e.pageX,e.pageY],this.options.disabled||(this.selectees=t(s.filter,this.element[0]),this._trigger("start",e),t(s.appendTo).append(this.helper),this.helper.css({left:e.pageX,top:e.pageY,width:0,height:0}),s.autoRefresh&&this.refresh(),this.selectees.filter(".ui-selected").each(function(){var s=t.data(this,"selectable-item");s.startselected=!0,e.metaKey||e.ctrlKey||(s.$element.removeClass("ui-selected"),s.selected=!1,s.$element.addClass("ui-unselecting"),s.unselecting=!0,i._trigger("unselecting",e,{unselecting:s.element}))}),t(e.target).parents().addBack().each(function(){var s,n=t.data(this,"selectable-item");return n?(s=!e.metaKey&&!e.ctrlKey||!n.$element.hasClass("ui-selected"),n.$element.removeClass(s?"ui-unselecting":"ui-selected").addClass(s?"ui-selecting":"ui-unselecting"),n.unselecting=!s,n.selecting=s,n.selected=s,s?i._trigger("selecting",e,{selecting:n.element}):i._trigger("unselecting",e,{unselecting:n.element}),!1):undefined}))},_mouseDrag:function(e){if(this.dragged=!0,!this.options.disabled){var i,s=this,n=this.options,a=this.opos[0],o=this.opos[1],r=e.pageX,l=e.pageY;return a>r&&(i=r,r=a,a=i),o>l&&(i=l,l=o,o=i),this.helper.css({left:a,top:o,width:r-a,height:l-o}),this.selectees.each(function(){var i=t.data(this,"selectable-item"),h=!1;i&&i.element!==s.element[0]&&("touch"===n.tolerance?h=!(i.left>r||a>i.right||i.top>l||o>i.bottom):"fit"===n.tolerance&&(h=i.left>a&&r>i.right&&i.top>o&&l>i.bottom),h?(i.selected&&(i.$element.removeClass("ui-selected"),i.selected=!1),i.unselecting&&(i.$element.removeClass("ui-unselecting"),i.unselecting=!1),i.selecting||(i.$element.addClass("ui-selecting"),i.selecting=!0,s._trigger("selecting",e,{selecting:i.element}))):(i.selecting&&((e.metaKey||e.ctrlKey)&&i.startselected?(i.$element.removeClass("ui-selecting"),i.selecting=!1,i.$element.addClass("ui-selected"),i.selected=!0):(i.$element.removeClass("ui-selecting"),i.selecting=!1,i.startselected&&(i.$element.addClass("ui-unselecting"),i.unselecting=!0),s._trigger("unselecting",e,{unselecting:i.element}))),i.selected&&(e.metaKey||e.ctrlKey||i.startselected||(i.$element.removeClass("ui-selected"),i.selected=!1,i.$element.addClass("ui-unselecting"),i.unselecting=!0,s._trigger("unselecting",e,{unselecting:i.element})))))}),!1}},_mouseStop:function(e){var i=this;return this.dragged=!1,t(".ui-unselecting",this.element[0]).each(function(){var s=t.data(this,"selectable-item");s.$element.removeClass("ui-unselecting"),s.unselecting=!1,s.startselected=!1,i._trigger("unselected",e,{unselected:s.element})}),t(".ui-selecting",this.element[0]).each(function(){var s=t.data(this,"selectable-item");s.$element.removeClass("ui-selecting").addClass("ui-selected"),s.selecting=!1,s.selected=!0,s.startselected=!0,i._trigger("selected",e,{selected:s.element})}),this._trigger("stop",e),this.helper.remove(),!1}})})(jQuery);(function(t){function e(t,e,i){return t>e&&e+i>t}function i(t){return/left|right/.test(t.css("float"))||/inline|table-cell/.test(t.css("display"))}t.widget("ui.sortable",t.ui.mouse,{version:"1.10.4",widgetEventPrefix:"sort",ready:!1,options:{appendTo:"parent",axis:!1,connectWith:!1,containment:!1,cursor:"auto",cursorAt:!1,dropOnEmpty:!0,forcePlaceholderSize:!1,forceHelperSize:!1,grid:!1,handle:!1,helper:"original",items:"> *",opacity:!1,placeholder:!1,revert:!1,scroll:!0,scrollSensitivity:20,scrollSpeed:20,scope:"default",tolerance:"intersect",zIndex:1e3,activate:null,beforeStop:null,change:null,deactivate:null,out:null,over:null,receive:null,remove:null,sort:null,start:null,stop:null,update:null},_create:function(){var t=this.options;this.containerCache={},this.element.addClass("ui-sortable"),this.refresh(),this.floating=this.items.length?"x"===t.axis||i(this.items[0].item):!1,this.offset=this.element.offset(),this._mouseInit(),this.ready=!0},_destroy:function(){this.element.removeClass("ui-sortable ui-sortable-disabled"),this._mouseDestroy();for(var t=this.items.length-1;t>=0;t--)this.items[t].item.removeData(this.widgetName+"-item");return this},_setOption:function(e,i){"disabled"===e?(this.options[e]=i,this.widget().toggleClass("ui-sortable-disabled",!!i)):t.Widget.prototype._setOption.apply(this,arguments)},_mouseCapture:function(e,i){var s=null,n=!1,o=this;return this.reverting?!1:this.options.disabled||"static"===this.options.type?!1:(this._refreshItems(e),t(e.target).parents().each(function(){return t.data(this,o.widgetName+"-item")===o?(s=t(this),!1):undefined}),t.data(e.target,o.widgetName+"-item")===o&&(s=t(e.target)),s?!this.options.handle||i||(t(this.options.handle,s).find("*").addBack().each(function(){this===e.target&&(n=!0)}),n)?(this.currentItem=s,this._removeCurrentsFromItems(),!0):!1:!1)},_mouseStart:function(e,i,s){var n,o,a=this.options;if(this.currentContainer=this,this.refreshPositions(),this.helper=this._createHelper(e),this._cacheHelperProportions(),this._cacheMargins(),this.scrollParent=this.helper.scrollParent(),this.offset=this.currentItem.offset(),this.offset={top:this.offset.top-this.margins.top,left:this.offset.left-this.margins.left},t.extend(this.offset,{click:{left:e.pageX-this.offset.left,top:e.pageY-this.offset.top},parent:this._getParentOffset(),relative:this._getRelativeOffset()}),this.helper.css("position","absolute"),this.cssPosition=this.helper.css("position"),this.originalPosition=this._generatePosition(e),this.originalPageX=e.pageX,this.originalPageY=e.pageY,a.cursorAt&&this._adjustOffsetFromHelper(a.cursorAt),this.domPosition={prev:this.currentItem.prev()[0],parent:this.currentItem.parent()[0]},this.helper[0]!==this.currentItem[0]&&this.currentItem.hide(),this._createPlaceholder(),a.containment&&this._setContainment(),a.cursor&&"auto"!==a.cursor&&(o=this.document.find("body"),this.storedCursor=o.css("cursor"),o.css("cursor",a.cursor),this.storedStylesheet=t("<style>*{ cursor: "+a.cursor+" !important; }</style>").appendTo(o)),a.opacity&&(this.helper.css("opacity")&&(this._storedOpacity=this.helper.css("opacity")),this.helper.css("opacity",a.opacity)),a.zIndex&&(this.helper.css("zIndex")&&(this._storedZIndex=this.helper.css("zIndex")),this.helper.css("zIndex",a.zIndex)),this.scrollParent[0]!==document&&"HTML"!==this.scrollParent[0].tagName&&(this.overflowOffset=this.scrollParent.offset()),this._trigger("start",e,this._uiHash()),this._preserveHelperProportions||this._cacheHelperProportions(),!s)for(n=this.containers.length-1;n>=0;n--)this.containers[n]._trigger("activate",e,this._uiHash(this));return t.ui.ddmanager&&(t.ui.ddmanager.current=this),t.ui.ddmanager&&!a.dropBehaviour&&t.ui.ddmanager.prepareOffsets(this,e),this.dragging=!0,this.helper.addClass("ui-sortable-helper"),this._mouseDrag(e),!0},_mouseDrag:function(e){var i,s,n,o,a=this.options,r=!1;for(this.position=this._generatePosition(e),this.positionAbs=this._convertPositionTo("absolute"),this.lastPositionAbs||(this.lastPositionAbs=this.positionAbs),this.options.scroll&&(this.scrollParent[0]!==document&&"HTML"!==this.scrollParent[0].tagName?(this.overflowOffset.top+this.scrollParent[0].offsetHeight-e.pageY<a.scrollSensitivity?this.scrollParent[0].scrollTop=r=this.scrollParent[0].scrollTop+a.scrollSpeed:e.pageY-this.overflowOffset.top<a.scrollSensitivity&&(this.scrollParent[0].scrollTop=r=this.scrollParent[0].scrollTop-a.scrollSpeed),this.overflowOffset.left+this.scrollParent[0].offsetWidth-e.pageX<a.scrollSensitivity?this.scrollParent[0].scrollLeft=r=this.scrollParent[0].scrollLeft+a.scrollSpeed:e.pageX-this.overflowOffset.left<a.scrollSensitivity&&(this.scrollParent[0].scrollLeft=r=this.scrollParent[0].scrollLeft-a.scrollSpeed)):(e.pageY-t(document).scrollTop()<a.scrollSensitivity?r=t(document).scrollTop(t(document).scrollTop()-a.scrollSpeed):t(window).height()-(e.pageY-t(document).scrollTop())<a.scrollSensitivity&&(r=t(document).scrollTop(t(document).scrollTop()+a.scrollSpeed)),e.pageX-t(document).scrollLeft()<a.scrollSensitivity?r=t(document).scrollLeft(t(document).scrollLeft()-a.scrollSpeed):t(window).width()-(e.pageX-t(document).scrollLeft())<a.scrollSensitivity&&(r=t(document).scrollLeft(t(document).scrollLeft()+a.scrollSpeed))),r!==!1&&t.ui.ddmanager&&!a.dropBehaviour&&t.ui.ddmanager.prepareOffsets(this,e)),this.positionAbs=this._convertPositionTo("absolute"),this.options.axis&&"y"===this.options.axis||(this.helper[0].style.left=this.position.left+"px"),this.options.axis&&"x"===this.options.axis||(this.helper[0].style.top=this.position.top+"px"),i=this.items.length-1;i>=0;i--)if(s=this.items[i],n=s.item[0],o=this._intersectsWithPointer(s),o&&s.instance===this.currentContainer&&n!==this.currentItem[0]&&this.placeholder[1===o?"next":"prev"]()[0]!==n&&!t.contains(this.placeholder[0],n)&&("semi-dynamic"===this.options.type?!t.contains(this.element[0],n):!0)){if(this.direction=1===o?"down":"up","pointer"!==this.options.tolerance&&!this._intersectsWithSides(s))break;this._rearrange(e,s),this._trigger("change",e,this._uiHash());break}return this._contactContainers(e),t.ui.ddmanager&&t.ui.ddmanager.drag(this,e),this._trigger("sort",e,this._uiHash()),this.lastPositionAbs=this.positionAbs,!1},_mouseStop:function(e,i){if(e){if(t.ui.ddmanager&&!this.options.dropBehaviour&&t.ui.ddmanager.drop(this,e),this.options.revert){var s=this,n=this.placeholder.offset(),o=this.options.axis,a={};o&&"x"!==o||(a.left=n.left-this.offset.parent.left-this.margins.left+(this.offsetParent[0]===document.body?0:this.offsetParent[0].scrollLeft)),o&&"y"!==o||(a.top=n.top-this.offset.parent.top-this.margins.top+(this.offsetParent[0]===document.body?0:this.offsetParent[0].scrollTop)),this.reverting=!0,t(this.helper).animate(a,parseInt(this.options.revert,10)||500,function(){s._clear(e)})}else this._clear(e,i);return!1}},cancel:function(){if(this.dragging){this._mouseUp({target:null}),"original"===this.options.helper?this.currentItem.css(this._storedCSS).removeClass("ui-sortable-helper"):this.currentItem.show();for(var e=this.containers.length-1;e>=0;e--)this.containers[e]._trigger("deactivate",null,this._uiHash(this)),this.containers[e].containerCache.over&&(this.containers[e]._trigger("out",null,this._uiHash(this)),this.containers[e].containerCache.over=0)}return this.placeholder&&(this.placeholder[0].parentNode&&this.placeholder[0].parentNode.removeChild(this.placeholder[0]),"original"!==this.options.helper&&this.helper&&this.helper[0].parentNode&&this.helper.remove(),t.extend(this,{helper:null,dragging:!1,reverting:!1,_noFinalSort:null}),this.domPosition.prev?t(this.domPosition.prev).after(this.currentItem):t(this.domPosition.parent).prepend(this.currentItem)),this},serialize:function(e){var i=this._getItemsAsjQuery(e&&e.connected),s=[];return e=e||{},t(i).each(function(){var i=(t(e.item||this).attr(e.attribute||"id")||"").match(e.expression||/(.+)[\-=_](.+)/);i&&s.push((e.key||i[1]+"[]")+"="+(e.key&&e.expression?i[1]:i[2]))}),!s.length&&e.key&&s.push(e.key+"="),s.join("&")},toArray:function(e){var i=this._getItemsAsjQuery(e&&e.connected),s=[];return e=e||{},i.each(function(){s.push(t(e.item||this).attr(e.attribute||"id")||"")}),s},_intersectsWith:function(t){var e=this.positionAbs.left,i=e+this.helperProportions.width,s=this.positionAbs.top,n=s+this.helperProportions.height,o=t.left,a=o+t.width,r=t.top,h=r+t.height,l=this.offset.click.top,c=this.offset.click.left,u="x"===this.options.axis||s+l>r&&h>s+l,d="y"===this.options.axis||e+c>o&&a>e+c,p=u&&d;return"pointer"===this.options.tolerance||this.options.forcePointerForContainers||"pointer"!==this.options.tolerance&&this.helperProportions[this.floating?"width":"height"]>t[this.floating?"width":"height"]?p:e+this.helperProportions.width/2>o&&a>i-this.helperProportions.width/2&&s+this.helperProportions.height/2>r&&h>n-this.helperProportions.height/2},_intersectsWithPointer:function(t){var i="x"===this.options.axis||e(this.positionAbs.top+this.offset.click.top,t.top,t.height),s="y"===this.options.axis||e(this.positionAbs.left+this.offset.click.left,t.left,t.width),n=i&&s,o=this._getDragVerticalDirection(),a=this._getDragHorizontalDirection();return n?this.floating?a&&"right"===a||"down"===o?2:1:o&&("down"===o?2:1):!1},_intersectsWithSides:function(t){var i=e(this.positionAbs.top+this.offset.click.top,t.top+t.height/2,t.height),s=e(this.positionAbs.left+this.offset.click.left,t.left+t.width/2,t.width),n=this._getDragVerticalDirection(),o=this._getDragHorizontalDirection();return this.floating&&o?"right"===o&&s||"left"===o&&!s:n&&("down"===n&&i||"up"===n&&!i)},_getDragVerticalDirection:function(){var t=this.positionAbs.top-this.lastPositionAbs.top;return 0!==t&&(t>0?"down":"up")},_getDragHorizontalDirection:function(){var t=this.positionAbs.left-this.lastPositionAbs.left;return 0!==t&&(t>0?"right":"left")},refresh:function(t){return this._refreshItems(t),this.refreshPositions(),this},_connectWith:function(){var t=this.options;return t.connectWith.constructor===String?[t.connectWith]:t.connectWith},_getItemsAsjQuery:function(e){function i(){r.push(this)}var s,n,o,a,r=[],h=[],l=this._connectWith();if(l&&e)for(s=l.length-1;s>=0;s--)for(o=t(l[s]),n=o.length-1;n>=0;n--)a=t.data(o[n],this.widgetFullName),a&&a!==this&&!a.options.disabled&&h.push([t.isFunction(a.options.items)?a.options.items.call(a.element):t(a.options.items,a.element).not(".ui-sortable-helper").not(".ui-sortable-placeholder"),a]);for(h.push([t.isFunction(this.options.items)?this.options.items.call(this.element,null,{options:this.options,item:this.currentItem}):t(this.options.items,this.element).not(".ui-sortable-helper").not(".ui-sortable-placeholder"),this]),s=h.length-1;s>=0;s--)h[s][0].each(i);return t(r)},_removeCurrentsFromItems:function(){var e=this.currentItem.find(":data("+this.widgetName+"-item)");this.items=t.grep(this.items,function(t){for(var i=0;e.length>i;i++)if(e[i]===t.item[0])return!1;return!0})},_refreshItems:function(e){this.items=[],this.containers=[this];var i,s,n,o,a,r,h,l,c=this.items,u=[[t.isFunction(this.options.items)?this.options.items.call(this.element[0],e,{item:this.currentItem}):t(this.options.items,this.element),this]],d=this._connectWith();if(d&&this.ready)for(i=d.length-1;i>=0;i--)for(n=t(d[i]),s=n.length-1;s>=0;s--)o=t.data(n[s],this.widgetFullName),o&&o!==this&&!o.options.disabled&&(u.push([t.isFunction(o.options.items)?o.options.items.call(o.element[0],e,{item:this.currentItem}):t(o.options.items,o.element),o]),this.containers.push(o));for(i=u.length-1;i>=0;i--)for(a=u[i][1],r=u[i][0],s=0,l=r.length;l>s;s++)h=t(r[s]),h.data(this.widgetName+"-item",a),c.push({item:h,instance:a,width:0,height:0,left:0,top:0})},refreshPositions:function(e){this.offsetParent&&this.helper&&(this.offset.parent=this._getParentOffset());var i,s,n,o;for(i=this.items.length-1;i>=0;i--)s=this.items[i],s.instance!==this.currentContainer&&this.currentContainer&&s.item[0]!==this.currentItem[0]||(n=this.options.toleranceElement?t(this.options.toleranceElement,s.item):s.item,e||(s.width=n.outerWidth(),s.height=n.outerHeight()),o=n.offset(),s.left=o.left,s.top=o.top);if(this.options.custom&&this.options.custom.refreshContainers)this.options.custom.refreshContainers.call(this);else for(i=this.containers.length-1;i>=0;i--)o=this.containers[i].element.offset(),this.containers[i].containerCache.left=o.left,this.containers[i].containerCache.top=o.top,this.containers[i].containerCache.width=this.containers[i].element.outerWidth(),this.containers[i].containerCache.height=this.containers[i].element.outerHeight();return this},_createPlaceholder:function(e){e=e||this;var i,s=e.options;s.placeholder&&s.placeholder.constructor!==String||(i=s.placeholder,s.placeholder={element:function(){var s=e.currentItem[0].nodeName.toLowerCase(),n=t("<"+s+">",e.document[0]).addClass(i||e.currentItem[0].className+" ui-sortable-placeholder").removeClass("ui-sortable-helper");return"tr"===s?e.currentItem.children().each(function(){t("<td>&#160;</td>",e.document[0]).attr("colspan",t(this).attr("colspan")||1).appendTo(n)}):"img"===s&&n.attr("src",e.currentItem.attr("src")),i||n.css("visibility","hidden"),n},update:function(t,n){(!i||s.forcePlaceholderSize)&&(n.height()||n.height(e.currentItem.innerHeight()-parseInt(e.currentItem.css("paddingTop")||0,10)-parseInt(e.currentItem.css("paddingBottom")||0,10)),n.width()||n.width(e.currentItem.innerWidth()-parseInt(e.currentItem.css("paddingLeft")||0,10)-parseInt(e.currentItem.css("paddingRight")||0,10)))}}),e.placeholder=t(s.placeholder.element.call(e.element,e.currentItem)),e.currentItem.after(e.placeholder),s.placeholder.update(e,e.placeholder)},_contactContainers:function(s){var n,o,a,r,h,l,c,u,d,p,f=null,g=null;for(n=this.containers.length-1;n>=0;n--)if(!t.contains(this.currentItem[0],this.containers[n].element[0]))if(this._intersectsWith(this.containers[n].containerCache)){if(f&&t.contains(this.containers[n].element[0],f.element[0]))continue;f=this.containers[n],g=n}else this.containers[n].containerCache.over&&(this.containers[n]._trigger("out",s,this._uiHash(this)),this.containers[n].containerCache.over=0);if(f)if(1===this.containers.length)this.containers[g].containerCache.over||(this.containers[g]._trigger("over",s,this._uiHash(this)),this.containers[g].containerCache.over=1);else{for(a=1e4,r=null,p=f.floating||i(this.currentItem),h=p?"left":"top",l=p?"width":"height",c=this.positionAbs[h]+this.offset.click[h],o=this.items.length-1;o>=0;o--)t.contains(this.containers[g].element[0],this.items[o].item[0])&&this.items[o].item[0]!==this.currentItem[0]&&(!p||e(this.positionAbs.top+this.offset.click.top,this.items[o].top,this.items[o].height))&&(u=this.items[o].item.offset()[h],d=!1,Math.abs(u-c)>Math.abs(u+this.items[o][l]-c)&&(d=!0,u+=this.items[o][l]),a>Math.abs(u-c)&&(a=Math.abs(u-c),r=this.items[o],this.direction=d?"up":"down"));if(!r&&!this.options.dropOnEmpty)return;if(this.currentContainer===this.containers[g])return;r?this._rearrange(s,r,null,!0):this._rearrange(s,null,this.containers[g].element,!0),this._trigger("change",s,this._uiHash()),this.containers[g]._trigger("change",s,this._uiHash(this)),this.currentContainer=this.containers[g],this.options.placeholder.update(this.currentContainer,this.placeholder),this.containers[g]._trigger("over",s,this._uiHash(this)),this.containers[g].containerCache.over=1}},_createHelper:function(e){var i=this.options,s=t.isFunction(i.helper)?t(i.helper.apply(this.element[0],[e,this.currentItem])):"clone"===i.helper?this.currentItem.clone():this.currentItem;return s.parents("body").length||t("parent"!==i.appendTo?i.appendTo:this.currentItem[0].parentNode)[0].appendChild(s[0]),s[0]===this.currentItem[0]&&(this._storedCSS={width:this.currentItem[0].style.width,height:this.currentItem[0].style.height,position:this.currentItem.css("position"),top:this.currentItem.css("top"),left:this.currentItem.css("left")}),(!s[0].style.width||i.forceHelperSize)&&s.width(this.currentItem.width()),(!s[0].style.height||i.forceHelperSize)&&s.height(this.currentItem.height()),s},_adjustOffsetFromHelper:function(e){"string"==typeof e&&(e=e.split(" ")),t.isArray(e)&&(e={left:+e[0],top:+e[1]||0}),"left"in e&&(this.offset.click.left=e.left+this.margins.left),"right"in e&&(this.offset.click.left=this.helperProportions.width-e.right+this.margins.left),"top"in e&&(this.offset.click.top=e.top+this.margins.top),"bottom"in e&&(this.offset.click.top=this.helperProportions.height-e.bottom+this.margins.top)},_getParentOffset:function(){this.offsetParent=this.helper.offsetParent();var e=this.offsetParent.offset();return"absolute"===this.cssPosition&&this.scrollParent[0]!==document&&t.contains(this.scrollParent[0],this.offsetParent[0])&&(e.left+=this.scrollParent.scrollLeft(),e.top+=this.scrollParent.scrollTop()),(this.offsetParent[0]===document.body||this.offsetParent[0].tagName&&"html"===this.offsetParent[0].tagName.toLowerCase()&&t.ui.ie)&&(e={top:0,left:0}),{top:e.top+(parseInt(this.offsetParent.css("borderTopWidth"),10)||0),left:e.left+(parseInt(this.offsetParent.css("borderLeftWidth"),10)||0)}},_getRelativeOffset:function(){if("relative"===this.cssPosition){var t=this.currentItem.position();return{top:t.top-(parseInt(this.helper.css("top"),10)||0)+this.scrollParent.scrollTop(),left:t.left-(parseInt(this.helper.css("left"),10)||0)+this.scrollParent.scrollLeft()}}return{top:0,left:0}},_cacheMargins:function(){this.margins={left:parseInt(this.currentItem.css("marginLeft"),10)||0,top:parseInt(this.currentItem.css("marginTop"),10)||0}},_cacheHelperProportions:function(){this.helperProportions={width:this.helper.outerWidth(),height:this.helper.outerHeight()}},_setContainment:function(){var e,i,s,n=this.options;"parent"===n.containment&&(n.containment=this.helper[0].parentNode),("document"===n.containment||"window"===n.containment)&&(this.containment=[0-this.offset.relative.left-this.offset.parent.left,0-this.offset.relative.top-this.offset.parent.top,t("document"===n.containment?document:window).width()-this.helperProportions.width-this.margins.left,(t("document"===n.containment?document:window).height()||document.body.parentNode.scrollHeight)-this.helperProportions.height-this.margins.top]),/^(document|window|parent)$/.test(n.containment)||(e=t(n.containment)[0],i=t(n.containment).offset(),s="hidden"!==t(e).css("overflow"),this.containment=[i.left+(parseInt(t(e).css("borderLeftWidth"),10)||0)+(parseInt(t(e).css("paddingLeft"),10)||0)-this.margins.left,i.top+(parseInt(t(e).css("borderTopWidth"),10)||0)+(parseInt(t(e).css("paddingTop"),10)||0)-this.margins.top,i.left+(s?Math.max(e.scrollWidth,e.offsetWidth):e.offsetWidth)-(parseInt(t(e).css("borderLeftWidth"),10)||0)-(parseInt(t(e).css("paddingRight"),10)||0)-this.helperProportions.width-this.margins.left,i.top+(s?Math.max(e.scrollHeight,e.offsetHeight):e.offsetHeight)-(parseInt(t(e).css("borderTopWidth"),10)||0)-(parseInt(t(e).css("paddingBottom"),10)||0)-this.helperProportions.height-this.margins.top])},_convertPositionTo:function(e,i){i||(i=this.position);var s="absolute"===e?1:-1,n="absolute"!==this.cssPosition||this.scrollParent[0]!==document&&t.contains(this.scrollParent[0],this.offsetParent[0])?this.scrollParent:this.offsetParent,o=/(html|body)/i.test(n[0].tagName);return{top:i.top+this.offset.relative.top*s+this.offset.parent.top*s-("fixed"===this.cssPosition?-this.scrollParent.scrollTop():o?0:n.scrollTop())*s,left:i.left+this.offset.relative.left*s+this.offset.parent.left*s-("fixed"===this.cssPosition?-this.scrollParent.scrollLeft():o?0:n.scrollLeft())*s}},_generatePosition:function(e){var i,s,n=this.options,o=e.pageX,a=e.pageY,r="absolute"!==this.cssPosition||this.scrollParent[0]!==document&&t.contains(this.scrollParent[0],this.offsetParent[0])?this.scrollParent:this.offsetParent,h=/(html|body)/i.test(r[0].tagName);return"relative"!==this.cssPosition||this.scrollParent[0]!==document&&this.scrollParent[0]!==this.offsetParent[0]||(this.offset.relative=this._getRelativeOffset()),this.originalPosition&&(this.containment&&(e.pageX-this.offset.click.left<this.containment[0]&&(o=this.containment[0]+this.offset.click.left),e.pageY-this.offset.click.top<this.containment[1]&&(a=this.containment[1]+this.offset.click.top),e.pageX-this.offset.click.left>this.containment[2]&&(o=this.containment[2]+this.offset.click.left),e.pageY-this.offset.click.top>this.containment[3]&&(a=this.containment[3]+this.offset.click.top)),n.grid&&(i=this.originalPageY+Math.round((a-this.originalPageY)/n.grid[1])*n.grid[1],a=this.containment?i-this.offset.click.top>=this.containment[1]&&i-this.offset.click.top<=this.containment[3]?i:i-this.offset.click.top>=this.containment[1]?i-n.grid[1]:i+n.grid[1]:i,s=this.originalPageX+Math.round((o-this.originalPageX)/n.grid[0])*n.grid[0],o=this.containment?s-this.offset.click.left>=this.containment[0]&&s-this.offset.click.left<=this.containment[2]?s:s-this.offset.click.left>=this.containment[0]?s-n.grid[0]:s+n.grid[0]:s)),{top:a-this.offset.click.top-this.offset.relative.top-this.offset.parent.top+("fixed"===this.cssPosition?-this.scrollParent.scrollTop():h?0:r.scrollTop()),left:o-this.offset.click.left-this.offset.relative.left-this.offset.parent.left+("fixed"===this.cssPosition?-this.scrollParent.scrollLeft():h?0:r.scrollLeft())}},_rearrange:function(t,e,i,s){i?i[0].appendChild(this.placeholder[0]):e.item[0].parentNode.insertBefore(this.placeholder[0],"down"===this.direction?e.item[0]:e.item[0].nextSibling),this.counter=this.counter?++this.counter:1;var n=this.counter;this._delay(function(){n===this.counter&&this.refreshPositions(!s)})},_clear:function(t,e){function i(t,e,i){return function(s){i._trigger(t,s,e._uiHash(e))}}this.reverting=!1;var s,n=[];if(!this._noFinalSort&&this.currentItem.parent().length&&this.placeholder.before(this.currentItem),this._noFinalSort=null,this.helper[0]===this.currentItem[0]){for(s in this._storedCSS)("auto"===this._storedCSS[s]||"static"===this._storedCSS[s])&&(this._storedCSS[s]="");this.currentItem.css(this._storedCSS).removeClass("ui-sortable-helper")}else this.currentItem.show();for(this.fromOutside&&!e&&n.push(function(t){this._trigger("receive",t,this._uiHash(this.fromOutside))}),!this.fromOutside&&this.domPosition.prev===this.currentItem.prev().not(".ui-sortable-helper")[0]&&this.domPosition.parent===this.currentItem.parent()[0]||e||n.push(function(t){this._trigger("update",t,this._uiHash())}),this!==this.currentContainer&&(e||(n.push(function(t){this._trigger("remove",t,this._uiHash())}),n.push(function(t){return function(e){t._trigger("receive",e,this._uiHash(this))}}.call(this,this.currentContainer)),n.push(function(t){return function(e){t._trigger("update",e,this._uiHash(this))}}.call(this,this.currentContainer)))),s=this.containers.length-1;s>=0;s--)e||n.push(i("deactivate",this,this.containers[s])),this.containers[s].containerCache.over&&(n.push(i("out",this,this.containers[s])),this.containers[s].containerCache.over=0);if(this.storedCursor&&(this.document.find("body").css("cursor",this.storedCursor),this.storedStylesheet.remove()),this._storedOpacity&&this.helper.css("opacity",this._storedOpacity),this._storedZIndex&&this.helper.css("zIndex","auto"===this._storedZIndex?"":this._storedZIndex),this.dragging=!1,this.cancelHelperRemoval){if(!e){for(this._trigger("beforeStop",t,this._uiHash()),s=0;n.length>s;s++)n[s].call(this,t);this._trigger("stop",t,this._uiHash())}return this.fromOutside=!1,!1}if(e||this._trigger("beforeStop",t,this._uiHash()),this.placeholder[0].parentNode.removeChild(this.placeholder[0]),this.helper[0]!==this.currentItem[0]&&this.helper.remove(),this.helper=null,!e){for(s=0;n.length>s;s++)n[s].call(this,t);this._trigger("stop",t,this._uiHash())}return this.fromOutside=!1,!0},_trigger:function(){t.Widget.prototype._trigger.apply(this,arguments)===!1&&this.cancel()},_uiHash:function(e){var i=e||this;return{helper:i.helper,placeholder:i.placeholder||t([]),position:i.position,originalPosition:i.originalPosition,offset:i.positionAbs,item:i.currentItem,sender:e?e.element:null}}})})(jQuery);(function(e){var t=0,i={},a={};i.height=i.paddingTop=i.paddingBottom=i.borderTopWidth=i.borderBottomWidth="hide",a.height=a.paddingTop=a.paddingBottom=a.borderTopWidth=a.borderBottomWidth="show",e.widget("ui.accordion",{version:"1.10.4",options:{active:0,animate:{},collapsible:!1,event:"click",header:"> li > :first-child,> :not(li):even",heightStyle:"auto",icons:{activeHeader:"ui-icon-triangle-1-s",header:"ui-icon-triangle-1-e"},activate:null,beforeActivate:null},_create:function(){var t=this.options;this.prevShow=this.prevHide=e(),this.element.addClass("ui-accordion ui-widget ui-helper-reset").attr("role","tablist"),t.collapsible||t.active!==!1&&null!=t.active||(t.active=0),this._processPanels(),0>t.active&&(t.active+=this.headers.length),this._refresh()},_getCreateEventData:function(){return{header:this.active,panel:this.active.length?this.active.next():e(),content:this.active.length?this.active.next():e()}},_createIcons:function(){var t=this.options.icons;t&&(e("<span>").addClass("ui-accordion-header-icon ui-icon "+t.header).prependTo(this.headers),this.active.children(".ui-accordion-header-icon").removeClass(t.header).addClass(t.activeHeader),this.headers.addClass("ui-accordion-icons"))},_destroyIcons:function(){this.headers.removeClass("ui-accordion-icons").children(".ui-accordion-header-icon").remove()},_destroy:function(){var e;this.element.removeClass("ui-accordion ui-widget ui-helper-reset").removeAttr("role"),this.headers.removeClass("ui-accordion-header ui-accordion-header-active ui-helper-reset ui-state-default ui-corner-all ui-state-active ui-state-disabled ui-corner-top").removeAttr("role").removeAttr("aria-expanded").removeAttr("aria-selected").removeAttr("aria-controls").removeAttr("tabIndex").each(function(){/^ui-accordion/.test(this.id)&&this.removeAttribute("id")}),this._destroyIcons(),e=this.headers.next().css("display","").removeAttr("role").removeAttr("aria-hidden").removeAttr("aria-labelledby").removeClass("ui-helper-reset ui-widget-content ui-corner-bottom ui-accordion-content ui-accordion-content-active ui-state-disabled").each(function(){/^ui-accordion/.test(this.id)&&this.removeAttribute("id")}),"content"!==this.options.heightStyle&&e.css("height","")},_setOption:function(e,t){return"active"===e?(this._activate(t),undefined):("event"===e&&(this.options.event&&this._off(this.headers,this.options.event),this._setupEvents(t)),this._super(e,t),"collapsible"!==e||t||this.options.active!==!1||this._activate(0),"icons"===e&&(this._destroyIcons(),t&&this._createIcons()),"disabled"===e&&this.headers.add(this.headers.next()).toggleClass("ui-state-disabled",!!t),undefined)},_keydown:function(t){if(!t.altKey&&!t.ctrlKey){var i=e.ui.keyCode,a=this.headers.length,s=this.headers.index(t.target),n=!1;switch(t.keyCode){case i.RIGHT:case i.DOWN:n=this.headers[(s+1)%a];break;case i.LEFT:case i.UP:n=this.headers[(s-1+a)%a];break;case i.SPACE:case i.ENTER:this._eventHandler(t);break;case i.HOME:n=this.headers[0];break;case i.END:n=this.headers[a-1]}n&&(e(t.target).attr("tabIndex",-1),e(n).attr("tabIndex",0),n.focus(),t.preventDefault())}},_panelKeyDown:function(t){t.keyCode===e.ui.keyCode.UP&&t.ctrlKey&&e(t.currentTarget).prev().focus()},refresh:function(){var t=this.options;this._processPanels(),t.active===!1&&t.collapsible===!0||!this.headers.length?(t.active=!1,this.active=e()):t.active===!1?this._activate(0):this.active.length&&!e.contains(this.element[0],this.active[0])?this.headers.length===this.headers.find(".ui-state-disabled").length?(t.active=!1,this.active=e()):this._activate(Math.max(0,t.active-1)):t.active=this.headers.index(this.active),this._destroyIcons(),this._refresh()},_processPanels:function(){this.headers=this.element.find(this.options.header).addClass("ui-accordion-header ui-helper-reset ui-state-default ui-corner-all"),this.headers.next().addClass("ui-accordion-content ui-helper-reset ui-widget-content ui-corner-bottom").filter(":not(.ui-accordion-content-active)").hide()},_refresh:function(){var i,a=this.options,s=a.heightStyle,n=this.element.parent(),r=this.accordionId="ui-accordion-"+(this.element.attr("id")||++t);this.active=this._findActive(a.active).addClass("ui-accordion-header-active ui-state-active ui-corner-top").removeClass("ui-corner-all"),this.active.next().addClass("ui-accordion-content-active").show(),this.headers.attr("role","tab").each(function(t){var i=e(this),a=i.attr("id"),s=i.next(),n=s.attr("id");a||(a=r+"-header-"+t,i.attr("id",a)),n||(n=r+"-panel-"+t,s.attr("id",n)),i.attr("aria-controls",n),s.attr("aria-labelledby",a)}).next().attr("role","tabpanel"),this.headers.not(this.active).attr({"aria-selected":"false","aria-expanded":"false",tabIndex:-1}).next().attr({"aria-hidden":"true"}).hide(),this.active.length?this.active.attr({"aria-selected":"true","aria-expanded":"true",tabIndex:0}).next().attr({"aria-hidden":"false"}):this.headers.eq(0).attr("tabIndex",0),this._createIcons(),this._setupEvents(a.event),"fill"===s?(i=n.height(),this.element.siblings(":visible").each(function(){var t=e(this),a=t.css("position");"absolute"!==a&&"fixed"!==a&&(i-=t.outerHeight(!0))}),this.headers.each(function(){i-=e(this).outerHeight(!0)}),this.headers.next().each(function(){e(this).height(Math.max(0,i-e(this).innerHeight()+e(this).height()))}).css("overflow","auto")):"auto"===s&&(i=0,this.headers.next().each(function(){i=Math.max(i,e(this).css("height","").height())}).height(i))},_activate:function(t){var i=this._findActive(t)[0];i!==this.active[0]&&(i=i||this.active[0],this._eventHandler({target:i,currentTarget:i,preventDefault:e.noop}))},_findActive:function(t){return"number"==typeof t?this.headers.eq(t):e()},_setupEvents:function(t){var i={keydown:"_keydown"};t&&e.each(t.split(" "),function(e,t){i[t]="_eventHandler"}),this._off(this.headers.add(this.headers.next())),this._on(this.headers,i),this._on(this.headers.next(),{keydown:"_panelKeyDown"}),this._hoverable(this.headers),this._focusable(this.headers)},_eventHandler:function(t){var i=this.options,a=this.active,s=e(t.currentTarget),n=s[0]===a[0],r=n&&i.collapsible,o=r?e():s.next(),h=a.next(),d={oldHeader:a,oldPanel:h,newHeader:r?e():s,newPanel:o};t.preventDefault(),n&&!i.collapsible||this._trigger("beforeActivate",t,d)===!1||(i.active=r?!1:this.headers.index(s),this.active=n?e():s,this._toggle(d),a.removeClass("ui-accordion-header-active ui-state-active"),i.icons&&a.children(".ui-accordion-header-icon").removeClass(i.icons.activeHeader).addClass(i.icons.header),n||(s.removeClass("ui-corner-all").addClass("ui-accordion-header-active ui-state-active ui-corner-top"),i.icons&&s.children(".ui-accordion-header-icon").removeClass(i.icons.header).addClass(i.icons.activeHeader),s.next().addClass("ui-accordion-content-active")))},_toggle:function(t){var i=t.newPanel,a=this.prevShow.length?this.prevShow:t.oldPanel;this.prevShow.add(this.prevHide).stop(!0,!0),this.prevShow=i,this.prevHide=a,this.options.animate?this._animate(i,a,t):(a.hide(),i.show(),this._toggleComplete(t)),a.attr({"aria-hidden":"true"}),a.prev().attr("aria-selected","false"),i.length&&a.length?a.prev().attr({tabIndex:-1,"aria-expanded":"false"}):i.length&&this.headers.filter(function(){return 0===e(this).attr("tabIndex")}).attr("tabIndex",-1),i.attr("aria-hidden","false").prev().attr({"aria-selected":"true",tabIndex:0,"aria-expanded":"true"})},_animate:function(e,t,s){var n,r,o,h=this,d=0,c=e.length&&(!t.length||e.index()<t.index()),l=this.options.animate||{},u=c&&l.down||l,v=function(){h._toggleComplete(s)};return"number"==typeof u&&(o=u),"string"==typeof u&&(r=u),r=r||u.easing||l.easing,o=o||u.duration||l.duration,t.length?e.length?(n=e.show().outerHeight(),t.animate(i,{duration:o,easing:r,step:function(e,t){t.now=Math.round(e)}}),e.hide().animate(a,{duration:o,easing:r,complete:v,step:function(e,i){i.now=Math.round(e),"height"!==i.prop?d+=i.now:"content"!==h.options.heightStyle&&(i.now=Math.round(n-t.outerHeight()-d),d=0)}}),undefined):t.animate(i,o,r,v):e.animate(a,o,r,v)},_toggleComplete:function(e){var t=e.oldPanel;t.removeClass("ui-accordion-content-active").prev().removeClass("ui-corner-top").addClass("ui-corner-all"),t.length&&(t.parent()[0].className=t.parent()[0].className),this._trigger("activate",null,e)}})})(jQuery);(function(e){e.widget("ui.autocomplete",{version:"1.10.4",defaultElement:"<input>",options:{appendTo:null,autoFocus:!1,delay:300,minLength:1,position:{my:"left top",at:"left bottom",collision:"none"},source:null,change:null,close:null,focus:null,open:null,response:null,search:null,select:null},requestIndex:0,pending:0,_create:function(){var t,i,s,n=this.element[0].nodeName.toLowerCase(),a="textarea"===n,o="input"===n;this.isMultiLine=a?!0:o?!1:this.element.prop("isContentEditable"),this.valueMethod=this.element[a||o?"val":"text"],this.isNewMenu=!0,this.element.addClass("ui-autocomplete-input").attr("autocomplete","off"),this._on(this.element,{keydown:function(n){if(this.element.prop("readOnly"))return t=!0,s=!0,i=!0,undefined;t=!1,s=!1,i=!1;var a=e.ui.keyCode;switch(n.keyCode){case a.PAGE_UP:t=!0,this._move("previousPage",n);break;case a.PAGE_DOWN:t=!0,this._move("nextPage",n);break;case a.UP:t=!0,this._keyEvent("previous",n);break;case a.DOWN:t=!0,this._keyEvent("next",n);break;case a.ENTER:case a.NUMPAD_ENTER:this.menu.active&&(t=!0,n.preventDefault(),this.menu.select(n));break;case a.TAB:this.menu.active&&this.menu.select(n);break;case a.ESCAPE:this.menu.element.is(":visible")&&(this._value(this.term),this.close(n),n.preventDefault());break;default:i=!0,this._searchTimeout(n)}},keypress:function(s){if(t)return t=!1,(!this.isMultiLine||this.menu.element.is(":visible"))&&s.preventDefault(),undefined;if(!i){var n=e.ui.keyCode;switch(s.keyCode){case n.PAGE_UP:this._move("previousPage",s);break;case n.PAGE_DOWN:this._move("nextPage",s);break;case n.UP:this._keyEvent("previous",s);break;case n.DOWN:this._keyEvent("next",s)}}},input:function(e){return s?(s=!1,e.preventDefault(),undefined):(this._searchTimeout(e),undefined)},focus:function(){this.selectedItem=null,this.previous=this._value()},blur:function(e){return this.cancelBlur?(delete this.cancelBlur,undefined):(clearTimeout(this.searching),this.close(e),this._change(e),undefined)}}),this._initSource(),this.menu=e("<ul>").addClass("ui-autocomplete ui-front").appendTo(this._appendTo()).menu({role:null}).hide().data("ui-menu"),this._on(this.menu.element,{mousedown:function(t){t.preventDefault(),this.cancelBlur=!0,this._delay(function(){delete this.cancelBlur});var i=this.menu.element[0];e(t.target).closest(".ui-menu-item").length||this._delay(function(){var t=this;this.document.one("mousedown",function(s){s.target===t.element[0]||s.target===i||e.contains(i,s.target)||t.close()})})},menufocus:function(t,i){if(this.isNewMenu&&(this.isNewMenu=!1,t.originalEvent&&/^mouse/.test(t.originalEvent.type)))return this.menu.blur(),this.document.one("mousemove",function(){e(t.target).trigger(t.originalEvent)}),undefined;var s=i.item.data("ui-autocomplete-item");!1!==this._trigger("focus",t,{item:s})?t.originalEvent&&/^key/.test(t.originalEvent.type)&&this._value(s.value):this.liveRegion.text(s.value)},menuselect:function(e,t){var i=t.item.data("ui-autocomplete-item"),s=this.previous;this.element[0]!==this.document[0].activeElement&&(this.element.focus(),this.previous=s,this._delay(function(){this.previous=s,this.selectedItem=i})),!1!==this._trigger("select",e,{item:i})&&this._value(i.value),this.term=this._value(),this.close(e),this.selectedItem=i}}),this.liveRegion=e("<span>",{role:"status","aria-live":"polite"}).addClass("ui-helper-hidden-accessible").insertBefore(this.element),this._on(this.window,{beforeunload:function(){this.element.removeAttr("autocomplete")}})},_destroy:function(){clearTimeout(this.searching),this.element.removeClass("ui-autocomplete-input").removeAttr("autocomplete"),this.menu.element.remove(),this.liveRegion.remove()},_setOption:function(e,t){this._super(e,t),"source"===e&&this._initSource(),"appendTo"===e&&this.menu.element.appendTo(this._appendTo()),"disabled"===e&&t&&this.xhr&&this.xhr.abort()},_appendTo:function(){var t=this.options.appendTo;return t&&(t=t.jquery||t.nodeType?e(t):this.document.find(t).eq(0)),t||(t=this.element.closest(".ui-front")),t.length||(t=this.document[0].body),t},_initSource:function(){var t,i,s=this;e.isArray(this.options.source)?(t=this.options.source,this.source=function(i,s){s(e.ui.autocomplete.filter(t,i.term))}):"string"==typeof this.options.source?(i=this.options.source,this.source=function(t,n){s.xhr&&s.xhr.abort(),s.xhr=e.ajax({url:i,data:t,dataType:"json",success:function(e){n(e)},error:function(){n([])}})}):this.source=this.options.source},_searchTimeout:function(e){clearTimeout(this.searching),this.searching=this._delay(function(){this.term!==this._value()&&(this.selectedItem=null,this.search(null,e))},this.options.delay)},search:function(e,t){return e=null!=e?e:this._value(),this.term=this._value(),e.length<this.options.minLength?this.close(t):this._trigger("search",t)!==!1?this._search(e):undefined},_search:function(e){this.pending++,this.element.addClass("ui-autocomplete-loading"),this.cancelSearch=!1,this.source({term:e},this._response())},_response:function(){var t=++this.requestIndex;return e.proxy(function(e){t===this.requestIndex&&this.__response(e),this.pending--,this.pending||this.element.removeClass("ui-autocomplete-loading")},this)},__response:function(e){e&&(e=this._normalize(e)),this._trigger("response",null,{content:e}),!this.options.disabled&&e&&e.length&&!this.cancelSearch?(this._suggest(e),this._trigger("open")):this._close()},close:function(e){this.cancelSearch=!0,this._close(e)},_close:function(e){this.menu.element.is(":visible")&&(this.menu.element.hide(),this.menu.blur(),this.isNewMenu=!0,this._trigger("close",e))},_change:function(e){this.previous!==this._value()&&this._trigger("change",e,{item:this.selectedItem})},_normalize:function(t){return t.length&&t[0].label&&t[0].value?t:e.map(t,function(t){return"string"==typeof t?{label:t,value:t}:e.extend({label:t.label||t.value,value:t.value||t.label},t)})},_suggest:function(t){var i=this.menu.element.empty();this._renderMenu(i,t),this.isNewMenu=!0,this.menu.refresh(),i.show(),this._resizeMenu(),i.position(e.extend({of:this.element},this.options.position)),this.options.autoFocus&&this.menu.next()},_resizeMenu:function(){var e=this.menu.element;e.outerWidth(Math.max(e.width("").outerWidth()+1,this.element.outerWidth()))},_renderMenu:function(t,i){var s=this;e.each(i,function(e,i){s._renderItemData(t,i)})},_renderItemData:function(e,t){return this._renderItem(e,t).data("ui-autocomplete-item",t)},_renderItem:function(t,i){return e("<li>").append(e("<a>").text(i.label)).appendTo(t)},_move:function(e,t){return this.menu.element.is(":visible")?this.menu.isFirstItem()&&/^previous/.test(e)||this.menu.isLastItem()&&/^next/.test(e)?(this._value(this.term),this.menu.blur(),undefined):(this.menu[e](t),undefined):(this.search(null,t),undefined)},widget:function(){return this.menu.element},_value:function(){return this.valueMethod.apply(this.element,arguments)},_keyEvent:function(e,t){(!this.isMultiLine||this.menu.element.is(":visible"))&&(this._move(e,t),t.preventDefault())}}),e.extend(e.ui.autocomplete,{escapeRegex:function(e){return e.replace(/[\-\[\]{}()*+?.,\\\^$|#\s]/g,"\\$&")},filter:function(t,i){var s=RegExp(e.ui.autocomplete.escapeRegex(i),"i");return e.grep(t,function(e){return s.test(e.label||e.value||e)})}}),e.widget("ui.autocomplete",e.ui.autocomplete,{options:{messages:{noResults:"No search results.",results:function(e){return e+(e>1?" results are":" result is")+" available, use up and down arrow keys to navigate."}}},__response:function(e){var t;this._superApply(arguments),this.options.disabled||this.cancelSearch||(t=e&&e.length?this.options.messages.results(e.length):this.options.messages.noResults,this.liveRegion.text(t))}})})(jQuery);(function(e){var t,i="ui-button ui-widget ui-state-default ui-corner-all",n="ui-button-icons-only ui-button-icon-only ui-button-text-icons ui-button-text-icon-primary ui-button-text-icon-secondary ui-button-text-only",s=function(){var t=e(this);setTimeout(function(){t.find(":ui-button").button("refresh")},1)},a=function(t){var i=t.name,n=t.form,s=e([]);return i&&(i=i.replace(/'/g,"\\'"),s=n?e(n).find("[name='"+i+"']"):e("[name='"+i+"']",t.ownerDocument).filter(function(){return!this.form})),s};e.widget("ui.button",{version:"1.10.4",defaultElement:"<button>",options:{disabled:null,text:!0,label:null,icons:{primary:null,secondary:null}},_create:function(){this.element.closest("form").unbind("reset"+this.eventNamespace).bind("reset"+this.eventNamespace,s),"boolean"!=typeof this.options.disabled?this.options.disabled=!!this.element.prop("disabled"):this.element.prop("disabled",this.options.disabled),this._determineButtonType(),this.hasTitle=!!this.buttonElement.attr("title");var n=this,o=this.options,r="checkbox"===this.type||"radio"===this.type,h=r?"":"ui-state-active";null===o.label&&(o.label="input"===this.type?this.buttonElement.val():this.buttonElement.html()),this._hoverable(this.buttonElement),this.buttonElement.addClass(i).attr("role","button").bind("mouseenter"+this.eventNamespace,function(){o.disabled||this===t&&e(this).addClass("ui-state-active")}).bind("mouseleave"+this.eventNamespace,function(){o.disabled||e(this).removeClass(h)}).bind("click"+this.eventNamespace,function(e){o.disabled&&(e.preventDefault(),e.stopImmediatePropagation())}),this._on({focus:function(){this.buttonElement.addClass("ui-state-focus")},blur:function(){this.buttonElement.removeClass("ui-state-focus")}}),r&&this.element.bind("change"+this.eventNamespace,function(){n.refresh()}),"checkbox"===this.type?this.buttonElement.bind("click"+this.eventNamespace,function(){return o.disabled?!1:undefined}):"radio"===this.type?this.buttonElement.bind("click"+this.eventNamespace,function(){if(o.disabled)return!1;e(this).addClass("ui-state-active"),n.buttonElement.attr("aria-pressed","true");var t=n.element[0];a(t).not(t).map(function(){return e(this).button("widget")[0]}).removeClass("ui-state-active").attr("aria-pressed","false")}):(this.buttonElement.bind("mousedown"+this.eventNamespace,function(){return o.disabled?!1:(e(this).addClass("ui-state-active"),t=this,n.document.one("mouseup",function(){t=null}),undefined)}).bind("mouseup"+this.eventNamespace,function(){return o.disabled?!1:(e(this).removeClass("ui-state-active"),undefined)}).bind("keydown"+this.eventNamespace,function(t){return o.disabled?!1:((t.keyCode===e.ui.keyCode.SPACE||t.keyCode===e.ui.keyCode.ENTER)&&e(this).addClass("ui-state-active"),undefined)}).bind("keyup"+this.eventNamespace+" blur"+this.eventNamespace,function(){e(this).removeClass("ui-state-active")}),this.buttonElement.is("a")&&this.buttonElement.keyup(function(t){t.keyCode===e.ui.keyCode.SPACE&&e(this).click()})),this._setOption("disabled",o.disabled),this._resetButton()},_determineButtonType:function(){var e,t,i;this.type=this.element.is("[type=checkbox]")?"checkbox":this.element.is("[type=radio]")?"radio":this.element.is("input")?"input":"button","checkbox"===this.type||"radio"===this.type?(e=this.element.parents().last(),t="label[for='"+this.element.attr("id")+"']",this.buttonElement=e.find(t),this.buttonElement.length||(e=e.length?e.siblings():this.element.siblings(),this.buttonElement=e.filter(t),this.buttonElement.length||(this.buttonElement=e.find(t))),this.element.addClass("ui-helper-hidden-accessible"),i=this.element.is(":checked"),i&&this.buttonElement.addClass("ui-state-active"),this.buttonElement.prop("aria-pressed",i)):this.buttonElement=this.element},widget:function(){return this.buttonElement},_destroy:function(){this.element.removeClass("ui-helper-hidden-accessible"),this.buttonElement.removeClass(i+" ui-state-active "+n).removeAttr("role").removeAttr("aria-pressed").html(this.buttonElement.find(".ui-button-text").html()),this.hasTitle||this.buttonElement.removeAttr("title")},_setOption:function(e,t){return this._super(e,t),"disabled"===e?(this.element.prop("disabled",!!t),t&&this.buttonElement.removeClass("ui-state-focus"),undefined):(this._resetButton(),undefined)},refresh:function(){var t=this.element.is("input, button")?this.element.is(":disabled"):this.element.hasClass("ui-button-disabled");t!==this.options.disabled&&this._setOption("disabled",t),"radio"===this.type?a(this.element[0]).each(function(){e(this).is(":checked")?e(this).button("widget").addClass("ui-state-active").attr("aria-pressed","true"):e(this).button("widget").removeClass("ui-state-active").attr("aria-pressed","false")}):"checkbox"===this.type&&(this.element.is(":checked")?this.buttonElement.addClass("ui-state-active").attr("aria-pressed","true"):this.buttonElement.removeClass("ui-state-active").attr("aria-pressed","false"))},_resetButton:function(){if("input"===this.type)return this.options.label&&this.element.val(this.options.label),undefined;var t=this.buttonElement.removeClass(n),i=e("<span></span>",this.document[0]).addClass("ui-button-text").html(this.options.label).appendTo(t.empty()).text(),s=this.options.icons,a=s.primary&&s.secondary,o=[];s.primary||s.secondary?(this.options.text&&o.push("ui-button-text-icon"+(a?"s":s.primary?"-primary":"-secondary")),s.primary&&t.prepend("<span class='ui-button-icon-primary ui-icon "+s.primary+"'></span>"),s.secondary&&t.append("<span class='ui-button-icon-secondary ui-icon "+s.secondary+"'></span>"),this.options.text||(o.push(a?"ui-button-icons-only":"ui-button-icon-only"),this.hasTitle||t.attr("title",e.trim(i)))):o.push("ui-button-text-only"),t.addClass(o.join(" "))}}),e.widget("ui.buttonset",{version:"1.10.4",options:{items:"button, input[type=button], input[type=submit], input[type=reset], input[type=checkbox], input[type=radio], a, :data(ui-button)"},_create:function(){this.element.addClass("ui-buttonset")},_init:function(){this.refresh()},_setOption:function(e,t){"disabled"===e&&this.buttons.button("option",e,t),this._super(e,t)},refresh:function(){var t="rtl"===this.element.css("direction");this.buttons=this.element.find(this.options.items).filter(":ui-button").button("refresh").end().not(":ui-button").button().end().map(function(){return e(this).button("widget")[0]}).removeClass("ui-corner-all ui-corner-left ui-corner-right").filter(":first").addClass(t?"ui-corner-right":"ui-corner-left").end().filter(":last").addClass(t?"ui-corner-left":"ui-corner-right").end().end()},_destroy:function(){this.element.removeClass("ui-buttonset"),this.buttons.map(function(){return e(this).button("widget")[0]}).removeClass("ui-corner-left ui-corner-right").end().button("destroy")}})})(jQuery);(function(e,t){function i(){this._curInst=null,this._keyEvent=!1,this._disabledInputs=[],this._datepickerShowing=!1,this._inDialog=!1,this._mainDivId="ui-datepicker-div",this._inlineClass="ui-datepicker-inline",this._appendClass="ui-datepicker-append",this._triggerClass="ui-datepicker-trigger",this._dialogClass="ui-datepicker-dialog",this._disableClass="ui-datepicker-disabled",this._unselectableClass="ui-datepicker-unselectable",this._currentClass="ui-datepicker-current-day",this._dayOverClass="ui-datepicker-days-cell-over",this.regional=[],this.regional[""]={closeText:"Done",prevText:"Prev",nextText:"Next",currentText:"Today",monthNames:["January","February","March","April","May","June","July","August","September","October","November","December"],monthNamesShort:["Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec"],dayNames:["Sunday","Monday","Tuesday","Wednesday","Thursday","Friday","Saturday"],dayNamesShort:["Sun","Mon","Tue","Wed","Thu","Fri","Sat"],dayNamesMin:["Su","Mo","Tu","We","Th","Fr","Sa"],weekHeader:"Wk",dateFormat:"mm/dd/yy",firstDay:0,isRTL:!1,showMonthAfterYear:!1,yearSuffix:""},this._defaults={showOn:"focus",showAnim:"fadeIn",showOptions:{},defaultDate:null,appendText:"",buttonText:"...",buttonImage:"",buttonImageOnly:!1,hideIfNoPrevNext:!1,navigationAsDateFormat:!1,gotoCurrent:!1,changeMonth:!1,changeYear:!1,yearRange:"c-10:c+10",showOtherMonths:!1,selectOtherMonths:!1,showWeek:!1,calculateWeek:this.iso8601Week,shortYearCutoff:"+10",minDate:null,maxDate:null,duration:"fast",beforeShowDay:null,beforeShow:null,onSelect:null,onChangeMonthYear:null,onClose:null,numberOfMonths:1,showCurrentAtPos:0,stepMonths:1,stepBigMonths:12,altField:"",altFormat:"",constrainInput:!0,showButtonPanel:!1,autoSize:!1,disabled:!1},e.extend(this._defaults,this.regional[""]),this.dpDiv=a(e("<div id='"+this._mainDivId+"' class='ui-datepicker ui-widget ui-widget-content ui-helper-clearfix ui-corner-all'></div>"))}function a(t){var i="button, .ui-datepicker-prev, .ui-datepicker-next, .ui-datepicker-calendar td a";return t.delegate(i,"mouseout",function(){e(this).removeClass("ui-state-hover"),-1!==this.className.indexOf("ui-datepicker-prev")&&e(this).removeClass("ui-datepicker-prev-hover"),-1!==this.className.indexOf("ui-datepicker-next")&&e(this).removeClass("ui-datepicker-next-hover")}).delegate(i,"mouseover",function(){e.datepicker._isDisabledDatepicker(n.inline?t.parent()[0]:n.input[0])||(e(this).parents(".ui-datepicker-calendar").find("a").removeClass("ui-state-hover"),e(this).addClass("ui-state-hover"),-1!==this.className.indexOf("ui-datepicker-prev")&&e(this).addClass("ui-datepicker-prev-hover"),-1!==this.className.indexOf("ui-datepicker-next")&&e(this).addClass("ui-datepicker-next-hover"))})}function s(t,i){e.extend(t,i);for(var a in i)null==i[a]&&(t[a]=i[a]);return t}e.extend(e.ui,{datepicker:{version:"1.10.4"}});var n,r="datepicker";e.extend(i.prototype,{markerClassName:"hasDatepicker",maxRows:4,_widgetDatepicker:function(){return this.dpDiv},setDefaults:function(e){return s(this._defaults,e||{}),this},_attachDatepicker:function(t,i){var a,s,n;a=t.nodeName.toLowerCase(),s="div"===a||"span"===a,t.id||(this.uuid+=1,t.id="dp"+this.uuid),n=this._newInst(e(t),s),n.settings=e.extend({},i||{}),"input"===a?this._connectDatepicker(t,n):s&&this._inlineDatepicker(t,n)},_newInst:function(t,i){var s=t[0].id.replace(/([^A-Za-z0-9_\-])/g,"\\\\$1");return{id:s,input:t,selectedDay:0,selectedMonth:0,selectedYear:0,drawMonth:0,drawYear:0,inline:i,dpDiv:i?a(e("<div class='"+this._inlineClass+" ui-datepicker ui-widget ui-widget-content ui-helper-clearfix ui-corner-all'></div>")):this.dpDiv}},_connectDatepicker:function(t,i){var a=e(t);i.append=e([]),i.trigger=e([]),a.hasClass(this.markerClassName)||(this._attachments(a,i),a.addClass(this.markerClassName).keydown(this._doKeyDown).keypress(this._doKeyPress).keyup(this._doKeyUp),this._autoSize(i),e.data(t,r,i),i.settings.disabled&&this._disableDatepicker(t))},_attachments:function(t,i){var a,s,n,r=this._get(i,"appendText"),o=this._get(i,"isRTL");i.append&&i.append.remove(),r&&(i.append=e("<span class='"+this._appendClass+"'>"+r+"</span>"),t[o?"before":"after"](i.append)),t.unbind("focus",this._showDatepicker),i.trigger&&i.trigger.remove(),a=this._get(i,"showOn"),("focus"===a||"both"===a)&&t.focus(this._showDatepicker),("button"===a||"both"===a)&&(s=this._get(i,"buttonText"),n=this._get(i,"buttonImage"),i.trigger=e(this._get(i,"buttonImageOnly")?e("<img/>").addClass(this._triggerClass).attr({src:n,alt:s,title:s}):e("<button type='button'></button>").addClass(this._triggerClass).html(n?e("<img/>").attr({src:n,alt:s,title:s}):s)),t[o?"before":"after"](i.trigger),i.trigger.click(function(){return e.datepicker._datepickerShowing&&e.datepicker._lastInput===t[0]?e.datepicker._hideDatepicker():e.datepicker._datepickerShowing&&e.datepicker._lastInput!==t[0]?(e.datepicker._hideDatepicker(),e.datepicker._showDatepicker(t[0])):e.datepicker._showDatepicker(t[0]),!1}))},_autoSize:function(e){if(this._get(e,"autoSize")&&!e.inline){var t,i,a,s,n=new Date(2009,11,20),r=this._get(e,"dateFormat");r.match(/[DM]/)&&(t=function(e){for(i=0,a=0,s=0;e.length>s;s++)e[s].length>i&&(i=e[s].length,a=s);return a},n.setMonth(t(this._get(e,r.match(/MM/)?"monthNames":"monthNamesShort"))),n.setDate(t(this._get(e,r.match(/DD/)?"dayNames":"dayNamesShort"))+20-n.getDay())),e.input.attr("size",this._formatDate(e,n).length)}},_inlineDatepicker:function(t,i){var a=e(t);a.hasClass(this.markerClassName)||(a.addClass(this.markerClassName).append(i.dpDiv),e.data(t,r,i),this._setDate(i,this._getDefaultDate(i),!0),this._updateDatepicker(i),this._updateAlternate(i),i.settings.disabled&&this._disableDatepicker(t),i.dpDiv.css("display","block"))},_dialogDatepicker:function(t,i,a,n,o){var u,c,h,l,d,p=this._dialogInst;return p||(this.uuid+=1,u="dp"+this.uuid,this._dialogInput=e("<input type='text' id='"+u+"' style='position: absolute; top: -100px; width: 0px;'/>"),this._dialogInput.keydown(this._doKeyDown),e("body").append(this._dialogInput),p=this._dialogInst=this._newInst(this._dialogInput,!1),p.settings={},e.data(this._dialogInput[0],r,p)),s(p.settings,n||{}),i=i&&i.constructor===Date?this._formatDate(p,i):i,this._dialogInput.val(i),this._pos=o?o.length?o:[o.pageX,o.pageY]:null,this._pos||(c=document.documentElement.clientWidth,h=document.documentElement.clientHeight,l=document.documentElement.scrollLeft||document.body.scrollLeft,d=document.documentElement.scrollTop||document.body.scrollTop,this._pos=[c/2-100+l,h/2-150+d]),this._dialogInput.css("left",this._pos[0]+20+"px").css("top",this._pos[1]+"px"),p.settings.onSelect=a,this._inDialog=!0,this.dpDiv.addClass(this._dialogClass),this._showDatepicker(this._dialogInput[0]),e.blockUI&&e.blockUI(this.dpDiv),e.data(this._dialogInput[0],r,p),this},_destroyDatepicker:function(t){var i,a=e(t),s=e.data(t,r);a.hasClass(this.markerClassName)&&(i=t.nodeName.toLowerCase(),e.removeData(t,r),"input"===i?(s.append.remove(),s.trigger.remove(),a.removeClass(this.markerClassName).unbind("focus",this._showDatepicker).unbind("keydown",this._doKeyDown).unbind("keypress",this._doKeyPress).unbind("keyup",this._doKeyUp)):("div"===i||"span"===i)&&a.removeClass(this.markerClassName).empty())},_enableDatepicker:function(t){var i,a,s=e(t),n=e.data(t,r);s.hasClass(this.markerClassName)&&(i=t.nodeName.toLowerCase(),"input"===i?(t.disabled=!1,n.trigger.filter("button").each(function(){this.disabled=!1}).end().filter("img").css({opacity:"1.0",cursor:""})):("div"===i||"span"===i)&&(a=s.children("."+this._inlineClass),a.children().removeClass("ui-state-disabled"),a.find("select.ui-datepicker-month, select.ui-datepicker-year").prop("disabled",!1)),this._disabledInputs=e.map(this._disabledInputs,function(e){return e===t?null:e}))},_disableDatepicker:function(t){var i,a,s=e(t),n=e.data(t,r);s.hasClass(this.markerClassName)&&(i=t.nodeName.toLowerCase(),"input"===i?(t.disabled=!0,n.trigger.filter("button").each(function(){this.disabled=!0}).end().filter("img").css({opacity:"0.5",cursor:"default"})):("div"===i||"span"===i)&&(a=s.children("."+this._inlineClass),a.children().addClass("ui-state-disabled"),a.find("select.ui-datepicker-month, select.ui-datepicker-year").prop("disabled",!0)),this._disabledInputs=e.map(this._disabledInputs,function(e){return e===t?null:e}),this._disabledInputs[this._disabledInputs.length]=t)},_isDisabledDatepicker:function(e){if(!e)return!1;for(var t=0;this._disabledInputs.length>t;t++)if(this._disabledInputs[t]===e)return!0;return!1},_getInst:function(t){try{return e.data(t,r)}catch(i){throw"Missing instance data for this datepicker"}},_optionDatepicker:function(i,a,n){var r,o,u,c,h=this._getInst(i);return 2===arguments.length&&"string"==typeof a?"defaults"===a?e.extend({},e.datepicker._defaults):h?"all"===a?e.extend({},h.settings):this._get(h,a):null:(r=a||{},"string"==typeof a&&(r={},r[a]=n),h&&(this._curInst===h&&this._hideDatepicker(),o=this._getDateDatepicker(i,!0),u=this._getMinMaxDate(h,"min"),c=this._getMinMaxDate(h,"max"),s(h.settings,r),null!==u&&r.dateFormat!==t&&r.minDate===t&&(h.settings.minDate=this._formatDate(h,u)),null!==c&&r.dateFormat!==t&&r.maxDate===t&&(h.settings.maxDate=this._formatDate(h,c)),"disabled"in r&&(r.disabled?this._disableDatepicker(i):this._enableDatepicker(i)),this._attachments(e(i),h),this._autoSize(h),this._setDate(h,o),this._updateAlternate(h),this._updateDatepicker(h)),t)},_changeDatepicker:function(e,t,i){this._optionDatepicker(e,t,i)},_refreshDatepicker:function(e){var t=this._getInst(e);t&&this._updateDatepicker(t)},_setDateDatepicker:function(e,t){var i=this._getInst(e);i&&(this._setDate(i,t),this._updateDatepicker(i),this._updateAlternate(i))},_getDateDatepicker:function(e,t){var i=this._getInst(e);return i&&!i.inline&&this._setDateFromField(i,t),i?this._getDate(i):null},_doKeyDown:function(t){var i,a,s,n=e.datepicker._getInst(t.target),r=!0,o=n.dpDiv.is(".ui-datepicker-rtl");if(n._keyEvent=!0,e.datepicker._datepickerShowing)switch(t.keyCode){case 9:e.datepicker._hideDatepicker(),r=!1;break;case 13:return s=e("td."+e.datepicker._dayOverClass+":not(."+e.datepicker._currentClass+")",n.dpDiv),s[0]&&e.datepicker._selectDay(t.target,n.selectedMonth,n.selectedYear,s[0]),i=e.datepicker._get(n,"onSelect"),i?(a=e.datepicker._formatDate(n),i.apply(n.input?n.input[0]:null,[a,n])):e.datepicker._hideDatepicker(),!1;case 27:e.datepicker._hideDatepicker();break;case 33:e.datepicker._adjustDate(t.target,t.ctrlKey?-e.datepicker._get(n,"stepBigMonths"):-e.datepicker._get(n,"stepMonths"),"M");break;case 34:e.datepicker._adjustDate(t.target,t.ctrlKey?+e.datepicker._get(n,"stepBigMonths"):+e.datepicker._get(n,"stepMonths"),"M");break;case 35:(t.ctrlKey||t.metaKey)&&e.datepicker._clearDate(t.target),r=t.ctrlKey||t.metaKey;break;case 36:(t.ctrlKey||t.metaKey)&&e.datepicker._gotoToday(t.target),r=t.ctrlKey||t.metaKey;break;case 37:(t.ctrlKey||t.metaKey)&&e.datepicker._adjustDate(t.target,o?1:-1,"D"),r=t.ctrlKey||t.metaKey,t.originalEvent.altKey&&e.datepicker._adjustDate(t.target,t.ctrlKey?-e.datepicker._get(n,"stepBigMonths"):-e.datepicker._get(n,"stepMonths"),"M");break;case 38:(t.ctrlKey||t.metaKey)&&e.datepicker._adjustDate(t.target,-7,"D"),r=t.ctrlKey||t.metaKey;break;case 39:(t.ctrlKey||t.metaKey)&&e.datepicker._adjustDate(t.target,o?-1:1,"D"),r=t.ctrlKey||t.metaKey,t.originalEvent.altKey&&e.datepicker._adjustDate(t.target,t.ctrlKey?+e.datepicker._get(n,"stepBigMonths"):+e.datepicker._get(n,"stepMonths"),"M");break;case 40:(t.ctrlKey||t.metaKey)&&e.datepicker._adjustDate(t.target,7,"D"),r=t.ctrlKey||t.metaKey;break;default:r=!1}else 36===t.keyCode&&t.ctrlKey?e.datepicker._showDatepicker(this):r=!1;r&&(t.preventDefault(),t.stopPropagation())},_doKeyPress:function(i){var a,s,n=e.datepicker._getInst(i.target);return e.datepicker._get(n,"constrainInput")?(a=e.datepicker._possibleChars(e.datepicker._get(n,"dateFormat")),s=String.fromCharCode(null==i.charCode?i.keyCode:i.charCode),i.ctrlKey||i.metaKey||" ">s||!a||a.indexOf(s)>-1):t},_doKeyUp:function(t){var i,a=e.datepicker._getInst(t.target);if(a.input.val()!==a.lastVal)try{i=e.datepicker.parseDate(e.datepicker._get(a,"dateFormat"),a.input?a.input.val():null,e.datepicker._getFormatConfig(a)),i&&(e.datepicker._setDateFromField(a),e.datepicker._updateAlternate(a),e.datepicker._updateDatepicker(a))}catch(s){}return!0},_showDatepicker:function(t){if(t=t.target||t,"input"!==t.nodeName.toLowerCase()&&(t=e("input",t.parentNode)[0]),!e.datepicker._isDisabledDatepicker(t)&&e.datepicker._lastInput!==t){var i,a,n,r,o,u,c;i=e.datepicker._getInst(t),e.datepicker._curInst&&e.datepicker._curInst!==i&&(e.datepicker._curInst.dpDiv.stop(!0,!0),i&&e.datepicker._datepickerShowing&&e.datepicker._hideDatepicker(e.datepicker._curInst.input[0])),a=e.datepicker._get(i,"beforeShow"),n=a?a.apply(t,[t,i]):{},n!==!1&&(s(i.settings,n),i.lastVal=null,e.datepicker._lastInput=t,e.datepicker._setDateFromField(i),e.datepicker._inDialog&&(t.value=""),e.datepicker._pos||(e.datepicker._pos=e.datepicker._findPos(t),e.datepicker._pos[1]+=t.offsetHeight),r=!1,e(t).parents().each(function(){return r|="fixed"===e(this).css("position"),!r}),o={left:e.datepicker._pos[0],top:e.datepicker._pos[1]},e.datepicker._pos=null,i.dpDiv.empty(),i.dpDiv.css({position:"absolute",display:"block",top:"-1000px"}),e.datepicker._updateDatepicker(i),o=e.datepicker._checkOffset(i,o,r),i.dpDiv.css({position:e.datepicker._inDialog&&e.blockUI?"static":r?"fixed":"absolute",display:"none",left:o.left+"px",top:o.top+"px"}),i.inline||(u=e.datepicker._get(i,"showAnim"),c=e.datepicker._get(i,"duration"),i.dpDiv.zIndex(e(t).zIndex()+1),e.datepicker._datepickerShowing=!0,e.effects&&e.effects.effect[u]?i.dpDiv.show(u,e.datepicker._get(i,"showOptions"),c):i.dpDiv[u||"show"](u?c:null),e.datepicker._shouldFocusInput(i)&&i.input.focus(),e.datepicker._curInst=i))}},_updateDatepicker:function(t){this.maxRows=4,n=t,t.dpDiv.empty().append(this._generateHTML(t)),this._attachHandlers(t),t.dpDiv.find("."+this._dayOverClass+" a").mouseover();var i,a=this._getNumberOfMonths(t),s=a[1],r=17;t.dpDiv.removeClass("ui-datepicker-multi-2 ui-datepicker-multi-3 ui-datepicker-multi-4").width(""),s>1&&t.dpDiv.addClass("ui-datepicker-multi-"+s).css("width",r*s+"em"),t.dpDiv[(1!==a[0]||1!==a[1]?"add":"remove")+"Class"]("ui-datepicker-multi"),t.dpDiv[(this._get(t,"isRTL")?"add":"remove")+"Class"]("ui-datepicker-rtl"),t===e.datepicker._curInst&&e.datepicker._datepickerShowing&&e.datepicker._shouldFocusInput(t)&&t.input.focus(),t.yearshtml&&(i=t.yearshtml,setTimeout(function(){i===t.yearshtml&&t.yearshtml&&t.dpDiv.find("select.ui-datepicker-year:first").replaceWith(t.yearshtml),i=t.yearshtml=null},0))},_shouldFocusInput:function(e){return e.input&&e.input.is(":visible")&&!e.input.is(":disabled")&&!e.input.is(":focus")},_checkOffset:function(t,i,a){var s=t.dpDiv.outerWidth(),n=t.dpDiv.outerHeight(),r=t.input?t.input.outerWidth():0,o=t.input?t.input.outerHeight():0,u=document.documentElement.clientWidth+(a?0:e(document).scrollLeft()),c=document.documentElement.clientHeight+(a?0:e(document).scrollTop());return i.left-=this._get(t,"isRTL")?s-r:0,i.left-=a&&i.left===t.input.offset().left?e(document).scrollLeft():0,i.top-=a&&i.top===t.input.offset().top+o?e(document).scrollTop():0,i.left-=Math.min(i.left,i.left+s>u&&u>s?Math.abs(i.left+s-u):0),i.top-=Math.min(i.top,i.top+n>c&&c>n?Math.abs(n+o):0),i},_findPos:function(t){for(var i,a=this._getInst(t),s=this._get(a,"isRTL");t&&("hidden"===t.type||1!==t.nodeType||e.expr.filters.hidden(t));)t=t[s?"previousSibling":"nextSibling"];return i=e(t).offset(),[i.left,i.top]},_hideDatepicker:function(t){var i,a,s,n,o=this._curInst;!o||t&&o!==e.data(t,r)||this._datepickerShowing&&(i=this._get(o,"showAnim"),a=this._get(o,"duration"),s=function(){e.datepicker._tidyDialog(o)},e.effects&&(e.effects.effect[i]||e.effects[i])?o.dpDiv.hide(i,e.datepicker._get(o,"showOptions"),a,s):o.dpDiv["slideDown"===i?"slideUp":"fadeIn"===i?"fadeOut":"hide"](i?a:null,s),i||s(),this._datepickerShowing=!1,n=this._get(o,"onClose"),n&&n.apply(o.input?o.input[0]:null,[o.input?o.input.val():"",o]),this._lastInput=null,this._inDialog&&(this._dialogInput.css({position:"absolute",left:"0",top:"-100px"}),e.blockUI&&(e.unblockUI(),e("body").append(this.dpDiv))),this._inDialog=!1)},_tidyDialog:function(e){e.dpDiv.removeClass(this._dialogClass).unbind(".ui-datepicker-calendar")},_checkExternalClick:function(t){if(e.datepicker._curInst){var i=e(t.target),a=e.datepicker._getInst(i[0]);(i[0].id!==e.datepicker._mainDivId&&0===i.parents("#"+e.datepicker._mainDivId).length&&!i.hasClass(e.datepicker.markerClassName)&&!i.closest("."+e.datepicker._triggerClass).length&&e.datepicker._datepickerShowing&&(!e.datepicker._inDialog||!e.blockUI)||i.hasClass(e.datepicker.markerClassName)&&e.datepicker._curInst!==a)&&e.datepicker._hideDatepicker()}},_adjustDate:function(t,i,a){var s=e(t),n=this._getInst(s[0]);this._isDisabledDatepicker(s[0])||(this._adjustInstDate(n,i+("M"===a?this._get(n,"showCurrentAtPos"):0),a),this._updateDatepicker(n))},_gotoToday:function(t){var i,a=e(t),s=this._getInst(a[0]);this._get(s,"gotoCurrent")&&s.currentDay?(s.selectedDay=s.currentDay,s.drawMonth=s.selectedMonth=s.currentMonth,s.drawYear=s.selectedYear=s.currentYear):(i=new Date,s.selectedDay=i.getDate(),s.drawMonth=s.selectedMonth=i.getMonth(),s.drawYear=s.selectedYear=i.getFullYear()),this._notifyChange(s),this._adjustDate(a)},_selectMonthYear:function(t,i,a){var s=e(t),n=this._getInst(s[0]);n["selected"+("M"===a?"Month":"Year")]=n["draw"+("M"===a?"Month":"Year")]=parseInt(i.options[i.selectedIndex].value,10),this._notifyChange(n),this._adjustDate(s)},_selectDay:function(t,i,a,s){var n,r=e(t);e(s).hasClass(this._unselectableClass)||this._isDisabledDatepicker(r[0])||(n=this._getInst(r[0]),n.selectedDay=n.currentDay=e("a",s).html(),n.selectedMonth=n.currentMonth=i,n.selectedYear=n.currentYear=a,this._selectDate(t,this._formatDate(n,n.currentDay,n.currentMonth,n.currentYear)))},_clearDate:function(t){var i=e(t);this._selectDate(i,"")},_selectDate:function(t,i){var a,s=e(t),n=this._getInst(s[0]);i=null!=i?i:this._formatDate(n),n.input&&n.input.val(i),this._updateAlternate(n),a=this._get(n,"onSelect"),a?a.apply(n.input?n.input[0]:null,[i,n]):n.input&&n.input.trigger("change"),n.inline?this._updateDatepicker(n):(this._hideDatepicker(),this._lastInput=n.input[0],"object"!=typeof n.input[0]&&n.input.focus(),this._lastInput=null)},_updateAlternate:function(t){var i,a,s,n=this._get(t,"altField");n&&(i=this._get(t,"altFormat")||this._get(t,"dateFormat"),a=this._getDate(t),s=this.formatDate(i,a,this._getFormatConfig(t)),e(n).each(function(){e(this).val(s)}))},noWeekends:function(e){var t=e.getDay();return[t>0&&6>t,""]},iso8601Week:function(e){var t,i=new Date(e.getTime());return i.setDate(i.getDate()+4-(i.getDay()||7)),t=i.getTime(),i.setMonth(0),i.setDate(1),Math.floor(Math.round((t-i)/864e5)/7)+1},parseDate:function(i,a,s){if(null==i||null==a)throw"Invalid arguments";if(a="object"==typeof a?""+a:a+"",""===a)return null;var n,r,o,u,c=0,h=(s?s.shortYearCutoff:null)||this._defaults.shortYearCutoff,l="string"!=typeof h?h:(new Date).getFullYear()%100+parseInt(h,10),d=(s?s.dayNamesShort:null)||this._defaults.dayNamesShort,p=(s?s.dayNames:null)||this._defaults.dayNames,g=(s?s.monthNamesShort:null)||this._defaults.monthNamesShort,m=(s?s.monthNames:null)||this._defaults.monthNames,f=-1,_=-1,v=-1,k=-1,y=!1,b=function(e){var t=i.length>n+1&&i.charAt(n+1)===e;return t&&n++,t},D=function(e){var t=b(e),i="@"===e?14:"!"===e?20:"y"===e&&t?4:"o"===e?3:2,s=RegExp("^\\d{1,"+i+"}"),n=a.substring(c).match(s);if(!n)throw"Missing number at position "+c;return c+=n[0].length,parseInt(n[0],10)},w=function(i,s,n){var r=-1,o=e.map(b(i)?n:s,function(e,t){return[[t,e]]}).sort(function(e,t){return-(e[1].length-t[1].length)});if(e.each(o,function(e,i){var s=i[1];return a.substr(c,s.length).toLowerCase()===s.toLowerCase()?(r=i[0],c+=s.length,!1):t}),-1!==r)return r+1;throw"Unknown name at position "+c},M=function(){if(a.charAt(c)!==i.charAt(n))throw"Unexpected literal at position "+c;c++};for(n=0;i.length>n;n++)if(y)"'"!==i.charAt(n)||b("'")?M():y=!1;else switch(i.charAt(n)){case"d":v=D("d");break;case"D":w("D",d,p);break;case"o":k=D("o");break;case"m":_=D("m");break;case"M":_=w("M",g,m);break;case"y":f=D("y");break;case"@":u=new Date(D("@")),f=u.getFullYear(),_=u.getMonth()+1,v=u.getDate();break;case"!":u=new Date((D("!")-this._ticksTo1970)/1e4),f=u.getFullYear(),_=u.getMonth()+1,v=u.getDate();break;case"'":b("'")?M():y=!0;break;default:M()}if(a.length>c&&(o=a.substr(c),!/^\s+/.test(o)))throw"Extra/unparsed characters found in date: "+o;if(-1===f?f=(new Date).getFullYear():100>f&&(f+=(new Date).getFullYear()-(new Date).getFullYear()%100+(l>=f?0:-100)),k>-1)for(_=1,v=k;;){if(r=this._getDaysInMonth(f,_-1),r>=v)break;_++,v-=r}if(u=this._daylightSavingAdjust(new Date(f,_-1,v)),u.getFullYear()!==f||u.getMonth()+1!==_||u.getDate()!==v)throw"Invalid date";return u},ATOM:"yy-mm-dd",COOKIE:"D, dd M yy",ISO_8601:"yy-mm-dd",RFC_822:"D, d M y",RFC_850:"DD, dd-M-y",RFC_1036:"D, d M y",RFC_1123:"D, d M yy",RFC_2822:"D, d M yy",RSS:"D, d M y",TICKS:"!",TIMESTAMP:"@",W3C:"yy-mm-dd",_ticksTo1970:1e7*60*60*24*(718685+Math.floor(492.5)-Math.floor(19.7)+Math.floor(4.925)),formatDate:function(e,t,i){if(!t)return"";var a,s=(i?i.dayNamesShort:null)||this._defaults.dayNamesShort,n=(i?i.dayNames:null)||this._defaults.dayNames,r=(i?i.monthNamesShort:null)||this._defaults.monthNamesShort,o=(i?i.monthNames:null)||this._defaults.monthNames,u=function(t){var i=e.length>a+1&&e.charAt(a+1)===t;return i&&a++,i},c=function(e,t,i){var a=""+t;if(u(e))for(;i>a.length;)a="0"+a;return a},h=function(e,t,i,a){return u(e)?a[t]:i[t]},l="",d=!1;if(t)for(a=0;e.length>a;a++)if(d)"'"!==e.charAt(a)||u("'")?l+=e.charAt(a):d=!1;else switch(e.charAt(a)){case"d":l+=c("d",t.getDate(),2);break;case"D":l+=h("D",t.getDay(),s,n);break;case"o":l+=c("o",Math.round((new Date(t.getFullYear(),t.getMonth(),t.getDate()).getTime()-new Date(t.getFullYear(),0,0).getTime())/864e5),3);break;case"m":l+=c("m",t.getMonth()+1,2);break;case"M":l+=h("M",t.getMonth(),r,o);break;case"y":l+=u("y")?t.getFullYear():(10>t.getYear()%100?"0":"")+t.getYear()%100;break;case"@":l+=t.getTime();break;case"!":l+=1e4*t.getTime()+this._ticksTo1970;break;case"'":u("'")?l+="'":d=!0;break;default:l+=e.charAt(a)}return l},_possibleChars:function(e){var t,i="",a=!1,s=function(i){var a=e.length>t+1&&e.charAt(t+1)===i;return a&&t++,a};for(t=0;e.length>t;t++)if(a)"'"!==e.charAt(t)||s("'")?i+=e.charAt(t):a=!1;else switch(e.charAt(t)){case"d":case"m":case"y":case"@":i+="0123456789";break;case"D":case"M":return null;case"'":s("'")?i+="'":a=!0;break;default:i+=e.charAt(t)}return i},_get:function(e,i){return e.settings[i]!==t?e.settings[i]:this._defaults[i]},_setDateFromField:function(e,t){if(e.input.val()!==e.lastVal){var i=this._get(e,"dateFormat"),a=e.lastVal=e.input?e.input.val():null,s=this._getDefaultDate(e),n=s,r=this._getFormatConfig(e);try{n=this.parseDate(i,a,r)||s}catch(o){a=t?"":a}e.selectedDay=n.getDate(),e.drawMonth=e.selectedMonth=n.getMonth(),e.drawYear=e.selectedYear=n.getFullYear(),e.currentDay=a?n.getDate():0,e.currentMonth=a?n.getMonth():0,e.currentYear=a?n.getFullYear():0,this._adjustInstDate(e)}},_getDefaultDate:function(e){return this._restrictMinMax(e,this._determineDate(e,this._get(e,"defaultDate"),new Date))},_determineDate:function(t,i,a){var s=function(e){var t=new Date;return t.setDate(t.getDate()+e),t},n=function(i){try{return e.datepicker.parseDate(e.datepicker._get(t,"dateFormat"),i,e.datepicker._getFormatConfig(t))}catch(a){}for(var s=(i.toLowerCase().match(/^c/)?e.datepicker._getDate(t):null)||new Date,n=s.getFullYear(),r=s.getMonth(),o=s.getDate(),u=/([+\-]?[0-9]+)\s*(d|D|w|W|m|M|y|Y)?/g,c=u.exec(i);c;){switch(c[2]||"d"){case"d":case"D":o+=parseInt(c[1],10);break;case"w":case"W":o+=7*parseInt(c[1],10);break;case"m":case"M":r+=parseInt(c[1],10),o=Math.min(o,e.datepicker._getDaysInMonth(n,r));break;case"y":case"Y":n+=parseInt(c[1],10),o=Math.min(o,e.datepicker._getDaysInMonth(n,r))}c=u.exec(i)}return new Date(n,r,o)},r=null==i||""===i?a:"string"==typeof i?n(i):"number"==typeof i?isNaN(i)?a:s(i):new Date(i.getTime());return r=r&&"Invalid Date"==""+r?a:r,r&&(r.setHours(0),r.setMinutes(0),r.setSeconds(0),r.setMilliseconds(0)),this._daylightSavingAdjust(r)},_daylightSavingAdjust:function(e){return e?(e.setHours(e.getHours()>12?e.getHours()+2:0),e):null},_setDate:function(e,t,i){var a=!t,s=e.selectedMonth,n=e.selectedYear,r=this._restrictMinMax(e,this._determineDate(e,t,new Date));e.selectedDay=e.currentDay=r.getDate(),e.drawMonth=e.selectedMonth=e.currentMonth=r.getMonth(),e.drawYear=e.selectedYear=e.currentYear=r.getFullYear(),s===e.selectedMonth&&n===e.selectedYear||i||this._notifyChange(e),this._adjustInstDate(e),e.input&&e.input.val(a?"":this._formatDate(e))},_getDate:function(e){var t=!e.currentYear||e.input&&""===e.input.val()?null:this._daylightSavingAdjust(new Date(e.currentYear,e.currentMonth,e.currentDay));return t},_attachHandlers:function(t){var i=this._get(t,"stepMonths"),a="#"+t.id.replace(/\\\\/g,"\\");t.dpDiv.find("[data-handler]").map(function(){var t={prev:function(){e.datepicker._adjustDate(a,-i,"M")},next:function(){e.datepicker._adjustDate(a,+i,"M")},hide:function(){e.datepicker._hideDatepicker()},today:function(){e.datepicker._gotoToday(a)},selectDay:function(){return e.datepicker._selectDay(a,+this.getAttribute("data-month"),+this.getAttribute("data-year"),this),!1},selectMonth:function(){return e.datepicker._selectMonthYear(a,this,"M"),!1},selectYear:function(){return e.datepicker._selectMonthYear(a,this,"Y"),!1}};e(this).bind(this.getAttribute("data-event"),t[this.getAttribute("data-handler")])})},_generateHTML:function(e){var t,i,a,s,n,r,o,u,c,h,l,d,p,g,m,f,_,v,k,y,b,D,w,M,C,x,I,N,T,A,E,S,Y,F,P,O,j,K,R,H=new Date,W=this._daylightSavingAdjust(new Date(H.getFullYear(),H.getMonth(),H.getDate())),L=this._get(e,"isRTL"),U=this._get(e,"showButtonPanel"),B=this._get(e,"hideIfNoPrevNext"),z=this._get(e,"navigationAsDateFormat"),q=this._getNumberOfMonths(e),G=this._get(e,"showCurrentAtPos"),J=this._get(e,"stepMonths"),Q=1!==q[0]||1!==q[1],V=this._daylightSavingAdjust(e.currentDay?new Date(e.currentYear,e.currentMonth,e.currentDay):new Date(9999,9,9)),$=this._getMinMaxDate(e,"min"),X=this._getMinMaxDate(e,"max"),Z=e.drawMonth-G,et=e.drawYear;if(0>Z&&(Z+=12,et--),X)for(t=this._daylightSavingAdjust(new Date(X.getFullYear(),X.getMonth()-q[0]*q[1]+1,X.getDate())),t=$&&$>t?$:t;this._daylightSavingAdjust(new Date(et,Z,1))>t;)Z--,0>Z&&(Z=11,et--);for(e.drawMonth=Z,e.drawYear=et,i=this._get(e,"prevText"),i=z?this.formatDate(i,this._daylightSavingAdjust(new Date(et,Z-J,1)),this._getFormatConfig(e)):i,a=this._canAdjustMonth(e,-1,et,Z)?"<a class='ui-datepicker-prev ui-corner-all' data-handler='prev' data-event='click' title='"+i+"'><span class='ui-icon ui-icon-circle-triangle-"+(L?"e":"w")+"'>"+i+"</span></a>":B?"":"<a class='ui-datepicker-prev ui-corner-all ui-state-disabled' title='"+i+"'><span class='ui-icon ui-icon-circle-triangle-"+(L?"e":"w")+"'>"+i+"</span></a>",s=this._get(e,"nextText"),s=z?this.formatDate(s,this._daylightSavingAdjust(new Date(et,Z+J,1)),this._getFormatConfig(e)):s,n=this._canAdjustMonth(e,1,et,Z)?"<a class='ui-datepicker-next ui-corner-all' data-handler='next' data-event='click' title='"+s+"'><span class='ui-icon ui-icon-circle-triangle-"+(L?"w":"e")+"'>"+s+"</span></a>":B?"":"<a class='ui-datepicker-next ui-corner-all ui-state-disabled' title='"+s+"'><span class='ui-icon ui-icon-circle-triangle-"+(L?"w":"e")+"'>"+s+"</span></a>",r=this._get(e,"currentText"),o=this._get(e,"gotoCurrent")&&e.currentDay?V:W,r=z?this.formatDate(r,o,this._getFormatConfig(e)):r,u=e.inline?"":"<button type='button' class='ui-datepicker-close ui-state-default ui-priority-primary ui-corner-all' data-handler='hide' data-event='click'>"+this._get(e,"closeText")+"</button>",c=U?"<div class='ui-datepicker-buttonpane ui-widget-content'>"+(L?u:"")+(this._isInRange(e,o)?"<button type='button' class='ui-datepicker-current ui-state-default ui-priority-secondary ui-corner-all' data-handler='today' data-event='click'>"+r+"</button>":"")+(L?"":u)+"</div>":"",h=parseInt(this._get(e,"firstDay"),10),h=isNaN(h)?0:h,l=this._get(e,"showWeek"),d=this._get(e,"dayNames"),p=this._get(e,"dayNamesMin"),g=this._get(e,"monthNames"),m=this._get(e,"monthNamesShort"),f=this._get(e,"beforeShowDay"),_=this._get(e,"showOtherMonths"),v=this._get(e,"selectOtherMonths"),k=this._getDefaultDate(e),y="",D=0;q[0]>D;D++){for(w="",this.maxRows=4,M=0;q[1]>M;M++){if(C=this._daylightSavingAdjust(new Date(et,Z,e.selectedDay)),x=" ui-corner-all",I="",Q){if(I+="<div class='ui-datepicker-group",q[1]>1)switch(M){case 0:I+=" ui-datepicker-group-first",x=" ui-corner-"+(L?"right":"left");break;case q[1]-1:I+=" ui-datepicker-group-last",x=" ui-corner-"+(L?"left":"right");break;default:I+=" ui-datepicker-group-middle",x=""}I+="'>"}for(I+="<div class='ui-datepicker-header ui-widget-header ui-helper-clearfix"+x+"'>"+(/all|left/.test(x)&&0===D?L?n:a:"")+(/all|right/.test(x)&&0===D?L?a:n:"")+this._generateMonthYearHeader(e,Z,et,$,X,D>0||M>0,g,m)+"</div><table class='ui-datepicker-calendar'><thead>"+"<tr>",N=l?"<th class='ui-datepicker-week-col'>"+this._get(e,"weekHeader")+"</th>":"",b=0;7>b;b++)T=(b+h)%7,N+="<th"+((b+h+6)%7>=5?" class='ui-datepicker-week-end'":"")+">"+"<span title='"+d[T]+"'>"+p[T]+"</span></th>";for(I+=N+"</tr></thead><tbody>",A=this._getDaysInMonth(et,Z),et===e.selectedYear&&Z===e.selectedMonth&&(e.selectedDay=Math.min(e.selectedDay,A)),E=(this._getFirstDayOfMonth(et,Z)-h+7)%7,S=Math.ceil((E+A)/7),Y=Q?this.maxRows>S?this.maxRows:S:S,this.maxRows=Y,F=this._daylightSavingAdjust(new Date(et,Z,1-E)),P=0;Y>P;P++){for(I+="<tr>",O=l?"<td class='ui-datepicker-week-col'>"+this._get(e,"calculateWeek")(F)+"</td>":"",b=0;7>b;b++)j=f?f.apply(e.input?e.input[0]:null,[F]):[!0,""],K=F.getMonth()!==Z,R=K&&!v||!j[0]||$&&$>F||X&&F>X,O+="<td class='"+((b+h+6)%7>=5?" ui-datepicker-week-end":"")+(K?" ui-datepicker-other-month":"")+(F.getTime()===C.getTime()&&Z===e.selectedMonth&&e._keyEvent||k.getTime()===F.getTime()&&k.getTime()===C.getTime()?" "+this._dayOverClass:"")+(R?" "+this._unselectableClass+" ui-state-disabled":"")+(K&&!_?"":" "+j[1]+(F.getTime()===V.getTime()?" "+this._currentClass:"")+(F.getTime()===W.getTime()?" ui-datepicker-today":""))+"'"+(K&&!_||!j[2]?"":" title='"+j[2].replace(/'/g,"&#39;")+"'")+(R?"":" data-handler='selectDay' data-event='click' data-month='"+F.getMonth()+"' data-year='"+F.getFullYear()+"'")+">"+(K&&!_?"&#xa0;":R?"<span class='ui-state-default'>"+F.getDate()+"</span>":"<a class='ui-state-default"+(F.getTime()===W.getTime()?" ui-state-highlight":"")+(F.getTime()===V.getTime()?" ui-state-active":"")+(K?" ui-priority-secondary":"")+"' href='#'>"+F.getDate()+"</a>")+"</td>",F.setDate(F.getDate()+1),F=this._daylightSavingAdjust(F);I+=O+"</tr>"}Z++,Z>11&&(Z=0,et++),I+="</tbody></table>"+(Q?"</div>"+(q[0]>0&&M===q[1]-1?"<div class='ui-datepicker-row-break'></div>":""):""),w+=I}y+=w}return y+=c,e._keyEvent=!1,y},_generateMonthYearHeader:function(e,t,i,a,s,n,r,o){var u,c,h,l,d,p,g,m,f=this._get(e,"changeMonth"),_=this._get(e,"changeYear"),v=this._get(e,"showMonthAfterYear"),k="<div class='ui-datepicker-title'>",y="";if(n||!f)y+="<span class='ui-datepicker-month'>"+r[t]+"</span>";else{for(u=a&&a.getFullYear()===i,c=s&&s.getFullYear()===i,y+="<select class='ui-datepicker-month' data-handler='selectMonth' data-event='change'>",h=0;12>h;h++)(!u||h>=a.getMonth())&&(!c||s.getMonth()>=h)&&(y+="<option value='"+h+"'"+(h===t?" selected='selected'":"")+">"+o[h]+"</option>");y+="</select>"}if(v||(k+=y+(!n&&f&&_?"":"&#xa0;")),!e.yearshtml)if(e.yearshtml="",n||!_)k+="<span class='ui-datepicker-year'>"+i+"</span>";else{for(l=this._get(e,"yearRange").split(":"),d=(new Date).getFullYear(),p=function(e){var t=e.match(/c[+\-].*/)?i+parseInt(e.substring(1),10):e.match(/[+\-].*/)?d+parseInt(e,10):parseInt(e,10);
return isNaN(t)?d:t},g=p(l[0]),m=Math.max(g,p(l[1]||"")),g=a?Math.max(g,a.getFullYear()):g,m=s?Math.min(m,s.getFullYear()):m,e.yearshtml+="<select class='ui-datepicker-year' data-handler='selectYear' data-event='change'>";m>=g;g++)e.yearshtml+="<option value='"+g+"'"+(g===i?" selected='selected'":"")+">"+g+"</option>";e.yearshtml+="</select>",k+=e.yearshtml,e.yearshtml=null}return k+=this._get(e,"yearSuffix"),v&&(k+=(!n&&f&&_?"":"&#xa0;")+y),k+="</div>"},_adjustInstDate:function(e,t,i){var a=e.drawYear+("Y"===i?t:0),s=e.drawMonth+("M"===i?t:0),n=Math.min(e.selectedDay,this._getDaysInMonth(a,s))+("D"===i?t:0),r=this._restrictMinMax(e,this._daylightSavingAdjust(new Date(a,s,n)));e.selectedDay=r.getDate(),e.drawMonth=e.selectedMonth=r.getMonth(),e.drawYear=e.selectedYear=r.getFullYear(),("M"===i||"Y"===i)&&this._notifyChange(e)},_restrictMinMax:function(e,t){var i=this._getMinMaxDate(e,"min"),a=this._getMinMaxDate(e,"max"),s=i&&i>t?i:t;return a&&s>a?a:s},_notifyChange:function(e){var t=this._get(e,"onChangeMonthYear");t&&t.apply(e.input?e.input[0]:null,[e.selectedYear,e.selectedMonth+1,e])},_getNumberOfMonths:function(e){var t=this._get(e,"numberOfMonths");return null==t?[1,1]:"number"==typeof t?[1,t]:t},_getMinMaxDate:function(e,t){return this._determineDate(e,this._get(e,t+"Date"),null)},_getDaysInMonth:function(e,t){return 32-this._daylightSavingAdjust(new Date(e,t,32)).getDate()},_getFirstDayOfMonth:function(e,t){return new Date(e,t,1).getDay()},_canAdjustMonth:function(e,t,i,a){var s=this._getNumberOfMonths(e),n=this._daylightSavingAdjust(new Date(i,a+(0>t?t:s[0]*s[1]),1));return 0>t&&n.setDate(this._getDaysInMonth(n.getFullYear(),n.getMonth())),this._isInRange(e,n)},_isInRange:function(e,t){var i,a,s=this._getMinMaxDate(e,"min"),n=this._getMinMaxDate(e,"max"),r=null,o=null,u=this._get(e,"yearRange");return u&&(i=u.split(":"),a=(new Date).getFullYear(),r=parseInt(i[0],10),o=parseInt(i[1],10),i[0].match(/[+\-].*/)&&(r+=a),i[1].match(/[+\-].*/)&&(o+=a)),(!s||t.getTime()>=s.getTime())&&(!n||t.getTime()<=n.getTime())&&(!r||t.getFullYear()>=r)&&(!o||o>=t.getFullYear())},_getFormatConfig:function(e){var t=this._get(e,"shortYearCutoff");return t="string"!=typeof t?t:(new Date).getFullYear()%100+parseInt(t,10),{shortYearCutoff:t,dayNamesShort:this._get(e,"dayNamesShort"),dayNames:this._get(e,"dayNames"),monthNamesShort:this._get(e,"monthNamesShort"),monthNames:this._get(e,"monthNames")}},_formatDate:function(e,t,i,a){t||(e.currentDay=e.selectedDay,e.currentMonth=e.selectedMonth,e.currentYear=e.selectedYear);var s=t?"object"==typeof t?t:this._daylightSavingAdjust(new Date(a,i,t)):this._daylightSavingAdjust(new Date(e.currentYear,e.currentMonth,e.currentDay));return this.formatDate(this._get(e,"dateFormat"),s,this._getFormatConfig(e))}}),e.fn.datepicker=function(t){if(!this.length)return this;e.datepicker.initialized||(e(document).mousedown(e.datepicker._checkExternalClick),e.datepicker.initialized=!0),0===e("#"+e.datepicker._mainDivId).length&&e("body").append(e.datepicker.dpDiv);var i=Array.prototype.slice.call(arguments,1);return"string"!=typeof t||"isDisabled"!==t&&"getDate"!==t&&"widget"!==t?"option"===t&&2===arguments.length&&"string"==typeof arguments[1]?e.datepicker["_"+t+"Datepicker"].apply(e.datepicker,[this[0]].concat(i)):this.each(function(){"string"==typeof t?e.datepicker["_"+t+"Datepicker"].apply(e.datepicker,[this].concat(i)):e.datepicker._attachDatepicker(this,t)}):e.datepicker["_"+t+"Datepicker"].apply(e.datepicker,[this[0]].concat(i))},e.datepicker=new i,e.datepicker.initialized=!1,e.datepicker.uuid=(new Date).getTime(),e.datepicker.version="1.10.4"})(jQuery);(function(e){var t={buttons:!0,height:!0,maxHeight:!0,maxWidth:!0,minHeight:!0,minWidth:!0,width:!0},i={maxHeight:!0,maxWidth:!0,minHeight:!0,minWidth:!0};e.widget("ui.dialog",{version:"1.10.4",options:{appendTo:"body",autoOpen:!0,buttons:[],closeOnEscape:!0,closeText:"close",dialogClass:"",draggable:!0,hide:null,height:"auto",maxHeight:null,maxWidth:null,minHeight:150,minWidth:150,modal:!1,position:{my:"center",at:"center",of:window,collision:"fit",using:function(t){var i=e(this).css(t).offset().top;0>i&&e(this).css("top",t.top-i)}},resizable:!0,show:null,title:null,width:300,beforeClose:null,close:null,drag:null,dragStart:null,dragStop:null,focus:null,open:null,resize:null,resizeStart:null,resizeStop:null},_create:function(){this.originalCss={display:this.element[0].style.display,width:this.element[0].style.width,minHeight:this.element[0].style.minHeight,maxHeight:this.element[0].style.maxHeight,height:this.element[0].style.height},this.originalPosition={parent:this.element.parent(),index:this.element.parent().children().index(this.element)},this.originalTitle=this.element.attr("title"),this.options.title=this.options.title||this.originalTitle,this._createWrapper(),this.element.show().removeAttr("title").addClass("ui-dialog-content ui-widget-content").appendTo(this.uiDialog),this._createTitlebar(),this._createButtonPane(),this.options.draggable&&e.fn.draggable&&this._makeDraggable(),this.options.resizable&&e.fn.resizable&&this._makeResizable(),this._isOpen=!1},_init:function(){this.options.autoOpen&&this.open()},_appendTo:function(){var t=this.options.appendTo;return t&&(t.jquery||t.nodeType)?e(t):this.document.find(t||"body").eq(0)},_destroy:function(){var e,t=this.originalPosition;this._destroyOverlay(),this.element.removeUniqueId().removeClass("ui-dialog-content ui-widget-content").css(this.originalCss).detach(),this.uiDialog.stop(!0,!0).remove(),this.originalTitle&&this.element.attr("title",this.originalTitle),e=t.parent.children().eq(t.index),e.length&&e[0]!==this.element[0]?e.before(this.element):t.parent.append(this.element)},widget:function(){return this.uiDialog},disable:e.noop,enable:e.noop,close:function(t){var i,a=this;if(this._isOpen&&this._trigger("beforeClose",t)!==!1){if(this._isOpen=!1,this._destroyOverlay(),!this.opener.filter(":focusable").focus().length)try{i=this.document[0].activeElement,i&&"body"!==i.nodeName.toLowerCase()&&e(i).blur()}catch(s){}this._hide(this.uiDialog,this.options.hide,function(){a._trigger("close",t)})}},isOpen:function(){return this._isOpen},moveToTop:function(){this._moveToTop()},_moveToTop:function(e,t){var i=!!this.uiDialog.nextAll(":visible").insertBefore(this.uiDialog).length;return i&&!t&&this._trigger("focus",e),i},open:function(){var t=this;return this._isOpen?(this._moveToTop()&&this._focusTabbable(),undefined):(this._isOpen=!0,this.opener=e(this.document[0].activeElement),this._size(),this._position(),this._createOverlay(),this._moveToTop(null,!0),this._show(this.uiDialog,this.options.show,function(){t._focusTabbable(),t._trigger("focus")}),this._trigger("open"),undefined)},_focusTabbable:function(){var e=this.element.find("[autofocus]");e.length||(e=this.element.find(":tabbable")),e.length||(e=this.uiDialogButtonPane.find(":tabbable")),e.length||(e=this.uiDialogTitlebarClose.filter(":tabbable")),e.length||(e=this.uiDialog),e.eq(0).focus()},_keepFocus:function(t){function i(){var t=this.document[0].activeElement,i=this.uiDialog[0]===t||e.contains(this.uiDialog[0],t);i||this._focusTabbable()}t.preventDefault(),i.call(this),this._delay(i)},_createWrapper:function(){this.uiDialog=e("<div>").addClass("ui-dialog ui-widget ui-widget-content ui-corner-all ui-front "+this.options.dialogClass).hide().attr({tabIndex:-1,role:"dialog"}).appendTo(this._appendTo()),this._on(this.uiDialog,{keydown:function(t){if(this.options.closeOnEscape&&!t.isDefaultPrevented()&&t.keyCode&&t.keyCode===e.ui.keyCode.ESCAPE)return t.preventDefault(),this.close(t),undefined;if(t.keyCode===e.ui.keyCode.TAB){var i=this.uiDialog.find(":tabbable"),a=i.filter(":first"),s=i.filter(":last");t.target!==s[0]&&t.target!==this.uiDialog[0]||t.shiftKey?t.target!==a[0]&&t.target!==this.uiDialog[0]||!t.shiftKey||(s.focus(1),t.preventDefault()):(a.focus(1),t.preventDefault())}},mousedown:function(e){this._moveToTop(e)&&this._focusTabbable()}}),this.element.find("[aria-describedby]").length||this.uiDialog.attr({"aria-describedby":this.element.uniqueId().attr("id")})},_createTitlebar:function(){var t;this.uiDialogTitlebar=e("<div>").addClass("ui-dialog-titlebar ui-widget-header ui-corner-all ui-helper-clearfix").prependTo(this.uiDialog),this._on(this.uiDialogTitlebar,{mousedown:function(t){e(t.target).closest(".ui-dialog-titlebar-close")||this.uiDialog.focus()}}),this.uiDialogTitlebarClose=e("<button type='button'></button>").button({label:this.options.closeText,icons:{primary:"ui-icon-closethick"},text:!1}).addClass("ui-dialog-titlebar-close").appendTo(this.uiDialogTitlebar),this._on(this.uiDialogTitlebarClose,{click:function(e){e.preventDefault(),this.close(e)}}),t=e("<span>").uniqueId().addClass("ui-dialog-title").prependTo(this.uiDialogTitlebar),this._title(t),this.uiDialog.attr({"aria-labelledby":t.attr("id")})},_title:function(e){this.options.title||e.html("&#160;"),e.text(this.options.title)},_createButtonPane:function(){this.uiDialogButtonPane=e("<div>").addClass("ui-dialog-buttonpane ui-widget-content ui-helper-clearfix"),this.uiButtonSet=e("<div>").addClass("ui-dialog-buttonset").appendTo(this.uiDialogButtonPane),this._createButtons()},_createButtons:function(){var t=this,i=this.options.buttons;return this.uiDialogButtonPane.remove(),this.uiButtonSet.empty(),e.isEmptyObject(i)||e.isArray(i)&&!i.length?(this.uiDialog.removeClass("ui-dialog-buttons"),undefined):(e.each(i,function(i,a){var s,n;a=e.isFunction(a)?{click:a,text:i}:a,a=e.extend({type:"button"},a),s=a.click,a.click=function(){s.apply(t.element[0],arguments)},n={icons:a.icons,text:a.showText},delete a.icons,delete a.showText,e("<button></button>",a).button(n).appendTo(t.uiButtonSet)}),this.uiDialog.addClass("ui-dialog-buttons"),this.uiDialogButtonPane.appendTo(this.uiDialog),undefined)},_makeDraggable:function(){function t(e){return{position:e.position,offset:e.offset}}var i=this,a=this.options;this.uiDialog.draggable({cancel:".ui-dialog-content, .ui-dialog-titlebar-close",handle:".ui-dialog-titlebar",containment:"document",start:function(a,s){e(this).addClass("ui-dialog-dragging"),i._blockFrames(),i._trigger("dragStart",a,t(s))},drag:function(e,a){i._trigger("drag",e,t(a))},stop:function(s,n){a.position=[n.position.left-i.document.scrollLeft(),n.position.top-i.document.scrollTop()],e(this).removeClass("ui-dialog-dragging"),i._unblockFrames(),i._trigger("dragStop",s,t(n))}})},_makeResizable:function(){function t(e){return{originalPosition:e.originalPosition,originalSize:e.originalSize,position:e.position,size:e.size}}var i=this,a=this.options,s=a.resizable,n=this.uiDialog.css("position"),r="string"==typeof s?s:"n,e,s,w,se,sw,ne,nw";this.uiDialog.resizable({cancel:".ui-dialog-content",containment:"document",alsoResize:this.element,maxWidth:a.maxWidth,maxHeight:a.maxHeight,minWidth:a.minWidth,minHeight:this._minHeight(),handles:r,start:function(a,s){e(this).addClass("ui-dialog-resizing"),i._blockFrames(),i._trigger("resizeStart",a,t(s))},resize:function(e,a){i._trigger("resize",e,t(a))},stop:function(s,n){a.height=e(this).height(),a.width=e(this).width(),e(this).removeClass("ui-dialog-resizing"),i._unblockFrames(),i._trigger("resizeStop",s,t(n))}}).css("position",n)},_minHeight:function(){var e=this.options;return"auto"===e.height?e.minHeight:Math.min(e.minHeight,e.height)},_position:function(){var e=this.uiDialog.is(":visible");e||this.uiDialog.show(),this.uiDialog.position(this.options.position),e||this.uiDialog.hide()},_setOptions:function(a){var s=this,n=!1,r={};e.each(a,function(e,a){s._setOption(e,a),e in t&&(n=!0),e in i&&(r[e]=a)}),n&&(this._size(),this._position()),this.uiDialog.is(":data(ui-resizable)")&&this.uiDialog.resizable("option",r)},_setOption:function(e,t){var i,a,s=this.uiDialog;"dialogClass"===e&&s.removeClass(this.options.dialogClass).addClass(t),"disabled"!==e&&(this._super(e,t),"appendTo"===e&&this.uiDialog.appendTo(this._appendTo()),"buttons"===e&&this._createButtons(),"closeText"===e&&this.uiDialogTitlebarClose.button({label:""+t}),"draggable"===e&&(i=s.is(":data(ui-draggable)"),i&&!t&&s.draggable("destroy"),!i&&t&&this._makeDraggable()),"position"===e&&this._position(),"resizable"===e&&(a=s.is(":data(ui-resizable)"),a&&!t&&s.resizable("destroy"),a&&"string"==typeof t&&s.resizable("option","handles",t),a||t===!1||this._makeResizable()),"title"===e&&this._title(this.uiDialogTitlebar.find(".ui-dialog-title")))},_size:function(){var e,t,i,a=this.options;this.element.show().css({width:"auto",minHeight:0,maxHeight:"none",height:0}),a.minWidth>a.width&&(a.width=a.minWidth),e=this.uiDialog.css({height:"auto",width:a.width}).outerHeight(),t=Math.max(0,a.minHeight-e),i="number"==typeof a.maxHeight?Math.max(0,a.maxHeight-e):"none","auto"===a.height?this.element.css({minHeight:t,maxHeight:i,height:"auto"}):this.element.height(Math.max(0,a.height-e)),this.uiDialog.is(":data(ui-resizable)")&&this.uiDialog.resizable("option","minHeight",this._minHeight())},_blockFrames:function(){this.iframeBlocks=this.document.find("iframe").map(function(){var t=e(this);return e("<div>").css({position:"absolute",width:t.outerWidth(),height:t.outerHeight()}).appendTo(t.parent()).offset(t.offset())[0]})},_unblockFrames:function(){this.iframeBlocks&&(this.iframeBlocks.remove(),delete this.iframeBlocks)},_allowInteraction:function(t){return e(t.target).closest(".ui-dialog").length?!0:!!e(t.target).closest(".ui-datepicker").length},_createOverlay:function(){if(this.options.modal){var t=this,i=this.widgetFullName;e.ui.dialog.overlayInstances||this._delay(function(){e.ui.dialog.overlayInstances&&this.document.bind("focusin.dialog",function(a){t._allowInteraction(a)||(a.preventDefault(),e(".ui-dialog:visible:last .ui-dialog-content").data(i)._focusTabbable())})}),this.overlay=e("<div>").addClass("ui-widget-overlay ui-front").appendTo(this._appendTo()),this._on(this.overlay,{mousedown:"_keepFocus"}),e.ui.dialog.overlayInstances++}},_destroyOverlay:function(){this.options.modal&&this.overlay&&(e.ui.dialog.overlayInstances--,e.ui.dialog.overlayInstances||this.document.unbind("focusin.dialog"),this.overlay.remove(),this.overlay=null)}}),e.ui.dialog.overlayInstances=0,e.uiBackCompat!==!1&&e.widget("ui.dialog",e.ui.dialog,{_position:function(){var t,i=this.options.position,a=[],s=[0,0];i?(("string"==typeof i||"object"==typeof i&&"0"in i)&&(a=i.split?i.split(" "):[i[0],i[1]],1===a.length&&(a[1]=a[0]),e.each(["left","top"],function(e,t){+a[e]===a[e]&&(s[e]=a[e],a[e]=t)}),i={my:a[0]+(0>s[0]?s[0]:"+"+s[0])+" "+a[1]+(0>s[1]?s[1]:"+"+s[1]),at:a.join(" ")}),i=e.extend({},e.ui.dialog.prototype.options.position,i)):i=e.ui.dialog.prototype.options.position,t=this.uiDialog.is(":visible"),t||this.uiDialog.show(),this.uiDialog.position(i),t||this.uiDialog.hide()}})})(jQuery);(function(t){t.widget("ui.menu",{version:"1.10.4",defaultElement:"<ul>",delay:300,options:{icons:{submenu:"ui-icon-carat-1-e"},menus:"ul",position:{my:"left top",at:"right top"},role:"menu",blur:null,focus:null,select:null},_create:function(){this.activeMenu=this.element,this.mouseHandled=!1,this.element.uniqueId().addClass("ui-menu ui-widget ui-widget-content ui-corner-all").toggleClass("ui-menu-icons",!!this.element.find(".ui-icon").length).attr({role:this.options.role,tabIndex:0}).bind("click"+this.eventNamespace,t.proxy(function(t){this.options.disabled&&t.preventDefault()},this)),this.options.disabled&&this.element.addClass("ui-state-disabled").attr("aria-disabled","true"),this._on({"mousedown .ui-menu-item > a":function(t){t.preventDefault()},"click .ui-state-disabled > a":function(t){t.preventDefault()},"click .ui-menu-item:has(a)":function(e){var i=t(e.target).closest(".ui-menu-item");!this.mouseHandled&&i.not(".ui-state-disabled").length&&(this.select(e),e.isPropagationStopped()||(this.mouseHandled=!0),i.has(".ui-menu").length?this.expand(e):!this.element.is(":focus")&&t(this.document[0].activeElement).closest(".ui-menu").length&&(this.element.trigger("focus",[!0]),this.active&&1===this.active.parents(".ui-menu").length&&clearTimeout(this.timer)))},"mouseenter .ui-menu-item":function(e){var i=t(e.currentTarget);i.siblings().children(".ui-state-active").removeClass("ui-state-active"),this.focus(e,i)},mouseleave:"collapseAll","mouseleave .ui-menu":"collapseAll",focus:function(t,e){var i=this.active||this.element.children(".ui-menu-item").eq(0);e||this.focus(t,i)},blur:function(e){this._delay(function(){t.contains(this.element[0],this.document[0].activeElement)||this.collapseAll(e)})},keydown:"_keydown"}),this.refresh(),this._on(this.document,{click:function(e){t(e.target).closest(".ui-menu").length||this.collapseAll(e),this.mouseHandled=!1}})},_destroy:function(){this.element.removeAttr("aria-activedescendant").find(".ui-menu").addBack().removeClass("ui-menu ui-widget ui-widget-content ui-corner-all ui-menu-icons").removeAttr("role").removeAttr("tabIndex").removeAttr("aria-labelledby").removeAttr("aria-expanded").removeAttr("aria-hidden").removeAttr("aria-disabled").removeUniqueId().show(),this.element.find(".ui-menu-item").removeClass("ui-menu-item").removeAttr("role").removeAttr("aria-disabled").children("a").removeUniqueId().removeClass("ui-corner-all ui-state-hover").removeAttr("tabIndex").removeAttr("role").removeAttr("aria-haspopup").children().each(function(){var e=t(this);e.data("ui-menu-submenu-carat")&&e.remove()}),this.element.find(".ui-menu-divider").removeClass("ui-menu-divider ui-widget-content")},_keydown:function(e){function i(t){return t.replace(/[\-\[\]{}()*+?.,\\\^$|#\s]/g,"\\$&")}var s,n,a,o,r,l=!0;switch(e.keyCode){case t.ui.keyCode.PAGE_UP:this.previousPage(e);break;case t.ui.keyCode.PAGE_DOWN:this.nextPage(e);break;case t.ui.keyCode.HOME:this._move("first","first",e);break;case t.ui.keyCode.END:this._move("last","last",e);break;case t.ui.keyCode.UP:this.previous(e);break;case t.ui.keyCode.DOWN:this.next(e);break;case t.ui.keyCode.LEFT:this.collapse(e);break;case t.ui.keyCode.RIGHT:this.active&&!this.active.is(".ui-state-disabled")&&this.expand(e);break;case t.ui.keyCode.ENTER:case t.ui.keyCode.SPACE:this._activate(e);break;case t.ui.keyCode.ESCAPE:this.collapse(e);break;default:l=!1,n=this.previousFilter||"",a=String.fromCharCode(e.keyCode),o=!1,clearTimeout(this.filterTimer),a===n?o=!0:a=n+a,r=RegExp("^"+i(a),"i"),s=this.activeMenu.children(".ui-menu-item").filter(function(){return r.test(t(this).children("a").text())}),s=o&&-1!==s.index(this.active.next())?this.active.nextAll(".ui-menu-item"):s,s.length||(a=String.fromCharCode(e.keyCode),r=RegExp("^"+i(a),"i"),s=this.activeMenu.children(".ui-menu-item").filter(function(){return r.test(t(this).children("a").text())})),s.length?(this.focus(e,s),s.length>1?(this.previousFilter=a,this.filterTimer=this._delay(function(){delete this.previousFilter},1e3)):delete this.previousFilter):delete this.previousFilter}l&&e.preventDefault()},_activate:function(t){this.active.is(".ui-state-disabled")||(this.active.children("a[aria-haspopup='true']").length?this.expand(t):this.select(t))},refresh:function(){var e,i=this.options.icons.submenu,s=this.element.find(this.options.menus);this.element.toggleClass("ui-menu-icons",!!this.element.find(".ui-icon").length),s.filter(":not(.ui-menu)").addClass("ui-menu ui-widget ui-widget-content ui-corner-all").hide().attr({role:this.options.role,"aria-hidden":"true","aria-expanded":"false"}).each(function(){var e=t(this),s=e.prev("a"),n=t("<span>").addClass("ui-menu-icon ui-icon "+i).data("ui-menu-submenu-carat",!0);s.attr("aria-haspopup","true").prepend(n),e.attr("aria-labelledby",s.attr("id"))}),e=s.add(this.element),e.children(":not(.ui-menu-item):has(a)").addClass("ui-menu-item").attr("role","presentation").children("a").uniqueId().addClass("ui-corner-all").attr({tabIndex:-1,role:this._itemRole()}),e.children(":not(.ui-menu-item)").each(function(){var e=t(this);/[^\-\u2014\u2013\s]/.test(e.text())||e.addClass("ui-widget-content ui-menu-divider")}),e.children(".ui-state-disabled").attr("aria-disabled","true"),this.active&&!t.contains(this.element[0],this.active[0])&&this.blur()},_itemRole:function(){return{menu:"menuitem",listbox:"option"}[this.options.role]},_setOption:function(t,e){"icons"===t&&this.element.find(".ui-menu-icon").removeClass(this.options.icons.submenu).addClass(e.submenu),this._super(t,e)},focus:function(t,e){var i,s;this.blur(t,t&&"focus"===t.type),this._scrollIntoView(e),this.active=e.first(),s=this.active.children("a").addClass("ui-state-focus"),this.options.role&&this.element.attr("aria-activedescendant",s.attr("id")),this.active.parent().closest(".ui-menu-item").children("a:first").addClass("ui-state-active"),t&&"keydown"===t.type?this._close():this.timer=this._delay(function(){this._close()},this.delay),i=e.children(".ui-menu"),i.length&&t&&/^mouse/.test(t.type)&&this._startOpening(i),this.activeMenu=e.parent(),this._trigger("focus",t,{item:e})},_scrollIntoView:function(e){var i,s,n,a,o,r;this._hasScroll()&&(i=parseFloat(t.css(this.activeMenu[0],"borderTopWidth"))||0,s=parseFloat(t.css(this.activeMenu[0],"paddingTop"))||0,n=e.offset().top-this.activeMenu.offset().top-i-s,a=this.activeMenu.scrollTop(),o=this.activeMenu.height(),r=e.height(),0>n?this.activeMenu.scrollTop(a+n):n+r>o&&this.activeMenu.scrollTop(a+n-o+r))},blur:function(t,e){e||clearTimeout(this.timer),this.active&&(this.active.children("a").removeClass("ui-state-focus"),this.active=null,this._trigger("blur",t,{item:this.active}))},_startOpening:function(t){clearTimeout(this.timer),"true"===t.attr("aria-hidden")&&(this.timer=this._delay(function(){this._close(),this._open(t)},this.delay))},_open:function(e){var i=t.extend({of:this.active},this.options.position);clearTimeout(this.timer),this.element.find(".ui-menu").not(e.parents(".ui-menu")).hide().attr("aria-hidden","true"),e.show().removeAttr("aria-hidden").attr("aria-expanded","true").position(i)},collapseAll:function(e,i){clearTimeout(this.timer),this.timer=this._delay(function(){var s=i?this.element:t(e&&e.target).closest(this.element.find(".ui-menu"));s.length||(s=this.element),this._close(s),this.blur(e),this.activeMenu=s},this.delay)},_close:function(t){t||(t=this.active?this.active.parent():this.element),t.find(".ui-menu").hide().attr("aria-hidden","true").attr("aria-expanded","false").end().find("a.ui-state-active").removeClass("ui-state-active")},collapse:function(t){var e=this.active&&this.active.parent().closest(".ui-menu-item",this.element);e&&e.length&&(this._close(),this.focus(t,e))},expand:function(t){var e=this.active&&this.active.children(".ui-menu ").children(".ui-menu-item").first();e&&e.length&&(this._open(e.parent()),this._delay(function(){this.focus(t,e)}))},next:function(t){this._move("next","first",t)},previous:function(t){this._move("prev","last",t)},isFirstItem:function(){return this.active&&!this.active.prevAll(".ui-menu-item").length},isLastItem:function(){return this.active&&!this.active.nextAll(".ui-menu-item").length},_move:function(t,e,i){var s;this.active&&(s="first"===t||"last"===t?this.active["first"===t?"prevAll":"nextAll"](".ui-menu-item").eq(-1):this.active[t+"All"](".ui-menu-item").eq(0)),s&&s.length&&this.active||(s=this.activeMenu.children(".ui-menu-item")[e]()),this.focus(i,s)},nextPage:function(e){var i,s,n;return this.active?(this.isLastItem()||(this._hasScroll()?(s=this.active.offset().top,n=this.element.height(),this.active.nextAll(".ui-menu-item").each(function(){return i=t(this),0>i.offset().top-s-n}),this.focus(e,i)):this.focus(e,this.activeMenu.children(".ui-menu-item")[this.active?"last":"first"]())),undefined):(this.next(e),undefined)},previousPage:function(e){var i,s,n;return this.active?(this.isFirstItem()||(this._hasScroll()?(s=this.active.offset().top,n=this.element.height(),this.active.prevAll(".ui-menu-item").each(function(){return i=t(this),i.offset().top-s+n>0}),this.focus(e,i)):this.focus(e,this.activeMenu.children(".ui-menu-item").first())),undefined):(this.next(e),undefined)},_hasScroll:function(){return this.element.outerHeight()<this.element.prop("scrollHeight")},select:function(e){this.active=this.active||t(e.target).closest(".ui-menu-item");var i={item:this.active};this.active.has(".ui-menu").length||this.collapseAll(e,!0),this._trigger("select",e,i)}})})(jQuery);(function(t,e){t.widget("ui.progressbar",{version:"1.10.4",options:{max:100,value:0,change:null,complete:null},min:0,_create:function(){this.oldValue=this.options.value=this._constrainedValue(),this.element.addClass("ui-progressbar ui-widget ui-widget-content ui-corner-all").attr({role:"progressbar","aria-valuemin":this.min}),this.valueDiv=t("<div class='ui-progressbar-value ui-widget-header ui-corner-left'></div>").appendTo(this.element),this._refreshValue()},_destroy:function(){this.element.removeClass("ui-progressbar ui-widget ui-widget-content ui-corner-all").removeAttr("role").removeAttr("aria-valuemin").removeAttr("aria-valuemax").removeAttr("aria-valuenow"),this.valueDiv.remove()},value:function(t){return t===e?this.options.value:(this.options.value=this._constrainedValue(t),this._refreshValue(),e)},_constrainedValue:function(t){return t===e&&(t=this.options.value),this.indeterminate=t===!1,"number"!=typeof t&&(t=0),this.indeterminate?!1:Math.min(this.options.max,Math.max(this.min,t))},_setOptions:function(t){var e=t.value;delete t.value,this._super(t),this.options.value=this._constrainedValue(e),this._refreshValue()},_setOption:function(t,e){"max"===t&&(e=Math.max(this.min,e)),this._super(t,e)},_percentage:function(){return this.indeterminate?100:100*(this.options.value-this.min)/(this.options.max-this.min)},_refreshValue:function(){var e=this.options.value,i=this._percentage();this.valueDiv.toggle(this.indeterminate||e>this.min).toggleClass("ui-corner-right",e===this.options.max).width(i.toFixed(0)+"%"),this.element.toggleClass("ui-progressbar-indeterminate",this.indeterminate),this.indeterminate?(this.element.removeAttr("aria-valuenow"),this.overlayDiv||(this.overlayDiv=t("<div class='ui-progressbar-overlay'></div>").appendTo(this.valueDiv))):(this.element.attr({"aria-valuemax":this.options.max,"aria-valuenow":e}),this.overlayDiv&&(this.overlayDiv.remove(),this.overlayDiv=null)),this.oldValue!==e&&(this.oldValue=e,this._trigger("change")),e===this.options.max&&this._trigger("complete")}})})(jQuery);(function(t){var e=5;t.widget("ui.slider",t.ui.mouse,{version:"1.10.4",widgetEventPrefix:"slide",options:{animate:!1,distance:0,max:100,min:0,orientation:"horizontal",range:!1,step:1,value:0,values:null,change:null,slide:null,start:null,stop:null},_create:function(){this._keySliding=!1,this._mouseSliding=!1,this._animateOff=!0,this._handleIndex=null,this._detectOrientation(),this._mouseInit(),this.element.addClass("ui-slider ui-slider-"+this.orientation+" ui-widget"+" ui-widget-content"+" ui-corner-all"),this._refresh(),this._setOption("disabled",this.options.disabled),this._animateOff=!1},_refresh:function(){this._createRange(),this._createHandles(),this._setupEvents(),this._refreshValue()},_createHandles:function(){var e,i,s=this.options,n=this.element.find(".ui-slider-handle").addClass("ui-state-default ui-corner-all"),a="<a class='ui-slider-handle ui-state-default ui-corner-all' href='#'></a>",o=[];for(i=s.values&&s.values.length||1,n.length>i&&(n.slice(i).remove(),n=n.slice(0,i)),e=n.length;i>e;e++)o.push(a);this.handles=n.add(t(o.join("")).appendTo(this.element)),this.handle=this.handles.eq(0),this.handles.each(function(e){t(this).data("ui-slider-handle-index",e)})},_createRange:function(){var e=this.options,i="";e.range?(e.range===!0&&(e.values?e.values.length&&2!==e.values.length?e.values=[e.values[0],e.values[0]]:t.isArray(e.values)&&(e.values=e.values.slice(0)):e.values=[this._valueMin(),this._valueMin()]),this.range&&this.range.length?this.range.removeClass("ui-slider-range-min ui-slider-range-max").css({left:"",bottom:""}):(this.range=t("<div></div>").appendTo(this.element),i="ui-slider-range ui-widget-header ui-corner-all"),this.range.addClass(i+("min"===e.range||"max"===e.range?" ui-slider-range-"+e.range:""))):(this.range&&this.range.remove(),this.range=null)},_setupEvents:function(){var t=this.handles.add(this.range).filter("a");this._off(t),this._on(t,this._handleEvents),this._hoverable(t),this._focusable(t)},_destroy:function(){this.handles.remove(),this.range&&this.range.remove(),this.element.removeClass("ui-slider ui-slider-horizontal ui-slider-vertical ui-widget ui-widget-content ui-corner-all"),this._mouseDestroy()},_mouseCapture:function(e){var i,s,n,a,o,r,l,h,u=this,c=this.options;return c.disabled?!1:(this.elementSize={width:this.element.outerWidth(),height:this.element.outerHeight()},this.elementOffset=this.element.offset(),i={x:e.pageX,y:e.pageY},s=this._normValueFromMouse(i),n=this._valueMax()-this._valueMin()+1,this.handles.each(function(e){var i=Math.abs(s-u.values(e));(n>i||n===i&&(e===u._lastChangedValue||u.values(e)===c.min))&&(n=i,a=t(this),o=e)}),r=this._start(e,o),r===!1?!1:(this._mouseSliding=!0,this._handleIndex=o,a.addClass("ui-state-active").focus(),l=a.offset(),h=!t(e.target).parents().addBack().is(".ui-slider-handle"),this._clickOffset=h?{left:0,top:0}:{left:e.pageX-l.left-a.width()/2,top:e.pageY-l.top-a.height()/2-(parseInt(a.css("borderTopWidth"),10)||0)-(parseInt(a.css("borderBottomWidth"),10)||0)+(parseInt(a.css("marginTop"),10)||0)},this.handles.hasClass("ui-state-hover")||this._slide(e,o,s),this._animateOff=!0,!0))},_mouseStart:function(){return!0},_mouseDrag:function(t){var e={x:t.pageX,y:t.pageY},i=this._normValueFromMouse(e);return this._slide(t,this._handleIndex,i),!1},_mouseStop:function(t){return this.handles.removeClass("ui-state-active"),this._mouseSliding=!1,this._stop(t,this._handleIndex),this._change(t,this._handleIndex),this._handleIndex=null,this._clickOffset=null,this._animateOff=!1,!1},_detectOrientation:function(){this.orientation="vertical"===this.options.orientation?"vertical":"horizontal"},_normValueFromMouse:function(t){var e,i,s,n,a;return"horizontal"===this.orientation?(e=this.elementSize.width,i=t.x-this.elementOffset.left-(this._clickOffset?this._clickOffset.left:0)):(e=this.elementSize.height,i=t.y-this.elementOffset.top-(this._clickOffset?this._clickOffset.top:0)),s=i/e,s>1&&(s=1),0>s&&(s=0),"vertical"===this.orientation&&(s=1-s),n=this._valueMax()-this._valueMin(),a=this._valueMin()+s*n,this._trimAlignValue(a)},_start:function(t,e){var i={handle:this.handles[e],value:this.value()};return this.options.values&&this.options.values.length&&(i.value=this.values(e),i.values=this.values()),this._trigger("start",t,i)},_slide:function(t,e,i){var s,n,a;this.options.values&&this.options.values.length?(s=this.values(e?0:1),2===this.options.values.length&&this.options.range===!0&&(0===e&&i>s||1===e&&s>i)&&(i=s),i!==this.values(e)&&(n=this.values(),n[e]=i,a=this._trigger("slide",t,{handle:this.handles[e],value:i,values:n}),s=this.values(e?0:1),a!==!1&&this.values(e,i))):i!==this.value()&&(a=this._trigger("slide",t,{handle:this.handles[e],value:i}),a!==!1&&this.value(i))},_stop:function(t,e){var i={handle:this.handles[e],value:this.value()};this.options.values&&this.options.values.length&&(i.value=this.values(e),i.values=this.values()),this._trigger("stop",t,i)},_change:function(t,e){if(!this._keySliding&&!this._mouseSliding){var i={handle:this.handles[e],value:this.value()};this.options.values&&this.options.values.length&&(i.value=this.values(e),i.values=this.values()),this._lastChangedValue=e,this._trigger("change",t,i)}},value:function(t){return arguments.length?(this.options.value=this._trimAlignValue(t),this._refreshValue(),this._change(null,0),undefined):this._value()},values:function(e,i){var s,n,a;if(arguments.length>1)return this.options.values[e]=this._trimAlignValue(i),this._refreshValue(),this._change(null,e),undefined;if(!arguments.length)return this._values();if(!t.isArray(arguments[0]))return this.options.values&&this.options.values.length?this._values(e):this.value();for(s=this.options.values,n=arguments[0],a=0;s.length>a;a+=1)s[a]=this._trimAlignValue(n[a]),this._change(null,a);this._refreshValue()},_setOption:function(e,i){var s,n=0;switch("range"===e&&this.options.range===!0&&("min"===i?(this.options.value=this._values(0),this.options.values=null):"max"===i&&(this.options.value=this._values(this.options.values.length-1),this.options.values=null)),t.isArray(this.options.values)&&(n=this.options.values.length),t.Widget.prototype._setOption.apply(this,arguments),e){case"orientation":this._detectOrientation(),this.element.removeClass("ui-slider-horizontal ui-slider-vertical").addClass("ui-slider-"+this.orientation),this._refreshValue();break;case"value":this._animateOff=!0,this._refreshValue(),this._change(null,0),this._animateOff=!1;break;case"values":for(this._animateOff=!0,this._refreshValue(),s=0;n>s;s+=1)this._change(null,s);this._animateOff=!1;break;case"min":case"max":this._animateOff=!0,this._refreshValue(),this._animateOff=!1;break;case"range":this._animateOff=!0,this._refresh(),this._animateOff=!1}},_value:function(){var t=this.options.value;return t=this._trimAlignValue(t)},_values:function(t){var e,i,s;if(arguments.length)return e=this.options.values[t],e=this._trimAlignValue(e);if(this.options.values&&this.options.values.length){for(i=this.options.values.slice(),s=0;i.length>s;s+=1)i[s]=this._trimAlignValue(i[s]);return i}return[]},_trimAlignValue:function(t){if(this._valueMin()>=t)return this._valueMin();if(t>=this._valueMax())return this._valueMax();var e=this.options.step>0?this.options.step:1,i=(t-this._valueMin())%e,s=t-i;return 2*Math.abs(i)>=e&&(s+=i>0?e:-e),parseFloat(s.toFixed(5))},_valueMin:function(){return this.options.min},_valueMax:function(){return this.options.max},_refreshValue:function(){var e,i,s,n,a,o=this.options.range,r=this.options,l=this,h=this._animateOff?!1:r.animate,u={};this.options.values&&this.options.values.length?this.handles.each(function(s){i=100*((l.values(s)-l._valueMin())/(l._valueMax()-l._valueMin())),u["horizontal"===l.orientation?"left":"bottom"]=i+"%",t(this).stop(1,1)[h?"animate":"css"](u,r.animate),l.options.range===!0&&("horizontal"===l.orientation?(0===s&&l.range.stop(1,1)[h?"animate":"css"]({left:i+"%"},r.animate),1===s&&l.range[h?"animate":"css"]({width:i-e+"%"},{queue:!1,duration:r.animate})):(0===s&&l.range.stop(1,1)[h?"animate":"css"]({bottom:i+"%"},r.animate),1===s&&l.range[h?"animate":"css"]({height:i-e+"%"},{queue:!1,duration:r.animate}))),e=i}):(s=this.value(),n=this._valueMin(),a=this._valueMax(),i=a!==n?100*((s-n)/(a-n)):0,u["horizontal"===this.orientation?"left":"bottom"]=i+"%",this.handle.stop(1,1)[h?"animate":"css"](u,r.animate),"min"===o&&"horizontal"===this.orientation&&this.range.stop(1,1)[h?"animate":"css"]({width:i+"%"},r.animate),"max"===o&&"horizontal"===this.orientation&&this.range[h?"animate":"css"]({width:100-i+"%"},{queue:!1,duration:r.animate}),"min"===o&&"vertical"===this.orientation&&this.range.stop(1,1)[h?"animate":"css"]({height:i+"%"},r.animate),"max"===o&&"vertical"===this.orientation&&this.range[h?"animate":"css"]({height:100-i+"%"},{queue:!1,duration:r.animate}))},_handleEvents:{keydown:function(i){var s,n,a,o,r=t(i.target).data("ui-slider-handle-index");switch(i.keyCode){case t.ui.keyCode.HOME:case t.ui.keyCode.END:case t.ui.keyCode.PAGE_UP:case t.ui.keyCode.PAGE_DOWN:case t.ui.keyCode.UP:case t.ui.keyCode.RIGHT:case t.ui.keyCode.DOWN:case t.ui.keyCode.LEFT:if(i.preventDefault(),!this._keySliding&&(this._keySliding=!0,t(i.target).addClass("ui-state-active"),s=this._start(i,r),s===!1))return}switch(o=this.options.step,n=a=this.options.values&&this.options.values.length?this.values(r):this.value(),i.keyCode){case t.ui.keyCode.HOME:a=this._valueMin();break;case t.ui.keyCode.END:a=this._valueMax();break;case t.ui.keyCode.PAGE_UP:a=this._trimAlignValue(n+(this._valueMax()-this._valueMin())/e);break;case t.ui.keyCode.PAGE_DOWN:a=this._trimAlignValue(n-(this._valueMax()-this._valueMin())/e);break;case t.ui.keyCode.UP:case t.ui.keyCode.RIGHT:if(n===this._valueMax())return;a=this._trimAlignValue(n+o);break;case t.ui.keyCode.DOWN:case t.ui.keyCode.LEFT:if(n===this._valueMin())return;a=this._trimAlignValue(n-o)}this._slide(i,r,a)},click:function(t){t.preventDefault()},keyup:function(e){var i=t(e.target).data("ui-slider-handle-index");this._keySliding&&(this._keySliding=!1,this._stop(e,i),this._change(e,i),t(e.target).removeClass("ui-state-active"))}}})})(jQuery);(function(t){function e(t){return function(){var e=this.element.val();t.apply(this,arguments),this._refresh(),e!==this.element.val()&&this._trigger("change")}}t.widget("ui.spinner",{version:"1.10.4",defaultElement:"<input>",widgetEventPrefix:"spin",options:{culture:null,icons:{down:"ui-icon-triangle-1-s",up:"ui-icon-triangle-1-n"},incremental:!0,max:null,min:null,numberFormat:null,page:10,step:1,change:null,spin:null,start:null,stop:null},_create:function(){this._setOption("max",this.options.max),this._setOption("min",this.options.min),this._setOption("step",this.options.step),""!==this.value()&&this._value(this.element.val(),!0),this._draw(),this._on(this._events),this._refresh(),this._on(this.window,{beforeunload:function(){this.element.removeAttr("autocomplete")}})},_getCreateOptions:function(){var e={},i=this.element;return t.each(["min","max","step"],function(t,s){var n=i.attr(s);void 0!==n&&n.length&&(e[s]=n)}),e},_events:{keydown:function(t){this._start(t)&&this._keydown(t)&&t.preventDefault()},keyup:"_stop",focus:function(){this.previous=this.element.val()},blur:function(t){return this.cancelBlur?(delete this.cancelBlur,void 0):(this._stop(),this._refresh(),this.previous!==this.element.val()&&this._trigger("change",t),void 0)},mousewheel:function(t,e){if(e){if(!this.spinning&&!this._start(t))return!1;this._spin((e>0?1:-1)*this.options.step,t),clearTimeout(this.mousewheelTimer),this.mousewheelTimer=this._delay(function(){this.spinning&&this._stop(t)},100),t.preventDefault()}},"mousedown .ui-spinner-button":function(e){function i(){var t=this.element[0]===this.document[0].activeElement;t||(this.element.focus(),this.previous=s,this._delay(function(){this.previous=s}))}var s;s=this.element[0]===this.document[0].activeElement?this.previous:this.element.val(),e.preventDefault(),i.call(this),this.cancelBlur=!0,this._delay(function(){delete this.cancelBlur,i.call(this)}),this._start(e)!==!1&&this._repeat(null,t(e.currentTarget).hasClass("ui-spinner-up")?1:-1,e)},"mouseup .ui-spinner-button":"_stop","mouseenter .ui-spinner-button":function(e){return t(e.currentTarget).hasClass("ui-state-active")?this._start(e)===!1?!1:(this._repeat(null,t(e.currentTarget).hasClass("ui-spinner-up")?1:-1,e),void 0):void 0},"mouseleave .ui-spinner-button":"_stop"},_draw:function(){var t=this.uiSpinner=this.element.addClass("ui-spinner-input").attr("autocomplete","off").wrap(this._uiSpinnerHtml()).parent().append(this._buttonHtml());this.element.attr("role","spinbutton"),this.buttons=t.find(".ui-spinner-button").attr("tabIndex",-1).button().removeClass("ui-corner-all"),this.buttons.height()>Math.ceil(.5*t.height())&&t.height()>0&&t.height(t.height()),this.options.disabled&&this.disable()},_keydown:function(e){var i=this.options,s=t.ui.keyCode;switch(e.keyCode){case s.UP:return this._repeat(null,1,e),!0;case s.DOWN:return this._repeat(null,-1,e),!0;case s.PAGE_UP:return this._repeat(null,i.page,e),!0;case s.PAGE_DOWN:return this._repeat(null,-i.page,e),!0}return!1},_uiSpinnerHtml:function(){return"<span class='ui-spinner ui-widget ui-widget-content ui-corner-all'></span>"},_buttonHtml:function(){return"<a class='ui-spinner-button ui-spinner-up ui-corner-tr'><span class='ui-icon "+this.options.icons.up+"'>&#9650;</span>"+"</a>"+"<a class='ui-spinner-button ui-spinner-down ui-corner-br'>"+"<span class='ui-icon "+this.options.icons.down+"'>&#9660;</span>"+"</a>"},_start:function(t){return this.spinning||this._trigger("start",t)!==!1?(this.counter||(this.counter=1),this.spinning=!0,!0):!1},_repeat:function(t,e,i){t=t||500,clearTimeout(this.timer),this.timer=this._delay(function(){this._repeat(40,e,i)},t),this._spin(e*this.options.step,i)},_spin:function(t,e){var i=this.value()||0;this.counter||(this.counter=1),i=this._adjustValue(i+t*this._increment(this.counter)),this.spinning&&this._trigger("spin",e,{value:i})===!1||(this._value(i),this.counter++)},_increment:function(e){var i=this.options.incremental;return i?t.isFunction(i)?i(e):Math.floor(e*e*e/5e4-e*e/500+17*e/200+1):1},_precision:function(){var t=this._precisionOf(this.options.step);return null!==this.options.min&&(t=Math.max(t,this._precisionOf(this.options.min))),t},_precisionOf:function(t){var e=""+t,i=e.indexOf(".");return-1===i?0:e.length-i-1},_adjustValue:function(t){var e,i,s=this.options;return e=null!==s.min?s.min:0,i=t-e,i=Math.round(i/s.step)*s.step,t=e+i,t=parseFloat(t.toFixed(this._precision())),null!==s.max&&t>s.max?s.max:null!==s.min&&s.min>t?s.min:t},_stop:function(t){this.spinning&&(clearTimeout(this.timer),clearTimeout(this.mousewheelTimer),this.counter=0,this.spinning=!1,this._trigger("stop",t))},_setOption:function(t,e){if("culture"===t||"numberFormat"===t){var i=this._parse(this.element.val());return this.options[t]=e,this.element.val(this._format(i)),void 0}("max"===t||"min"===t||"step"===t)&&"string"==typeof e&&(e=this._parse(e)),"icons"===t&&(this.buttons.first().find(".ui-icon").removeClass(this.options.icons.up).addClass(e.up),this.buttons.last().find(".ui-icon").removeClass(this.options.icons.down).addClass(e.down)),this._super(t,e),"disabled"===t&&(e?(this.element.prop("disabled",!0),this.buttons.button("disable")):(this.element.prop("disabled",!1),this.buttons.button("enable")))},_setOptions:e(function(t){this._super(t),this._value(this.element.val())}),_parse:function(t){return"string"==typeof t&&""!==t&&(t=window.Globalize&&this.options.numberFormat?Globalize.parseFloat(t,10,this.options.culture):+t),""===t||isNaN(t)?null:t},_format:function(t){return""===t?"":window.Globalize&&this.options.numberFormat?Globalize.format(t,this.options.numberFormat,this.options.culture):t},_refresh:function(){this.element.attr({"aria-valuemin":this.options.min,"aria-valuemax":this.options.max,"aria-valuenow":this._parse(this.element.val())})},_value:function(t,e){var i;""!==t&&(i=this._parse(t),null!==i&&(e||(i=this._adjustValue(i)),t=this._format(i))),this.element.val(t),this._refresh()},_destroy:function(){this.element.removeClass("ui-spinner-input").prop("disabled",!1).removeAttr("autocomplete").removeAttr("role").removeAttr("aria-valuemin").removeAttr("aria-valuemax").removeAttr("aria-valuenow"),this.uiSpinner.replaceWith(this.element)},stepUp:e(function(t){this._stepUp(t)}),_stepUp:function(t){this._start()&&(this._spin((t||1)*this.options.step),this._stop())},stepDown:e(function(t){this._stepDown(t)}),_stepDown:function(t){this._start()&&(this._spin((t||1)*-this.options.step),this._stop())},pageUp:e(function(t){this._stepUp((t||1)*this.options.page)}),pageDown:e(function(t){this._stepDown((t||1)*this.options.page)}),value:function(t){return arguments.length?(e(this._value).call(this,t),void 0):this._parse(this.element.val())},widget:function(){return this.uiSpinner}})})(jQuery);(function(t,e){function i(){return++n}function s(t){return t=t.cloneNode(!1),t.hash.length>1&&decodeURIComponent(t.href.replace(a,""))===decodeURIComponent(location.href.replace(a,""))}var n=0,a=/#.*$/;t.widget("ui.tabs",{version:"1.10.4",delay:300,options:{active:null,collapsible:!1,event:"click",heightStyle:"content",hide:null,show:null,activate:null,beforeActivate:null,beforeLoad:null,load:null},_create:function(){var e=this,i=this.options;this.running=!1,this.element.addClass("ui-tabs ui-widget ui-widget-content ui-corner-all").toggleClass("ui-tabs-collapsible",i.collapsible).delegate(".ui-tabs-nav > li","mousedown"+this.eventNamespace,function(e){t(this).is(".ui-state-disabled")&&e.preventDefault()}).delegate(".ui-tabs-anchor","focus"+this.eventNamespace,function(){t(this).closest("li").is(".ui-state-disabled")&&this.blur()}),this._processTabs(),i.active=this._initialActive(),t.isArray(i.disabled)&&(i.disabled=t.unique(i.disabled.concat(t.map(this.tabs.filter(".ui-state-disabled"),function(t){return e.tabs.index(t)}))).sort()),this.active=this.options.active!==!1&&this.anchors.length?this._findActive(i.active):t(),this._refresh(),this.active.length&&this.load(i.active)},_initialActive:function(){var i=this.options.active,s=this.options.collapsible,n=location.hash.substring(1);return null===i&&(n&&this.tabs.each(function(s,a){return t(a).attr("aria-controls")===n?(i=s,!1):e}),null===i&&(i=this.tabs.index(this.tabs.filter(".ui-tabs-active"))),(null===i||-1===i)&&(i=this.tabs.length?0:!1)),i!==!1&&(i=this.tabs.index(this.tabs.eq(i)),-1===i&&(i=s?!1:0)),!s&&i===!1&&this.anchors.length&&(i=0),i},_getCreateEventData:function(){return{tab:this.active,panel:this.active.length?this._getPanelForTab(this.active):t()}},_tabKeydown:function(i){var s=t(this.document[0].activeElement).closest("li"),n=this.tabs.index(s),a=!0;if(!this._handlePageNav(i)){switch(i.keyCode){case t.ui.keyCode.RIGHT:case t.ui.keyCode.DOWN:n++;break;case t.ui.keyCode.UP:case t.ui.keyCode.LEFT:a=!1,n--;break;case t.ui.keyCode.END:n=this.anchors.length-1;break;case t.ui.keyCode.HOME:n=0;break;case t.ui.keyCode.SPACE:return i.preventDefault(),clearTimeout(this.activating),this._activate(n),e;case t.ui.keyCode.ENTER:return i.preventDefault(),clearTimeout(this.activating),this._activate(n===this.options.active?!1:n),e;default:return}i.preventDefault(),clearTimeout(this.activating),n=this._focusNextTab(n,a),i.ctrlKey||(s.attr("aria-selected","false"),this.tabs.eq(n).attr("aria-selected","true"),this.activating=this._delay(function(){this.option("active",n)},this.delay))}},_panelKeydown:function(e){this._handlePageNav(e)||e.ctrlKey&&e.keyCode===t.ui.keyCode.UP&&(e.preventDefault(),this.active.focus())},_handlePageNav:function(i){return i.altKey&&i.keyCode===t.ui.keyCode.PAGE_UP?(this._activate(this._focusNextTab(this.options.active-1,!1)),!0):i.altKey&&i.keyCode===t.ui.keyCode.PAGE_DOWN?(this._activate(this._focusNextTab(this.options.active+1,!0)),!0):e},_findNextTab:function(e,i){function s(){return e>n&&(e=0),0>e&&(e=n),e}for(var n=this.tabs.length-1;-1!==t.inArray(s(),this.options.disabled);)e=i?e+1:e-1;return e},_focusNextTab:function(t,e){return t=this._findNextTab(t,e),this.tabs.eq(t).focus(),t},_setOption:function(t,i){return"active"===t?(this._activate(i),e):"disabled"===t?(this._setupDisabled(i),e):(this._super(t,i),"collapsible"===t&&(this.element.toggleClass("ui-tabs-collapsible",i),i||this.options.active!==!1||this._activate(0)),"event"===t&&this._setupEvents(i),"heightStyle"===t&&this._setupHeightStyle(i),e)},_tabId:function(t){return t.attr("aria-controls")||"ui-tabs-"+i()},_sanitizeSelector:function(t){return t?t.replace(/[!"$%&'()*+,.\/:;<=>?@\[\]\^`{|}~]/g,"\\$&"):""},refresh:function(){var e=this.options,i=this.tablist.children(":has(a[href])");e.disabled=t.map(i.filter(".ui-state-disabled"),function(t){return i.index(t)}),this._processTabs(),e.active!==!1&&this.anchors.length?this.active.length&&!t.contains(this.tablist[0],this.active[0])?this.tabs.length===e.disabled.length?(e.active=!1,this.active=t()):this._activate(this._findNextTab(Math.max(0,e.active-1),!1)):e.active=this.tabs.index(this.active):(e.active=!1,this.active=t()),this._refresh()},_refresh:function(){this._setupDisabled(this.options.disabled),this._setupEvents(this.options.event),this._setupHeightStyle(this.options.heightStyle),this.tabs.not(this.active).attr({"aria-selected":"false",tabIndex:-1}),this.panels.not(this._getPanelForTab(this.active)).hide().attr({"aria-expanded":"false","aria-hidden":"true"}),this.active.length?(this.active.addClass("ui-tabs-active ui-state-active").attr({"aria-selected":"true",tabIndex:0}),this._getPanelForTab(this.active).show().attr({"aria-expanded":"true","aria-hidden":"false"})):this.tabs.eq(0).attr("tabIndex",0)},_processTabs:function(){var e=this;this.tablist=this._getList().addClass("ui-tabs-nav ui-helper-reset ui-helper-clearfix ui-widget-header ui-corner-all").attr("role","tablist"),this.tabs=this.tablist.find("> li:has(a[href])").addClass("ui-state-default ui-corner-top").attr({role:"tab",tabIndex:-1}),this.anchors=this.tabs.map(function(){return t("a",this)[0]}).addClass("ui-tabs-anchor").attr({role:"presentation",tabIndex:-1}),this.panels=t(),this.anchors.each(function(i,n){var a,o,r,h=t(n).uniqueId().attr("id"),l=t(n).closest("li"),c=l.attr("aria-controls");s(n)?(a=n.hash,o=e.element.find(e._sanitizeSelector(a))):(r=e._tabId(l),a="#"+r,o=e.element.find(a),o.length||(o=e._createPanel(r),o.insertAfter(e.panels[i-1]||e.tablist)),o.attr("aria-live","polite")),o.length&&(e.panels=e.panels.add(o)),c&&l.data("ui-tabs-aria-controls",c),l.attr({"aria-controls":a.substring(1),"aria-labelledby":h}),o.attr("aria-labelledby",h)}),this.panels.addClass("ui-tabs-panel ui-widget-content ui-corner-bottom").attr("role","tabpanel")},_getList:function(){return this.tablist||this.element.find("ol,ul").eq(0)},_createPanel:function(e){return t("<div>").attr("id",e).addClass("ui-tabs-panel ui-widget-content ui-corner-bottom").data("ui-tabs-destroy",!0)},_setupDisabled:function(e){t.isArray(e)&&(e.length?e.length===this.anchors.length&&(e=!0):e=!1);for(var i,s=0;i=this.tabs[s];s++)e===!0||-1!==t.inArray(s,e)?t(i).addClass("ui-state-disabled").attr("aria-disabled","true"):t(i).removeClass("ui-state-disabled").removeAttr("aria-disabled");this.options.disabled=e},_setupEvents:function(e){var i={click:function(t){t.preventDefault()}};e&&t.each(e.split(" "),function(t,e){i[e]="_eventHandler"}),this._off(this.anchors.add(this.tabs).add(this.panels)),this._on(this.anchors,i),this._on(this.tabs,{keydown:"_tabKeydown"}),this._on(this.panels,{keydown:"_panelKeydown"}),this._focusable(this.tabs),this._hoverable(this.tabs)},_setupHeightStyle:function(e){var i,s=this.element.parent();"fill"===e?(i=s.height(),i-=this.element.outerHeight()-this.element.height(),this.element.siblings(":visible").each(function(){var e=t(this),s=e.css("position");"absolute"!==s&&"fixed"!==s&&(i-=e.outerHeight(!0))}),this.element.children().not(this.panels).each(function(){i-=t(this).outerHeight(!0)}),this.panels.each(function(){t(this).height(Math.max(0,i-t(this).innerHeight()+t(this).height()))}).css("overflow","auto")):"auto"===e&&(i=0,this.panels.each(function(){i=Math.max(i,t(this).height("").height())}).height(i))},_eventHandler:function(e){var i=this.options,s=this.active,n=t(e.currentTarget),a=n.closest("li"),o=a[0]===s[0],r=o&&i.collapsible,h=r?t():this._getPanelForTab(a),l=s.length?this._getPanelForTab(s):t(),c={oldTab:s,oldPanel:l,newTab:r?t():a,newPanel:h};e.preventDefault(),a.hasClass("ui-state-disabled")||a.hasClass("ui-tabs-loading")||this.running||o&&!i.collapsible||this._trigger("beforeActivate",e,c)===!1||(i.active=r?!1:this.tabs.index(a),this.active=o?t():a,this.xhr&&this.xhr.abort(),l.length||h.length||t.error("jQuery UI Tabs: Mismatching fragment identifier."),h.length&&this.load(this.tabs.index(a),e),this._toggle(e,c))},_toggle:function(e,i){function s(){a.running=!1,a._trigger("activate",e,i)}function n(){i.newTab.closest("li").addClass("ui-tabs-active ui-state-active"),o.length&&a.options.show?a._show(o,a.options.show,s):(o.show(),s())}var a=this,o=i.newPanel,r=i.oldPanel;this.running=!0,r.length&&this.options.hide?this._hide(r,this.options.hide,function(){i.oldTab.closest("li").removeClass("ui-tabs-active ui-state-active"),n()}):(i.oldTab.closest("li").removeClass("ui-tabs-active ui-state-active"),r.hide(),n()),r.attr({"aria-expanded":"false","aria-hidden":"true"}),i.oldTab.attr("aria-selected","false"),o.length&&r.length?i.oldTab.attr("tabIndex",-1):o.length&&this.tabs.filter(function(){return 0===t(this).attr("tabIndex")}).attr("tabIndex",-1),o.attr({"aria-expanded":"true","aria-hidden":"false"}),i.newTab.attr({"aria-selected":"true",tabIndex:0})},_activate:function(e){var i,s=this._findActive(e);s[0]!==this.active[0]&&(s.length||(s=this.active),i=s.find(".ui-tabs-anchor")[0],this._eventHandler({target:i,currentTarget:i,preventDefault:t.noop}))},_findActive:function(e){return e===!1?t():this.tabs.eq(e)},_getIndex:function(t){return"string"==typeof t&&(t=this.anchors.index(this.anchors.filter("[href$='"+t+"']"))),t},_destroy:function(){this.xhr&&this.xhr.abort(),this.element.removeClass("ui-tabs ui-widget ui-widget-content ui-corner-all ui-tabs-collapsible"),this.tablist.removeClass("ui-tabs-nav ui-helper-reset ui-helper-clearfix ui-widget-header ui-corner-all").removeAttr("role"),this.anchors.removeClass("ui-tabs-anchor").removeAttr("role").removeAttr("tabIndex").removeUniqueId(),this.tabs.add(this.panels).each(function(){t.data(this,"ui-tabs-destroy")?t(this).remove():t(this).removeClass("ui-state-default ui-state-active ui-state-disabled ui-corner-top ui-corner-bottom ui-widget-content ui-tabs-active ui-tabs-panel").removeAttr("tabIndex").removeAttr("aria-live").removeAttr("aria-busy").removeAttr("aria-selected").removeAttr("aria-labelledby").removeAttr("aria-hidden").removeAttr("aria-expanded").removeAttr("role")}),this.tabs.each(function(){var e=t(this),i=e.data("ui-tabs-aria-controls");i?e.attr("aria-controls",i).removeData("ui-tabs-aria-controls"):e.removeAttr("aria-controls")}),this.panels.show(),"content"!==this.options.heightStyle&&this.panels.css("height","")},enable:function(i){var s=this.options.disabled;s!==!1&&(i===e?s=!1:(i=this._getIndex(i),s=t.isArray(s)?t.map(s,function(t){return t!==i?t:null}):t.map(this.tabs,function(t,e){return e!==i?e:null})),this._setupDisabled(s))},disable:function(i){var s=this.options.disabled;if(s!==!0){if(i===e)s=!0;else{if(i=this._getIndex(i),-1!==t.inArray(i,s))return;s=t.isArray(s)?t.merge([i],s).sort():[i]}this._setupDisabled(s)}},load:function(e,i){e=this._getIndex(e);var n=this,a=this.tabs.eq(e),o=a.find(".ui-tabs-anchor"),r=this._getPanelForTab(a),h={tab:a,panel:r};s(o[0])||(this.xhr=t.ajax(this._ajaxSettings(o,i,h)),this.xhr&&"canceled"!==this.xhr.statusText&&(a.addClass("ui-tabs-loading"),r.attr("aria-busy","true"),this.xhr.success(function(t){setTimeout(function(){r.html(t),n._trigger("load",i,h)},1)}).complete(function(t,e){setTimeout(function(){"abort"===e&&n.panels.stop(!1,!0),a.removeClass("ui-tabs-loading"),r.removeAttr("aria-busy"),t===n.xhr&&delete n.xhr},1)})))},_ajaxSettings:function(e,i,s){var n=this;return{url:e.attr("href"),beforeSend:function(e,a){return n._trigger("beforeLoad",i,t.extend({jqXHR:e,ajaxSettings:a},s))}}},_getPanelForTab:function(e){var i=t(e).attr("aria-controls");return this.element.find(this._sanitizeSelector("#"+i))}})})(jQuery);(function(t){function e(e,i){var s=(e.attr("aria-describedby")||"").split(/\s+/);s.push(i),e.data("ui-tooltip-id",i).attr("aria-describedby",t.trim(s.join(" ")))}function i(e){var i=e.data("ui-tooltip-id"),s=(e.attr("aria-describedby")||"").split(/\s+/),n=t.inArray(i,s);-1!==n&&s.splice(n,1),e.removeData("ui-tooltip-id"),s=t.trim(s.join(" ")),s?e.attr("aria-describedby",s):e.removeAttr("aria-describedby")}var s=0;t.widget("ui.tooltip",{version:"1.10.4",options:{content:function(){var e=t(this).attr("title")||"";return t("<a>").text(e).html()},hide:!0,items:"[title]:not([disabled])",position:{my:"left top+15",at:"left bottom",collision:"flipfit flip"},show:!0,tooltipClass:null,track:!1,close:null,open:null},_create:function(){this._on({mouseover:"open",focusin:"open"}),this.tooltips={},this.parents={},this.options.disabled&&this._disable()},_setOption:function(e,i){var s=this;return"disabled"===e?(this[i?"_disable":"_enable"](),this.options[e]=i,void 0):(this._super(e,i),"content"===e&&t.each(this.tooltips,function(t,e){s._updateContent(e)}),void 0)},_disable:function(){var e=this;t.each(this.tooltips,function(i,s){var n=t.Event("blur");n.target=n.currentTarget=s[0],e.close(n,!0)}),this.element.find(this.options.items).addBack().each(function(){var e=t(this);e.is("[title]")&&e.data("ui-tooltip-title",e.attr("title")).attr("title","")})},_enable:function(){this.element.find(this.options.items).addBack().each(function(){var e=t(this);e.data("ui-tooltip-title")&&e.attr("title",e.data("ui-tooltip-title"))})},open:function(e){var i=this,s=t(e?e.target:this.element).closest(this.options.items);s.length&&!s.data("ui-tooltip-id")&&(s.attr("title")&&s.data("ui-tooltip-title",s.attr("title")),s.data("ui-tooltip-open",!0),e&&"mouseover"===e.type&&s.parents().each(function(){var e,s=t(this);s.data("ui-tooltip-open")&&(e=t.Event("blur"),e.target=e.currentTarget=this,i.close(e,!0)),s.attr("title")&&(s.uniqueId(),i.parents[this.id]={element:this,title:s.attr("title")},s.attr("title",""))}),this._updateContent(s,e))},_updateContent:function(t,e){var i,s=this.options.content,n=this,o=e?e.type:null;return"string"==typeof s?this._open(e,t,s):(i=s.call(t[0],function(i){t.data("ui-tooltip-open")&&n._delay(function(){e&&(e.type=o),this._open(e,t,i)})}),i&&this._open(e,t,i),void 0)},_open:function(i,s,n){function o(t){l.of=t,a.is(":hidden")||a.position(l)}var a,r,h,l=t.extend({},this.options.position);if(n){if(a=this._find(s),a.length)return a.find(".ui-tooltip-content").html(n),void 0;s.is("[title]")&&(i&&"mouseover"===i.type?s.attr("title",""):s.removeAttr("title")),a=this._tooltip(s),e(s,a.attr("id")),a.find(".ui-tooltip-content").html(n),this.options.track&&i&&/^mouse/.test(i.type)?(this._on(this.document,{mousemove:o}),o(i)):a.position(t.extend({of:s},this.options.position)),a.hide(),this._show(a,this.options.show),this.options.show&&this.options.show.delay&&(h=this.delayedShow=setInterval(function(){a.is(":visible")&&(o(l.of),clearInterval(h))},t.fx.interval)),this._trigger("open",i,{tooltip:a}),r={keyup:function(e){if(e.keyCode===t.ui.keyCode.ESCAPE){var i=t.Event(e);i.currentTarget=s[0],this.close(i,!0)}},remove:function(){this._removeTooltip(a)}},i&&"mouseover"!==i.type||(r.mouseleave="close"),i&&"focusin"!==i.type||(r.focusout="close"),this._on(!0,s,r)}},close:function(e){var s=this,n=t(e?e.currentTarget:this.element),o=this._find(n);this.closing||(clearInterval(this.delayedShow),n.data("ui-tooltip-title")&&n.attr("title",n.data("ui-tooltip-title")),i(n),o.stop(!0),this._hide(o,this.options.hide,function(){s._removeTooltip(t(this))}),n.removeData("ui-tooltip-open"),this._off(n,"mouseleave focusout keyup"),n[0]!==this.element[0]&&this._off(n,"remove"),this._off(this.document,"mousemove"),e&&"mouseleave"===e.type&&t.each(this.parents,function(e,i){t(i.element).attr("title",i.title),delete s.parents[e]}),this.closing=!0,this._trigger("close",e,{tooltip:o}),this.closing=!1)},_tooltip:function(e){var i="ui-tooltip-"+s++,n=t("<div>").attr({id:i,role:"tooltip"}).addClass("ui-tooltip ui-widget ui-corner-all ui-widget-content "+(this.options.tooltipClass||""));return t("<div>").addClass("ui-tooltip-content").appendTo(n),n.appendTo(this.document[0].body),this.tooltips[i]=e,n},_find:function(e){var i=e.data("ui-tooltip-id");return i?t("#"+i):t()},_removeTooltip:function(t){t.remove(),delete this.tooltips[t.attr("id")]},_destroy:function(){var e=this;t.each(this.tooltips,function(i,s){var n=t.Event("blur");n.target=n.currentTarget=s[0],e.close(n,!0),t("#"+i).remove(),s.data("ui-tooltip-title")&&(s.attr("title",s.data("ui-tooltip-title")),s.removeData("ui-tooltip-title"))})}})})(jQuery);(function(t,e){var i="ui-effects-";t.effects={effect:{}},function(t,e){function i(t,e,i){var s=u[e.type]||{};return null==t?i||!e.def?null:e.def:(t=s.floor?~~t:parseFloat(t),isNaN(t)?e.def:s.mod?(t+s.mod)%s.mod:0>t?0:t>s.max?s.max:t)}function s(i){var s=h(),n=s._rgba=[];return i=i.toLowerCase(),f(l,function(t,a){var o,r=a.re.exec(i),l=r&&a.parse(r),h=a.space||"rgba";return l?(o=s[h](l),s[c[h].cache]=o[c[h].cache],n=s._rgba=o._rgba,!1):e}),n.length?("0,0,0,0"===n.join()&&t.extend(n,a.transparent),s):a[i]}function n(t,e,i){return i=(i+1)%1,1>6*i?t+6*(e-t)*i:1>2*i?e:2>3*i?t+6*(e-t)*(2/3-i):t}var a,o="backgroundColor borderBottomColor borderLeftColor borderRightColor borderTopColor color columnRuleColor outlineColor textDecorationColor textEmphasisColor",r=/^([\-+])=\s*(\d+\.?\d*)/,l=[{re:/rgba?\(\s*(\d{1,3})\s*,\s*(\d{1,3})\s*,\s*(\d{1,3})\s*(?:,\s*(\d?(?:\.\d+)?)\s*)?\)/,parse:function(t){return[t[1],t[2],t[3],t[4]]}},{re:/rgba?\(\s*(\d+(?:\.\d+)?)\%\s*,\s*(\d+(?:\.\d+)?)\%\s*,\s*(\d+(?:\.\d+)?)\%\s*(?:,\s*(\d?(?:\.\d+)?)\s*)?\)/,parse:function(t){return[2.55*t[1],2.55*t[2],2.55*t[3],t[4]]}},{re:/#([a-f0-9]{2})([a-f0-9]{2})([a-f0-9]{2})/,parse:function(t){return[parseInt(t[1],16),parseInt(t[2],16),parseInt(t[3],16)]}},{re:/#([a-f0-9])([a-f0-9])([a-f0-9])/,parse:function(t){return[parseInt(t[1]+t[1],16),parseInt(t[2]+t[2],16),parseInt(t[3]+t[3],16)]}},{re:/hsla?\(\s*(\d+(?:\.\d+)?)\s*,\s*(\d+(?:\.\d+)?)\%\s*,\s*(\d+(?:\.\d+)?)\%\s*(?:,\s*(\d?(?:\.\d+)?)\s*)?\)/,space:"hsla",parse:function(t){return[t[1],t[2]/100,t[3]/100,t[4]]}}],h=t.Color=function(e,i,s,n){return new t.Color.fn.parse(e,i,s,n)},c={rgba:{props:{red:{idx:0,type:"byte"},green:{idx:1,type:"byte"},blue:{idx:2,type:"byte"}}},hsla:{props:{hue:{idx:0,type:"degrees"},saturation:{idx:1,type:"percent"},lightness:{idx:2,type:"percent"}}}},u={"byte":{floor:!0,max:255},percent:{max:1},degrees:{mod:360,floor:!0}},d=h.support={},p=t("<p>")[0],f=t.each;p.style.cssText="background-color:rgba(1,1,1,.5)",d.rgba=p.style.backgroundColor.indexOf("rgba")>-1,f(c,function(t,e){e.cache="_"+t,e.props.alpha={idx:3,type:"percent",def:1}}),h.fn=t.extend(h.prototype,{parse:function(n,o,r,l){if(n===e)return this._rgba=[null,null,null,null],this;(n.jquery||n.nodeType)&&(n=t(n).css(o),o=e);var u=this,d=t.type(n),p=this._rgba=[];return o!==e&&(n=[n,o,r,l],d="array"),"string"===d?this.parse(s(n)||a._default):"array"===d?(f(c.rgba.props,function(t,e){p[e.idx]=i(n[e.idx],e)}),this):"object"===d?(n instanceof h?f(c,function(t,e){n[e.cache]&&(u[e.cache]=n[e.cache].slice())}):f(c,function(e,s){var a=s.cache;f(s.props,function(t,e){if(!u[a]&&s.to){if("alpha"===t||null==n[t])return;u[a]=s.to(u._rgba)}u[a][e.idx]=i(n[t],e,!0)}),u[a]&&0>t.inArray(null,u[a].slice(0,3))&&(u[a][3]=1,s.from&&(u._rgba=s.from(u[a])))}),this):e},is:function(t){var i=h(t),s=!0,n=this;return f(c,function(t,a){var o,r=i[a.cache];return r&&(o=n[a.cache]||a.to&&a.to(n._rgba)||[],f(a.props,function(t,i){return null!=r[i.idx]?s=r[i.idx]===o[i.idx]:e})),s}),s},_space:function(){var t=[],e=this;return f(c,function(i,s){e[s.cache]&&t.push(i)}),t.pop()},transition:function(t,e){var s=h(t),n=s._space(),a=c[n],o=0===this.alpha()?h("transparent"):this,r=o[a.cache]||a.to(o._rgba),l=r.slice();return s=s[a.cache],f(a.props,function(t,n){var a=n.idx,o=r[a],h=s[a],c=u[n.type]||{};null!==h&&(null===o?l[a]=h:(c.mod&&(h-o>c.mod/2?o+=c.mod:o-h>c.mod/2&&(o-=c.mod)),l[a]=i((h-o)*e+o,n)))}),this[n](l)},blend:function(e){if(1===this._rgba[3])return this;var i=this._rgba.slice(),s=i.pop(),n=h(e)._rgba;return h(t.map(i,function(t,e){return(1-s)*n[e]+s*t}))},toRgbaString:function(){var e="rgba(",i=t.map(this._rgba,function(t,e){return null==t?e>2?1:0:t});return 1===i[3]&&(i.pop(),e="rgb("),e+i.join()+")"},toHslaString:function(){var e="hsla(",i=t.map(this.hsla(),function(t,e){return null==t&&(t=e>2?1:0),e&&3>e&&(t=Math.round(100*t)+"%"),t});return 1===i[3]&&(i.pop(),e="hsl("),e+i.join()+")"},toHexString:function(e){var i=this._rgba.slice(),s=i.pop();return e&&i.push(~~(255*s)),"#"+t.map(i,function(t){return t=(t||0).toString(16),1===t.length?"0"+t:t}).join("")},toString:function(){return 0===this._rgba[3]?"transparent":this.toRgbaString()}}),h.fn.parse.prototype=h.fn,c.hsla.to=function(t){if(null==t[0]||null==t[1]||null==t[2])return[null,null,null,t[3]];var e,i,s=t[0]/255,n=t[1]/255,a=t[2]/255,o=t[3],r=Math.max(s,n,a),l=Math.min(s,n,a),h=r-l,c=r+l,u=.5*c;return e=l===r?0:s===r?60*(n-a)/h+360:n===r?60*(a-s)/h+120:60*(s-n)/h+240,i=0===h?0:.5>=u?h/c:h/(2-c),[Math.round(e)%360,i,u,null==o?1:o]},c.hsla.from=function(t){if(null==t[0]||null==t[1]||null==t[2])return[null,null,null,t[3]];var e=t[0]/360,i=t[1],s=t[2],a=t[3],o=.5>=s?s*(1+i):s+i-s*i,r=2*s-o;return[Math.round(255*n(r,o,e+1/3)),Math.round(255*n(r,o,e)),Math.round(255*n(r,o,e-1/3)),a]},f(c,function(s,n){var a=n.props,o=n.cache,l=n.to,c=n.from;h.fn[s]=function(s){if(l&&!this[o]&&(this[o]=l(this._rgba)),s===e)return this[o].slice();var n,r=t.type(s),u="array"===r||"object"===r?s:arguments,d=this[o].slice();return f(a,function(t,e){var s=u["object"===r?t:e.idx];null==s&&(s=d[e.idx]),d[e.idx]=i(s,e)}),c?(n=h(c(d)),n[o]=d,n):h(d)},f(a,function(e,i){h.fn[e]||(h.fn[e]=function(n){var a,o=t.type(n),l="alpha"===e?this._hsla?"hsla":"rgba":s,h=this[l](),c=h[i.idx];return"undefined"===o?c:("function"===o&&(n=n.call(this,c),o=t.type(n)),null==n&&i.empty?this:("string"===o&&(a=r.exec(n),a&&(n=c+parseFloat(a[2])*("+"===a[1]?1:-1))),h[i.idx]=n,this[l](h)))})})}),h.hook=function(e){var i=e.split(" ");f(i,function(e,i){t.cssHooks[i]={set:function(e,n){var a,o,r="";if("transparent"!==n&&("string"!==t.type(n)||(a=s(n)))){if(n=h(a||n),!d.rgba&&1!==n._rgba[3]){for(o="backgroundColor"===i?e.parentNode:e;(""===r||"transparent"===r)&&o&&o.style;)try{r=t.css(o,"backgroundColor"),o=o.parentNode}catch(l){}n=n.blend(r&&"transparent"!==r?r:"_default")}n=n.toRgbaString()}try{e.style[i]=n}catch(l){}}},t.fx.step[i]=function(e){e.colorInit||(e.start=h(e.elem,i),e.end=h(e.end),e.colorInit=!0),t.cssHooks[i].set(e.elem,e.start.transition(e.end,e.pos))}})},h.hook(o),t.cssHooks.borderColor={expand:function(t){var e={};return f(["Top","Right","Bottom","Left"],function(i,s){e["border"+s+"Color"]=t}),e}},a=t.Color.names={aqua:"#00ffff",black:"#000000",blue:"#0000ff",fuchsia:"#ff00ff",gray:"#808080",green:"#008000",lime:"#00ff00",maroon:"#800000",navy:"#000080",olive:"#808000",purple:"#800080",red:"#ff0000",silver:"#c0c0c0",teal:"#008080",white:"#ffffff",yellow:"#ffff00",transparent:[null,null,null,0],_default:"#ffffff"}}(jQuery),function(){function i(e){var i,s,n=e.ownerDocument.defaultView?e.ownerDocument.defaultView.getComputedStyle(e,null):e.currentStyle,a={};if(n&&n.length&&n[0]&&n[n[0]])for(s=n.length;s--;)i=n[s],"string"==typeof n[i]&&(a[t.camelCase(i)]=n[i]);else for(i in n)"string"==typeof n[i]&&(a[i]=n[i]);return a}function s(e,i){var s,n,o={};for(s in i)n=i[s],e[s]!==n&&(a[s]||(t.fx.step[s]||!isNaN(parseFloat(n)))&&(o[s]=n));return o}var n=["add","remove","toggle"],a={border:1,borderBottom:1,borderColor:1,borderLeft:1,borderRight:1,borderTop:1,borderWidth:1,margin:1,padding:1};t.each(["borderLeftStyle","borderRightStyle","borderBottomStyle","borderTopStyle"],function(e,i){t.fx.step[i]=function(t){("none"!==t.end&&!t.setAttr||1===t.pos&&!t.setAttr)&&(jQuery.style(t.elem,i,t.end),t.setAttr=!0)}}),t.fn.addBack||(t.fn.addBack=function(t){return this.add(null==t?this.prevObject:this.prevObject.filter(t))}),t.effects.animateClass=function(e,a,o,r){var l=t.speed(a,o,r);return this.queue(function(){var a,o=t(this),r=o.attr("class")||"",h=l.children?o.find("*").addBack():o;h=h.map(function(){var e=t(this);return{el:e,start:i(this)}}),a=function(){t.each(n,function(t,i){e[i]&&o[i+"Class"](e[i])})},a(),h=h.map(function(){return this.end=i(this.el[0]),this.diff=s(this.start,this.end),this}),o.attr("class",r),h=h.map(function(){var e=this,i=t.Deferred(),s=t.extend({},l,{queue:!1,complete:function(){i.resolve(e)}});return this.el.animate(this.diff,s),i.promise()}),t.when.apply(t,h.get()).done(function(){a(),t.each(arguments,function(){var e=this.el;t.each(this.diff,function(t){e.css(t,"")})}),l.complete.call(o[0])})})},t.fn.extend({addClass:function(e){return function(i,s,n,a){return s?t.effects.animateClass.call(this,{add:i},s,n,a):e.apply(this,arguments)}}(t.fn.addClass),removeClass:function(e){return function(i,s,n,a){return arguments.length>1?t.effects.animateClass.call(this,{remove:i},s,n,a):e.apply(this,arguments)}}(t.fn.removeClass),toggleClass:function(i){return function(s,n,a,o,r){return"boolean"==typeof n||n===e?a?t.effects.animateClass.call(this,n?{add:s}:{remove:s},a,o,r):i.apply(this,arguments):t.effects.animateClass.call(this,{toggle:s},n,a,o)}}(t.fn.toggleClass),switchClass:function(e,i,s,n,a){return t.effects.animateClass.call(this,{add:i,remove:e},s,n,a)}})}(),function(){function s(e,i,s,n){return t.isPlainObject(e)&&(i=e,e=e.effect),e={effect:e},null==i&&(i={}),t.isFunction(i)&&(n=i,s=null,i={}),("number"==typeof i||t.fx.speeds[i])&&(n=s,s=i,i={}),t.isFunction(s)&&(n=s,s=null),i&&t.extend(e,i),s=s||i.duration,e.duration=t.fx.off?0:"number"==typeof s?s:s in t.fx.speeds?t.fx.speeds[s]:t.fx.speeds._default,e.complete=n||i.complete,e}function n(e){return!e||"number"==typeof e||t.fx.speeds[e]?!0:"string"!=typeof e||t.effects.effect[e]?t.isFunction(e)?!0:"object"!=typeof e||e.effect?!1:!0:!0}t.extend(t.effects,{version:"1.10.4",save:function(t,e){for(var s=0;e.length>s;s++)null!==e[s]&&t.data(i+e[s],t[0].style[e[s]])},restore:function(t,s){var n,a;for(a=0;s.length>a;a++)null!==s[a]&&(n=t.data(i+s[a]),n===e&&(n=""),t.css(s[a],n))},setMode:function(t,e){return"toggle"===e&&(e=t.is(":hidden")?"show":"hide"),e},getBaseline:function(t,e){var i,s;switch(t[0]){case"top":i=0;break;case"middle":i=.5;break;case"bottom":i=1;break;default:i=t[0]/e.height}switch(t[1]){case"left":s=0;break;case"center":s=.5;break;case"right":s=1;break;default:s=t[1]/e.width}return{x:s,y:i}},createWrapper:function(e){if(e.parent().is(".ui-effects-wrapper"))return e.parent();var i={width:e.outerWidth(!0),height:e.outerHeight(!0),"float":e.css("float")},s=t("<div></div>").addClass("ui-effects-wrapper").css({fontSize:"100%",background:"transparent",border:"none",margin:0,padding:0}),n={width:e.width(),height:e.height()},a=document.activeElement;try{a.id}catch(o){a=document.body}return e.wrap(s),(e[0]===a||t.contains(e[0],a))&&t(a).focus(),s=e.parent(),"static"===e.css("position")?(s.css({position:"relative"}),e.css({position:"relative"})):(t.extend(i,{position:e.css("position"),zIndex:e.css("z-index")}),t.each(["top","left","bottom","right"],function(t,s){i[s]=e.css(s),isNaN(parseInt(i[s],10))&&(i[s]="auto")}),e.css({position:"relative",top:0,left:0,right:"auto",bottom:"auto"})),e.css(n),s.css(i).show()},removeWrapper:function(e){var i=document.activeElement;return e.parent().is(".ui-effects-wrapper")&&(e.parent().replaceWith(e),(e[0]===i||t.contains(e[0],i))&&t(i).focus()),e},setTransition:function(e,i,s,n){return n=n||{},t.each(i,function(t,i){var a=e.cssUnit(i);a[0]>0&&(n[i]=a[0]*s+a[1])}),n}}),t.fn.extend({effect:function(){function e(e){function s(){t.isFunction(a)&&a.call(n[0]),t.isFunction(e)&&e()}var n=t(this),a=i.complete,r=i.mode;(n.is(":hidden")?"hide"===r:"show"===r)?(n[r](),s()):o.call(n[0],i,s)}var i=s.apply(this,arguments),n=i.mode,a=i.queue,o=t.effects.effect[i.effect];return t.fx.off||!o?n?this[n](i.duration,i.complete):this.each(function(){i.complete&&i.complete.call(this)}):a===!1?this.each(e):this.queue(a||"fx",e)},show:function(t){return function(e){if(n(e))return t.apply(this,arguments);var i=s.apply(this,arguments);return i.mode="show",this.effect.call(this,i)}}(t.fn.show),hide:function(t){return function(e){if(n(e))return t.apply(this,arguments);var i=s.apply(this,arguments);return i.mode="hide",this.effect.call(this,i)}}(t.fn.hide),toggle:function(t){return function(e){if(n(e)||"boolean"==typeof e)return t.apply(this,arguments);var i=s.apply(this,arguments);return i.mode="toggle",this.effect.call(this,i)}}(t.fn.toggle),cssUnit:function(e){var i=this.css(e),s=[];return t.each(["em","px","%","pt"],function(t,e){i.indexOf(e)>0&&(s=[parseFloat(i),e])}),s}})}(),function(){var e={};t.each(["Quad","Cubic","Quart","Quint","Expo"],function(t,i){e[i]=function(e){return Math.pow(e,t+2)}}),t.extend(e,{Sine:function(t){return 1-Math.cos(t*Math.PI/2)},Circ:function(t){return 1-Math.sqrt(1-t*t)},Elastic:function(t){return 0===t||1===t?t:-Math.pow(2,8*(t-1))*Math.sin((80*(t-1)-7.5)*Math.PI/15)},Back:function(t){return t*t*(3*t-2)},Bounce:function(t){for(var e,i=4;((e=Math.pow(2,--i))-1)/11>t;);return 1/Math.pow(4,3-i)-7.5625*Math.pow((3*e-2)/22-t,2)}}),t.each(e,function(e,i){t.easing["easeIn"+e]=i,t.easing["easeOut"+e]=function(t){return 1-i(1-t)},t.easing["easeInOut"+e]=function(t){return.5>t?i(2*t)/2:1-i(-2*t+2)/2}})}()})(jQuery);(function(t){var e=/up|down|vertical/,i=/up|left|vertical|horizontal/;t.effects.effect.blind=function(s,n){var a,o,r,l=t(this),h=["position","top","bottom","left","right","height","width"],c=t.effects.setMode(l,s.mode||"hide"),u=s.direction||"up",d=e.test(u),p=d?"height":"width",f=d?"top":"left",g=i.test(u),m={},v="show"===c;l.parent().is(".ui-effects-wrapper")?t.effects.save(l.parent(),h):t.effects.save(l,h),l.show(),a=t.effects.createWrapper(l).css({overflow:"hidden"}),o=a[p](),r=parseFloat(a.css(f))||0,m[p]=v?o:0,g||(l.css(d?"bottom":"right",0).css(d?"top":"left","auto").css({position:"absolute"}),m[f]=v?r:o+r),v&&(a.css(p,0),g||a.css(f,r+o)),a.animate(m,{duration:s.duration,easing:s.easing,queue:!1,complete:function(){"hide"===c&&l.hide(),t.effects.restore(l,h),t.effects.removeWrapper(l),n()}})}})(jQuery);(function(t){t.effects.effect.bounce=function(e,i){var s,n,a,o=t(this),r=["position","top","bottom","left","right","height","width"],l=t.effects.setMode(o,e.mode||"effect"),h="hide"===l,c="show"===l,u=e.direction||"up",d=e.distance,p=e.times||5,f=2*p+(c||h?1:0),g=e.duration/f,m=e.easing,v="up"===u||"down"===u?"top":"left",_="up"===u||"left"===u,b=o.queue(),y=b.length;for((c||h)&&r.push("opacity"),t.effects.save(o,r),o.show(),t.effects.createWrapper(o),d||(d=o["top"===v?"outerHeight":"outerWidth"]()/3),c&&(a={opacity:1},a[v]=0,o.css("opacity",0).css(v,_?2*-d:2*d).animate(a,g,m)),h&&(d/=Math.pow(2,p-1)),a={},a[v]=0,s=0;p>s;s++)n={},n[v]=(_?"-=":"+=")+d,o.animate(n,g,m).animate(a,g,m),d=h?2*d:d/2;h&&(n={opacity:0},n[v]=(_?"-=":"+=")+d,o.animate(n,g,m)),o.queue(function(){h&&o.hide(),t.effects.restore(o,r),t.effects.removeWrapper(o),i()}),y>1&&b.splice.apply(b,[1,0].concat(b.splice(y,f+1))),o.dequeue()}})(jQuery);(function(t){t.effects.effect.clip=function(e,i){var s,n,a,o=t(this),r=["position","top","bottom","left","right","height","width"],l=t.effects.setMode(o,e.mode||"hide"),h="show"===l,c=e.direction||"vertical",u="vertical"===c,d=u?"height":"width",p=u?"top":"left",f={};t.effects.save(o,r),o.show(),s=t.effects.createWrapper(o).css({overflow:"hidden"}),n="IMG"===o[0].tagName?s:o,a=n[d](),h&&(n.css(d,0),n.css(p,a/2)),f[d]=h?a:0,f[p]=h?0:a/2,n.animate(f,{queue:!1,duration:e.duration,easing:e.easing,complete:function(){h||o.hide(),t.effects.restore(o,r),t.effects.removeWrapper(o),i()}})}})(jQuery);(function(t){t.effects.effect.drop=function(e,i){var s,n=t(this),a=["position","top","bottom","left","right","opacity","height","width"],o=t.effects.setMode(n,e.mode||"hide"),r="show"===o,l=e.direction||"left",h="up"===l||"down"===l?"top":"left",c="up"===l||"left"===l?"pos":"neg",u={opacity:r?1:0};t.effects.save(n,a),n.show(),t.effects.createWrapper(n),s=e.distance||n["top"===h?"outerHeight":"outerWidth"](!0)/2,r&&n.css("opacity",0).css(h,"pos"===c?-s:s),u[h]=(r?"pos"===c?"+=":"-=":"pos"===c?"-=":"+=")+s,n.animate(u,{queue:!1,duration:e.duration,easing:e.easing,complete:function(){"hide"===o&&n.hide(),t.effects.restore(n,a),t.effects.removeWrapper(n),i()}})}})(jQuery);(function(t){t.effects.effect.explode=function(e,i){function s(){b.push(this),b.length===u*d&&n()}function n(){p.css({visibility:"visible"}),t(b).remove(),g||p.hide(),i()}var a,o,r,l,h,c,u=e.pieces?Math.round(Math.sqrt(e.pieces)):3,d=u,p=t(this),f=t.effects.setMode(p,e.mode||"hide"),g="show"===f,m=p.show().css("visibility","hidden").offset(),v=Math.ceil(p.outerWidth()/d),_=Math.ceil(p.outerHeight()/u),b=[];for(a=0;u>a;a++)for(l=m.top+a*_,c=a-(u-1)/2,o=0;d>o;o++)r=m.left+o*v,h=o-(d-1)/2,p.clone().appendTo("body").wrap("<div></div>").css({position:"absolute",visibility:"visible",left:-o*v,top:-a*_}).parent().addClass("ui-effects-explode").css({position:"absolute",overflow:"hidden",width:v,height:_,left:r+(g?h*v:0),top:l+(g?c*_:0),opacity:g?0:1}).animate({left:r+(g?0:h*v),top:l+(g?0:c*_),opacity:g?1:0},e.duration||500,e.easing,s)}})(jQuery);(function(t){t.effects.effect.fade=function(e,i){var s=t(this),n=t.effects.setMode(s,e.mode||"toggle");s.animate({opacity:n},{queue:!1,duration:e.duration,easing:e.easing,complete:i})}})(jQuery);(function(t){t.effects.effect.fold=function(e,i){var s,n,a=t(this),o=["position","top","bottom","left","right","height","width"],r=t.effects.setMode(a,e.mode||"hide"),l="show"===r,h="hide"===r,c=e.size||15,u=/([0-9]+)%/.exec(c),d=!!e.horizFirst,p=l!==d,f=p?["width","height"]:["height","width"],g=e.duration/2,m={},v={};t.effects.save(a,o),a.show(),s=t.effects.createWrapper(a).css({overflow:"hidden"}),n=p?[s.width(),s.height()]:[s.height(),s.width()],u&&(c=parseInt(u[1],10)/100*n[h?0:1]),l&&s.css(d?{height:0,width:c}:{height:c,width:0}),m[f[0]]=l?n[0]:c,v[f[1]]=l?n[1]:0,s.animate(m,g,e.easing).animate(v,g,e.easing,function(){h&&a.hide(),t.effects.restore(a,o),t.effects.removeWrapper(a),i()})}})(jQuery);(function(t){t.effects.effect.highlight=function(e,i){var s=t(this),n=["backgroundImage","backgroundColor","opacity"],a=t.effects.setMode(s,e.mode||"show"),o={backgroundColor:s.css("backgroundColor")};"hide"===a&&(o.opacity=0),t.effects.save(s,n),s.show().css({backgroundImage:"none",backgroundColor:e.color||"#ffff99"}).animate(o,{queue:!1,duration:e.duration,easing:e.easing,complete:function(){"hide"===a&&s.hide(),t.effects.restore(s,n),i()}})}})(jQuery);(function(t){t.effects.effect.pulsate=function(e,i){var s,n=t(this),a=t.effects.setMode(n,e.mode||"show"),o="show"===a,r="hide"===a,l=o||"hide"===a,h=2*(e.times||5)+(l?1:0),c=e.duration/h,u=0,d=n.queue(),p=d.length;for((o||!n.is(":visible"))&&(n.css("opacity",0).show(),u=1),s=1;h>s;s++)n.animate({opacity:u},c,e.easing),u=1-u;n.animate({opacity:u},c,e.easing),n.queue(function(){r&&n.hide(),i()}),p>1&&d.splice.apply(d,[1,0].concat(d.splice(p,h+1))),n.dequeue()}})(jQuery);(function(t){t.effects.effect.puff=function(e,i){var s=t(this),n=t.effects.setMode(s,e.mode||"hide"),a="hide"===n,o=parseInt(e.percent,10)||150,r=o/100,l={height:s.height(),width:s.width(),outerHeight:s.outerHeight(),outerWidth:s.outerWidth()};t.extend(e,{effect:"scale",queue:!1,fade:!0,mode:n,complete:i,percent:a?o:100,from:a?l:{height:l.height*r,width:l.width*r,outerHeight:l.outerHeight*r,outerWidth:l.outerWidth*r}}),s.effect(e)},t.effects.effect.scale=function(e,i){var s=t(this),n=t.extend(!0,{},e),a=t.effects.setMode(s,e.mode||"effect"),o=parseInt(e.percent,10)||(0===parseInt(e.percent,10)?0:"hide"===a?0:100),r=e.direction||"both",l=e.origin,h={height:s.height(),width:s.width(),outerHeight:s.outerHeight(),outerWidth:s.outerWidth()},c={y:"horizontal"!==r?o/100:1,x:"vertical"!==r?o/100:1};n.effect="size",n.queue=!1,n.complete=i,"effect"!==a&&(n.origin=l||["middle","center"],n.restore=!0),n.from=e.from||("show"===a?{height:0,width:0,outerHeight:0,outerWidth:0}:h),n.to={height:h.height*c.y,width:h.width*c.x,outerHeight:h.outerHeight*c.y,outerWidth:h.outerWidth*c.x},n.fade&&("show"===a&&(n.from.opacity=0,n.to.opacity=1),"hide"===a&&(n.from.opacity=1,n.to.opacity=0)),s.effect(n)},t.effects.effect.size=function(e,i){var s,n,a,o=t(this),r=["position","top","bottom","left","right","width","height","overflow","opacity"],l=["position","top","bottom","left","right","overflow","opacity"],h=["width","height","overflow"],c=["fontSize"],u=["borderTopWidth","borderBottomWidth","paddingTop","paddingBottom"],d=["borderLeftWidth","borderRightWidth","paddingLeft","paddingRight"],p=t.effects.setMode(o,e.mode||"effect"),f=e.restore||"effect"!==p,g=e.scale||"both",m=e.origin||["middle","center"],v=o.css("position"),_=f?r:l,b={height:0,width:0,outerHeight:0,outerWidth:0};"show"===p&&o.show(),s={height:o.height(),width:o.width(),outerHeight:o.outerHeight(),outerWidth:o.outerWidth()},"toggle"===e.mode&&"show"===p?(o.from=e.to||b,o.to=e.from||s):(o.from=e.from||("show"===p?b:s),o.to=e.to||("hide"===p?b:s)),a={from:{y:o.from.height/s.height,x:o.from.width/s.width},to:{y:o.to.height/s.height,x:o.to.width/s.width}},("box"===g||"both"===g)&&(a.from.y!==a.to.y&&(_=_.concat(u),o.from=t.effects.setTransition(o,u,a.from.y,o.from),o.to=t.effects.setTransition(o,u,a.to.y,o.to)),a.from.x!==a.to.x&&(_=_.concat(d),o.from=t.effects.setTransition(o,d,a.from.x,o.from),o.to=t.effects.setTransition(o,d,a.to.x,o.to))),("content"===g||"both"===g)&&a.from.y!==a.to.y&&(_=_.concat(c).concat(h),o.from=t.effects.setTransition(o,c,a.from.y,o.from),o.to=t.effects.setTransition(o,c,a.to.y,o.to)),t.effects.save(o,_),o.show(),t.effects.createWrapper(o),o.css("overflow","hidden").css(o.from),m&&(n=t.effects.getBaseline(m,s),o.from.top=(s.outerHeight-o.outerHeight())*n.y,o.from.left=(s.outerWidth-o.outerWidth())*n.x,o.to.top=(s.outerHeight-o.to.outerHeight)*n.y,o.to.left=(s.outerWidth-o.to.outerWidth)*n.x),o.css(o.from),("content"===g||"both"===g)&&(u=u.concat(["marginTop","marginBottom"]).concat(c),d=d.concat(["marginLeft","marginRight"]),h=r.concat(u).concat(d),o.find("*[width]").each(function(){var i=t(this),s={height:i.height(),width:i.width(),outerHeight:i.outerHeight(),outerWidth:i.outerWidth()};f&&t.effects.save(i,h),i.from={height:s.height*a.from.y,width:s.width*a.from.x,outerHeight:s.outerHeight*a.from.y,outerWidth:s.outerWidth*a.from.x},i.to={height:s.height*a.to.y,width:s.width*a.to.x,outerHeight:s.height*a.to.y,outerWidth:s.width*a.to.x},a.from.y!==a.to.y&&(i.from=t.effects.setTransition(i,u,a.from.y,i.from),i.to=t.effects.setTransition(i,u,a.to.y,i.to)),a.from.x!==a.to.x&&(i.from=t.effects.setTransition(i,d,a.from.x,i.from),i.to=t.effects.setTransition(i,d,a.to.x,i.to)),i.css(i.from),i.animate(i.to,e.duration,e.easing,function(){f&&t.effects.restore(i,h)})})),o.animate(o.to,{queue:!1,duration:e.duration,easing:e.easing,complete:function(){0===o.to.opacity&&o.css("opacity",o.from.opacity),"hide"===p&&o.hide(),t.effects.restore(o,_),f||("static"===v?o.css({position:"relative",top:o.to.top,left:o.to.left}):t.each(["top","left"],function(t,e){o.css(e,function(e,i){var s=parseInt(i,10),n=t?o.to.left:o.to.top;return"auto"===i?n+"px":s+n+"px"})})),t.effects.removeWrapper(o),i()}})}})(jQuery);(function(t){t.effects.effect.shake=function(e,i){var s,n=t(this),a=["position","top","bottom","left","right","height","width"],o=t.effects.setMode(n,e.mode||"effect"),r=e.direction||"left",l=e.distance||20,h=e.times||3,c=2*h+1,u=Math.round(e.duration/c),d="up"===r||"down"===r?"top":"left",p="up"===r||"left"===r,f={},g={},m={},v=n.queue(),_=v.length;for(t.effects.save(n,a),n.show(),t.effects.createWrapper(n),f[d]=(p?"-=":"+=")+l,g[d]=(p?"+=":"-=")+2*l,m[d]=(p?"-=":"+=")+2*l,n.animate(f,u,e.easing),s=1;h>s;s++)n.animate(g,u,e.easing).animate(m,u,e.easing);n.animate(g,u,e.easing).animate(f,u/2,e.easing).queue(function(){"hide"===o&&n.hide(),t.effects.restore(n,a),t.effects.removeWrapper(n),i()}),_>1&&v.splice.apply(v,[1,0].concat(v.splice(_,c+1))),n.dequeue()}})(jQuery);(function(t){t.effects.effect.slide=function(e,i){var s,n=t(this),a=["position","top","bottom","left","right","width","height"],o=t.effects.setMode(n,e.mode||"show"),r="show"===o,l=e.direction||"left",h="up"===l||"down"===l?"top":"left",c="up"===l||"left"===l,u={};t.effects.save(n,a),n.show(),s=e.distance||n["top"===h?"outerHeight":"outerWidth"](!0),t.effects.createWrapper(n).css({overflow:"hidden"}),r&&n.css(h,c?isNaN(s)?"-"+s:-s:s),u[h]=(r?c?"+=":"-=":c?"-=":"+=")+s,n.animate(u,{queue:!1,duration:e.duration,easing:e.easing,complete:function(){"hide"===o&&n.hide(),t.effects.restore(n,a),t.effects.removeWrapper(n),i()}})}})(jQuery);(function(t){t.effects.effect.transfer=function(e,i){var s=t(this),n=t(e.to),a="fixed"===n.css("position"),o=t("body"),r=a?o.scrollTop():0,l=a?o.scrollLeft():0,h=n.offset(),c={top:h.top-r,left:h.left-l,height:n.innerHeight(),width:n.innerWidth()},u=s.offset(),d=t("<div class='ui-effects-transfer'></div>").appendTo(document.body).addClass(e.className).css({top:u.top-r,left:u.left-l,height:s.innerHeight(),width:s.innerWidth(),position:a?"fixed":"absolute"}).animate(c,e.duration,e.easing,function(){d.remove(),i()})}})(jQuery);