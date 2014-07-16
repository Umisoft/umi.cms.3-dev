define(["Ember"], function(Ember){

Ember.TEMPLATES["UMI/application"] = Ember.Handlebars.template(function anonymous(Handlebars,depth0,helpers,partials,data) {
this.compilerInfo = [4,'>= 1.0.0'];
helpers = this.merge(helpers, Ember.Handlebars.helpers); data = data || {};
  var buffer = '', stack1, helper, options, escapeExpression=this.escapeExpression, helperMissing=helpers.helperMissing;


  data.buffer.push("<div class=\"s-full-height-before umi-header\"> ");
  data.buffer.push(escapeExpression(helpers.view.call(depth0, "topBar", {hash:{},hashTypes:{},hashContexts:{},contexts:[depth0],types:["STRING"],data:data})));
  data.buffer.push(" ");
  data.buffer.push(escapeExpression((helper = helpers.render || (depth0 && depth0.render),options={hash:{},hashTypes:{},hashContexts:{},contexts:[depth0],types:["STRING"],data:data},helper ? helper.call(depth0, "dock", options) : helperMissing.call(depth0, "render", "dock", options))));
  data.buffer.push(" </div> ");
  stack1 = helpers._triageMustache.call(depth0, "outlet", {hash:{},hashTypes:{},hashContexts:{},contexts:[depth0],types:["ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" ");
  data.buffer.push(escapeExpression((helper = helpers.outlet || (depth0 && depth0.outlet),options={hash:{},hashTypes:{},hashContexts:{},contexts:[depth0],types:["STRING"],data:data},helper ? helper.call(depth0, "popup", options) : helperMissing.call(depth0, "outlet", "popup", options))));
  return buffer;
  
});

Ember.TEMPLATES["UMI/component"] = Ember.Handlebars.template(function anonymous(Handlebars,depth0,helpers,partials,data) {
this.compilerInfo = [4,'>= 1.0.0'];
helpers = this.merge(helpers, Ember.Handlebars.helpers); data = data || {};
  var buffer = '', stack1, helperMissing=helpers.helperMissing, escapeExpression=this.escapeExpression, self=this;

function program1(depth0,data) {
  
  var buffer = '', stack1;
  data.buffer.push(" ");
  stack1 = helpers['if'].call(depth0, "sideBarControl", {hash:{},hashTypes:{},hashContexts:{},inverse:self.noop,fn:self.program(2, program2, data),contexts:[depth0],types:["ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" <div ");
  data.buffer.push(escapeExpression(helpers['bind-attr'].call(depth0, {hash:{
    'class': (":umi-divider-right sideBarControl::wide")
  },hashTypes:{'class': "STRING"},hashContexts:{'class': depth0},contexts:[],types:[],data:data})));
  data.buffer.push("> <div class=\"umi-component columns small-12 s-padding-clear s-full-height\"> ");
  stack1 = helpers._triageMustache.call(depth0, "outlet", {hash:{},hashTypes:{},hashContexts:{},contexts:[depth0],types:["ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" </div> </div> ");
  return buffer;
  }
function program2(depth0,data) {
  
  var buffer = '', helper, options;
  data.buffer.push(" <div class=\"umi-divider-left\"> <div class=\"umi-divider-left-content\"> ");
  data.buffer.push(escapeExpression((helper = helpers.outlet || (depth0 && depth0.outlet),options={hash:{},hashTypes:{},hashContexts:{},contexts:[depth0],types:["STRING"],data:data},helper ? helper.call(depth0, "sideBar", options) : helperMissing.call(depth0, "outlet", "sideBar", options))));
  data.buffer.push(" </div> <div class=\"umi-divider\"></div> </div> <div class=\"umi-left-bottom-panel s-unselectable\"> <a href=\"javascript:void(0)\" class=\"button white square umi-divider-left-toggle\"> <i class=\"icon icon-left\"></i> </a> </div> ");
  return buffer;
  }

  data.buffer.push("<div class=\"s-full-height\"> ");
  stack1 = helpers.view.call(depth0, "divider", {hash:{
    'modelBinding': ("model")
  },hashTypes:{'modelBinding': "STRING"},hashContexts:{'modelBinding': depth0},inverse:self.noop,fn:self.program(1, program1, data),contexts:[depth0],types:["STRING"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" </div>");
  return buffer;
  
});

Ember.TEMPLATES["UMI/counterLayout"] = Ember.Handlebars.template(function anonymous(Handlebars,depth0,helpers,partials,data) {
this.compilerInfo = [4,'>= 1.0.0'];
helpers = this.merge(helpers, Ember.Handlebars.helpers); data = data || {};
  var buffer = '', escapeExpression=this.escapeExpression;


  data.buffer.push("<style> .umi-counter{ height: 100%; } .umi-counter-header, .umi-counter-period, .umi-counter-content{ float: left; padding: 20px 30px 0; width: calc(100% - 200px); box-sizing: border-box; } .umi-counter-date{ float: left; margin-right: 30px; } .umi-counter-info{ float: left; width: calc(100% - 200px); height: 100%; } </style> <div class=\"umi-counter\" style=\"background: #F5F6F7;\"> ");
  data.buffer.push(escapeExpression(helpers.view.call(depth0, "accordion", {hash:{},hashTypes:{},hashContexts:{},contexts:[depth0],types:["STRING"],data:data})));
  data.buffer.push(" <div class=\"umi-counter-info\"> <div class=\"umi-counter-period\"> <div class=\"umi-counter-date large-4 small-12\"> <div> <span class=\"umi-form-label\">Начало отчетного периода</span> </div> <div class=\"umi-input-wrapper-date\"> <input type=\"text\" class=\"umi-date umi-date-from\" /> <i class=\"icon icon-calendar\"></i> </div> </div> <div class=\"umi-counter-date large-4 small-12\"> <div> <span class=\"umi-form-label\">Конец отчетного периода</span> </div> <div class=\"umi-input-wrapper-date\"> <input type=\"text\" class=\"umi-date umi-date-to\" /> <i class=\"icon icon-calendar\"></i> </div> </div> </div> <div class=\"umi-counter-content\"> ");
  data.buffer.push(escapeExpression(helpers.view.call(depth0, "chartControl", {hash:{},hashTypes:{},hashContexts:{},contexts:[depth0],types:["STRING"],data:data})));
  data.buffer.push(" </div> <div class=\"umi-counter-content\"> ");
  data.buffer.push(escapeExpression(helpers.view.call(depth0, "table", {hash:{},hashTypes:{},hashContexts:{},contexts:[depth0],types:["STRING"],data:data})));
  data.buffer.push(" </div> </div> </div>");
  return buffer;
  
});

Ember.TEMPLATES["UMI/editForm"] = Ember.Handlebars.template(function anonymous(Handlebars,depth0,helpers,partials,data) {
this.compilerInfo = [4,'>= 1.0.0'];
helpers = this.merge(helpers, Ember.Handlebars.helpers); data = data || {};
  var helper, options, helperMissing=helpers.helperMissing, escapeExpression=this.escapeExpression;


  data.buffer.push(escapeExpression((helper = helpers.render || (depth0 && depth0.render),options={hash:{},hashTypes:{},hashContexts:{},contexts:[depth0,depth0],types:["STRING","ID"],data:data},helper ? helper.call(depth0, "formControl", "model", options) : helperMissing.call(depth0, "render", "formControl", "model", options))));
  
});

Ember.TEMPLATES["UMI/empty"] = Ember.Handlebars.template(function anonymous(Handlebars,depth0,helpers,partials,data) {
this.compilerInfo = [4,'>= 1.0.0'];
helpers = this.merge(helpers, Ember.Handlebars.helpers); data = data || {};
  var buffer = '', stack1;


  data.buffer.push("<div class=\"s-full-height panel\"> <h3 class=\"text-center\">");
  stack1 = helpers._triageMustache.call(depth0, "model.control.params.content", {hash:{},hashTypes:{},hashContexts:{},contexts:[depth0],types:["ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push("</h3> </div> ");
  return buffer;
  
});

Ember.TEMPLATES["UMI/files"] = Ember.Handlebars.template(function anonymous(Handlebars,depth0,helpers,partials,data) {
this.compilerInfo = [4,'>= 1.0.0'];
helpers = this.merge(helpers, Ember.Handlebars.helpers); data = data || {};
  var escapeExpression=this.escapeExpression;


  data.buffer.push(escapeExpression(helpers.view.call(depth0, "fileManager", {hash:{
    'content': ("model")
  },hashTypes:{'content': "ID"},hashContexts:{'content': depth0},contexts:[depth0],types:["STRING"],data:data})));
  
});

Ember.TEMPLATES["UMI/filter"] = Ember.Handlebars.template(function anonymous(Handlebars,depth0,helpers,partials,data) {
this.compilerInfo = [4,'>= 1.0.0'];
helpers = this.merge(helpers, Ember.Handlebars.helpers); data = data || {};
  var helper, options, helperMissing=helpers.helperMissing, escapeExpression=this.escapeExpression;


  data.buffer.push(escapeExpression((helper = helpers.render || (depth0 && depth0.render),options={hash:{},hashTypes:{},hashContexts:{},contexts:[depth0,depth0],types:["STRING","ID"],data:data},helper ? helper.call(depth0, "tableControl", "model", options) : helperMissing.call(depth0, "render", "tableControl", "model", options))));
  
});

Ember.TEMPLATES["UMI/getBacklinks"] = Ember.Handlebars.template(function anonymous(Handlebars,depth0,helpers,partials,data) {
this.compilerInfo = [4,'>= 1.0.0'];
helpers = this.merge(helpers, Ember.Handlebars.helpers); data = data || {};
  var escapeExpression=this.escapeExpression;


  data.buffer.push(escapeExpression(helpers.view.call(depth0, "backlinksTable", {hash:{
    'contentBinding': ("model")
  },hashTypes:{'contentBinding': "STRING"},hashContexts:{'contentBinding': depth0},contexts:[depth0],types:["STRING"],data:data})));
  
});

Ember.TEMPLATES["UMI/host"] = Ember.Handlebars.template(function anonymous(Handlebars,depth0,helpers,partials,data) {
this.compilerInfo = [4,'>= 1.0.0'];
helpers = this.merge(helpers, Ember.Handlebars.helpers); data = data || {};
  var escapeExpression=this.escapeExpression;


  data.buffer.push(escapeExpression(helpers.view.call(depth0, "yaHostTable", {hash:{
    'contentBinding': ("model")
  },hashTypes:{'contentBinding': "STRING"},hashContexts:{'contentBinding': depth0},contexts:[depth0],types:["STRING"],data:data})));
  
});

Ember.TEMPLATES["UMI/indexed"] = Ember.Handlebars.template(function anonymous(Handlebars,depth0,helpers,partials,data) {
this.compilerInfo = [4,'>= 1.0.0'];
helpers = this.merge(helpers, Ember.Handlebars.helpers); data = data || {};
  var escapeExpression=this.escapeExpression;


  data.buffer.push(escapeExpression(helpers.view.call(depth0, "yaIndexesTable", {hash:{
    'contentBinding': ("model")
  },hashTypes:{'contentBinding': "STRING"},hashContexts:{'contentBinding': depth0},contexts:[depth0],types:["STRING"],data:data})));
  
});

Ember.TEMPLATES["UMI/megaIndex"] = Ember.Handlebars.template(function anonymous(Handlebars,depth0,helpers,partials,data) {
this.compilerInfo = [4,'>= 1.0.0'];
helpers = this.merge(helpers, Ember.Handlebars.helpers); data = data || {};
  var escapeExpression=this.escapeExpression;


  data.buffer.push(escapeExpression(helpers.view.call(depth0, "seoMegaIndex", {hash:{
    'contentBinding': ("this")
  },hashTypes:{'contentBinding': "STRING"},hashContexts:{'contentBinding': depth0},contexts:[depth0],types:["STRING"],data:data})));
  
});

Ember.TEMPLATES["UMI/simpleForm"] = Ember.Handlebars.template(function anonymous(Handlebars,depth0,helpers,partials,data) {
this.compilerInfo = [4,'>= 1.0.0'];
helpers = this.merge(helpers, Ember.Handlebars.helpers); data = data || {};
  var helper, options, helperMissing=helpers.helperMissing, escapeExpression=this.escapeExpression;


  data.buffer.push(escapeExpression((helper = helpers.render || (depth0 && depth0.render),options={hash:{},hashTypes:{},hashContexts:{},contexts:[depth0,depth0],types:["STRING","ID"],data:data},helper ? helper.call(depth0, "formBase", "model", options) : helperMissing.call(depth0, "render", "formBase", "model", options))));
  
});

Ember.TEMPLATES["UMI/simpleTable"] = Ember.Handlebars.template(function anonymous(Handlebars,depth0,helpers,partials,data) {
this.compilerInfo = [4,'>= 1.0.0'];
helpers = this.merge(helpers, Ember.Handlebars.helpers); data = data || {};
  var escapeExpression=this.escapeExpression;


  data.buffer.push(escapeExpression(helpers.view.call(depth0, "tableCounters", {hash:{
    'contentBinding': ("model")
  },hashTypes:{'contentBinding': "STRING"},hashContexts:{'contentBinding': depth0},contexts:[depth0],types:["STRING"],data:data})));
  
});

Ember.TEMPLATES["UMI/siteAnalyze"] = Ember.Handlebars.template(function anonymous(Handlebars,depth0,helpers,partials,data) {
this.compilerInfo = [4,'>= 1.0.0'];
helpers = this.merge(helpers, Ember.Handlebars.helpers); data = data || {};
  var escapeExpression=this.escapeExpression;


  data.buffer.push(escapeExpression(helpers.view.call(depth0, "siteAnalyzeTable", {hash:{
    'contentBinding': ("model")
  },hashTypes:{'contentBinding': "STRING"},hashContexts:{'contentBinding': depth0},contexts:[depth0],types:["STRING"],data:data})));
  
});

Ember.TEMPLATES["UMI/tops"] = Ember.Handlebars.template(function anonymous(Handlebars,depth0,helpers,partials,data) {
this.compilerInfo = [4,'>= 1.0.0'];
helpers = this.merge(helpers, Ember.Handlebars.helpers); data = data || {};
  var escapeExpression=this.escapeExpression;


  data.buffer.push(escapeExpression(helpers.view.call(depth0, "yaTopsTable", {hash:{
    'contentBinding': ("model")
  },hashTypes:{'contentBinding': "STRING"},hashContexts:{'contentBinding': depth0},contexts:[depth0],types:["STRING"],data:data})));
  
});

Ember.TEMPLATES["UMI/yandexWebmaster"] = Ember.Handlebars.template(function anonymous(Handlebars,depth0,helpers,partials,data) {
this.compilerInfo = [4,'>= 1.0.0'];
helpers = this.merge(helpers, Ember.Handlebars.helpers); data = data || {};
  var escapeExpression=this.escapeExpression;


  data.buffer.push(escapeExpression(helpers.view.call(depth0, "seoYandexWebmaster", {hash:{
    'contentBinding': ("this")
  },hashTypes:{'contentBinding': "STRING"},hashContexts:{'contentBinding': depth0},contexts:[depth0],types:["STRING"],data:data})));
  
});

Ember.TEMPLATES["UMI/errors"] = Ember.Handlebars.template(function anonymous(Handlebars,depth0,helpers,partials,data) {
this.compilerInfo = [4,'>= 1.0.0'];
helpers = this.merge(helpers, Ember.Handlebars.helpers); data = data || {};
  var buffer = '', stack1, self=this;

function program1(depth0,data) {
  
  var buffer = '', stack1;
  data.buffer.push(" ");
  stack1 = helpers._triageMustache.call(depth0, "status", {hash:{},hashTypes:{},hashContexts:{},contexts:[depth0],types:["ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(". ");
  return buffer;
  }

function program3(depth0,data) {
  
  var buffer = '', stack1;
  data.buffer.push(" <p>");
  stack1 = helpers._triageMustache.call(depth0, "model.content", {hash:{},hashTypes:{},hashContexts:{},contexts:[depth0],types:["ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push("</p> ");
  return buffer;
  }

function program5(depth0,data) {
  
  var buffer = '', stack1;
  data.buffer.push(" <div class=\"text-left\"> <code>");
  stack1 = helpers._triageMustache.call(depth0, "stack", {hash:{},hashTypes:{},hashContexts:{},contexts:[depth0],types:["ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push("</code> </div> ");
  return buffer;
  }

function program7(depth0,data) {
  
  var buffer = '', stack1;
  data.buffer.push(" <div class=\"alert-box error\"> <ul> ");
  stack1 = helpers.each.call(depth0, "lists", {hash:{},hashTypes:{},hashContexts:{},inverse:self.noop,fn:self.program(8, program8, data),contexts:[depth0],types:["ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" </ul> </div> ");
  return buffer;
  }
function program8(depth0,data) {
  
  var buffer = '', stack1;
  data.buffer.push(" <li>");
  stack1 = helpers._triageMustache.call(depth0, "error", {hash:{},hashTypes:{},hashContexts:{},contexts:[depth0],types:["ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push("</li> ");
  return buffer;
  }

  data.buffer.push("<div class=\"umi-component s-full-height\"> <div class=\"row\"> <div class=\"small-10 columns small-centered text-center\"> <p></p> <div> <h2> ");
  stack1 = helpers['if'].call(depth0, "status", {hash:{},hashTypes:{},hashContexts:{},inverse:self.noop,fn:self.program(1, program1, data),contexts:[depth0],types:["ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" ");
  stack1 = helpers._triageMustache.call(depth0, "title", {hash:{},hashTypes:{},hashContexts:{},contexts:[depth0],types:["ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" </h2> ");
  stack1 = helpers['if'].call(depth0, "model.content", {hash:{},hashTypes:{},hashContexts:{},inverse:self.noop,fn:self.program(3, program3, data),contexts:[depth0],types:["ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" ");
  stack1 = helpers['if'].call(depth0, "stack", {hash:{},hashTypes:{},hashContexts:{},inverse:self.noop,fn:self.program(5, program5, data),contexts:[depth0],types:["ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" ");
  stack1 = helpers['if'].call(depth0, "lists", {hash:{},hashTypes:{},hashContexts:{},inverse:self.noop,fn:self.program(7, program7, data),contexts:[depth0],types:["ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" </div> </div> </div> </div>");
  return buffer;
  
});

Ember.TEMPLATES["UMI/menu"] = Ember.Handlebars.template(function anonymous(Handlebars,depth0,helpers,partials,data) {
this.compilerInfo = [4,'>= 1.0.0'];
helpers = this.merge(helpers, Ember.Handlebars.helpers); data = data || {};
  var helper, options, helperMissing=helpers.helperMissing, escapeExpression=this.escapeExpression;


  data.buffer.push(escapeExpression((helper = helpers.render || (depth0 && depth0.render),options={hash:{},hashTypes:{},hashContexts:{},contexts:[depth0,depth0],types:["STRING","ID"],data:data},helper ? helper.call(depth0, "sideMenu", "model", options) : helperMissing.call(depth0, "render", "sideMenu", "model", options))));
  
});

Ember.TEMPLATES["UMI/tree"] = Ember.Handlebars.template(function anonymous(Handlebars,depth0,helpers,partials,data) {
this.compilerInfo = [4,'>= 1.0.0'];
helpers = this.merge(helpers, Ember.Handlebars.helpers); data = data || {};
  var helper, options, helperMissing=helpers.helperMissing, escapeExpression=this.escapeExpression;


  data.buffer.push(escapeExpression((helper = helpers.render || (depth0 && depth0.render),options={hash:{},hashTypes:{},hashContexts:{},contexts:[depth0,depth0],types:["STRING","ID"],data:data},helper ? helper.call(depth0, "treeControl", "model", options) : helperMissing.call(depth0, "render", "treeControl", "model", options))));
  
});

Ember.TEMPLATES["UMI/partials/chartControl"] = Ember.Handlebars.template(function anonymous(Handlebars,depth0,helpers,partials,data) {
this.compilerInfo = [4,'>= 1.0.0'];
helpers = this.merge(helpers, Ember.Handlebars.helpers); data = data || {};
  


  data.buffer.push("<div class=\"umi-canvas-wrapper\"> <canvas id=\"umi-metrika-canvas\"></canvas> </div>");
  
});

Ember.TEMPLATES["UMI/partials/dialog-layout"] = Ember.Handlebars.template(function anonymous(Handlebars,depth0,helpers,partials,data) {
this.compilerInfo = [4,'>= 1.0.0'];
helpers = this.merge(helpers, Ember.Handlebars.helpers); data = data || {};
  var stack1, escapeExpression=this.escapeExpression, self=this;

function program1(depth0,data) {
  
  var buffer = '', stack1;
  data.buffer.push(" <div class=\"umi-overlay\"></div> <div ");
  data.buffer.push(escapeExpression(helpers['bind-attr'].call(depth0, {hash:{
    'class': (":umi-dialog model.type")
  },hashTypes:{'class': "STRING"},hashContexts:{'class': depth0},contexts:[],types:[],data:data})));
  data.buffer.push("> ");
  stack1 = helpers['if'].call(depth0, "model.close", {hash:{},hashTypes:{},hashContexts:{},inverse:self.noop,fn:self.program(2, program2, data),contexts:[depth0],types:["ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" ");
  stack1 = helpers._triageMustache.call(depth0, "yield", {hash:{},hashTypes:{},hashContexts:{},contexts:[depth0],types:["ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" </div> ");
  return buffer;
  }
function program2(depth0,data) {
  
  var buffer = '';
  data.buffer.push(" <a href=\"\" ");
  data.buffer.push(escapeExpression(helpers.action.call(depth0, "close", "model", {hash:{
    'target': ("view")
  },hashTypes:{'target': "STRING"},hashContexts:{'target': depth0},contexts:[depth0,depth0],types:["STRING","ID"],data:data})));
  data.buffer.push(" class=\"close\">&times;</a> ");
  return buffer;
  }

  stack1 = helpers['if'].call(depth0, "model", {hash:{},hashTypes:{},hashContexts:{},inverse:self.noop,fn:self.program(1, program1, data),contexts:[depth0],types:["ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  else { data.buffer.push(''); }
  
});

Ember.TEMPLATES["UMI/partials/dialog-template"] = Ember.Handlebars.template(function anonymous(Handlebars,depth0,helpers,partials,data) {
this.compilerInfo = [4,'>= 1.0.0'];
helpers = this.merge(helpers, Ember.Handlebars.helpers); data = data || {};
  var buffer = '', stack1, escapeExpression=this.escapeExpression, helperMissing=helpers.helperMissing, self=this;

function program1(depth0,data) {
  
  var buffer = '', stack1;
  data.buffer.push(" <h5 class=\"subheader umi-dialog-header\">");
  stack1 = helpers._triageMustache.call(depth0, "model.title", {hash:{},hashTypes:{},hashContexts:{},contexts:[depth0],types:["ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push("</h5> ");
  return buffer;
  }

function program3(depth0,data) {
  
  var buffer = '';
  data.buffer.push(" ");
  data.buffer.push(escapeExpression(helpers._triageMustache.call(depth0, "model.content", {hash:{
    'unescaped': ("true")
  },hashTypes:{'unescaped': "STRING"},hashContexts:{'unescaped': depth0},contexts:[depth0],types:["ID"],data:data})));
  data.buffer.push(" ");
  return buffer;
  }

function program5(depth0,data) {
  
  var buffer = '', stack1, helper, options;
  data.buffer.push(" <div class=\"umi-dialog-content\"> ");
  stack1 = helpers._triageMustache.call(depth0, "checkbox-element", {hash:{},hashTypes:{},hashContexts:{},contexts:[depth0],types:["ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" ");
  data.buffer.push(escapeExpression((helper = helpers.i18n || (depth0 && depth0.i18n),options={hash:{},hashTypes:{},hashContexts:{},contexts:[depth0],types:["STRING"],data:data},helper ? helper.call(depth0, "Remember my choice", options) : helperMissing.call(depth0, "i18n", "Remember my choice", options))));
  data.buffer.push(" </div> ");
  return buffer;
  }

function program7(depth0,data) {
  
  var buffer = '', stack1;
  data.buffer.push(" <button class=\"button small secondary left\" ");
  data.buffer.push(escapeExpression(helpers.action.call(depth0, "confirm", {hash:{
    'target': ("view")
  },hashTypes:{'target': "STRING"},hashContexts:{'target': depth0},contexts:[depth0],types:["STRING"],data:data})));
  data.buffer.push(">");
  stack1 = helpers._triageMustache.call(depth0, "model.confirm", {hash:{},hashTypes:{},hashContexts:{},contexts:[depth0],types:["ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push("</button> ");
  return buffer;
  }

function program9(depth0,data) {
  
  var buffer = '', stack1;
  data.buffer.push(" <button class=\"button small secondary right\" ");
  data.buffer.push(escapeExpression(helpers.action.call(depth0, "close", {hash:{
    'target': ("view")
  },hashTypes:{'target': "STRING"},hashContexts:{'target': depth0},contexts:[depth0],types:["STRING"],data:data})));
  data.buffer.push(">");
  stack1 = helpers._triageMustache.call(depth0, "model.reject", {hash:{},hashTypes:{},hashContexts:{},contexts:[depth0],types:["ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push("</button> ");
  return buffer;
  }

  stack1 = helpers['if'].call(depth0, "model.title", {hash:{},hashTypes:{},hashContexts:{},inverse:self.noop,fn:self.program(1, program1, data),contexts:[depth0],types:["ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" <div class=\"umi-dialog-content\"> ");
  stack1 = helpers['if'].call(depth0, "model.content", {hash:{},hashTypes:{},hashContexts:{},inverse:self.noop,fn:self.program(3, program3, data),contexts:[depth0],types:["ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" </div> ");
  stack1 = helpers['if'].call(depth0, "model.proposeRemember", {hash:{},hashTypes:{},hashContexts:{},inverse:self.noop,fn:self.program(5, program5, data),contexts:[depth0],types:["ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" ");
  stack1 = helpers['if'].call(depth0, "model.confirm", {hash:{},hashTypes:{},hashContexts:{},inverse:self.noop,fn:self.program(7, program7, data),contexts:[depth0],types:["ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" ");
  stack1 = helpers['if'].call(depth0, "model.reject", {hash:{},hashTypes:{},hashContexts:{},inverse:self.noop,fn:self.program(9, program9, data),contexts:[depth0],types:["ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" ");
  return buffer;
  
});

Ember.TEMPLATES["UMI/partials/dock"] = Ember.Handlebars.template(function anonymous(Handlebars,depth0,helpers,partials,data) {
this.compilerInfo = [4,'>= 1.0.0'];
helpers = this.merge(helpers, Ember.Handlebars.helpers); data = data || {};
  var buffer = '', stack1, escapeExpression=this.escapeExpression, self=this, helperMissing=helpers.helperMissing;

function program1(depth0,data) {
  
  var buffer = '', stack1;
  data.buffer.push(" ");
  stack1 = helpers.view.call(depth0, "dockModuleButton", {hash:{
    'module': ("module")
  },hashTypes:{'module': "ID"},hashContexts:{'module': depth0},inverse:self.noop,fn:self.program(2, program2, data),contexts:[depth0],types:["STRING"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" ");
  return buffer;
  }
function program2(depth0,data) {
  
  var buffer = '', stack1, helper, options;
  data.buffer.push(" ");
  stack1 = (helper = helpers['link-to'] || (depth0 && depth0['link-to']),options={hash:{
    'tagName': ("span"),
    'class': ("dock-module {{unbound module.name}}")
  },hashTypes:{'tagName': "STRING",'class': "STRING"},hashContexts:{'tagName': depth0,'class': depth0},inverse:self.noop,fn:self.program(3, program3, data),contexts:[depth0,depth0],types:["STRING","ID"],data:data},helper ? helper.call(depth0, "module", "module.name", options) : helperMissing.call(depth0, "link-to", "module", "module.name", options));
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" <ul class=\"f-dropdown center\"> ");
  stack1 = helpers.each.call(depth0, "component", "in", "module.components", {hash:{},hashTypes:{},hashContexts:{},inverse:self.noop,fn:self.program(5, program5, data),contexts:[depth0,depth0,depth0],types:["ID","ID","ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" </ul> ");
  return buffer;
  }
function program3(depth0,data) {
  
  var buffer = '', stack1;
  data.buffer.push(" <img ");
  data.buffer.push(escapeExpression(helpers['bind-attr'].call(depth0, {hash:{
    'src': ("view.icon")
  },hashTypes:{'src': "ID"},hashContexts:{'src': depth0},contexts:[],types:[],data:data})));
  data.buffer.push(" /> <span>");
  stack1 = helpers._triageMustache.call(depth0, "module.displayName", {hash:{},hashTypes:{},hashContexts:{},contexts:[depth0],types:["ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push("</span> ");
  return buffer;
  }

function program5(depth0,data) {
  
  var buffer = '', stack1, helper, options;
  data.buffer.push(" <li class=\"");
  data.buffer.push(escapeExpression(helpers.unbound.call(depth0, "component.name", {hash:{},hashTypes:{},hashContexts:{},contexts:[depth0],types:["ID"],data:data})));
  data.buffer.push("\"> ");
  stack1 = (helper = helpers['link-to'] || (depth0 && depth0['link-to']),options={hash:{},hashTypes:{},hashContexts:{},inverse:self.noop,fn:self.program(6, program6, data),contexts:[depth0,depth0,depth0],types:["STRING","ID","ID"],data:data},helper ? helper.call(depth0, "component", "module.name", "component.name", options) : helperMissing.call(depth0, "link-to", "component", "module.name", "component.name", options));
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" </li> ");
  return buffer;
  }
function program6(depth0,data) {
  
  var buffer = '', stack1;
  data.buffer.push(" ");
  stack1 = helpers._triageMustache.call(depth0, "component.displayName", {hash:{},hashTypes:{},hashContexts:{},contexts:[depth0],types:["ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" ");
  return buffer;
  }

function program8(depth0,data) {
  
  var buffer = '', stack1;
  data.buffer.push(" ");
  stack1 = helpers['with'].call(depth0, "activeModule", "as", "module", {hash:{},hashTypes:{},hashContexts:{},inverse:self.noop,fn:self.program(9, program9, data),contexts:[depth0,depth0,depth0],types:["ID","ID","ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" ");
  return buffer;
  }
function program9(depth0,data) {
  
  var buffer = '', stack1;
  data.buffer.push(" ");
  stack1 = helpers.each.call(depth0, "component", "in", "module.components", {hash:{},hashTypes:{},hashContexts:{},inverse:self.noop,fn:self.program(10, program10, data),contexts:[depth0,depth0,depth0],types:["ID","ID","ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" ");
  return buffer;
  }
function program10(depth0,data) {
  
  var buffer = '', stack1, helper, options;
  data.buffer.push(" ");
  stack1 = (helper = helpers['link-to'] || (depth0 && depth0['link-to']),options={hash:{
    'class': ("component.name")
  },hashTypes:{'class': "ID"},hashContexts:{'class': depth0},inverse:self.noop,fn:self.program(6, program6, data),contexts:[depth0,depth0,depth0],types:["STRING","ID","ID"],data:data},helper ? helper.call(depth0, "component", "module.name", "component.name", options) : helperMissing.call(depth0, "link-to", "component", "module.name", "component.name", options));
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" ");
  return buffer;
  }

  data.buffer.push("<div class=\"dock-wrapper\"> <div class=\"dock-wrapper-bg\"> <ul class=\"dock navigation\"> ");
  stack1 = helpers.each.call(depth0, "module", "in", "modules", {hash:{},hashTypes:{},hashContexts:{},inverse:self.noop,fn:self.program(1, program1, data),contexts:[depth0,depth0,depth0],types:["ID","ID","ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" </ul> </div> </div> <div class=\"dock-components\"> <nav class=\"components-nav\"> ");
  stack1 = helpers['if'].call(depth0, "activeModule", {hash:{},hashTypes:{},hashContexts:{},inverse:self.noop,fn:self.program(8, program8, data),contexts:[depth0],types:["ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" </nav> </div>");
  return buffer;
  
});

Ember.TEMPLATES["UMI/partials/checkboxGroup/CollectionElement"] = Ember.Handlebars.template(function anonymous(Handlebars,depth0,helpers,partials,data) {
this.compilerInfo = [4,'>= 1.0.0'];
helpers = this.merge(helpers, Ember.Handlebars.helpers); data = data || {};
  var stack1, escapeExpression=this.escapeExpression, self=this;

function program1(depth0,data) {
  
  var buffer = '';
  data.buffer.push(" ");
  data.buffer.push(escapeExpression(helpers.view.call(depth0, "view.checkboxElementView", {hash:{},hashTypes:{},hashContexts:{},contexts:[depth0],types:["ID"],data:data})));
  data.buffer.push(" ");
  return buffer;
  }

  stack1 = helpers.each.call(depth0, "meta.choices", {hash:{},hashTypes:{},hashContexts:{},inverse:self.noop,fn:self.program(1, program1, data),contexts:[depth0],types:["ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  else { data.buffer.push(''); }
  
});

Ember.TEMPLATES["UMI/partials/checkboxGroup"] = Ember.Handlebars.template(function anonymous(Handlebars,depth0,helpers,partials,data) {
this.compilerInfo = [4,'>= 1.0.0'];
helpers = this.merge(helpers, Ember.Handlebars.helpers); data = data || {};
  var stack1, escapeExpression=this.escapeExpression, self=this;

function program1(depth0,data) {
  
  var buffer = '';
  data.buffer.push(" ");
  data.buffer.push(escapeExpression(helpers.view.call(depth0, "checkboxElement", {hash:{
    'meta': ("element")
  },hashTypes:{'meta': "ID"},hashContexts:{'meta': depth0},contexts:[depth0],types:["STRING"],data:data})));
  data.buffer.push(" ");
  return buffer;
  }

  stack1 = helpers.each.call(depth0, "element", "in", "meta.choices", {hash:{},hashTypes:{},hashContexts:{},inverse:self.noop,fn:self.program(1, program1, data),contexts:[depth0,depth0,depth0],types:["ID","ID","ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  else { data.buffer.push(''); }
  
});

Ember.TEMPLATES["UMI/partials/dateElement"] = Ember.Handlebars.template(function anonymous(Handlebars,depth0,helpers,partials,data) {
this.compilerInfo = [4,'>= 1.0.0'];
helpers = this.merge(helpers, Ember.Handlebars.helpers); data = data || {};
  var buffer = '', escapeExpression=this.escapeExpression;


  data.buffer.push("<div class=\"small-11 columns\"> ");
  data.buffer.push(escapeExpression(helpers.view.call(depth0, "textElement", {hash:{
    'object': ("view.object"),
    'meta': ("view.meta")
  },hashTypes:{'object': "ID",'meta': "ID"},hashContexts:{'object': depth0,'meta': depth0},contexts:[depth0],types:["STRING"],data:data})));
  data.buffer.push(" </div> <div class=\"small-1 columns\"> <span class=\"postfix\" ");
  data.buffer.push(escapeExpression(helpers.action.call(depth0, "clearValue", {hash:{
    'target': ("view")
  },hashTypes:{'target': "STRING"},hashContexts:{'target': depth0},contexts:[depth0],types:["STRING"],data:data})));
  data.buffer.push("> <i class=\"icon icon-delete\"></i> </span> </div>");
  return buffer;
  
});

Ember.TEMPLATES["UMI/partials/dateTimeElement"] = Ember.Handlebars.template(function anonymous(Handlebars,depth0,helpers,partials,data) {
this.compilerInfo = [4,'>= 1.0.0'];
helpers = this.merge(helpers, Ember.Handlebars.helpers); data = data || {};
  var buffer = '', helper, options, helperMissing=helpers.helperMissing, escapeExpression=this.escapeExpression;


  data.buffer.push("<div class=\"small-11 columns\"> ");
  data.buffer.push(escapeExpression((helper = helpers.input || (depth0 && depth0.input),options={hash:{
    'type': ("text"),
    'class': ("umi-date"),
    'value': ("view.value")
  },hashTypes:{'type': "STRING",'class': "STRING",'value': "ID"},hashContexts:{'type': depth0,'class': depth0,'value': depth0},contexts:[],types:[],data:data},helper ? helper.call(depth0, options) : helperMissing.call(depth0, "input", options))));
  data.buffer.push(" </div> <div class=\"small-1 columns\"> <span class=\"postfix\" ");
  data.buffer.push(escapeExpression(helpers.action.call(depth0, "clearValue", {hash:{
    'target': ("view")
  },hashTypes:{'target': "STRING"},hashContexts:{'target': depth0},contexts:[depth0],types:["STRING"],data:data})));
  data.buffer.push("> <i class=\"icon icon-delete\"></i> </span> </div>");
  return buffer;
  
});

Ember.TEMPLATES["UMI/partials/fileElement"] = Ember.Handlebars.template(function anonymous(Handlebars,depth0,helpers,partials,data) {
this.compilerInfo = [4,'>= 1.0.0'];
helpers = this.merge(helpers, Ember.Handlebars.helpers); data = data || {};
  var buffer = '', escapeExpression=this.escapeExpression;


  data.buffer.push("<div class=\"small-10 columns\"> ");
  data.buffer.push(escapeExpression(helpers.view.call(depth0, "textElement", {hash:{
    'object': ("view.object"),
    'meta': ("view.meta")
  },hashTypes:{'object': "ID",'meta': "ID"},hashContexts:{'object': depth0,'meta': depth0},contexts:[depth0],types:["STRING"],data:data})));
  data.buffer.push(" </div> <div class=\"small-2 columns\"> <span class=\"postfix\"> <i class=\"icon icon-delete\" ");
  data.buffer.push(escapeExpression(helpers.action.call(depth0, "clearValue", {hash:{
    'target': ("view")
  },hashTypes:{'target': "STRING"},hashContexts:{'target': depth0},contexts:[depth0],types:["STRING"],data:data})));
  data.buffer.push("></i> <i class=\"icon icon-open-folder\" ");
  data.buffer.push(escapeExpression(helpers.action.call(depth0, "showPopup", "fileManager", "view.object", "view.meta", {hash:{},hashTypes:{},hashContexts:{},contexts:[depth0,depth0,depth0,depth0],types:["STRING","STRING","ID","ID"],data:data})));
  data.buffer.push("></i> </span> </div>");
  return buffer;
  
});

Ember.TEMPLATES["UMI/partials/imageElement"] = Ember.Handlebars.template(function anonymous(Handlebars,depth0,helpers,partials,data) {
this.compilerInfo = [4,'>= 1.0.0'];
helpers = this.merge(helpers, Ember.Handlebars.helpers); data = data || {};
  var buffer = '', escapeExpression=this.escapeExpression;


  data.buffer.push("<div class=\"small-10 columns\"> ");
  data.buffer.push(escapeExpression(helpers.view.call(depth0, "textElement", {hash:{
    'object': ("view.object"),
    'meta': ("view.meta")
  },hashTypes:{'object': "ID",'meta': "ID"},hashContexts:{'object': depth0,'meta': depth0},contexts:[depth0],types:["STRING"],data:data})));
  data.buffer.push(" </div> <div class=\"small-2 columns\"> <span class=\"postfix\"> <i class=\"icon icon-delete\" ");
  data.buffer.push(escapeExpression(helpers.action.call(depth0, "clearValue", {hash:{
    'target': ("view")
  },hashTypes:{'target': "STRING"},hashContexts:{'target': depth0},contexts:[depth0],types:["STRING"],data:data})));
  data.buffer.push("></i> <i class=\"icon icon-image\" ");
  data.buffer.push(escapeExpression(helpers.action.call(depth0, "showPopup", "fileManager", "view.object", "view.meta", {hash:{},hashTypes:{},hashContexts:{},contexts:[depth0,depth0,depth0,depth0],types:["STRING","STRING","ID","ID"],data:data})));
  data.buffer.push("></i> </span> </div>");
  return buffer;
  
});

Ember.TEMPLATES["UMI/partials/multi-select-lazy-choices"] = Ember.Handlebars.template(function anonymous(Handlebars,depth0,helpers,partials,data) {
this.compilerInfo = [4,'>= 1.0.0'];
helpers = this.merge(helpers, Ember.Handlebars.helpers); data = data || {};
  var buffer = '', stack1, escapeExpression=this.escapeExpression, helperMissing=helpers.helperMissing, self=this;

function program1(depth0,data) {
  
  var buffer = '', stack1;
  data.buffer.push(" <ul class=\"umi-multi-select-list\"> ");
  stack1 = helpers.each.call(depth0, "view.notSelectedObjects", {hash:{},hashTypes:{},hashContexts:{},inverse:self.program(4, program4, data),fn:self.program(2, program2, data),contexts:[depth0],types:["ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" </ul> ");
  return buffer;
  }
function program2(depth0,data) {
  
  var buffer = '', stack1;
  data.buffer.push(" <li ");
  data.buffer.push(escapeExpression(helpers.action.call(depth0, "select", "id", {hash:{
    'target': ("view")
  },hashTypes:{'target': "STRING"},hashContexts:{'target': depth0},contexts:[depth0,depth0],types:["STRING","ID"],data:data})));
  data.buffer.push(" ");
  data.buffer.push(escapeExpression(helpers['bind-attr'].call(depth0, {hash:{
    'class': ("hover")
  },hashTypes:{'class': "STRING"},hashContexts:{'class': depth0},contexts:[],types:[],data:data})));
  data.buffer.push("> ");
  stack1 = helpers._triageMustache.call(depth0, "displayName", {hash:{},hashTypes:{},hashContexts:{},contexts:[depth0],types:["ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" </li> ");
  return buffer;
  }

function program4(depth0,data) {
  
  var buffer = '', helper, options;
  data.buffer.push(" <li class=\"placeholder\"> ");
  data.buffer.push(escapeExpression((helper = helpers.i18n || (depth0 && depth0.i18n),options={hash:{},hashTypes:{},hashContexts:{},contexts:[depth0],types:["STRING"],data:data},helper ? helper.call(depth0, "Nothing found", options) : helperMissing.call(depth0, "i18n", "Nothing found", options))));
  data.buffer.push(" </li> ");
  return buffer;
  }

function program6(depth0,data) {
  
  var buffer = '', stack1;
  data.buffer.push(" <div class=\"selected-list\"> ");
  stack1 = helpers.each.call(depth0, "view.selectedObjects", {hash:{},hashTypes:{},hashContexts:{},inverse:self.noop,fn:self.program(7, program7, data),contexts:[depth0],types:["ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" </div> ");
  return buffer;
  }
function program7(depth0,data) {
  
  var buffer = '', stack1;
  data.buffer.push(" <div class=\"item\" ");
  data.buffer.push(escapeExpression(helpers.action.call(depth0, "unSelect", "id", {hash:{
    'target': ("view")
  },hashTypes:{'target': "STRING"},hashContexts:{'target': depth0},contexts:[depth0,depth0],types:["STRING","ID"],data:data})));
  data.buffer.push(">");
  stack1 = helpers._triageMustache.call(depth0, "displayName", {hash:{},hashTypes:{},hashContexts:{},contexts:[depth0],types:["ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" <i class=\"close\">&times;</i></div> ");
  return buffer;
  }

  data.buffer.push("<div class=\"small-12 columns\"> ");
  data.buffer.push(escapeExpression(helpers.view.call(depth0, "view.inputView", {hash:{},hashTypes:{},hashContexts:{},contexts:[depth0],types:["ID"],data:data})));
  data.buffer.push(" <span class=\"postfix radius\" ");
  data.buffer.push(escapeExpression(helpers.action.call(depth0, "toggleList", {hash:{
    'target': ("view")
  },hashTypes:{'target': "STRING"},hashContexts:{'target': depth0},contexts:[depth0],types:["STRING"],data:data})));
  data.buffer.push("> <i class=\"triangle\"></i> </span> ");
  stack1 = helpers['if'].call(depth0, "view.isOpen", {hash:{},hashTypes:{},hashContexts:{},inverse:self.noop,fn:self.program(1, program1, data),contexts:[depth0],types:["ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" </div> ");
  stack1 = helpers['if'].call(depth0, "view.selectedObjects.length", {hash:{},hashTypes:{},hashContexts:{},inverse:self.noop,fn:self.program(6, program6, data),contexts:[depth0],types:["ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  return buffer;
  
});

Ember.TEMPLATES["UMI/partials/multi-select"] = Ember.Handlebars.template(function anonymous(Handlebars,depth0,helpers,partials,data) {
this.compilerInfo = [4,'>= 1.0.0'];
helpers = this.merge(helpers, Ember.Handlebars.helpers); data = data || {};
  var buffer = '', stack1, escapeExpression=this.escapeExpression, helperMissing=helpers.helperMissing, self=this;

function program1(depth0,data) {
  
  var buffer = '', stack1;
  data.buffer.push(" <ul class=\"umi-multi-select-list\"> ");
  stack1 = helpers.each.call(depth0, "view.notSelectedObjects", {hash:{},hashTypes:{},hashContexts:{},inverse:self.program(4, program4, data),fn:self.program(2, program2, data),contexts:[depth0],types:["ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" </ul> ");
  return buffer;
  }
function program2(depth0,data) {
  
  var buffer = '', stack1;
  data.buffer.push(" <li ");
  data.buffer.push(escapeExpression(helpers.action.call(depth0, "select", "value", {hash:{
    'target': ("view")
  },hashTypes:{'target': "STRING"},hashContexts:{'target': depth0},contexts:[depth0,depth0],types:["STRING","ID"],data:data})));
  data.buffer.push(" ");
  data.buffer.push(escapeExpression(helpers['bind-attr'].call(depth0, {hash:{
    'class': ("hover")
  },hashTypes:{'class': "STRING"},hashContexts:{'class': depth0},contexts:[],types:[],data:data})));
  data.buffer.push("> ");
  stack1 = helpers._triageMustache.call(depth0, "label", {hash:{},hashTypes:{},hashContexts:{},contexts:[depth0],types:["ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" </li> ");
  return buffer;
  }

function program4(depth0,data) {
  
  var buffer = '', helper, options;
  data.buffer.push(" <li class=\"placeholder\"> ");
  data.buffer.push(escapeExpression((helper = helpers.i18n || (depth0 && depth0.i18n),options={hash:{},hashTypes:{},hashContexts:{},contexts:[depth0],types:["STRING"],data:data},helper ? helper.call(depth0, "Nothing found", options) : helperMissing.call(depth0, "i18n", "Nothing found", options))));
  data.buffer.push(" </li> ");
  return buffer;
  }

function program6(depth0,data) {
  
  var buffer = '', stack1;
  data.buffer.push(" <div class=\"selected-list\"> ");
  stack1 = helpers.each.call(depth0, "view.selectedObjects", {hash:{},hashTypes:{},hashContexts:{},inverse:self.noop,fn:self.program(7, program7, data),contexts:[depth0],types:["ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" </div> ");
  return buffer;
  }
function program7(depth0,data) {
  
  var buffer = '', stack1;
  data.buffer.push(" <div class=\"item\" ");
  data.buffer.push(escapeExpression(helpers.action.call(depth0, "unSelect", "value", {hash:{
    'target': ("view")
  },hashTypes:{'target': "STRING"},hashContexts:{'target': depth0},contexts:[depth0,depth0],types:["STRING","ID"],data:data})));
  data.buffer.push(">");
  stack1 = helpers._triageMustache.call(depth0, "label", {hash:{},hashTypes:{},hashContexts:{},contexts:[depth0],types:["ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" <i class=\"close\">&times;</i></div> ");
  return buffer;
  }

  data.buffer.push("<div class=\"small-12 columns\"> ");
  data.buffer.push(escapeExpression(helpers.view.call(depth0, "view.inputView", {hash:{},hashTypes:{},hashContexts:{},contexts:[depth0],types:["ID"],data:data})));
  data.buffer.push(" <span class=\"postfix radius\" ");
  data.buffer.push(escapeExpression(helpers.action.call(depth0, "toggleList", {hash:{
    'target': ("view")
  },hashTypes:{'target': "STRING"},hashContexts:{'target': depth0},contexts:[depth0],types:["STRING"],data:data})));
  data.buffer.push("> <i class=\"triangle\"></i> </span> ");
  stack1 = helpers['if'].call(depth0, "view.isOpen", {hash:{},hashTypes:{},hashContexts:{},inverse:self.noop,fn:self.program(1, program1, data),contexts:[depth0],types:["ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" </div> ");
  stack1 = helpers['if'].call(depth0, "view.selectedObjects.length", {hash:{},hashTypes:{},hashContexts:{},inverse:self.noop,fn:self.program(6, program6, data),contexts:[depth0],types:["ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  return buffer;
  
});

Ember.TEMPLATES["UMI/partials/permissions"] = Ember.Handlebars.template(function anonymous(Handlebars,depth0,helpers,partials,data) {
this.compilerInfo = [4,'>= 1.0.0'];
helpers = this.merge(helpers, Ember.Handlebars.helpers); data = data || {};
  var buffer = '', stack1, escapeExpression=this.escapeExpression, self=this;

function program1(depth0,data) {
  
  var buffer = '', stack1;
  data.buffer.push(" <dl class=\"accordion\"> <dd class=\"accordion-navigation\"> <a class=\"accordion-navigation-button\" href=\"javascript:void(0)\"><i class=\"icon icon-right\"></i> ");
  stack1 = helpers._triageMustache.call(depth0, "component.label", {hash:{},hashTypes:{},hashContexts:{},contexts:[depth0],types:["ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push("</a> <div class=\"content\"> ");
  data.buffer.push(escapeExpression(helpers.view.call(depth0, "permissionsPartial", {hash:{
    'component': ("component")
  },hashTypes:{'component': "ID"},hashContexts:{'component': depth0},contexts:[depth0],types:["STRING"],data:data})));
  data.buffer.push(" </div> </dd> </dl> ");
  return buffer;
  }

  data.buffer.push("<div class=\"umi-permissions\"> ");
  stack1 = helpers.each.call(depth0, "component", "in", "view.meta.resources", {hash:{},hashTypes:{},hashContexts:{},inverse:self.noop,fn:self.program(1, program1, data),contexts:[depth0,depth0,depth0],types:["ID","ID","ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" </div>");
  return buffer;
  
});

Ember.TEMPLATES["UMI/partials/permissions/partial"] = Ember.Handlebars.template(function anonymous(Handlebars,depth0,helpers,partials,data) {
this.compilerInfo = [4,'>= 1.0.0'];
helpers = this.merge(helpers, Ember.Handlebars.helpers); data = data || {};
  var stack1, escapeExpression=this.escapeExpression, self=this;

function program1(depth0,data) {
  
  var buffer = '', stack1;
  data.buffer.push(" <li class=\"umi-permissions-role-list-item\"> <div class=\"umi-permissions-role\"> ");
  stack1 = helpers['if'].call(depth0, "role.component", {hash:{},hashTypes:{},hashContexts:{},inverse:self.noop,fn:self.program(2, program2, data),contexts:[depth0],types:["ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" <span class=\"umi-permissions-role-label\" ");
  data.buffer.push(escapeExpression(helpers['bind-attr'].call(depth0, {hash:{
    'data-permissions-component-path': ("view.component.path")
  },hashTypes:{'data-permissions-component-path': "STRING"},hashContexts:{'data-permissions-component-path': depth0},contexts:[],types:[],data:data})));
  data.buffer.push("> ");
  data.buffer.push(escapeExpression(helpers.view.call(depth0, "checkboxElement", {hash:{
    'meta': ("role"),
    'name': ("role.value"),
    'attributeValue': ("role.value"),
    'value': (""),
    'className': ("umi-permissions-role-checkbox")
  },hashTypes:{'meta': "ID",'name': "ID",'attributeValue': "ID",'value': "STRING",'className': "STRING"},hashContexts:{'meta': depth0,'name': depth0,'attributeValue': depth0,'value': depth0,'className': depth0},contexts:[depth0],types:["STRING"],data:data})));
  data.buffer.push(" </span> </div> ");
  stack1 = helpers['if'].call(depth0, "role.component", {hash:{},hashTypes:{},hashContexts:{},inverse:self.noop,fn:self.program(4, program4, data),contexts:[depth0],types:["ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" </li> ");
  return buffer;
  }
function program2(depth0,data) {
  
  
  data.buffer.push(" <span class=\"button tiny square white left s-margin-clear umi-permissions-role-button-expand\"> <i class=\"icon icon-right\"></i> </span> ");
  }

function program4(depth0,data) {
  
  var buffer = '';
  data.buffer.push(" <div class=\"umi-permissions-component\"> ");
  data.buffer.push(escapeExpression(helpers.view.call(depth0, "permissionsPartial", {hash:{
    'component': ("role.component")
  },hashTypes:{'component': "ID"},hashContexts:{'component': depth0},contexts:[depth0],types:["STRING"],data:data})));
  data.buffer.push(" </div> ");
  return buffer;
  }

  stack1 = helpers.each.call(depth0, "role", "in", "view.component.roles", {hash:{},hashTypes:{},hashContexts:{},inverse:self.noop,fn:self.program(1, program1, data),contexts:[depth0,depth0,depth0],types:["ID","ID","ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  else { data.buffer.push(''); }
  
});

Ember.TEMPLATES["UMI/partials/radioElement"] = Ember.Handlebars.template(function anonymous(Handlebars,depth0,helpers,partials,data) {
this.compilerInfo = [4,'>= 1.0.0'];
helpers = this.merge(helpers, Ember.Handlebars.helpers); data = data || {};
  var stack1, escapeExpression=this.escapeExpression, self=this;

function program1(depth0,data) {
  
  var buffer = '';
  data.buffer.push(" ");
  data.buffer.push(escapeExpression(helpers.view.call(depth0, "view.radioElementView", {hash:{},hashTypes:{},hashContexts:{},contexts:[depth0],types:["ID"],data:data})));
  data.buffer.push(" ");
  return buffer;
  }

  stack1 = helpers.each.call(depth0, "view.meta.choices", {hash:{},hashTypes:{},hashContexts:{},inverse:self.noop,fn:self.program(1, program1, data),contexts:[depth0],types:["ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  else { data.buffer.push(''); }
  
});

Ember.TEMPLATES["UMI/partials/textareaElement"] = Ember.Handlebars.template(function anonymous(Handlebars,depth0,helpers,partials,data) {
this.compilerInfo = [4,'>= 1.0.0'];
helpers = this.merge(helpers, Ember.Handlebars.helpers); data = data || {};
  var buffer = '', escapeExpression=this.escapeExpression;


  data.buffer.push(escapeExpression(helpers.view.call(depth0, "view.textareaView", {hash:{
    'meta': ("meta"),
    'object': ("object")
  },hashTypes:{'meta': "ID",'object': "ID"},hashContexts:{'meta': depth0,'object': depth0},contexts:[depth0],types:["ID"],data:data})));
  data.buffer.push(" <div class=\"umi-element-textarea-resizer\"></div>");
  return buffer;
  
});

Ember.TEMPLATES["UMI/partials/timeElement"] = Ember.Handlebars.template(function anonymous(Handlebars,depth0,helpers,partials,data) {
this.compilerInfo = [4,'>= 1.0.0'];
helpers = this.merge(helpers, Ember.Handlebars.helpers); data = data || {};
  var buffer = '', escapeExpression=this.escapeExpression;


  data.buffer.push("<div class=\"small-11 columns\"> ");
  data.buffer.push(escapeExpression(helpers.view.call(depth0, "view.inputView", {hash:{},hashTypes:{},hashContexts:{},contexts:[depth0],types:["ID"],data:data})));
  data.buffer.push(" </div> <div class=\"small-1 columns\"> <span class=\"postfix\"> <i class=\"icon icon-delete\"></i> </span> </div> <style> .umi-time-picker{ position: absolute; float: left; width: 200px; height: 200px; background: #FFFFFF; } </style>");
  return buffer;
  
});

Ember.TEMPLATES["UMI/partials/form"] = Ember.Handlebars.template(function anonymous(Handlebars,depth0,helpers,partials,data) {
this.compilerInfo = [4,'>= 1.0.0'];
helpers = this.merge(helpers, Ember.Handlebars.helpers); data = data || {};
  var buffer = '', stack1, escapeExpression=this.escapeExpression, self=this;

function program1(depth0,data) {
  
  var buffer = '';
  data.buffer.push(" <div class=\"s-full-height-before\"> ");
  data.buffer.push(escapeExpression(helpers.view.call(depth0, "toolbar", {hash:{
    'toolbar': ("control.toolbar")
  },hashTypes:{'toolbar': "ID"},hashContexts:{'toolbar': depth0},contexts:[depth0],types:["STRING"],data:data})));
  data.buffer.push(" </div> ");
  return buffer;
  }

function program3(depth0,data) {
  
  var buffer = '', stack1;
  data.buffer.push(" ");
  stack1 = helpers['if'].call(depth0, "view.hasFieldset", {hash:{},hashTypes:{},hashContexts:{},inverse:self.noop,fn:self.program(4, program4, data),contexts:[depth0],types:["ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" <div class=\"row s-full-height collapse\"> <div class=\"columns small-12 magellan-content\"> ");
  stack1 = helpers.each.call(depth0, "formElement", "in", "formElements", {hash:{
    'itemViewClass': ("view.elementView")
  },hashTypes:{'itemViewClass': "STRING"},hashContexts:{'itemViewClass': depth0},inverse:self.noop,fn:self.program(10, program10, data),contexts:[depth0,depth0,depth0],types:["ID","ID","ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" </div> </div> ");
  return buffer;
  }
function program4(depth0,data) {
  
  var buffer = '', stack1;
  data.buffer.push(" ");
  stack1 = helpers.view.call(depth0, "magellan", {hash:{
    'elements': ("formElements")
  },hashTypes:{'elements': "ID"},hashContexts:{'elements': depth0},inverse:self.noop,fn:self.program(5, program5, data),contexts:[depth0],types:["STRING"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" ");
  return buffer;
  }
function program5(depth0,data) {
  
  var buffer = '', stack1;
  data.buffer.push(" ");
  stack1 = helpers.each.call(depth0, "formElement", "in", "view.elements", {hash:{
    'itemViewClass': ("view.elementView")
  },hashTypes:{'itemViewClass': "STRING"},hashContexts:{'itemViewClass': depth0},inverse:self.noop,fn:self.program(6, program6, data),contexts:[depth0,depth0,depth0],types:["ID","ID","ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" ");
  return buffer;
  }
function program6(depth0,data) {
  
  var buffer = '', stack1;
  data.buffer.push(" ");
  stack1 = helpers['if'].call(depth0, "view.isFieldset", {hash:{},hashTypes:{},hashContexts:{},inverse:self.noop,fn:self.program(7, program7, data),contexts:[depth0],types:["ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" ");
  return buffer;
  }
function program7(depth0,data) {
  
  var buffer = '', stack1;
  data.buffer.push(" ");
  stack1 = helpers.view.call(depth0, "view.parentView.buttonView", {hash:{
    'model': ("formElement")
  },hashTypes:{'model': "ID"},hashContexts:{'model': depth0},inverse:self.noop,fn:self.program(8, program8, data),contexts:[depth0],types:["ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" ");
  return buffer;
  }
function program8(depth0,data) {
  
  var buffer = '', stack1;
  data.buffer.push(" ");
  stack1 = helpers._triageMustache.call(depth0, "formElement.label", {hash:{},hashTypes:{},hashContexts:{},contexts:[depth0],types:["ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" ");
  return buffer;
  }

function program10(depth0,data) {
  
  var buffer = '', stack1;
  data.buffer.push(" ");
  stack1 = helpers['if'].call(depth0, "view.isFieldset", {hash:{},hashTypes:{},hashContexts:{},inverse:self.program(15, program15, data),fn:self.program(11, program11, data),contexts:[depth0],types:["ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" ");
  return buffer;
  }
function program11(depth0,data) {
  
  var buffer = '', stack1;
  data.buffer.push(" <fieldset id=\"fieldset-");
  data.buffer.push(escapeExpression(helpers.unbound.call(depth0, "formElement.id", {hash:{},hashTypes:{},hashContexts:{},contexts:[depth0],types:["ID"],data:data})));
  data.buffer.push("\"> <legend ");
  data.buffer.push(escapeExpression(helpers.action.call(depth0, "expand", {hash:{
    'target': ("view")
  },hashTypes:{'target': "STRING"},hashContexts:{'target': depth0},contexts:[depth0],types:["STRING"],data:data})));
  data.buffer.push(" class=\"s-unselectable\"> <i ");
  data.buffer.push(escapeExpression(helpers['bind-attr'].call(depth0, {hash:{
    'class': (":icon view.isExpanded:icon-bottom:icon-right")
  },hashTypes:{'class': "STRING"},hashContexts:{'class': depth0},contexts:[],types:[],data:data})));
  data.buffer.push("></i> ");
  stack1 = helpers._triageMustache.call(depth0, "formElement.label", {hash:{},hashTypes:{},hashContexts:{},contexts:[depth0],types:["ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" </legend> ");
  stack1 = helpers['if'].call(depth0, "view.isExpanded", {hash:{},hashTypes:{},hashContexts:{},inverse:self.noop,fn:self.program(12, program12, data),contexts:[depth0],types:["ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" </fieldset> ");
  return buffer;
  }
function program12(depth0,data) {
  
  var buffer = '', stack1;
  data.buffer.push(" ");
  stack1 = helpers.each.call(depth0, "element", "in", "formElement.elements", {hash:{
    'itemViewClass': ("view.parentView.elementView")
  },hashTypes:{'itemViewClass': "STRING"},hashContexts:{'itemViewClass': depth0},inverse:self.noop,fn:self.program(13, program13, data),contexts:[depth0,depth0,depth0],types:["ID","ID","ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" ");
  return buffer;
  }
function program13(depth0,data) {
  
  var buffer = '';
  data.buffer.push(" ");
  data.buffer.push(escapeExpression(helpers.view.call(depth0, "fieldBase", {hash:{
    'metaBinding': ("element"),
    'objectBinding': ("element")
  },hashTypes:{'metaBinding': "STRING",'objectBinding': "STRING"},hashContexts:{'metaBinding': depth0,'objectBinding': depth0},contexts:[depth0],types:["STRING"],data:data})));
  data.buffer.push(" ");
  return buffer;
  }

function program15(depth0,data) {
  
  var buffer = '';
  data.buffer.push(" <br /> ");
  data.buffer.push(escapeExpression(helpers.view.call(depth0, "fieldBase", {hash:{
    'metaBinding': ("formElement"),
    'objectBinding': ("formElement")
  },hashTypes:{'metaBinding': "STRING",'objectBinding': "STRING"},hashContexts:{'metaBinding': depth0,'objectBinding': depth0},contexts:[depth0],types:["STRING"],data:data})));
  data.buffer.push(" ");
  return buffer;
  }

function program17(depth0,data) {
  
  var buffer = '';
  data.buffer.push(" ");
  data.buffer.push(escapeExpression(helpers.view.call(depth0, "view.submitToolbarView", {hash:{
    'elements': ("model.control.submitToolbar")
  },hashTypes:{'elements': "ID"},hashContexts:{'elements': depth0},contexts:[depth0],types:["ID"],data:data})));
  data.buffer.push(" ");
  return buffer;
  }

  stack1 = helpers['if'].call(depth0, "control.toolbar", {hash:{},hashTypes:{},hashContexts:{},inverse:self.noop,fn:self.program(1, program1, data),contexts:[depth0],types:["ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" <div class=\"s-full-height\"> ");
  stack1 = helpers['with'].call(depth0, "model.control.meta.elements", "as", "formElements", {hash:{},hashTypes:{},hashContexts:{},inverse:self.noop,fn:self.program(3, program3, data),contexts:[depth0,depth0,depth0],types:["ID","ID","ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" </div> ");
  stack1 = helpers['if'].call(depth0, "model.control.submitToolbar", {hash:{},hashTypes:{},hashContexts:{},inverse:self.noop,fn:self.program(17, program17, data),contexts:[depth0],types:["ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  return buffer;
  
});

Ember.TEMPLATES["UMI/partials/formControl"] = Ember.Handlebars.template(function anonymous(Handlebars,depth0,helpers,partials,data) {
this.compilerInfo = [4,'>= 1.0.0'];
helpers = this.merge(helpers, Ember.Handlebars.helpers); data = data || {};
  var buffer = '', stack1, escapeExpression=this.escapeExpression, self=this;

function program1(depth0,data) {
  
  var buffer = '';
  data.buffer.push(" <div class=\"s-full-height-before\"> ");
  data.buffer.push(escapeExpression(helpers.view.call(depth0, "toolbar", {hash:{
    'toolbar': ("control.toolbar")
  },hashTypes:{'toolbar': "ID"},hashContexts:{'toolbar': depth0},contexts:[depth0],types:["STRING"],data:data})));
  data.buffer.push(" </div> ");
  return buffer;
  }

function program3(depth0,data) {
  
  var buffer = '', stack1;
  data.buffer.push(" ");
  stack1 = helpers['if'].call(depth0, "view.hasFieldset", {hash:{},hashTypes:{},hashContexts:{},inverse:self.noop,fn:self.program(4, program4, data),contexts:[depth0],types:["ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" <div class=\"row s-full-height collapse\"> <div class=\"columns small-12 magellan-content\"> ");
  stack1 = helpers.each.call(depth0, "formElement", "in", "formElements", {hash:{
    'itemViewClass': ("view.elementView")
  },hashTypes:{'itemViewClass': "STRING"},hashContexts:{'itemViewClass': depth0},inverse:self.noop,fn:self.program(10, program10, data),contexts:[depth0,depth0,depth0],types:["ID","ID","ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" </div> </div> ");
  return buffer;
  }
function program4(depth0,data) {
  
  var buffer = '', stack1;
  data.buffer.push(" ");
  stack1 = helpers.view.call(depth0, "magellan", {hash:{
    'elements': ("formElements")
  },hashTypes:{'elements': "ID"},hashContexts:{'elements': depth0},inverse:self.noop,fn:self.program(5, program5, data),contexts:[depth0],types:["STRING"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" ");
  return buffer;
  }
function program5(depth0,data) {
  
  var buffer = '', stack1;
  data.buffer.push(" ");
  stack1 = helpers.each.call(depth0, "formElement", "in", "view.elements", {hash:{
    'itemViewClass': ("view.elementView")
  },hashTypes:{'itemViewClass': "STRING"},hashContexts:{'itemViewClass': depth0},inverse:self.noop,fn:self.program(6, program6, data),contexts:[depth0,depth0,depth0],types:["ID","ID","ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" ");
  return buffer;
  }
function program6(depth0,data) {
  
  var buffer = '', stack1;
  data.buffer.push(" ");
  stack1 = helpers['if'].call(depth0, "view.isFieldset", {hash:{},hashTypes:{},hashContexts:{},inverse:self.noop,fn:self.program(7, program7, data),contexts:[depth0],types:["ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" ");
  return buffer;
  }
function program7(depth0,data) {
  
  var buffer = '', stack1;
  data.buffer.push(" ");
  stack1 = helpers.view.call(depth0, "view.parentView.buttonView", {hash:{
    'model': ("formElement")
  },hashTypes:{'model': "ID"},hashContexts:{'model': depth0},inverse:self.noop,fn:self.program(8, program8, data),contexts:[depth0],types:["ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" ");
  return buffer;
  }
function program8(depth0,data) {
  
  var buffer = '', stack1;
  data.buffer.push(" ");
  stack1 = helpers._triageMustache.call(depth0, "formElement.label", {hash:{},hashTypes:{},hashContexts:{},contexts:[depth0],types:["ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" ");
  return buffer;
  }

function program10(depth0,data) {
  
  var buffer = '', stack1;
  data.buffer.push(" ");
  stack1 = helpers['if'].call(depth0, "view.isFieldset", {hash:{},hashTypes:{},hashContexts:{},inverse:self.program(15, program15, data),fn:self.program(11, program11, data),contexts:[depth0],types:["ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" ");
  return buffer;
  }
function program11(depth0,data) {
  
  var buffer = '', stack1;
  data.buffer.push(" <fieldset id=\"fieldset-");
  data.buffer.push(escapeExpression(helpers.unbound.call(depth0, "formElement.id", {hash:{},hashTypes:{},hashContexts:{},contexts:[depth0],types:["ID"],data:data})));
  data.buffer.push("\"> <legend ");
  data.buffer.push(escapeExpression(helpers.action.call(depth0, "expand", {hash:{
    'target': ("view")
  },hashTypes:{'target': "STRING"},hashContexts:{'target': depth0},contexts:[depth0],types:["STRING"],data:data})));
  data.buffer.push(" class=\"s-unselectable\"> <i ");
  data.buffer.push(escapeExpression(helpers['bind-attr'].call(depth0, {hash:{
    'class': (":icon view.isExpanded:icon-bottom:icon-right")
  },hashTypes:{'class': "STRING"},hashContexts:{'class': depth0},contexts:[],types:[],data:data})));
  data.buffer.push("></i> ");
  stack1 = helpers._triageMustache.call(depth0, "formElement.label", {hash:{},hashTypes:{},hashContexts:{},contexts:[depth0],types:["ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" </legend> ");
  stack1 = helpers['if'].call(depth0, "view.isExpanded", {hash:{},hashTypes:{},hashContexts:{},inverse:self.noop,fn:self.program(12, program12, data),contexts:[depth0],types:["ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" </fieldset> ");
  return buffer;
  }
function program12(depth0,data) {
  
  var buffer = '', stack1;
  data.buffer.push(" ");
  stack1 = helpers.each.call(depth0, "element", "in", "formElement.elements", {hash:{
    'itemViewClass': ("view.parentView.elementView")
  },hashTypes:{'itemViewClass': "STRING"},hashContexts:{'itemViewClass': depth0},inverse:self.noop,fn:self.program(13, program13, data),contexts:[depth0,depth0,depth0],types:["ID","ID","ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" ");
  return buffer;
  }
function program13(depth0,data) {
  
  var buffer = '';
  data.buffer.push(" ");
  data.buffer.push(escapeExpression(helpers.view.call(depth0, "fieldFormControl", {hash:{
    'metaBinding': ("element"),
    'objectBinding': ("controller.object")
  },hashTypes:{'metaBinding': "STRING",'objectBinding': "STRING"},hashContexts:{'metaBinding': depth0,'objectBinding': depth0},contexts:[depth0],types:["STRING"],data:data})));
  data.buffer.push(" ");
  return buffer;
  }

function program15(depth0,data) {
  
  var buffer = '';
  data.buffer.push(" ");
  data.buffer.push(escapeExpression(helpers.view.call(depth0, "fieldFormControl", {hash:{
    'metaBinding': ("formElement"),
    'objectBinding': ("controller.object")
  },hashTypes:{'metaBinding': "STRING",'objectBinding': "STRING"},hashContexts:{'metaBinding': depth0,'objectBinding': depth0},contexts:[depth0],types:["STRING"],data:data})));
  data.buffer.push(" ");
  return buffer;
  }

function program17(depth0,data) {
  
  var buffer = '';
  data.buffer.push(" ");
  data.buffer.push(escapeExpression(helpers.view.call(depth0, "submitToolbar", {hash:{
    'elements': ("model.control.submitToolbar")
  },hashTypes:{'elements': "ID"},hashContexts:{'elements': depth0},contexts:[depth0],types:["STRING"],data:data})));
  data.buffer.push(" ");
  return buffer;
  }

  stack1 = helpers['if'].call(depth0, "control.toolbar", {hash:{},hashTypes:{},hashContexts:{},inverse:self.noop,fn:self.program(1, program1, data),contexts:[depth0],types:["ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" <div class=\"s-full-height\"> ");
  stack1 = helpers['with'].call(depth0, "model.control.meta.elements", "as", "formElements", {hash:{},hashTypes:{},hashContexts:{},inverse:self.noop,fn:self.program(3, program3, data),contexts:[depth0,depth0,depth0],types:["ID","ID","ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" </div> ");
  stack1 = helpers['if'].call(depth0, "model.control.submitToolbar", {hash:{},hashTypes:{},hashContexts:{},inverse:self.noop,fn:self.program(17, program17, data),contexts:[depth0],types:["ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  return buffer;
  
});

Ember.TEMPLATES["UMI/partials/form/submitToolbar"] = Ember.Handlebars.template(function anonymous(Handlebars,depth0,helpers,partials,data) {
this.compilerInfo = [4,'>= 1.0.0'];
helpers = this.merge(helpers, Ember.Handlebars.helpers); data = data || {};
  var stack1, escapeExpression=this.escapeExpression, self=this;

function program1(depth0,data) {
  
  var buffer = '';
  data.buffer.push(" ");
  data.buffer.push(escapeExpression(helpers.view.call(depth0, "view.elementView", {hash:{},hashTypes:{},hashContexts:{},contexts:[depth0],types:["ID"],data:data})));
  data.buffer.push(" ");
  return buffer;
  }

  stack1 = helpers.each.call(depth0, "view.elements", {hash:{},hashTypes:{},hashContexts:{},inverse:self.noop,fn:self.program(1, program1, data),contexts:[depth0],types:["ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  else { data.buffer.push(''); }
  
});

Ember.TEMPLATES["UMI/partials/alert-box/close-all"] = Ember.Handlebars.template(function anonymous(Handlebars,depth0,helpers,partials,data) {
this.compilerInfo = [4,'>= 1.0.0'];
helpers = this.merge(helpers, Ember.Handlebars.helpers); data = data || {};
  var escapeExpression=this.escapeExpression;


  data.buffer.push(escapeExpression(helpers._triageMustache.call(depth0, "view.content.content", {hash:{
    'unescaped': ("true")
  },hashTypes:{'unescaped': "STRING"},hashContexts:{'unescaped': depth0},contexts:[depth0],types:["ID"],data:data})));
  
});

Ember.TEMPLATES["UMI/partials/alert-box"] = Ember.Handlebars.template(function anonymous(Handlebars,depth0,helpers,partials,data) {
this.compilerInfo = [4,'>= 1.0.0'];
helpers = this.merge(helpers, Ember.Handlebars.helpers); data = data || {};
  var buffer = '', stack1, escapeExpression=this.escapeExpression, self=this;

function program1(depth0,data) {
  
  var buffer = '', stack1;
  data.buffer.push(" <h5 class=\"subheader\">");
  stack1 = helpers._triageMustache.call(depth0, "view.content.title", {hash:{},hashTypes:{},hashContexts:{},contexts:[depth0],types:["ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push("</h5> ");
  return buffer;
  }

function program3(depth0,data) {
  
  var buffer = '';
  data.buffer.push(" <span ");
  data.buffer.push(escapeExpression(helpers.action.call(depth0, "close", "view.content", {hash:{
    'target': ("view")
  },hashTypes:{'target': "STRING"},hashContexts:{'target': depth0},contexts:[depth0,depth0],types:["STRING","ID"],data:data})));
  data.buffer.push(" class=\"close\">&times;</span> ");
  return buffer;
  }

  stack1 = helpers['if'].call(depth0, "view.content.title", {hash:{},hashTypes:{},hashContexts:{},inverse:self.noop,fn:self.program(1, program1, data),contexts:[depth0],types:["ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" ");
  data.buffer.push(escapeExpression(helpers._triageMustache.call(depth0, "view.content.content", {hash:{
    'unescaped': ("true")
  },hashTypes:{'unescaped': "STRING"},hashContexts:{'unescaped': depth0},contexts:[depth0],types:["ID"],data:data})));
  data.buffer.push(" ");
  stack1 = helpers['if'].call(depth0, "view.content.close", {hash:{},hashTypes:{},hashContexts:{},inverse:self.noop,fn:self.program(3, program3, data),contexts:[depth0],types:["ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  return buffer;
  
});

Ember.TEMPLATES["UMI/partials/popup"] = Ember.Handlebars.template(function anonymous(Handlebars,depth0,helpers,partials,data) {
this.compilerInfo = [4,'>= 1.0.0'];
helpers = this.merge(helpers, Ember.Handlebars.helpers); data = data || {};
  var buffer = '', stack1, escapeExpression=this.escapeExpression;


  data.buffer.push("<div class=\"umi-popup-header\"> <span class=\"umi-popup-title\">");
  stack1 = helpers._triageMustache.call(depth0, "view.title", {hash:{},hashTypes:{},hashContexts:{},contexts:[depth0],types:["ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push("</span> <a href=\"#\" class=\"umi-popup-close-button\" ");
  data.buffer.push(escapeExpression(helpers.action.call(depth0, "closePopup", {hash:{
    'target': ("view")
  },hashTypes:{'target': "STRING"},hashContexts:{'target': depth0},contexts:[depth0],types:["STRING"],data:data})));
  data.buffer.push("> <i class=\"icon icon-delete\"></i> </a> </div> <div class=\"umi-popup-content\"> ");
  stack1 = helpers._triageMustache.call(depth0, "yield", {hash:{},hashTypes:{},hashContexts:{},contexts:[depth0],types:["ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" </div> <div class=\"umi-popup-resizer\"></div>");
  return buffer;
  
});

Ember.TEMPLATES["UMI/partials/sideMenu"] = Ember.Handlebars.template(function anonymous(Handlebars,depth0,helpers,partials,data) {
this.compilerInfo = [4,'>= 1.0.0'];
helpers = this.merge(helpers, Ember.Handlebars.helpers); data = data || {};
  var buffer = '', stack1, self=this, helperMissing=helpers.helperMissing;

function program1(depth0,data) {
  
  var buffer = '', stack1, helper, options;
  data.buffer.push(" ");
  stack1 = (helper = helpers['link-to'] || (depth0 && depth0['link-to']),options={hash:{
    'tagName': ("li")
  },hashTypes:{'tagName': "STRING"},hashContexts:{'tagName': depth0},inverse:self.noop,fn:self.program(2, program2, data),contexts:[depth0,depth0],types:["STRING","ID"],data:data},helper ? helper.call(depth0, "context", "object.id", options) : helperMissing.call(depth0, "link-to", "context", "object.id", options));
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" ");
  return buffer;
  }
function program2(depth0,data) {
  
  var buffer = '', stack1;
  data.buffer.push("<a href=\"javascript:void(0)\">");
  stack1 = helpers._triageMustache.call(depth0, "object.displayName", {hash:{},hashTypes:{},hashContexts:{},contexts:[depth0],types:["ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push("</a>");
  return buffer;
  }

  data.buffer.push("<ul class=\"side-nav\"> ");
  stack1 = helpers.each.call(depth0, "object", "in", "objects", {hash:{},hashTypes:{},hashContexts:{},inverse:self.noop,fn:self.program(1, program1, data),contexts:[depth0,depth0,depth0],types:["ID","ID","ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" </ul> ");
  return buffer;
  
});

Ember.TEMPLATES["UMI/partials/table"] = Ember.Handlebars.template(function anonymous(Handlebars,depth0,helpers,partials,data) {
this.compilerInfo = [4,'>= 1.0.0'];
helpers = this.merge(helpers, Ember.Handlebars.helpers); data = data || {};
  var stack1, self=this, helperMissing=helpers.helperMissing, escapeExpression=this.escapeExpression;

function program1(depth0,data) {
  
  var buffer = '', stack1;
  data.buffer.push(" <p></p> <h3 class=\"text-center\">");
  stack1 = helpers._triageMustache.call(depth0, "view.error", {hash:{},hashTypes:{},hashContexts:{},contexts:[depth0],types:["ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push("</h3> ");
  return buffer;
  }

function program3(depth0,data) {
  
  var buffer = '', stack1;
  data.buffer.push(" ");
  data.buffer.push(escapeExpression(helpers.view.call(depth0, "view.paginationView", {hash:{},hashTypes:{},hashContexts:{},contexts:[depth0],types:["ID"],data:data})));
  data.buffer.push(" <div class=\"umi-table-header-wrap\"> <table class=\"umi-table-header\"> <tbody> <tr class=\"umi-table-tr\"> ");
  stack1 = helpers.each.call(depth0, "view.headers", {hash:{},hashTypes:{},hashContexts:{},inverse:self.noop,fn:self.program(4, program4, data),contexts:[depth0],types:["ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" <td class=\"umi-table-empty-column\"></td> </tr> </tbody> </table> </div> <div class=\"umi-table-header-shadow\"></div> <div class=\"s-scroll-wrap\"> <table class=\"umi-table-content\"> <tbody> <tr class=\"umi-table-content-sizer\"> ");
  stack1 = helpers.each.call(depth0, "view.headers", {hash:{},hashTypes:{},hashContexts:{},inverse:self.noop,fn:self.program(6, program6, data),contexts:[depth0],types:["ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" </tr> ");
  stack1 = helpers.each.call(depth0, "row", "in", "view.visibleRows", {hash:{},hashTypes:{},hashContexts:{},inverse:self.program(11, program11, data),fn:self.program(8, program8, data),contexts:[depth0,depth0,depth0],types:["ID","ID","ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" </tbody> </table> </div> ");
  return buffer;
  }
function program4(depth0,data) {
  
  var buffer = '', stack1;
  data.buffer.push(" <td class=\"umi-table-td\" style=\"width: 200px;\"> <div class=\"umi-table-td-relative-wrap\"> <div class=\"umi-table-cell\">");
  stack1 = helpers._triageMustache.call(depth0, "", {hash:{},hashTypes:{},hashContexts:{},contexts:[depth0],types:["ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push("</div> <div class=\"umi-table-header-column-resizer\"></div> </div> </td> ");
  return buffer;
  }

function program6(depth0,data) {
  
  
  data.buffer.push(" <td class=\"umi-table-td\" style=\"width: 200px;\"></td> ");
  }

function program8(depth0,data) {
  
  var buffer = '', stack1;
  data.buffer.push(" <tr class=\"umi-table-content-tr\"> ");
  stack1 = helpers.each.call(depth0, "property", "in", "row", {hash:{},hashTypes:{},hashContexts:{},inverse:self.noop,fn:self.program(9, program9, data),contexts:[depth0,depth0,depth0],types:["ID","ID","ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" <td class=\"umi-table-empty-column\"></td> </tr> ");
  return buffer;
  }
function program9(depth0,data) {
  
  var buffer = '', stack1;
  data.buffer.push(" <td class=\"umi-table-td\"> <div class=\"umi-table-cell\">");
  stack1 = helpers._triageMustache.call(depth0, "property", {hash:{},hashTypes:{},hashContexts:{},contexts:[depth0],types:["ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push("</div> </td> ");
  return buffer;
  }

function program11(depth0,data) {
  
  var buffer = '', helper, options;
  data.buffer.push(" <tr class=\"umi-table-content-tr\"> <td> ");
  data.buffer.push(escapeExpression((helper = helpers.i18n || (depth0 && depth0.i18n),options={hash:{},hashTypes:{},hashContexts:{},contexts:[depth0,depth0],types:["STRING","STRING"],data:data},helper ? helper.call(depth0, "No data", "table", options) : helperMissing.call(depth0, "i18n", "No data", "table", options))));
  data.buffer.push(" </td> </tr> ");
  return buffer;
  }

  stack1 = helpers['if'].call(depth0, "view.error", {hash:{},hashTypes:{},hashContexts:{},inverse:self.program(3, program3, data),fn:self.program(1, program1, data),contexts:[depth0],types:["ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  else { data.buffer.push(''); }
  
});

Ember.TEMPLATES["UMI/partials/table/toolbar"] = Ember.Handlebars.template(function anonymous(Handlebars,depth0,helpers,partials,data) {
this.compilerInfo = [4,'>= 1.0.0'];
helpers = this.merge(helpers, Ember.Handlebars.helpers); data = data || {};
  var buffer = '', stack1, helper, options, self=this, helperMissing=helpers.helperMissing, escapeExpression=this.escapeExpression;

function program1(depth0,data) {
  
  
  data.buffer.push(" <i class=\"icon black icon-left-thin\"></i> ");
  }

function program3(depth0,data) {
  
  
  data.buffer.push(" <i class=\"icon black icon-right-thin\"></i> ");
  }

  data.buffer.push("<div class=\"right umi-table-control-pagination\"> <div class=\"right pagination-controls\"> <span class=\"pagination-counter\"> ");
  stack1 = helpers._triageMustache.call(depth0, "view.counter", {hash:{},hashTypes:{},hashContexts:{},contexts:[depth0],types:["ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" </span> ");
  stack1 = helpers.view.call(depth0, "view.prevButtonView", {hash:{},hashTypes:{},hashContexts:{},inverse:self.noop,fn:self.program(1, program1, data),contexts:[depth0],types:["ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" ");
  stack1 = helpers.view.call(depth0, "view.nextButtonView", {hash:{},hashTypes:{},hashContexts:{},inverse:self.noop,fn:self.program(3, program3, data),contexts:[depth0],types:["ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" </div> <div class=\"right pagination-limit\"> <span class=\"pagination-label\">");
  data.buffer.push(escapeExpression((helper = helpers.i18n || (depth0 && depth0.i18n),options={hash:{},hashTypes:{},hashContexts:{},contexts:[depth0,depth0],types:["STRING","STRING"],data:data},helper ? helper.call(depth0, "Rows on page", "table", options) : helperMissing.call(depth0, "i18n", "Rows on page", "table", options))));
  data.buffer.push(":</span> ");
  data.buffer.push(escapeExpression(helpers.view.call(depth0, "view.limitView", {hash:{},hashTypes:{},hashContexts:{},contexts:[depth0],types:["ID"],data:data})));
  data.buffer.push(" </div> </div> ");
  return buffer;
  
});

Ember.TEMPLATES["UMI/partials/tableControl"] = Ember.Handlebars.template(function anonymous(Handlebars,depth0,helpers,partials,data) {
this.compilerInfo = [4,'>= 1.0.0'];
helpers = this.merge(helpers, Ember.Handlebars.helpers); data = data || {};
  var buffer = '', stack1, self=this, helperMissing=helpers.helperMissing, escapeExpression=this.escapeExpression;

function program1(depth0,data) {
  
  var buffer = '', stack1;
  data.buffer.push(" ");
  stack1 = helpers.view.call(depth0, "view.parentView.paginationView", {hash:{},hashTypes:{},hashContexts:{},inverse:self.noop,fn:self.program(2, program2, data),contexts:[depth0],types:["ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" ");
  return buffer;
  }
function program2(depth0,data) {
  
  var buffer = '', stack1, helper, options;
  data.buffer.push(" <div class=\"right pagination-controls\"> <span class=\"pagination-counter\"> ");
  stack1 = helpers._triageMustache.call(depth0, "view.counter", {hash:{},hashTypes:{},hashContexts:{},contexts:[depth0],types:["ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" </span> ");
  stack1 = helpers.view.call(depth0, "view.prevButtonView", {hash:{},hashTypes:{},hashContexts:{},inverse:self.noop,fn:self.program(3, program3, data),contexts:[depth0],types:["ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" ");
  stack1 = helpers.view.call(depth0, "view.nextButtonView", {hash:{},hashTypes:{},hashContexts:{},inverse:self.noop,fn:self.program(5, program5, data),contexts:[depth0],types:["ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" </div> <div class=\"right pagination-limit\"> <span class=\"pagination-label\">");
  data.buffer.push(escapeExpression((helper = helpers.i18n || (depth0 && depth0.i18n),options={hash:{},hashTypes:{},hashContexts:{},contexts:[depth0,depth0],types:["STRING","STRING"],data:data},helper ? helper.call(depth0, "Rows on page", "tableControl", options) : helperMissing.call(depth0, "i18n", "Rows on page", "tableControl", options))));
  data.buffer.push(":</span> ");
  data.buffer.push(escapeExpression(helpers.view.call(depth0, "view.limitView", {hash:{},hashTypes:{},hashContexts:{},contexts:[depth0],types:["ID"],data:data})));
  data.buffer.push(" </div> ");
  return buffer;
  }
function program3(depth0,data) {
  
  
  data.buffer.push(" <i class=\"icon black icon-left-thin\"></i> ");
  }

function program5(depth0,data) {
  
  
  data.buffer.push(" <i class=\"icon black icon-right-thin\"></i> ");
  }

function program7(depth0,data) {
  
  var buffer = '', stack1, helper, options;
  data.buffer.push(" <td class=\"umi-table-control-header-column column-id-");
  data.buffer.push(escapeExpression((helper = helpers.filterClassName || (depth0 && depth0.filterClassName),options={hash:{},hashTypes:{},hashContexts:{},contexts:[depth0],types:["ID"],data:data},helper ? helper.call(depth0, "column.attributes.name", options) : helperMissing.call(depth0, "filterClassName", "column.attributes.name", options))));
  data.buffer.push("\" style=\"width: 200px\"> <div class=\"umi-table-control-cell-firefox-fix\"> <div class=\"umi-table-control-header-cell\"> ");
  stack1 = helpers._triageMustache.call(depth0, "column.label", {hash:{},hashTypes:{},hashContexts:{},contexts:[depth0],types:["ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" </div> <div class=\"umi-table-control-column-resizer\"></div> </div> </td> ");
  return buffer;
  }

function program9(depth0,data) {
  
  var buffer = '', stack1, helper, options;
  data.buffer.push(" <td class=\"umi-table-control-header-column\"> <div class=\"umi-table-control-header-cell filter column-id-");
  data.buffer.push(escapeExpression((helper = helpers.filterClassName || (depth0 && depth0.filterClassName),options={hash:{},hashTypes:{},hashContexts:{},contexts:[depth0],types:["ID"],data:data},helper ? helper.call(depth0, "column.attributes.name", options) : helperMissing.call(depth0, "filterClassName", "column.attributes.name", options))));
  data.buffer.push("\"> ");
  data.buffer.push(escapeExpression(helpers.view.call(depth0, "view.filterRowView", {hash:{
    'column': ("column")
  },hashTypes:{'column': "ID"},hashContexts:{'column': depth0},contexts:[depth0],types:["ID"],data:data})));
  data.buffer.push(" ");
  stack1 = helpers.view.call(depth0, "view.sortHandlerView", {hash:{
    'propertyName': ("column.attributes.name")
  },hashTypes:{'propertyName': "ID"},hashContexts:{'propertyName': depth0},inverse:self.noop,fn:self.program(10, program10, data),contexts:[depth0],types:["ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" </div> </td> ");
  return buffer;
  }
function program10(depth0,data) {
  
  var buffer = '';
  data.buffer.push(" <i ");
  data.buffer.push(escapeExpression(helpers['bind-attr'].call(depth0, {hash:{
    'class': (":icon :black view.sortAscending:icon-bottom-thin:icon-top-thin")
  },hashTypes:{'class': "STRING"},hashContexts:{'class': depth0},contexts:[],types:[],data:data})));
  data.buffer.push("></i> ");
  return buffer;
  }

function program12(depth0,data) {
  
  var buffer = '', helper, options;
  data.buffer.push(" <td class=\"umi-table-control-content-cell column-id-");
  data.buffer.push(escapeExpression((helper = helpers.filterClassName || (depth0 && depth0.filterClassName),options={hash:{},hashTypes:{},hashContexts:{},contexts:[depth0],types:["ID"],data:data},helper ? helper.call(depth0, "column.attributes.name", options) : helperMissing.call(depth0, "filterClassName", "column.attributes.name", options))));
  data.buffer.push("\" style=\"width: 200px\"></td> ");
  return buffer;
  }

function program14(depth0,data) {
  
  var buffer = '', stack1;
  data.buffer.push(" ");
  stack1 = helpers.each.call(depth0, "object", "in", "objects", {hash:{},hashTypes:{},hashContexts:{},inverse:self.program(19, program19, data),fn:self.program(15, program15, data),contexts:[depth0,depth0,depth0],types:["ID","ID","ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" ");
  return buffer;
  }
function program15(depth0,data) {
  
  var buffer = '', stack1;
  data.buffer.push(" ");
  stack1 = helpers.view.call(depth0, "view.rowView", {hash:{
    'object': ("object")
  },hashTypes:{'object': "ID"},hashContexts:{'object': depth0},inverse:self.noop,fn:self.program(16, program16, data),contexts:[depth0],types:["ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" ");
  return buffer;
  }
function program16(depth0,data) {
  
  var buffer = '', stack1;
  data.buffer.push(" ");
  stack1 = helpers.each.call(depth0, "column", "in", "fieldsList", {hash:{},hashTypes:{},hashContexts:{},inverse:self.noop,fn:self.program(17, program17, data),contexts:[depth0,depth0,depth0],types:["ID","ID","ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" <td class=\"umi-table-control-empty-column\"></td> ");
  return buffer;
  }
function program17(depth0,data) {
  
  var buffer = '';
  data.buffer.push(" <td class=\"umi-table-control-content-cell\"> ");
  data.buffer.push(escapeExpression(helpers.view.call(depth0, "tableCellContent", {hash:{
    'objectBinding': ("object"),
    'column': ("column")
  },hashTypes:{'objectBinding': "STRING",'column': "ID"},hashContexts:{'objectBinding': depth0,'column': depth0},contexts:[depth0],types:["STRING"],data:data})));
  data.buffer.push(" </td> ");
  return buffer;
  }

function program19(depth0,data) {
  
  var buffer = '', helper, options;
  data.buffer.push(" <tr> <td> <div class=\"umi-table-control-content-div-empty\"> <span>");
  data.buffer.push(escapeExpression((helper = helpers.i18n || (depth0 && depth0.i18n),options={hash:{},hashTypes:{},hashContexts:{},contexts:[depth0,depth0],types:["STRING","STRING"],data:data},helper ? helper.call(depth0, "No data", "tableControl", options) : helperMissing.call(depth0, "i18n", "No data", "tableControl", options))));
  data.buffer.push("</span> </div> </td> </tr> ");
  return buffer;
  }

function program21(depth0,data) {
  
  var buffer = '', stack1;
  data.buffer.push(" <div ");
  data.buffer.push(escapeExpression(helpers['bind-attr'].call(depth0, {hash:{
    'class': (":umi-table-control-column-fixed-cell object.active::umi-inactive")
  },hashTypes:{'class': "STRING"},hashContexts:{'class': depth0},contexts:[],types:[],data:data})));
  data.buffer.push(" data-objectId=\"");
  data.buffer.push(escapeExpression(helpers.unbound.call(depth0, "object.id", {hash:{},hashTypes:{},hashContexts:{},contexts:[depth0],types:["ID"],data:data})));
  data.buffer.push("\"> ");
  stack1 = helpers['if'].call(depth0, "controller.parentController.contextToolbar", {hash:{},hashTypes:{},hashContexts:{},inverse:self.noop,fn:self.program(22, program22, data),contexts:[depth0],types:["ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" </div> ");
  return buffer;
  }
function program22(depth0,data) {
  
  var buffer = '', stack1;
  data.buffer.push(" ");
  stack1 = helpers.view.call(depth0, "tableControlContextToolbar", {hash:{
    'elements': ("controller.parentController.contextToolbar")
  },hashTypes:{'elements': "ID"},hashContexts:{'elements': depth0},inverse:self.noop,fn:self.program(23, program23, data),contexts:[depth0],types:["STRING"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" ");
  return buffer;
  }
function program23(depth0,data) {
  
  var buffer = '', stack1;
  data.buffer.push(" ");
  stack1 = helpers.each.call(depth0, "view.elements", {hash:{},hashTypes:{},hashContexts:{},inverse:self.noop,fn:self.program(24, program24, data),contexts:[depth0],types:["ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" ");
  return buffer;
  }
function program24(depth0,data) {
  
  var buffer = '';
  data.buffer.push(" ");
  data.buffer.push(escapeExpression(helpers.view.call(depth0, "view.elementView", {hash:{},hashTypes:{},hashContexts:{},contexts:[depth0],types:["ID"],data:data})));
  data.buffer.push(" ");
  return buffer;
  }

  stack1 = helpers.view.call(depth0, "toolbar", {hash:{
    'toolbar': ("toolbar")
  },hashTypes:{'toolbar': "ID"},hashContexts:{'toolbar': depth0},inverse:self.noop,fn:self.program(1, program1, data),contexts:[depth0],types:["STRING"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" <div class=\"umi-table-control-header s-unselectable\"> <div class=\"umi-table-control-header-center\"> <table class=\"umi-table-control-header\"> <tbody> <tr class=\"umi-table-control-row\"> ");
  stack1 = helpers.each.call(depth0, "column", "in", "fieldsList", {hash:{},hashTypes:{},hashContexts:{},inverse:self.noop,fn:self.program(7, program7, data),contexts:[depth0,depth0,depth0],types:["ID","ID","ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" <td class=\"umi-table-control-empty-column\"></td> </tr> <tr class=\"umi-table-control-row filters\"> ");
  stack1 = helpers.each.call(depth0, "column", "in", "fieldsList", {hash:{},hashTypes:{},hashContexts:{},inverse:self.noop,fn:self.program(9, program9, data),contexts:[depth0,depth0,depth0],types:["ID","ID","ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" <td class=\"umi-table-control-empty-column\"></td> </tr> </tbody> </table> </div> <div class=\"umi-table-control-header-fixed-right\"> <div class=\"umi-table-control-header-title\"> <div class=\"umi-table-control-header-fixed-right-first\"> </div> <div class=\"umi-table-control-header-fixed-right-second\"> </div> </div> <div class=\"umi-table-control-header-filter\"> </div> </div> </div> <div class=\"umi-table-control-content-wrapper\"> <div class=\"s-scroll-wrap\"> <table class=\"umi-table-control-content\"> <tbody> <tr class=\"umi-table-control-content-row-size\"> ");
  stack1 = helpers.each.call(depth0, "column", "in", "fieldsList", {hash:{},hashTypes:{},hashContexts:{},inverse:self.noop,fn:self.program(12, program12, data),contexts:[depth0,depth0,depth0],types:["ID","ID","ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" <td class=\"umi-table-control-empty-column\"></td> </tr> ");
  stack1 = helpers['if'].call(depth0, "objects.content.isFulfilled", {hash:{},hashTypes:{},hashContexts:{},inverse:self.noop,fn:self.program(14, program14, data),contexts:[depth0],types:["ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" </tbody> </table> </div> <!-- Колонка справа от контента --> <div class=\"umi-table-control-content-fixed-right\"> ");
  stack1 = helpers.each.call(depth0, "object", "in", "objects", {hash:{
    'itemController': ("tableControlContextToolbarItem")
  },hashTypes:{'itemController': "STRING"},hashContexts:{'itemController': depth0},inverse:self.noop,fn:self.program(21, program21, data),contexts:[depth0,depth0,depth0],types:["ID","ID","ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" </div> </div>");
  return buffer;
  
});

Ember.TEMPLATES["UMI/partials/button"] = Ember.Handlebars.template(function anonymous(Handlebars,depth0,helpers,partials,data) {
this.compilerInfo = [4,'>= 1.0.0'];
helpers = this.merge(helpers, Ember.Handlebars.helpers); data = data || {};
  var buffer = '', stack1, escapeExpression=this.escapeExpression, self=this;

function program1(depth0,data) {
  
  var buffer = '';
  data.buffer.push(" <i class=\"icon icon-");
  data.buffer.push(escapeExpression(helpers.unbound.call(depth0, "view.meta.behaviour.name", {hash:{},hashTypes:{},hashContexts:{},contexts:[depth0],types:["ID"],data:data})));
  data.buffer.push("\"></i> ");
  return buffer;
  }

  stack1 = helpers['if'].call(depth0, "view.meta.attributes.hasIcon", {hash:{},hashTypes:{},hashContexts:{},inverse:self.noop,fn:self.program(1, program1, data),contexts:[depth0],types:["ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" ");
  stack1 = helpers._triageMustache.call(depth0, "view.label", {hash:{},hashTypes:{},hashContexts:{},contexts:[depth0],types:["ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  return buffer;
  
});

Ember.TEMPLATES["UMI/partials/dropdownButton/backupList"] = Ember.Handlebars.template(function anonymous(Handlebars,depth0,helpers,partials,data) {
this.compilerInfo = [4,'>= 1.0.0'];
helpers = this.merge(helpers, Ember.Handlebars.helpers); data = data || {};
  var buffer = '', stack1, escapeExpression=this.escapeExpression, helperMissing=helpers.helperMissing, self=this;

function program1(depth0,data) {
  
  var buffer = '';
  data.buffer.push(" <span> ");
  data.buffer.push(escapeExpression(helpers.unbound.call(depth0, "view.meta.attributes.label", {hash:{},hashTypes:{},hashContexts:{},contexts:[depth0],types:["ID"],data:data})));
  data.buffer.push(" </span> ");
  return buffer;
  }

function program3(depth0,data) {
  
  var buffer = '', stack1;
  data.buffer.push(" <div class=\"row collapse\" ");
  data.buffer.push(escapeExpression(helpers.action.call(depth0, "applyBackup", "", {hash:{
    'target': ("view")
  },hashTypes:{'target': "STRING"},hashContexts:{'target': depth0},contexts:[depth0,depth0],types:["STRING","ID"],data:data})));
  data.buffer.push("> <div class=\"columns small-6 place-button\"> ");
  stack1 = helpers['if'].call(depth0, "isActive", {hash:{},hashTypes:{},hashContexts:{},inverse:self.program(6, program6, data),fn:self.program(4, program4, data),contexts:[depth0],types:["ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" ");
  stack1 = helpers._triageMustache.call(depth0, "created.date", {hash:{},hashTypes:{},hashContexts:{},contexts:[depth0],types:["ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" </div> <div class=\"columns small-6\"> ");
  stack1 = helpers['if'].call(depth0, "user", {hash:{},hashTypes:{},hashContexts:{},inverse:self.program(10, program10, data),fn:self.program(8, program8, data),contexts:[depth0],types:["ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" </div> </div> ");
  return buffer;
  }
function program4(depth0,data) {
  
  
  data.buffer.push(" <button class=\"button flat tiny square\"> <i class=\"icon icon-accept\"></i> </button> ");
  }

function program6(depth0,data) {
  
  
  data.buffer.push(" <button class=\"button flat tiny square\"> <i class=\"icon icon-renew\"></i> </button> ");
  }

function program8(depth0,data) {
  
  var buffer = '', stack1;
  data.buffer.push(" ");
  stack1 = helpers._triageMustache.call(depth0, "user", {hash:{},hashTypes:{},hashContexts:{},contexts:[depth0],types:["ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" ");
  return buffer;
  }

function program10(depth0,data) {
  
  var buffer = '', helper, options;
  data.buffer.push(" ");
  data.buffer.push(escapeExpression((helper = helpers.i18n || (depth0 && depth0.i18n),options={hash:{},hashTypes:{},hashContexts:{},contexts:[depth0,depth0],types:["STRING","STRING"],data:data},helper ? helper.call(depth0, "User name", "toolbar:dropdownButton", options) : helperMissing.call(depth0, "i18n", "User name", "toolbar:dropdownButton", options))));
  data.buffer.push(" ");
  return buffer;
  }

  data.buffer.push("<a href=\"javascript:void(0)\" class=\"button white dropdown\" ");
  data.buffer.push(escapeExpression(helpers.action.call(depth0, "open", {hash:{
    'target': ("view")
  },hashTypes:{'target': "STRING"},hashContexts:{'target': depth0},contexts:[depth0],types:["STRING"],data:data})));
  data.buffer.push("> <i class=\"icon icon-backupList\"></i> ");
  stack1 = helpers['if'].call(depth0, "view.meta.attributes.label", {hash:{},hashTypes:{},hashContexts:{},inverse:self.noop,fn:self.program(1, program1, data),contexts:[depth0],types:["ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" </a> <div class=\"f-dropdown umi-dropdown dropdown-rows right\"> <div class=\"row collapse\"> <div class=\"columns small-12\"> <strong>");
  stack1 = helpers._triageMustache.call(depth0, "view.button.displayName", {hash:{},hashTypes:{},hashContexts:{},contexts:[depth0],types:["ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push("</strong> </div> </div> <div class=\"s-scroll-wrap\"> <div> ");
  stack1 = helpers.each.call(depth0, "view.backupList", {hash:{},hashTypes:{},hashContexts:{},inverse:self.noop,fn:self.program(3, program3, data),contexts:[depth0],types:["ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" </div> </div> </div>");
  return buffer;
  
});

Ember.TEMPLATES["UMI/partials/dropdownButton"] = Ember.Handlebars.template(function anonymous(Handlebars,depth0,helpers,partials,data) {
this.compilerInfo = [4,'>= 1.0.0'];
helpers = this.merge(helpers, Ember.Handlebars.helpers); data = data || {};
  var buffer = '', stack1, escapeExpression=this.escapeExpression, self=this;

function program1(depth0,data) {
  
  var buffer = '';
  data.buffer.push(" <i class=\"");
  data.buffer.push(escapeExpression(helpers.unbound.call(depth0, "view.meta.attributes.icon.class", {hash:{},hashTypes:{},hashContexts:{},contexts:[depth0],types:["ID"],data:data})));
  data.buffer.push("\"></i> ");
  return buffer;
  }

function program3(depth0,data) {
  
  var buffer = '';
  data.buffer.push(" ");
  data.buffer.push(escapeExpression(helpers.unbound.call(depth0, "view.meta.attributes.label", {hash:{},hashTypes:{},hashContexts:{},contexts:[depth0],types:["ID"],data:data})));
  data.buffer.push(" ");
  return buffer;
  }

function program5(depth0,data) {
  
  var buffer = '', stack1;
  data.buffer.push(" <span ");
  data.buffer.push(escapeExpression(helpers.action.call(depth0, "sendActionForBehaviour", "behaviour", {hash:{
    'target': ("view")
  },hashTypes:{'target': "STRING"},hashContexts:{'target': depth0},contexts:[depth0,depth0],types:["STRING","ID"],data:data})));
  data.buffer.push("> ");
  stack1 = helpers._triageMustache.call(depth0, "attributes.label", {hash:{},hashTypes:{},hashContexts:{},contexts:[depth0],types:["ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" </span> ");
  return buffer;
  }

  stack1 = helpers['if'].call(depth0, "view.meta.attributes.icon", {hash:{},hashTypes:{},hashContexts:{},inverse:self.noop,fn:self.program(1, program1, data),contexts:[depth0],types:["ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" ");
  stack1 = helpers['if'].call(depth0, "view.meta.attributes.label", {hash:{},hashTypes:{},hashContexts:{},inverse:self.noop,fn:self.program(3, program3, data),contexts:[depth0],types:["ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" <ul class=\"f-dropdown\"> ");
  stack1 = helpers.each.call(depth0, "view.meta.choices", {hash:{},hashTypes:{},hashContexts:{},inverse:self.noop,fn:self.program(5, program5, data),contexts:[depth0],types:["ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" </ul> ");
  return buffer;
  
});

Ember.TEMPLATES["UMI/partials/splitButton"] = Ember.Handlebars.template(function anonymous(Handlebars,depth0,helpers,partials,data) {
this.compilerInfo = [4,'>= 1.0.0'];
helpers = this.merge(helpers, Ember.Handlebars.helpers); data = data || {};
  var buffer = '', stack1, escapeExpression=this.escapeExpression, self=this;

function program1(depth0,data) {
  
  var buffer = '';
  data.buffer.push(" <i ");
  data.buffer.push(escapeExpression(helpers['bind-attr'].call(depth0, {hash:{
    'class': (":icon view.defaultBehaviourIcon")
  },hashTypes:{'class': "STRING"},hashContexts:{'class': depth0},contexts:[],types:[],data:data})));
  data.buffer.push("></i> ");
  return buffer;
  }

function program3(depth0,data) {
  
  var buffer = '', stack1;
  data.buffer.push(" <b class=\"button-label\">");
  stack1 = helpers._triageMustache.call(depth0, "view.label", {hash:{},hashTypes:{},hashContexts:{},contexts:[depth0],types:["ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push("</b> ");
  return buffer;
  }

function program5(depth0,data) {
  
  var buffer = '', stack1;
  data.buffer.push(" ");
  stack1 = helpers.view.call(depth0, "view.itemView", {hash:{},hashTypes:{},hashContexts:{},inverse:self.noop,fn:self.program(6, program6, data),contexts:[depth0],types:["ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" ");
  return buffer;
  }
function program6(depth0,data) {
  
  var buffer = '', stack1;
  data.buffer.push(" <i ");
  data.buffer.push(escapeExpression(helpers['bind-attr'].call(depth0, {hash:{
    'class': (":icon :icon-accept view.isDefaultBehaviour::white")
  },hashTypes:{'class': "STRING"},hashContexts:{'class': depth0},contexts:[],types:[],data:data})));
  data.buffer.push(" ");
  data.buffer.push(escapeExpression(helpers.action.call(depth0, "toggleDefaultBehaviour", "view._parentView.contentIndex", {hash:{
    'target': ("view.parentView"),
    'on': ("mouseUp")
  },hashTypes:{'target': "STRING",'on': "STRING"},hashContexts:{'target': depth0,'on': depth0},contexts:[depth0,depth0],types:["STRING","ID"],data:data})));
  data.buffer.push("></i> <a ");
  data.buffer.push(escapeExpression(helpers.action.call(depth0, "sendActionForBehaviour", "behaviour", {hash:{
    'target': ("view.parentView"),
    'on': ("mouseUp")
  },hashTypes:{'target': "STRING",'on': "STRING"},hashContexts:{'target': depth0,'on': depth0},contexts:[depth0,depth0],types:["STRING","ID"],data:data})));
  data.buffer.push("> ");
  stack1 = helpers._triageMustache.call(depth0, "view.label", {hash:{},hashTypes:{},hashContexts:{},contexts:[depth0],types:["ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" </a> ");
  return buffer;
  }

  stack1 = helpers['if'].call(depth0, "view.meta.attributes.hasIcon", {hash:{},hashTypes:{},hashContexts:{},inverse:self.noop,fn:self.program(1, program1, data),contexts:[depth0],types:["ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" ");
  stack1 = helpers['if'].call(depth0, "view.label", {hash:{},hashTypes:{},hashContexts:{},inverse:self.noop,fn:self.program(3, program3, data),contexts:[depth0],types:["ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" <span class=\"dropdown-toggler\" ");
  data.buffer.push(escapeExpression(helpers.action.call(depth0, "open", {hash:{
    'target': ("view")
  },hashTypes:{'target': "STRING"},hashContexts:{'target': depth0},contexts:[depth0],types:["STRING"],data:data})));
  data.buffer.push("></span> <ul class=\"f-dropdown composite\"> ");
  stack1 = helpers.each.call(depth0, "view.meta.behaviour.choices", {hash:{},hashTypes:{},hashContexts:{},inverse:self.noop,fn:self.program(5, program5, data),contexts:[depth0],types:["ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" </ul> ");
  return buffer;
  
});

Ember.TEMPLATES["UMI/partials/toolbar"] = Ember.Handlebars.template(function anonymous(Handlebars,depth0,helpers,partials,data) {
this.compilerInfo = [4,'>= 1.0.0'];
helpers = this.merge(helpers, Ember.Handlebars.helpers); data = data || {};
  var buffer = '', stack1, escapeExpression=this.escapeExpression, self=this;

function program1(depth0,data) {
  
  var buffer = '';
  data.buffer.push(" ");
  data.buffer.push(escapeExpression(helpers.view.call(depth0, "view.elementView", {hash:{},hashTypes:{},hashContexts:{},contexts:[depth0],types:["ID"],data:data})));
  data.buffer.push(" ");
  return buffer;
  }

  data.buffer.push("<ul class=\"button-group left\"> ");
  stack1 = helpers.each.call(depth0, "view.toolbar", {hash:{},hashTypes:{},hashContexts:{},inverse:self.noop,fn:self.program(1, program1, data),contexts:[depth0],types:["ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" </ul> <div class=\"right\"> ");
  stack1 = helpers._triageMustache.call(depth0, "yield", {hash:{},hashTypes:{},hashContexts:{},contexts:[depth0],types:["ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" </div>");
  return buffer;
  
});

Ember.TEMPLATES["UMI/partials/topBar"] = Ember.Handlebars.template(function anonymous(Handlebars,depth0,helpers,partials,data) {
this.compilerInfo = [4,'>= 1.0.0'];
helpers = this.merge(helpers, Ember.Handlebars.helpers); data = data || {};
  var buffer = '', helper, options, escapeExpression=this.escapeExpression, helperMissing=helpers.helperMissing;


  data.buffer.push("<nav class=\"umi-top-bar\"> <ul class=\"umi-top-bar-list left\"> <li> <a href=\"javascript:void(0)\" ");
  data.buffer.push(escapeExpression(helpers.action.call(depth0, "viewOnSite", {hash:{},hashTypes:{},hashContexts:{},contexts:[depth0],types:["STRING"],data:data})));
  data.buffer.push(" class=\"button tiny flat umi-top-bar-button\"> ");
  data.buffer.push(escapeExpression((helper = helpers.i18n || (depth0 && depth0.i18n),options={hash:{},hashTypes:{},hashContexts:{},contexts:[depth0],types:["STRING"],data:data},helper ? helper.call(depth0, "Open site in new tab", options) : helperMissing.call(depth0, "i18n", "Open site in new tab", options))));
  data.buffer.push(" <i class=\"icon icon-viewOnSite\"></i> </a> </li> </ul> <ul class=\"umi-top-bar-list right\"> <li> ");
  data.buffer.push(escapeExpression(helpers.view.call(depth0, "view.dropdownView", {hash:{
    'tagName': ("span"),
    'class': ("button tiny flat dropdown umi-top-bar-button")
  },hashTypes:{'tagName': "STRING",'class': "STRING"},hashContexts:{'tagName': depth0,'class': depth0},contexts:[depth0],types:["ID"],data:data})));
  data.buffer.push(" </li> ");
  data.buffer.push(escapeExpression(helpers.view.call(depth0, "notificationList", {hash:{},hashTypes:{},hashContexts:{},contexts:[depth0],types:["STRING"],data:data})));
  data.buffer.push(" </ul> </nav>");
  return buffer;
  
});

Ember.TEMPLATES["UMI/partials/treeControl"] = Ember.Handlebars.template(function anonymous(Handlebars,depth0,helpers,partials,data) {
this.compilerInfo = [4,'>= 1.0.0'];
helpers = this.merge(helpers, Ember.Handlebars.helpers); data = data || {};
  var buffer = '', stack1, escapeExpression=this.escapeExpression, self=this;

function program1(depth0,data) {
  
  var buffer = '';
  data.buffer.push(" ");
  data.buffer.push(escapeExpression(helpers.view.call(depth0, "treeItem", {hash:{
    'treeControlView': ("view"),
    'item': ("item")
  },hashTypes:{'treeControlView': "ID",'item': "ID"},hashContexts:{'treeControlView': depth0,'item': depth0},contexts:[depth0],types:["STRING"],data:data})));
  data.buffer.push(" ");
  return buffer;
  }

  data.buffer.push("<div class=\"columns small-12\" style=\"overflow: hidden;\"> <div class=\"row s-full-height umi-tree-wrapper\"> <ul class=\"umi-tree-list umi-tree\"> ");
  stack1 = helpers.each.call(depth0, "item", "in", "root", {hash:{},hashTypes:{},hashContexts:{},inverse:self.noop,fn:self.program(1, program1, data),contexts:[depth0,depth0,depth0],types:["ID","ID","ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" </ul> </div> </div>");
  return buffer;
  
});

Ember.TEMPLATES["UMI/partials/treeControl/treeItem"] = Ember.Handlebars.template(function anonymous(Handlebars,depth0,helpers,partials,data) {
this.compilerInfo = [4,'>= 1.0.0'];
helpers = this.merge(helpers, Ember.Handlebars.helpers); data = data || {};
  var buffer = '', stack1, escapeExpression=this.escapeExpression, self=this, helperMissing=helpers.helperMissing;

function program1(depth0,data) {
  
  var buffer = '', stack1;
  data.buffer.push(" <span ");
  data.buffer.push(escapeExpression(helpers.action.call(depth0, "expanded", {hash:{
    'on': ("mouseDown"),
    'target': ("view")
  },hashTypes:{'on': "STRING",'target': "STRING"},hashContexts:{'on': depth0,'target': depth0},contexts:[depth0],types:["STRING"],data:data})));
  data.buffer.push(" ");
  data.buffer.push(escapeExpression(helpers['bind-attr'].call(depth0, {hash:{
    'class': (":umi-expand")
  },hashTypes:{'class': "STRING"},hashContexts:{'class': depth0},contexts:[],types:[],data:data})));
  data.buffer.push("> ");
  stack1 = helpers['if'].call(depth0, "view.isExpanded", {hash:{},hashTypes:{},hashContexts:{},inverse:self.program(3, program3, data),fn:self.program(2, program2, data),contexts:[depth0],types:["ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" </span> <i ");
  data.buffer.push(escapeExpression(helpers['bind-attr'].call(depth0, {hash:{
    'class': (":icon :umi-tree-type-icon :icon-document view.item.root::move")
  },hashTypes:{'class': "STRING"},hashContexts:{'class': depth0},contexts:[],types:[],data:data})));
  data.buffer.push("></i> ");
  return buffer;
  }
function program2(depth0,data) {
  
  var buffer = '', stack1;
  data.buffer.push(" ");
  stack1 = helpers['if'].call(depth0, "view.sortedChildren", {hash:{},hashTypes:{},hashContexts:{},inverse:self.program(5, program5, data),fn:self.program(3, program3, data),contexts:[depth0],types:["ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" ");
  return buffer;
  }
function program3(depth0,data) {
  
  var buffer = '';
  data.buffer.push(" <i ");
  data.buffer.push(escapeExpression(helpers['bind-attr'].call(depth0, {hash:{
    'class': (":icon view.isExpanded:icon-bottom:icon-right")
  },hashTypes:{'class': "STRING"},hashContexts:{'class': depth0},contexts:[],types:[],data:data})));
  data.buffer.push("></i> ");
  return buffer;
  }

function program5(depth0,data) {
  
  
  data.buffer.push(" <i class=\"animate animate-loader-20\"></i> ");
  }

function program7(depth0,data) {
  
  var buffer = '';
  data.buffer.push(" <i ");
  data.buffer.push(escapeExpression(helpers['bind-attr'].call(depth0, {hash:{
    'class': (":icon :umi-tree-type-icon :icon-document view.item.root::move")
  },hashTypes:{'class': "STRING"},hashContexts:{'class': depth0},contexts:[],types:[],data:data})));
  data.buffer.push("></i> ");
  return buffer;
  }

function program9(depth0,data) {
  
  var buffer = '', stack1, helper, options;
  data.buffer.push(" ");
  stack1 = (helper = helpers['link-to'] || (depth0 && depth0['link-to']),options={hash:{
    'class': ("tree-item-link")
  },hashTypes:{'class': "STRING"},hashContexts:{'class': depth0},inverse:self.noop,fn:self.program(10, program10, data),contexts:[depth0,depth0,depth0],types:["STRING","ID","STRING"],data:data},helper ? helper.call(depth0, "action", "view.item.id", "editForm", options) : helperMissing.call(depth0, "link-to", "action", "view.item.id", "editForm", options));
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" ");
  return buffer;
  }
function program10(depth0,data) {
  
  var buffer = '', stack1;
  data.buffer.push(" ");
  stack1 = helpers._triageMustache.call(depth0, "view.savedDisplayName", {hash:{},hashTypes:{},hashContexts:{},contexts:[depth0],types:["ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" ");
  return buffer;
  }

function program12(depth0,data) {
  
  var buffer = '', stack1, helper, options;
  data.buffer.push(" ");
  stack1 = (helper = helpers['link-to'] || (depth0 && depth0['link-to']),options={hash:{
    'class': ("tree-item-link")
  },hashTypes:{'class': "STRING"},hashContexts:{'class': depth0},inverse:self.noop,fn:self.program(10, program10, data),contexts:[depth0,depth0],types:["STRING","ID"],data:data},helper ? helper.call(depth0, "context", "view.item.id", options) : helperMissing.call(depth0, "link-to", "context", "view.item.id", options));
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" ");
  return buffer;
  }

function program14(depth0,data) {
  
  var buffer = '', stack1, helper, options;
  data.buffer.push(" ");
  stack1 = (helper = helpers.render || (depth0 && depth0.render),options={hash:{},hashTypes:{},hashContexts:{},inverse:self.noop,fn:self.program(15, program15, data),contexts:[depth0,depth0],types:["STRING","ID"],data:data},helper ? helper.call(depth0, "treeControlContextToolbar", "view.item", options) : helperMissing.call(depth0, "render", "treeControlContextToolbar", "view.item", options));
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" ");
  return buffer;
  }
function program15(depth0,data) {
  
  var buffer = '', stack1;
  data.buffer.push(" ");
  stack1 = helpers.each.call(depth0, "parentController.contextToolbar", {hash:{},hashTypes:{},hashContexts:{},inverse:self.noop,fn:self.program(16, program16, data),contexts:[depth0],types:["ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" ");
  return buffer;
  }
function program16(depth0,data) {
  
  var buffer = '';
  data.buffer.push(" ");
  data.buffer.push(escapeExpression(helpers.view.call(depth0, "view.elementView", {hash:{},hashTypes:{},hashContexts:{},contexts:[depth0],types:["ID"],data:data})));
  data.buffer.push(" ");
  return buffer;
  }

function program18(depth0,data) {
  
  var buffer = '', stack1;
  data.buffer.push(" ");
  stack1 = helpers['if'].call(depth0, "view.isExpanded", {hash:{},hashTypes:{},hashContexts:{},inverse:self.noop,fn:self.program(19, program19, data),contexts:[depth0],types:["ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" ");
  return buffer;
  }
function program19(depth0,data) {
  
  var buffer = '', stack1;
  data.buffer.push(" <ul class=\"umi-tree-list\" data-parent-id=\"");
  data.buffer.push(escapeExpression(helpers.unbound.call(depth0, "item.id", {hash:{},hashTypes:{},hashContexts:{},contexts:[depth0],types:["ID"],data:data})));
  data.buffer.push("\"> ");
  stack1 = helpers.each.call(depth0, "item", "in", "view.sortedChildren", {hash:{},hashTypes:{},hashContexts:{},inverse:self.noop,fn:self.program(20, program20, data),contexts:[depth0,depth0,depth0],types:["ID","ID","ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" </ul> ");
  return buffer;
  }
function program20(depth0,data) {
  
  var buffer = '';
  data.buffer.push(" ");
  data.buffer.push(escapeExpression(helpers.view.call(depth0, "treeItem", {hash:{
    'treeControlView': ("view.treeControlView"),
    'item': ("item")
  },hashTypes:{'treeControlView': "ID",'item': "ID"},hashContexts:{'treeControlView': depth0,'item': depth0},contexts:[depth0],types:["STRING"],data:data})));
  data.buffer.push(" ");
  return buffer;
  }

  data.buffer.push("<div ");
  data.buffer.push(escapeExpression(helpers['bind-attr'].call(depth0, {hash:{
    'class': (":umi-item view.item.type view.active view.inActive")
  },hashTypes:{'class': "STRING"},hashContexts:{'class': depth0},contexts:[],types:[],data:data})));
  data.buffer.push("> ");
  stack1 = helpers['if'].call(depth0, "item.childCount", {hash:{},hashTypes:{},hashContexts:{},inverse:self.program(7, program7, data),fn:self.program(1, program1, data),contexts:[depth0],types:["ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" ");
  stack1 = helpers['if'].call(depth0, "view.editLink", {hash:{},hashTypes:{},hashContexts:{},inverse:self.program(12, program12, data),fn:self.program(9, program9, data),contexts:[depth0],types:["ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" ");
  stack1 = helpers['if'].call(depth0, "controller.contextToolbar", {hash:{},hashTypes:{},hashContexts:{},inverse:self.noop,fn:self.program(14, program14, data),contexts:[depth0],types:["ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" </div> ");
  stack1 = helpers['if'].call(depth0, "item.childCount", {hash:{},hashTypes:{},hashContexts:{},inverse:self.noop,fn:self.program(18, program18, data),contexts:[depth0],types:["ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  return buffer;
  
});

Ember.TEMPLATES["UMI/partials/treeSimple/item"] = Ember.Handlebars.template(function anonymous(Handlebars,depth0,helpers,partials,data) {
this.compilerInfo = [4,'>= 1.0.0'];
helpers = this.merge(helpers, Ember.Handlebars.helpers); data = data || {};
  var buffer = '', stack1, helper, options, escapeExpression=this.escapeExpression, self=this, helperMissing=helpers.helperMissing;

function program1(depth0,data) {
  
  var buffer = '', stack1;
  data.buffer.push(" ");
  stack1 = helpers['if'].call(depth0, "components", {hash:{},hashTypes:{},hashContexts:{},inverse:self.program(4, program4, data),fn:self.program(2, program2, data),contexts:[depth0],types:["ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" ");
  stack1 = helpers['if'].call(depth0, "resource", {hash:{},hashTypes:{},hashContexts:{},inverse:self.program(7, program7, data),fn:self.program(6, program6, data),contexts:[depth0],types:["ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" ");
  return buffer;
  }
function program2(depth0,data) {
  
  var buffer = '';
  data.buffer.push(" <span ");
  data.buffer.push(escapeExpression(helpers.action.call(depth0, "expanded", {hash:{
    'target': ("view")
  },hashTypes:{'target': "STRING"},hashContexts:{'target': depth0},contexts:[depth0],types:["STRING"],data:data})));
  data.buffer.push(" ");
  data.buffer.push(escapeExpression(helpers['bind-attr'].call(depth0, {hash:{
    'class': (":umi-expand")
  },hashTypes:{'class': "STRING"},hashContexts:{'class': depth0},contexts:[],types:[],data:data})));
  data.buffer.push("> <i ");
  data.buffer.push(escapeExpression(helpers['bind-attr'].call(depth0, {hash:{
    'class': (":icon view.isExpanded:icon-bottom:icon-right")
  },hashTypes:{'class': "STRING"},hashContexts:{'class': depth0},contexts:[],types:[],data:data})));
  data.buffer.push("></i> </span> <i ");
  data.buffer.push(escapeExpression(helpers['bind-attr'].call(depth0, {hash:{
    'class': (":icon :umi-tree-type-icon :icon-document")
  },hashTypes:{'class': "STRING"},hashContexts:{'class': depth0},contexts:[],types:[],data:data})));
  data.buffer.push("></i> ");
  return buffer;
  }

function program4(depth0,data) {
  
  var buffer = '';
  data.buffer.push(" <i ");
  data.buffer.push(escapeExpression(helpers['bind-attr'].call(depth0, {hash:{
    'class': (":icon :umi-tree-type-icon :icon-document")
  },hashTypes:{'class': "STRING"},hashContexts:{'class': depth0},contexts:[],types:[],data:data})));
  data.buffer.push("></i> ");
  return buffer;
  }

function program6(depth0,data) {
  
  var buffer = '', stack1, helper, options;
  data.buffer.push(" ");
  stack1 = (helper = helpers['link-to'] || (depth0 && depth0['link-to']),options={hash:{
    'class': ("tree-item-link")
  },hashTypes:{'class': "STRING"},hashContexts:{'class': depth0},inverse:self.noop,fn:self.program(7, program7, data),contexts:[depth0,depth0],types:["STRING","ID"],data:data},helper ? helper.call(depth0, "settings.component", "view.nestedSlug", options) : helperMissing.call(depth0, "link-to", "settings.component", "view.nestedSlug", options));
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" ");
  return buffer;
  }
function program7(depth0,data) {
  
  var buffer = '', stack1;
  data.buffer.push(" ");
  stack1 = helpers._triageMustache.call(depth0, "displayName", {hash:{},hashTypes:{},hashContexts:{},contexts:[depth0],types:["ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" ");
  return buffer;
  }

function program9(depth0,data) {
  
  var buffer = '', stack1;
  data.buffer.push(" <ul class=\"umi-tree-list\"> ");
  stack1 = helpers.each.call(depth0, "components", {hash:{},hashTypes:{},hashContexts:{},inverse:self.noop,fn:self.program(10, program10, data),contexts:[depth0],types:["ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" </ul> ");
  return buffer;
  }
function program10(depth0,data) {
  
  var buffer = '';
  data.buffer.push(" ");
  data.buffer.push(escapeExpression(helpers.view.call(depth0, "treeSimpleItem", {hash:{},hashTypes:{},hashContexts:{},contexts:[depth0],types:["STRING"],data:data})));
  data.buffer.push(" ");
  return buffer;
  }

  stack1 = (helper = helpers['link-to'] || (depth0 && depth0['link-to']),options={hash:{
    'tagName': ("div"),
    'class': ("umi-item"),
    'disabled': (true),
    'bubbles': (false)
  },hashTypes:{'tagName': "STRING",'class': "STRING",'disabled': "BOOLEAN",'bubbles': "BOOLEAN"},hashContexts:{'tagName': depth0,'class': depth0,'disabled': depth0,'bubbles': depth0},inverse:self.noop,fn:self.program(1, program1, data),contexts:[depth0,depth0],types:["STRING","ID"],data:data},helper ? helper.call(depth0, "settings.component", "view.nestedSlug", options) : helperMissing.call(depth0, "link-to", "settings.component", "view.nestedSlug", options));
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" ");
  stack1 = helpers['if'].call(depth0, "view.isExpanded", {hash:{},hashTypes:{},hashContexts:{},inverse:self.noop,fn:self.program(9, program9, data),contexts:[depth0],types:["ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" ");
  return buffer;
  
});

Ember.TEMPLATES["UMI/partials/treeSimple/list"] = Ember.Handlebars.template(function anonymous(Handlebars,depth0,helpers,partials,data) {
this.compilerInfo = [4,'>= 1.0.0'];
helpers = this.merge(helpers, Ember.Handlebars.helpers); data = data || {};
  var buffer = '', stack1, escapeExpression=this.escapeExpression, self=this;

function program1(depth0,data) {
  
  var buffer = '';
  data.buffer.push(" ");
  data.buffer.push(escapeExpression(helpers.view.call(depth0, "treeSimpleItem", {hash:{},hashTypes:{},hashContexts:{},contexts:[depth0],types:["STRING"],data:data})));
  data.buffer.push(" ");
  return buffer;
  }

  data.buffer.push("<div class=\"columns small-12\"> <div class=\"row s-full-height umi-tree-wrapper\"> <ul class=\"umi-tree-list umi-tree\"> ");
  stack1 = helpers.each.call(depth0, "view.collection", {hash:{},hashTypes:{},hashContexts:{},inverse:self.noop,fn:self.program(1, program1, data),contexts:[depth0],types:["ID"],data:data});
  if(stack1 || stack1 === 0) { data.buffer.push(stack1); }
  data.buffer.push(" </ul> </div> </div>");
  return buffer;
  
});

});