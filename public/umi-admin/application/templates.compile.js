define(["Ember"], function(Ember){

Ember.TEMPLATES["UMI/application"] = Ember.Handlebars.template({"compiler":[6,">= 2.0.0-beta.1"],"main":function(depth0,helpers,partials,data) {
  var stack1, escapeExpression=this.escapeExpression, helperMissing=helpers.helperMissing, buffer = '';
  data.buffer.push("<div class=\"s-full-height-before umi-header\"> ");
  data.buffer.push(escapeExpression(helpers.view.call(depth0, "topBar", {"name":"view","hash":{},"hashTypes":{},"hashContexts":{},"types":["STRING"],"contexts":[depth0],"data":data})));
  data.buffer.push(" ");
  data.buffer.push(escapeExpression(((helpers.render || (depth0 && depth0.render) || helperMissing).call(depth0, "dock", {"name":"render","hash":{},"hashTypes":{},"hashContexts":{},"types":["STRING"],"contexts":[depth0],"data":data}))));
  data.buffer.push(" </div> ");
  stack1 = helpers._triageMustache.call(depth0, "outlet", {"name":"_triageMustache","hash":{},"hashTypes":{},"hashContexts":{},"types":["ID"],"contexts":[depth0],"data":data});
  if (stack1 != null) { data.buffer.push(stack1); }
  data.buffer.push(" ");
  data.buffer.push(escapeExpression(((helpers.outlet || (depth0 && depth0.outlet) || helperMissing).call(depth0, "popup", {"name":"outlet","hash":{},"hashTypes":{},"hashContexts":{},"types":["STRING"],"contexts":[depth0],"data":data}))));
  return buffer;
},"useData":true});

Ember.TEMPLATES["UMI/component"] = Ember.Handlebars.template({"1":function(depth0,helpers,partials,data) {
  var stack1, escapeExpression=this.escapeExpression, buffer = '';
  data.buffer.push(" ");
  stack1 = helpers['if'].call(depth0, "sideBarControl", {"name":"if","hash":{},"hashTypes":{},"hashContexts":{},"fn":this.program(2, data),"inverse":this.noop,"types":["ID"],"contexts":[depth0],"data":data});
  if (stack1 != null) { data.buffer.push(stack1); }
  data.buffer.push(" <div ");
  data.buffer.push(escapeExpression(helpers['bind-attr'].call(depth0, {"name":"bind-attr","hash":{
    'class': (":umi-divider-right sideBarControl::wide")
  },"hashTypes":{'class': "STRING"},"hashContexts":{'class': depth0},"types":[],"contexts":[],"data":data})));
  data.buffer.push("> <div class=\"umi-component columns small-12 s-padding-clear s-full-height\"> ");
  stack1 = helpers._triageMustache.call(depth0, "outlet", {"name":"_triageMustache","hash":{},"hashTypes":{},"hashContexts":{},"types":["ID"],"contexts":[depth0],"data":data});
  if (stack1 != null) { data.buffer.push(stack1); }
  data.buffer.push(" </div> </div> ");
  return buffer;
},"2":function(depth0,helpers,partials,data) {
  var helperMissing=helpers.helperMissing, escapeExpression=this.escapeExpression, buffer = '';
  data.buffer.push(" <div class=\"umi-divider-left\"> <div class=\"umi-divider-left-content\"> ");
  data.buffer.push(escapeExpression(((helpers.outlet || (depth0 && depth0.outlet) || helperMissing).call(depth0, "sideBar", {"name":"outlet","hash":{},"hashTypes":{},"hashContexts":{},"types":["STRING"],"contexts":[depth0],"data":data}))));
  data.buffer.push(" </div> <div class=\"umi-divider\"></div> </div> <div class=\"umi-left-bottom-panel s-unselectable\"> <a href=\"javascript:void(0)\" class=\"button white square umi-divider-left-toggle\"> <i class=\"icon icon-left\"></i> </a> </div> ");
  return buffer;
},"compiler":[6,">= 2.0.0-beta.1"],"main":function(depth0,helpers,partials,data) {
  var stack1, buffer = '';
  data.buffer.push("<div class=\"s-full-height\"> ");
  stack1 = helpers.view.call(depth0, "divider", {"name":"view","hash":{
    'modelBinding': ("model")
  },"hashTypes":{'modelBinding': "STRING"},"hashContexts":{'modelBinding': depth0},"fn":this.program(1, data),"inverse":this.noop,"types":["STRING"],"contexts":[depth0],"data":data});
  if (stack1 != null) { data.buffer.push(stack1); }
  data.buffer.push(" </div>");
  return buffer;
},"useData":true});

Ember.TEMPLATES["UMI/editForm"] = Ember.Handlebars.template({"compiler":[6,">= 2.0.0-beta.1"],"main":function(depth0,helpers,partials,data) {
  var helperMissing=helpers.helperMissing, escapeExpression=this.escapeExpression;
  data.buffer.push(escapeExpression(((helpers.render || (depth0 && depth0.render) || helperMissing).call(depth0, "formCollection", "model", {"name":"render","hash":{},"hashTypes":{},"hashContexts":{},"types":["STRING","ID"],"contexts":[depth0,depth0],"data":data}))));
  },"useData":true});

Ember.TEMPLATES["UMI/empty"] = Ember.Handlebars.template({"compiler":[6,">= 2.0.0-beta.1"],"main":function(depth0,helpers,partials,data) {
  var stack1, buffer = '';
  data.buffer.push("<div class=\"s-full-height panel\"> <h3 class=\"text-center\">");
  stack1 = helpers._triageMustache.call(depth0, "model.control.params.content", {"name":"_triageMustache","hash":{},"hashTypes":{},"hashContexts":{},"types":["ID"],"contexts":[depth0],"data":data});
  if (stack1 != null) { data.buffer.push(stack1); }
  data.buffer.push("</h3> </div> ");
  return buffer;
},"useData":true});

Ember.TEMPLATES["UMI/files"] = Ember.Handlebars.template({"compiler":[6,">= 2.0.0-beta.1"],"main":function(depth0,helpers,partials,data) {
  var escapeExpression=this.escapeExpression;
  data.buffer.push(escapeExpression(helpers.view.call(depth0, "fileManager", {"name":"view","hash":{
    'content': ("model")
  },"hashTypes":{'content': "ID"},"hashContexts":{'content': depth0},"types":["STRING"],"contexts":[depth0],"data":data})));
  },"useData":true});

Ember.TEMPLATES["UMI/filter"] = Ember.Handlebars.template({"compiler":[6,">= 2.0.0-beta.1"],"main":function(depth0,helpers,partials,data) {
  var helperMissing=helpers.helperMissing, escapeExpression=this.escapeExpression;
  data.buffer.push(escapeExpression(((helpers.render || (depth0 && depth0.render) || helperMissing).call(depth0, "tableControl", "model", {"name":"render","hash":{},"hashTypes":{},"hashContexts":{},"types":["STRING","ID"],"contexts":[depth0,depth0],"data":data}))));
  },"useData":true});

Ember.TEMPLATES["UMI/getBacklinks"] = Ember.Handlebars.template({"compiler":[6,">= 2.0.0-beta.1"],"main":function(depth0,helpers,partials,data) {
  var escapeExpression=this.escapeExpression;
  data.buffer.push(escapeExpression(helpers.view.call(depth0, "backlinksTable", {"name":"view","hash":{
    'contentBinding': ("model")
  },"hashTypes":{'contentBinding': "STRING"},"hashContexts":{'contentBinding': depth0},"types":["STRING"],"contexts":[depth0],"data":data})));
  },"useData":true});

Ember.TEMPLATES["UMI/host"] = Ember.Handlebars.template({"compiler":[6,">= 2.0.0-beta.1"],"main":function(depth0,helpers,partials,data) {
  var escapeExpression=this.escapeExpression;
  data.buffer.push(escapeExpression(helpers.view.call(depth0, "yaHostTable", {"name":"view","hash":{
    'contentBinding': ("model")
  },"hashTypes":{'contentBinding': "STRING"},"hashContexts":{'contentBinding': depth0},"types":["STRING"],"contexts":[depth0],"data":data})));
  },"useData":true});

Ember.TEMPLATES["UMI/indexed"] = Ember.Handlebars.template({"compiler":[6,">= 2.0.0-beta.1"],"main":function(depth0,helpers,partials,data) {
  var escapeExpression=this.escapeExpression;
  data.buffer.push(escapeExpression(helpers.view.call(depth0, "yaIndexesTable", {"name":"view","hash":{
    'contentBinding': ("model")
  },"hashTypes":{'contentBinding': "STRING"},"hashContexts":{'contentBinding': depth0},"types":["STRING"],"contexts":[depth0],"data":data})));
  },"useData":true});

Ember.TEMPLATES["UMI/simpleForm"] = Ember.Handlebars.template({"compiler":[6,">= 2.0.0-beta.1"],"main":function(depth0,helpers,partials,data) {
  var helperMissing=helpers.helperMissing, escapeExpression=this.escapeExpression;
  data.buffer.push(escapeExpression(((helpers.render || (depth0 && depth0.render) || helperMissing).call(depth0, "formSimple", "model", {"name":"render","hash":{},"hashTypes":{},"hashContexts":{},"types":["STRING","ID"],"contexts":[depth0,depth0],"data":data}))));
  },"useData":true});

Ember.TEMPLATES["UMI/simpleTable"] = Ember.Handlebars.template({"compiler":[6,">= 2.0.0-beta.1"],"main":function(depth0,helpers,partials,data) {
  var escapeExpression=this.escapeExpression;
  data.buffer.push(escapeExpression(helpers.view.call(depth0, "tableCounters", {"name":"view","hash":{
    'contentBinding': ("model")
  },"hashTypes":{'contentBinding': "STRING"},"hashContexts":{'contentBinding': depth0},"types":["STRING"],"contexts":[depth0],"data":data})));
  },"useData":true});

Ember.TEMPLATES["UMI/siteAnalyze"] = Ember.Handlebars.template({"compiler":[6,">= 2.0.0-beta.1"],"main":function(depth0,helpers,partials,data) {
  var escapeExpression=this.escapeExpression;
  data.buffer.push(escapeExpression(helpers.view.call(depth0, "siteAnalyzeTable", {"name":"view","hash":{
    'contentBinding': ("model")
  },"hashTypes":{'contentBinding': "STRING"},"hashContexts":{'contentBinding': depth0},"types":["STRING"],"contexts":[depth0],"data":data})));
  },"useData":true});

Ember.TEMPLATES["UMI/tops"] = Ember.Handlebars.template({"compiler":[6,">= 2.0.0-beta.1"],"main":function(depth0,helpers,partials,data) {
  var escapeExpression=this.escapeExpression;
  data.buffer.push(escapeExpression(helpers.view.call(depth0, "yaTopsTable", {"name":"view","hash":{
    'contentBinding': ("model")
  },"hashTypes":{'contentBinding': "STRING"},"hashContexts":{'contentBinding': depth0},"types":["STRING"],"contexts":[depth0],"data":data})));
  },"useData":true});

Ember.TEMPLATES["UMI/update"] = Ember.Handlebars.template({"compiler":[6,">= 2.0.0-beta.1"],"main":function(depth0,helpers,partials,data) {
  var escapeExpression=this.escapeExpression;
  data.buffer.push(escapeExpression(helpers.view.call(depth0, "updateLayout", {"name":"view","hash":{
    'data': ("model")
  },"hashTypes":{'data': "ID"},"hashContexts":{'data': depth0},"types":["STRING"],"contexts":[depth0],"data":data})));
  },"useData":true});

Ember.TEMPLATES["UMI/errors"] = Ember.Handlebars.template({"1":function(depth0,helpers,partials,data) {
  var stack1, buffer = '';
  data.buffer.push(" <p>");
  stack1 = helpers._triageMustache.call(depth0, "model.content", {"name":"_triageMustache","hash":{},"hashTypes":{},"hashContexts":{},"types":["ID"],"contexts":[depth0],"data":data});
  if (stack1 != null) { data.buffer.push(stack1); }
  data.buffer.push("</p> ");
  return buffer;
},"3":function(depth0,helpers,partials,data) {
  var stack1, buffer = '';
  data.buffer.push(" <div class=\"text-left\"> <code>");
  stack1 = helpers._triageMustache.call(depth0, "stack", {"name":"_triageMustache","hash":{},"hashTypes":{},"hashContexts":{},"types":["ID"],"contexts":[depth0],"data":data});
  if (stack1 != null) { data.buffer.push(stack1); }
  data.buffer.push("</code> </div> ");
  return buffer;
},"5":function(depth0,helpers,partials,data) {
  var stack1, buffer = '';
  data.buffer.push(" <div class=\"alert-box error\"> <ul> ");
  stack1 = helpers.each.call(depth0, "lists", {"name":"each","hash":{},"hashTypes":{},"hashContexts":{},"fn":this.program(6, data),"inverse":this.noop,"types":["ID"],"contexts":[depth0],"data":data});
  if (stack1 != null) { data.buffer.push(stack1); }
  data.buffer.push(" </ul> </div> ");
  return buffer;
},"6":function(depth0,helpers,partials,data) {
  var stack1, buffer = '';
  data.buffer.push(" <li>");
  stack1 = helpers._triageMustache.call(depth0, "error", {"name":"_triageMustache","hash":{},"hashTypes":{},"hashContexts":{},"types":["ID"],"contexts":[depth0],"data":data});
  if (stack1 != null) { data.buffer.push(stack1); }
  data.buffer.push("</li> ");
  return buffer;
},"compiler":[6,">= 2.0.0-beta.1"],"main":function(depth0,helpers,partials,data) {
  var stack1, buffer = '';
  data.buffer.push("<div class=\"umi-component s-full-height\"> <div class=\"row\"> <div class=\"small-10 columns small-centered text-center\"> <p></p> <div>  <h2> ");
  stack1 = helpers._triageMustache.call(depth0, "title", {"name":"_triageMustache","hash":{},"hashTypes":{},"hashContexts":{},"types":["ID"],"contexts":[depth0],"data":data});
  if (stack1 != null) { data.buffer.push(stack1); }
  data.buffer.push(". </h2>  ");
  stack1 = helpers['if'].call(depth0, "model.content", {"name":"if","hash":{},"hashTypes":{},"hashContexts":{},"fn":this.program(1, data),"inverse":this.noop,"types":["ID"],"contexts":[depth0],"data":data});
  if (stack1 != null) { data.buffer.push(stack1); }
  data.buffer.push(" ");
  stack1 = helpers['if'].call(depth0, "stack", {"name":"if","hash":{},"hashTypes":{},"hashContexts":{},"fn":this.program(3, data),"inverse":this.noop,"types":["ID"],"contexts":[depth0],"data":data});
  if (stack1 != null) { data.buffer.push(stack1); }
  data.buffer.push(" ");
  stack1 = helpers['if'].call(depth0, "lists", {"name":"if","hash":{},"hashTypes":{},"hashContexts":{},"fn":this.program(5, data),"inverse":this.noop,"types":["ID"],"contexts":[depth0],"data":data});
  if (stack1 != null) { data.buffer.push(stack1); }
  data.buffer.push(" </div> </div> </div> </div>");
  return buffer;
},"useData":true});

Ember.TEMPLATES["UMI/menu"] = Ember.Handlebars.template({"compiler":[6,">= 2.0.0-beta.1"],"main":function(depth0,helpers,partials,data) {
  var helperMissing=helpers.helperMissing, escapeExpression=this.escapeExpression;
  data.buffer.push(escapeExpression(((helpers.render || (depth0 && depth0.render) || helperMissing).call(depth0, "sideMenu", "model", {"name":"render","hash":{},"hashTypes":{},"hashContexts":{},"types":["STRING","ID"],"contexts":[depth0,depth0],"data":data}))));
  },"useData":true});

Ember.TEMPLATES["UMI/tree"] = Ember.Handlebars.template({"compiler":[6,">= 2.0.0-beta.1"],"main":function(depth0,helpers,partials,data) {
  var helperMissing=helpers.helperMissing, escapeExpression=this.escapeExpression;
  data.buffer.push(escapeExpression(((helpers.render || (depth0 && depth0.render) || helperMissing).call(depth0, "treeControl", "model", {"name":"render","hash":{},"hashTypes":{},"hashContexts":{},"types":["STRING","ID"],"contexts":[depth0,depth0],"data":data}))));
  },"useData":true});

Ember.TEMPLATES["UMI/partials/dialog-layout"] = Ember.Handlebars.template({"1":function(depth0,helpers,partials,data) {
  var stack1, escapeExpression=this.escapeExpression, buffer = '';
  data.buffer.push(" <div class=\"umi-overlay\"></div> <div ");
  data.buffer.push(escapeExpression(helpers['bind-attr'].call(depth0, {"name":"bind-attr","hash":{
    'class': (":umi-dialog model.type")
  },"hashTypes":{'class': "STRING"},"hashContexts":{'class': depth0},"types":[],"contexts":[],"data":data})));
  data.buffer.push("> ");
  stack1 = helpers['if'].call(depth0, "model.close", {"name":"if","hash":{},"hashTypes":{},"hashContexts":{},"fn":this.program(2, data),"inverse":this.noop,"types":["ID"],"contexts":[depth0],"data":data});
  if (stack1 != null) { data.buffer.push(stack1); }
  data.buffer.push(" ");
  stack1 = helpers._triageMustache.call(depth0, "yield", {"name":"_triageMustache","hash":{},"hashTypes":{},"hashContexts":{},"types":["ID"],"contexts":[depth0],"data":data});
  if (stack1 != null) { data.buffer.push(stack1); }
  data.buffer.push(" </div> ");
  return buffer;
},"2":function(depth0,helpers,partials,data) {
  var escapeExpression=this.escapeExpression, buffer = '';
  data.buffer.push(" <a href=\"\" ");
  data.buffer.push(escapeExpression(helpers.action.call(depth0, "close", "model", {"name":"action","hash":{
    'target': ("view")
  },"hashTypes":{'target': "STRING"},"hashContexts":{'target': depth0},"types":["STRING","ID"],"contexts":[depth0,depth0],"data":data})));
  data.buffer.push(" class=\"close\"><i class=\"icon white icon-close\"></i></a> ");
  return buffer;
},"compiler":[6,">= 2.0.0-beta.1"],"main":function(depth0,helpers,partials,data) {
  var stack1;
  stack1 = helpers['if'].call(depth0, "model", {"name":"if","hash":{},"hashTypes":{},"hashContexts":{},"fn":this.program(1, data),"inverse":this.noop,"types":["ID"],"contexts":[depth0],"data":data});
  if (stack1 != null) { data.buffer.push(stack1); }
  else { data.buffer.push(''); }
  },"useData":true});

Ember.TEMPLATES["UMI/partials/dialog-template"] = Ember.Handlebars.template({"1":function(depth0,helpers,partials,data) {
  var stack1, buffer = '';
  data.buffer.push(" <div class=\"umi-dialog-header\">");
  stack1 = helpers._triageMustache.call(depth0, "model.title", {"name":"_triageMustache","hash":{},"hashTypes":{},"hashContexts":{},"types":["ID"],"contexts":[depth0],"data":data});
  if (stack1 != null) { data.buffer.push(stack1); }
  data.buffer.push("</div> ");
  return buffer;
},"3":function(depth0,helpers,partials,data) {
  var escapeExpression=this.escapeExpression, buffer = '';
  data.buffer.push(" <div class=\"umi-dialog-content\"> ");
  data.buffer.push(escapeExpression(helpers._triageMustache.call(depth0, "model.content", {"name":"_triageMustache","hash":{
    'unescaped': ("true")
  },"hashTypes":{'unescaped': "STRING"},"hashContexts":{'unescaped': depth0},"types":["ID"],"contexts":[depth0],"data":data})));
  data.buffer.push(" </div> ");
  return buffer;
},"5":function(depth0,helpers,partials,data) {
  var stack1, buffer = '';
  data.buffer.push(" <div class=\"umi-dialog-buttons\"> ");
  stack1 = helpers['if'].call(depth0, "model.confirm", {"name":"if","hash":{},"hashTypes":{},"hashContexts":{},"fn":this.program(6, data),"inverse":this.noop,"types":["ID"],"contexts":[depth0],"data":data});
  if (stack1 != null) { data.buffer.push(stack1); }
  data.buffer.push(" ");
  stack1 = helpers['if'].call(depth0, "model.reject", {"name":"if","hash":{},"hashTypes":{},"hashContexts":{},"fn":this.program(8, data),"inverse":this.noop,"types":["ID"],"contexts":[depth0],"data":data});
  if (stack1 != null) { data.buffer.push(stack1); }
  data.buffer.push(" </div> ");
  return buffer;
},"6":function(depth0,helpers,partials,data) {
  var stack1, escapeExpression=this.escapeExpression, buffer = '';
  data.buffer.push(" <div class=\"button primary left\" ");
  data.buffer.push(escapeExpression(helpers.action.call(depth0, "confirm", {"name":"action","hash":{
    'target': ("view")
  },"hashTypes":{'target': "STRING"},"hashContexts":{'target': depth0},"types":["STRING"],"contexts":[depth0],"data":data})));
  data.buffer.push(">");
  stack1 = helpers._triageMustache.call(depth0, "model.confirm", {"name":"_triageMustache","hash":{},"hashTypes":{},"hashContexts":{},"types":["ID"],"contexts":[depth0],"data":data});
  if (stack1 != null) { data.buffer.push(stack1); }
  data.buffer.push("</div> ");
  return buffer;
},"8":function(depth0,helpers,partials,data) {
  var stack1, escapeExpression=this.escapeExpression, buffer = '';
  data.buffer.push(" <div class=\"button secondary right\" ");
  data.buffer.push(escapeExpression(helpers.action.call(depth0, "close", {"name":"action","hash":{
    'target': ("view")
  },"hashTypes":{'target': "STRING"},"hashContexts":{'target': depth0},"types":["STRING"],"contexts":[depth0],"data":data})));
  data.buffer.push(">");
  stack1 = helpers._triageMustache.call(depth0, "model.reject", {"name":"_triageMustache","hash":{},"hashTypes":{},"hashContexts":{},"types":["ID"],"contexts":[depth0],"data":data});
  if (stack1 != null) { data.buffer.push(stack1); }
  data.buffer.push("</div> ");
  return buffer;
},"compiler":[6,">= 2.0.0-beta.1"],"main":function(depth0,helpers,partials,data) {
  var stack1, buffer = '';
  stack1 = helpers['if'].call(depth0, "model.title", {"name":"if","hash":{},"hashTypes":{},"hashContexts":{},"fn":this.program(1, data),"inverse":this.noop,"types":["ID"],"contexts":[depth0],"data":data});
  if (stack1 != null) { data.buffer.push(stack1); }
  data.buffer.push(" <div class=\"umi-dialog-section\"> ");
  stack1 = helpers['if'].call(depth0, "model.content", {"name":"if","hash":{},"hashTypes":{},"hashContexts":{},"fn":this.program(3, data),"inverse":this.noop,"types":["ID"],"contexts":[depth0],"data":data});
  if (stack1 != null) { data.buffer.push(stack1); }
  data.buffer.push(" ");
  stack1 = helpers['if'].call(depth0, "view.hasButtons", {"name":"if","hash":{},"hashTypes":{},"hashContexts":{},"fn":this.program(5, data),"inverse":this.noop,"types":["ID"],"contexts":[depth0],"data":data});
  if (stack1 != null) { data.buffer.push(stack1); }
  data.buffer.push(" </div>");
  return buffer;
},"useData":true});

Ember.TEMPLATES["UMI/partials/dock"] = Ember.Handlebars.template({"1":function(depth0,helpers,partials,data) {
  var stack1, buffer = '';
  data.buffer.push(" ");
  stack1 = helpers.view.call(depth0, "dockModuleButton", {"name":"view","hash":{
    'module': ("module")
  },"hashTypes":{'module': "ID"},"hashContexts":{'module': depth0},"fn":this.program(2, data),"inverse":this.noop,"types":["STRING"],"contexts":[depth0],"data":data});
  if (stack1 != null) { data.buffer.push(stack1); }
  data.buffer.push(" ");
  return buffer;
},"2":function(depth0,helpers,partials,data) {
  var stack1, escapeExpression=this.escapeExpression, buffer = '';
  data.buffer.push(" ");
  stack1 = helpers['if'].call(depth0, "view.parentView.isTouchDevice", {"name":"if","hash":{},"hashTypes":{},"hashContexts":{},"fn":this.program(3, data),"inverse":this.program(5, data),"types":["ID"],"contexts":[depth0],"data":data});
  if (stack1 != null) { data.buffer.push(stack1); }
  data.buffer.push(" <ul ");
  data.buffer.push(escapeExpression(helpers['bind-attr'].call(depth0, {"name":"bind-attr","hash":{
    'class': (":f-dropdown view.parentView.isTouchDevice:is-touch-mode")
  },"hashTypes":{'class': "STRING"},"hashContexts":{'class': depth0},"types":[],"contexts":[],"data":data})));
  data.buffer.push(" data-dropdown-content> ");
  stack1 = helpers.each.call(depth0, "component", "in", "module.components", {"name":"each","hash":{},"hashTypes":{},"hashContexts":{},"fn":this.program(8, data),"inverse":this.noop,"types":["ID","ID","ID"],"contexts":[depth0,depth0,depth0],"data":data});
  if (stack1 != null) { data.buffer.push(stack1); }
  data.buffer.push(" </ul> ");
  return buffer;
},"3":function(depth0,helpers,partials,data) {
  var stack1, escapeExpression=this.escapeExpression, buffer = '';
  data.buffer.push(" <a href=\"javascript: void(0)\" class=\"dock-module dropdown\" data-dropdown=\"\" data-options=\"selectorById: false; isHover: true; buttonSelector: .dropdown;\"> <div class=\"umi-dock-module-icon umi-dock-module-");
  data.buffer.push(escapeExpression(helpers.unbound.call(depth0, "view.module.name", {"name":"unbound","hash":{},"hashTypes":{},"hashContexts":{},"types":["ID"],"contexts":[depth0],"data":data})));
  data.buffer.push("\"></div> <span>");
  stack1 = helpers._triageMustache.call(depth0, "module.displayName", {"name":"_triageMustache","hash":{},"hashTypes":{},"hashContexts":{},"types":["ID"],"contexts":[depth0],"data":data});
  if (stack1 != null) { data.buffer.push(stack1); }
  data.buffer.push("</span> </a> ");
  return buffer;
},"5":function(depth0,helpers,partials,data) {
  var stack1, helperMissing=helpers.helperMissing, buffer = '';
  data.buffer.push(" ");
  stack1 = ((helpers['link-to'] || (depth0 && depth0['link-to']) || helperMissing).call(depth0, "module", "module.name", {"name":"link-to","hash":{
    'data-options': ("selectorById: false; isHover: true; buttonSelector: .dropdown;"),
    'data-dropdown': (""),
    'class': ("dock-module dropdown")
  },"hashTypes":{'data-options': "STRING",'data-dropdown': "STRING",'class': "STRING"},"hashContexts":{'data-options': depth0,'data-dropdown': depth0,'class': depth0},"fn":this.program(6, data),"inverse":this.noop,"types":["STRING","ID"],"contexts":[depth0,depth0],"data":data}));
  if (stack1 != null) { data.buffer.push(stack1); }
  data.buffer.push(" ");
  return buffer;
},"6":function(depth0,helpers,partials,data) {
  var stack1, escapeExpression=this.escapeExpression, buffer = '';
  data.buffer.push(" <div class=\"umi-dock-module-icon umi-dock-module-");
  data.buffer.push(escapeExpression(helpers.unbound.call(depth0, "view.module.name", {"name":"unbound","hash":{},"hashTypes":{},"hashContexts":{},"types":["ID"],"contexts":[depth0],"data":data})));
  data.buffer.push("\"></div> <span>");
  stack1 = helpers._triageMustache.call(depth0, "module.displayName", {"name":"_triageMustache","hash":{},"hashTypes":{},"hashContexts":{},"types":["ID"],"contexts":[depth0],"data":data});
  if (stack1 != null) { data.buffer.push(stack1); }
  data.buffer.push("</span> ");
  return buffer;
},"8":function(depth0,helpers,partials,data) {
  var stack1, escapeExpression=this.escapeExpression, helperMissing=helpers.helperMissing, buffer = '';
  data.buffer.push(" <li class=\"");
  data.buffer.push(escapeExpression(helpers.unbound.call(depth0, "component.name", {"name":"unbound","hash":{},"hashTypes":{},"hashContexts":{},"types":["ID"],"contexts":[depth0],"data":data})));
  data.buffer.push("\"> ");
  stack1 = ((helpers['link-to'] || (depth0 && depth0['link-to']) || helperMissing).call(depth0, "component", "module.name", "component.name", {"name":"link-to","hash":{},"hashTypes":{},"hashContexts":{},"fn":this.program(9, data),"inverse":this.noop,"types":["STRING","ID","ID"],"contexts":[depth0,depth0,depth0],"data":data}));
  if (stack1 != null) { data.buffer.push(stack1); }
  data.buffer.push(" </li> ");
  return buffer;
},"9":function(depth0,helpers,partials,data) {
  var stack1, buffer = '';
  data.buffer.push(" ");
  stack1 = helpers._triageMustache.call(depth0, "component.displayName", {"name":"_triageMustache","hash":{},"hashTypes":{},"hashContexts":{},"types":["ID"],"contexts":[depth0],"data":data});
  if (stack1 != null) { data.buffer.push(stack1); }
  data.buffer.push(" ");
  return buffer;
},"11":function(depth0,helpers,partials,data) {
  var stack1, buffer = '';
  data.buffer.push(" ");
  stack1 = helpers['with'].call(depth0, "activeModule", "as", "module", {"name":"with","hash":{},"hashTypes":{},"hashContexts":{},"fn":this.program(12, data),"inverse":this.noop,"types":["ID","ID","ID"],"contexts":[depth0,depth0,depth0],"data":data});
  if (stack1 != null) { data.buffer.push(stack1); }
  data.buffer.push(" ");
  return buffer;
},"12":function(depth0,helpers,partials,data) {
  var stack1, buffer = '';
  data.buffer.push(" ");
  stack1 = helpers.each.call(depth0, "component", "in", "module.components", {"name":"each","hash":{},"hashTypes":{},"hashContexts":{},"fn":this.program(13, data),"inverse":this.noop,"types":["ID","ID","ID"],"contexts":[depth0,depth0,depth0],"data":data});
  if (stack1 != null) { data.buffer.push(stack1); }
  data.buffer.push(" ");
  return buffer;
},"13":function(depth0,helpers,partials,data) {
  var stack1, helperMissing=helpers.helperMissing, buffer = '';
  data.buffer.push(" ");
  stack1 = ((helpers['link-to'] || (depth0 && depth0['link-to']) || helperMissing).call(depth0, "component", "module.name", "component.name", {"name":"link-to","hash":{
    'class': ("component.name")
  },"hashTypes":{'class': "ID"},"hashContexts":{'class': depth0},"fn":this.program(9, data),"inverse":this.noop,"types":["STRING","ID","ID"],"contexts":[depth0,depth0,depth0],"data":data}));
  if (stack1 != null) { data.buffer.push(stack1); }
  data.buffer.push(" ");
  return buffer;
},"compiler":[6,">= 2.0.0-beta.1"],"main":function(depth0,helpers,partials,data) {
  var stack1, buffer = '';
  data.buffer.push("<div class=\"dock-wrapper\"> <div class=\"dock-wrapper-bg\"> <ul class=\"dock navigation\"> ");
  stack1 = helpers.each.call(depth0, "module", "in", "sortedModules", {"name":"each","hash":{},"hashTypes":{},"hashContexts":{},"fn":this.program(1, data),"inverse":this.noop,"types":["ID","ID","ID"],"contexts":[depth0,depth0,depth0],"data":data});
  if (stack1 != null) { data.buffer.push(stack1); }
  data.buffer.push(" </ul> </div> </div> <div class=\"dock-components\"> <nav class=\"components-nav\"> ");
  stack1 = helpers['if'].call(depth0, "activeModule", {"name":"if","hash":{},"hashTypes":{},"hashContexts":{},"fn":this.program(11, data),"inverse":this.noop,"types":["ID"],"contexts":[depth0],"data":data});
  if (stack1 != null) { data.buffer.push(stack1); }
  data.buffer.push(" </nav> </div>");
  return buffer;
},"useData":true});

Ember.TEMPLATES["UMI/partials/checkboxGroup/CollectionElement"] = Ember.Handlebars.template({"1":function(depth0,helpers,partials,data) {
  var escapeExpression=this.escapeExpression, buffer = '';
  data.buffer.push(" ");
  data.buffer.push(escapeExpression(helpers.view.call(depth0, "view.checkboxElementView", {"name":"view","hash":{},"hashTypes":{},"hashContexts":{},"types":["ID"],"contexts":[depth0],"data":data})));
  data.buffer.push(" ");
  return buffer;
},"compiler":[6,">= 2.0.0-beta.1"],"main":function(depth0,helpers,partials,data) {
  var stack1;
  stack1 = helpers.each.call(depth0, "meta.choices", {"name":"each","hash":{},"hashTypes":{},"hashContexts":{},"fn":this.program(1, data),"inverse":this.noop,"types":["ID"],"contexts":[depth0],"data":data});
  if (stack1 != null) { data.buffer.push(stack1); }
  else { data.buffer.push(''); }
  },"useData":true});

Ember.TEMPLATES["UMI/partials/checkboxGroup"] = Ember.Handlebars.template({"1":function(depth0,helpers,partials,data) {
  var escapeExpression=this.escapeExpression, buffer = '';
  data.buffer.push(" ");
  data.buffer.push(escapeExpression(helpers.view.call(depth0, "checkboxElement", {"name":"view","hash":{
    'meta': ("element")
  },"hashTypes":{'meta': "ID"},"hashContexts":{'meta': depth0},"types":["STRING"],"contexts":[depth0],"data":data})));
  data.buffer.push(" ");
  return buffer;
},"compiler":[6,">= 2.0.0-beta.1"],"main":function(depth0,helpers,partials,data) {
  var stack1;
  stack1 = helpers.each.call(depth0, "element", "in", "meta.choices", {"name":"each","hash":{},"hashTypes":{},"hashContexts":{},"fn":this.program(1, data),"inverse":this.noop,"types":["ID","ID","ID"],"contexts":[depth0,depth0,depth0],"data":data});
  if (stack1 != null) { data.buffer.push(stack1); }
  else { data.buffer.push(''); }
  },"useData":true});

Ember.TEMPLATES["UMI/partials/dateElement"] = Ember.Handlebars.template({"compiler":[6,">= 2.0.0-beta.1"],"main":function(depth0,helpers,partials,data) {
  var escapeExpression=this.escapeExpression, buffer = '';
  data.buffer.push("<div class=\"small-11 columns\"> ");
  data.buffer.push(escapeExpression(helpers.view.call(depth0, "textElement", {"name":"view","hash":{
    'meta': ("view.meta"),
    'object': ("view.object")
  },"hashTypes":{'meta': "ID",'object': "ID"},"hashContexts":{'meta': depth0,'object': depth0},"types":["STRING"],"contexts":[depth0],"data":data})));
  data.buffer.push(" </div> <div class=\"small-1 columns\"> <span class=\"postfix\" ");
  data.buffer.push(escapeExpression(helpers.action.call(depth0, "clearValue", {"name":"action","hash":{
    'target': ("view")
  },"hashTypes":{'target': "STRING"},"hashContexts":{'target': depth0},"types":["STRING"],"contexts":[depth0],"data":data})));
  data.buffer.push("> <i class=\"icon icon-delete\"></i> </span> </div>");
  return buffer;
},"useData":true});

Ember.TEMPLATES["UMI/partials/dateTimeElement"] = Ember.Handlebars.template({"compiler":[6,">= 2.0.0-beta.1"],"main":function(depth0,helpers,partials,data) {
  var helperMissing=helpers.helperMissing, escapeExpression=this.escapeExpression, buffer = '';
  data.buffer.push("<div class=\"small-11 columns\"> ");
  data.buffer.push(escapeExpression(((helpers.input || (depth0 && depth0.input) || helperMissing).call(depth0, {"name":"input","hash":{
    'value': ("view.value"),
    'class': ("umi-date"),
    'type': ("text")
  },"hashTypes":{'value': "ID",'class': "STRING",'type': "STRING"},"hashContexts":{'value': depth0,'class': depth0,'type': depth0},"types":[],"contexts":[],"data":data}))));
  data.buffer.push(" </div> <div class=\"small-1 columns\"> <span class=\"postfix\" ");
  data.buffer.push(escapeExpression(helpers.action.call(depth0, "clearValue", {"name":"action","hash":{
    'target': ("view")
  },"hashTypes":{'target': "STRING"},"hashContexts":{'target': depth0},"types":["STRING"],"contexts":[depth0],"data":data})));
  data.buffer.push("> <i class=\"icon icon-delete\"></i> </span> </div>");
  return buffer;
},"useData":true});

Ember.TEMPLATES["UMI/partials/fileElement"] = Ember.Handlebars.template({"1":function(depth0,helpers,partials,data) {
  var escapeExpression=this.escapeExpression, buffer = '';
  data.buffer.push(" <li> <span class=\"button flat white square\" ");
  data.buffer.push(escapeExpression(helpers.action.call(depth0, "clearValue", {"name":"action","hash":{
    'target': ("view")
  },"hashTypes":{'target': "STRING"},"hashContexts":{'target': depth0},"types":["STRING"],"contexts":[depth0],"data":data})));
  data.buffer.push("> <i class=\"icon icon-delete\"></i> </span> </li> ");
  return buffer;
},"compiler":[6,">= 2.0.0-beta.1"],"main":function(depth0,helpers,partials,data) {
  var stack1, escapeExpression=this.escapeExpression, buffer = '';
  data.buffer.push("<div ");
  data.buffer.push(escapeExpression(helpers['bind-attr'].call(depth0, {"name":"bind-attr","hash":{
    'class': (":small-2 :columns :umi-columns-fixed view.value:small-2-right:small-1-right")
  },"hashTypes":{'class': "STRING"},"hashContexts":{'class': depth0},"types":[],"contexts":[],"data":data})));
  data.buffer.push("> <span class=\"postfix\"> <span class=\"button-group\"> <li> <span class=\"button flat white square\" ");
  data.buffer.push(escapeExpression(helpers.action.call(depth0, "showPopup", "view.popupParams", {"name":"action","hash":{
    'target': ("view")
  },"hashTypes":{'target': "STRING"},"hashContexts":{'target': depth0},"types":["STRING","ID"],"contexts":[depth0,depth0],"data":data})));
  data.buffer.push("> <i class=\"icon icon-open-folder\"></i> </span> </li> ");
  stack1 = helpers['if'].call(depth0, "view.value", {"name":"if","hash":{},"hashTypes":{},"hashContexts":{},"fn":this.program(1, data),"inverse":this.noop,"types":["ID"],"contexts":[depth0],"data":data});
  if (stack1 != null) { data.buffer.push(stack1); }
  data.buffer.push(" </span> </span> </div> <div class=\"small-10 columns\"> ");
  data.buffer.push(escapeExpression(helpers.view.call(depth0, "textElement", {"name":"view","hash":{
    'meta': ("view.meta"),
    'object': ("view.object")
  },"hashTypes":{'meta': "ID",'object': "ID"},"hashContexts":{'meta': depth0,'object': depth0},"types":["STRING"],"contexts":[depth0],"data":data})));
  data.buffer.push(" </div>");
  return buffer;
},"useData":true});

Ember.TEMPLATES["UMI/partials/multi-select-lazy-choices"] = Ember.Handlebars.template({"1":function(depth0,helpers,partials,data) {
  var stack1, buffer = '';
  data.buffer.push(" <ul class=\"umi-multi-select-list\"> ");
  stack1 = helpers.each.call(depth0, "view.notSelectedObjects", {"name":"each","hash":{},"hashTypes":{},"hashContexts":{},"fn":this.program(2, data),"inverse":this.program(4, data),"types":["ID"],"contexts":[depth0],"data":data});
  if (stack1 != null) { data.buffer.push(stack1); }
  data.buffer.push(" </ul> ");
  return buffer;
},"2":function(depth0,helpers,partials,data) {
  var stack1, escapeExpression=this.escapeExpression, buffer = '';
  data.buffer.push(" <li ");
  data.buffer.push(escapeExpression(helpers.action.call(depth0, "select", "id", {"name":"action","hash":{
    'target': ("view")
  },"hashTypes":{'target': "STRING"},"hashContexts":{'target': depth0},"types":["STRING","ID"],"contexts":[depth0,depth0],"data":data})));
  data.buffer.push(" ");
  data.buffer.push(escapeExpression(helpers['bind-attr'].call(depth0, {"name":"bind-attr","hash":{
    'class': ("hover")
  },"hashTypes":{'class': "STRING"},"hashContexts":{'class': depth0},"types":[],"contexts":[],"data":data})));
  data.buffer.push("> ");
  stack1 = helpers._triageMustache.call(depth0, "displayName", {"name":"_triageMustache","hash":{},"hashTypes":{},"hashContexts":{},"types":["ID"],"contexts":[depth0],"data":data});
  if (stack1 != null) { data.buffer.push(stack1); }
  data.buffer.push(" </li> ");
  return buffer;
},"4":function(depth0,helpers,partials,data) {
  var helperMissing=helpers.helperMissing, escapeExpression=this.escapeExpression, buffer = '';
  data.buffer.push(" <li class=\"placeholder\"> ");
  data.buffer.push(escapeExpression(((helpers.i18n || (depth0 && depth0.i18n) || helperMissing).call(depth0, "Nothing found", {"name":"i18n","hash":{},"hashTypes":{},"hashContexts":{},"types":["STRING"],"contexts":[depth0],"data":data}))));
  data.buffer.push(" </li> ");
  return buffer;
},"6":function(depth0,helpers,partials,data) {
  var stack1, buffer = '';
  data.buffer.push(" <div class=\"selected-list\"> ");
  stack1 = helpers.each.call(depth0, "view.selectedObjects", {"name":"each","hash":{},"hashTypes":{},"hashContexts":{},"fn":this.program(7, data),"inverse":this.noop,"types":["ID"],"contexts":[depth0],"data":data});
  if (stack1 != null) { data.buffer.push(stack1); }
  data.buffer.push(" </div> ");
  return buffer;
},"7":function(depth0,helpers,partials,data) {
  var stack1, escapeExpression=this.escapeExpression, buffer = '';
  data.buffer.push(" <div class=\"item\" ");
  data.buffer.push(escapeExpression(helpers.action.call(depth0, "unSelect", "id", {"name":"action","hash":{
    'target': ("view")
  },"hashTypes":{'target': "STRING"},"hashContexts":{'target': depth0},"types":["STRING","ID"],"contexts":[depth0,depth0],"data":data})));
  data.buffer.push(">");
  stack1 = helpers._triageMustache.call(depth0, "displayName", {"name":"_triageMustache","hash":{},"hashTypes":{},"hashContexts":{},"types":["ID"],"contexts":[depth0],"data":data});
  if (stack1 != null) { data.buffer.push(stack1); }
  data.buffer.push(" <i class=\"close\">&times;</i></div> ");
  return buffer;
},"compiler":[6,">= 2.0.0-beta.1"],"main":function(depth0,helpers,partials,data) {
  var stack1, escapeExpression=this.escapeExpression, buffer = '';
  data.buffer.push("<div class=\"small-12 columns\"> ");
  data.buffer.push(escapeExpression(helpers.view.call(depth0, "view.inputView", {"name":"view","hash":{},"hashTypes":{},"hashContexts":{},"types":["ID"],"contexts":[depth0],"data":data})));
  data.buffer.push(" <span class=\"postfix radius\" ");
  data.buffer.push(escapeExpression(helpers.action.call(depth0, "toggleList", {"name":"action","hash":{
    'target': ("view")
  },"hashTypes":{'target': "STRING"},"hashContexts":{'target': depth0},"types":["STRING"],"contexts":[depth0],"data":data})));
  data.buffer.push("> <i class=\"triangle\"></i> </span> ");
  stack1 = helpers['if'].call(depth0, "view.isOpen", {"name":"if","hash":{},"hashTypes":{},"hashContexts":{},"fn":this.program(1, data),"inverse":this.noop,"types":["ID"],"contexts":[depth0],"data":data});
  if (stack1 != null) { data.buffer.push(stack1); }
  data.buffer.push(" </div> ");
  stack1 = helpers['if'].call(depth0, "view.selectedObjects.length", {"name":"if","hash":{},"hashTypes":{},"hashContexts":{},"fn":this.program(6, data),"inverse":this.noop,"types":["ID"],"contexts":[depth0],"data":data});
  if (stack1 != null) { data.buffer.push(stack1); }
  return buffer;
},"useData":true});

Ember.TEMPLATES["UMI/partials/multi-select"] = Ember.Handlebars.template({"1":function(depth0,helpers,partials,data) {
  var stack1, buffer = '';
  data.buffer.push(" <ul class=\"umi-multi-select-list\"> ");
  stack1 = helpers.each.call(depth0, "view.notSelectedObjects", {"name":"each","hash":{},"hashTypes":{},"hashContexts":{},"fn":this.program(2, data),"inverse":this.program(4, data),"types":["ID"],"contexts":[depth0],"data":data});
  if (stack1 != null) { data.buffer.push(stack1); }
  data.buffer.push(" </ul> ");
  return buffer;
},"2":function(depth0,helpers,partials,data) {
  var stack1, escapeExpression=this.escapeExpression, buffer = '';
  data.buffer.push(" <li ");
  data.buffer.push(escapeExpression(helpers.action.call(depth0, "select", "value", {"name":"action","hash":{
    'target': ("view")
  },"hashTypes":{'target': "STRING"},"hashContexts":{'target': depth0},"types":["STRING","ID"],"contexts":[depth0,depth0],"data":data})));
  data.buffer.push(" ");
  data.buffer.push(escapeExpression(helpers['bind-attr'].call(depth0, {"name":"bind-attr","hash":{
    'class': ("hover")
  },"hashTypes":{'class': "STRING"},"hashContexts":{'class': depth0},"types":[],"contexts":[],"data":data})));
  data.buffer.push("> ");
  stack1 = helpers._triageMustache.call(depth0, "label", {"name":"_triageMustache","hash":{},"hashTypes":{},"hashContexts":{},"types":["ID"],"contexts":[depth0],"data":data});
  if (stack1 != null) { data.buffer.push(stack1); }
  data.buffer.push(" </li> ");
  return buffer;
},"4":function(depth0,helpers,partials,data) {
  var helperMissing=helpers.helperMissing, escapeExpression=this.escapeExpression, buffer = '';
  data.buffer.push(" <li class=\"placeholder\"> ");
  data.buffer.push(escapeExpression(((helpers.i18n || (depth0 && depth0.i18n) || helperMissing).call(depth0, "Nothing found", {"name":"i18n","hash":{},"hashTypes":{},"hashContexts":{},"types":["STRING"],"contexts":[depth0],"data":data}))));
  data.buffer.push(" </li> ");
  return buffer;
},"6":function(depth0,helpers,partials,data) {
  var stack1, buffer = '';
  data.buffer.push(" <div class=\"selected-list\"> ");
  stack1 = helpers.each.call(depth0, "view.selectedObjects", {"name":"each","hash":{},"hashTypes":{},"hashContexts":{},"fn":this.program(7, data),"inverse":this.noop,"types":["ID"],"contexts":[depth0],"data":data});
  if (stack1 != null) { data.buffer.push(stack1); }
  data.buffer.push(" </div> ");
  return buffer;
},"7":function(depth0,helpers,partials,data) {
  var stack1, escapeExpression=this.escapeExpression, buffer = '';
  data.buffer.push(" <div class=\"item\" ");
  data.buffer.push(escapeExpression(helpers.action.call(depth0, "unSelect", "value", {"name":"action","hash":{
    'target': ("view")
  },"hashTypes":{'target': "STRING"},"hashContexts":{'target': depth0},"types":["STRING","ID"],"contexts":[depth0,depth0],"data":data})));
  data.buffer.push(">");
  stack1 = helpers._triageMustache.call(depth0, "label", {"name":"_triageMustache","hash":{},"hashTypes":{},"hashContexts":{},"types":["ID"],"contexts":[depth0],"data":data});
  if (stack1 != null) { data.buffer.push(stack1); }
  data.buffer.push(" <i class=\"close\">&times;</i></div> ");
  return buffer;
},"compiler":[6,">= 2.0.0-beta.1"],"main":function(depth0,helpers,partials,data) {
  var stack1, escapeExpression=this.escapeExpression, buffer = '';
  data.buffer.push("<div class=\"small-12 columns\"> ");
  data.buffer.push(escapeExpression(helpers.view.call(depth0, "view.inputView", {"name":"view","hash":{},"hashTypes":{},"hashContexts":{},"types":["ID"],"contexts":[depth0],"data":data})));
  data.buffer.push(" <span class=\"postfix radius\" ");
  data.buffer.push(escapeExpression(helpers.action.call(depth0, "toggleList", {"name":"action","hash":{
    'target': ("view")
  },"hashTypes":{'target': "STRING"},"hashContexts":{'target': depth0},"types":["STRING"],"contexts":[depth0],"data":data})));
  data.buffer.push("> <i class=\"triangle\"></i> </span> ");
  stack1 = helpers['if'].call(depth0, "view.isOpen", {"name":"if","hash":{},"hashTypes":{},"hashContexts":{},"fn":this.program(1, data),"inverse":this.noop,"types":["ID"],"contexts":[depth0],"data":data});
  if (stack1 != null) { data.buffer.push(stack1); }
  data.buffer.push(" </div> ");
  stack1 = helpers['if'].call(depth0, "view.selectedObjects.length", {"name":"if","hash":{},"hashTypes":{},"hashContexts":{},"fn":this.program(6, data),"inverse":this.noop,"types":["ID"],"contexts":[depth0],"data":data});
  if (stack1 != null) { data.buffer.push(stack1); }
  return buffer;
},"useData":true});

Ember.TEMPLATES["UMI/partials/objectRelationElement"] = Ember.Handlebars.template({"1":function(depth0,helpers,partials,data) {
  var escapeExpression=this.escapeExpression, buffer = '';
  data.buffer.push(" <li> <span class=\"button flat white square\" ");
  data.buffer.push(escapeExpression(helpers.action.call(depth0, "clearValue", {"name":"action","hash":{
    'target': ("view")
  },"hashTypes":{'target': "STRING"},"hashContexts":{'target': depth0},"types":["STRING"],"contexts":[depth0],"data":data})));
  data.buffer.push("> <i class=\"icon icon-delete\"></i> </span> </li> ");
  return buffer;
},"compiler":[6,">= 2.0.0-beta.1"],"main":function(depth0,helpers,partials,data) {
  var stack1, escapeExpression=this.escapeExpression, buffer = '';
  data.buffer.push("<div ");
  data.buffer.push(escapeExpression(helpers['bind-attr'].call(depth0, {"name":"bind-attr","hash":{
    'class': (":small-2 :columns :umi-columns-fixed view.value:small-2-right:small-1-right")
  },"hashTypes":{'class': "STRING"},"hashContexts":{'class': depth0},"types":[],"contexts":[],"data":data})));
  data.buffer.push("> <span class=\"postfix\"> <span class=\"button-group\"> <li> <span class=\"button flat white square\" ");
  data.buffer.push(escapeExpression(helpers.action.call(depth0, "showPopup", "view.popupParams", {"name":"action","hash":{
    'target': ("view")
  },"hashTypes":{'target': "STRING"},"hashContexts":{'target': depth0},"types":["STRING","ID"],"contexts":[depth0,depth0],"data":data})));
  data.buffer.push("> <i class=\"icon icon-open-folder\"></i> </span> </li> ");
  stack1 = helpers['if'].call(depth0, "view.value", {"name":"if","hash":{},"hashTypes":{},"hashContexts":{},"fn":this.program(1, data),"inverse":this.noop,"types":["ID"],"contexts":[depth0],"data":data});
  if (stack1 != null) { data.buffer.push(stack1); }
  data.buffer.push(" </span> </span> </div> <div class=\"small-10 columns\"> ");
  data.buffer.push(escapeExpression(helpers.view.call(depth0, "view.inputView", {"name":"view","hash":{
    'meta': ("view.meta"),
    'object': ("view.object")
  },"hashTypes":{'meta': "ID",'object': "ID"},"hashContexts":{'meta': depth0,'object': depth0},"types":["ID"],"contexts":[depth0],"data":data})));
  data.buffer.push(" </div>");
  return buffer;
},"useData":true});

Ember.TEMPLATES["UMI/partials/objectRelationLayout"] = Ember.Handlebars.template({"1":function(depth0,helpers,partials,data) {
  var stack1, escapeExpression=this.escapeExpression, helperMissing=helpers.helperMissing, buffer = '';
  data.buffer.push(" ");
  stack1 = helpers['if'].call(depth0, "sideBarControl", {"name":"if","hash":{},"hashTypes":{},"hashContexts":{},"fn":this.program(2, data),"inverse":this.noop,"types":["ID"],"contexts":[depth0],"data":data});
  if (stack1 != null) { data.buffer.push(stack1); }
  data.buffer.push(" <div ");
  data.buffer.push(escapeExpression(helpers['bind-attr'].call(depth0, {"name":"bind-attr","hash":{
    'class': (":umi-divider-right sideBarControl::wide")
  },"hashTypes":{'class': "STRING"},"hashContexts":{'class': depth0},"types":[],"contexts":[],"data":data})));
  data.buffer.push("> <div class=\"columns small-12 s-padding-clear s-full-height\"> ");
  data.buffer.push(escapeExpression(((helpers.render || (depth0 && depth0.render) || helperMissing).call(depth0, "tableControlShared", "tableControlSettings", {"name":"render","hash":{},"hashTypes":{},"hashContexts":{},"types":["STRING","ID"],"contexts":[depth0,depth0],"data":data}))));
  data.buffer.push(" </div> </div> ");
  return buffer;
},"2":function(depth0,helpers,partials,data) {
  var escapeExpression=this.escapeExpression, buffer = '';
  data.buffer.push(" <div class=\"umi-divider-left\"> <div class=\"umi-divider-left-content\"> ");
  data.buffer.push(escapeExpression(helpers.view.call(depth0, "view.parentView.sideMenu", {"name":"view","hash":{},"hashTypes":{},"hashContexts":{},"types":["ID"],"contexts":[depth0],"data":data})));
  data.buffer.push(" </div> <div class=\"umi-divider\"></div> </div> <div class=\"umi-left-bottom-panel s-unselectable\"> <a href=\"javascript:void(0)\" class=\"button white square umi-divider-left-toggle\"> <i class=\"icon icon-left\"></i> </a> </div> ");
  return buffer;
},"compiler":[6,">= 2.0.0-beta.1"],"main":function(depth0,helpers,partials,data) {
  var stack1;
  stack1 = helpers.view.call(depth0, "divider", {"name":"view","hash":{},"hashTypes":{},"hashContexts":{},"fn":this.program(1, data),"inverse":this.noop,"types":["STRING"],"contexts":[depth0],"data":data});
  if (stack1 != null) { data.buffer.push(stack1); }
  else { data.buffer.push(''); }
  },"useData":true});

Ember.TEMPLATES["UMI/partials/objectRelationLayout/sideMenu"] = Ember.Handlebars.template({"1":function(depth0,helpers,partials,data) {
  var stack1, buffer = '';
  data.buffer.push(" ");
  stack1 = helpers.view.call(depth0, "view.itemView", {"name":"view","hash":{
    'item': ("item")
  },"hashTypes":{'item': "ID"},"hashContexts":{'item': depth0},"fn":this.program(2, data),"inverse":this.noop,"types":["ID"],"contexts":[depth0],"data":data});
  if (stack1 != null) { data.buffer.push(stack1); }
  data.buffer.push(" ");
  return buffer;
},"2":function(depth0,helpers,partials,data) {
  var stack1, buffer = '';
  data.buffer.push(" <a href=\"javascript:void(0)\">");
  stack1 = helpers._triageMustache.call(depth0, "item.displayName", {"name":"_triageMustache","hash":{},"hashTypes":{},"hashContexts":{},"types":["ID"],"contexts":[depth0],"data":data});
  if (stack1 != null) { data.buffer.push(stack1); }
  data.buffer.push("</a> ");
  return buffer;
},"compiler":[6,">= 2.0.0-beta.1"],"main":function(depth0,helpers,partials,data) {
  var stack1;
  stack1 = helpers.each.call(depth0, "item", "in", "collections", {"name":"each","hash":{},"hashTypes":{},"hashContexts":{},"fn":this.program(1, data),"inverse":this.noop,"types":["ID","ID","ID"],"contexts":[depth0,depth0,depth0],"data":data});
  if (stack1 != null) { data.buffer.push(stack1); }
  else { data.buffer.push(''); }
  },"useData":true});

Ember.TEMPLATES["UMI/partials/permissions"] = Ember.Handlebars.template({"1":function(depth0,helpers,partials,data) {
  var stack1, escapeExpression=this.escapeExpression, buffer = '';
  data.buffer.push(" <dl class=\"accordion\"> <dd class=\"accordion-navigation\"> <a class=\"accordion-navigation-button\" href=\"javascript:void(0)\"><i class=\"icon icon-right\"></i> ");
  stack1 = helpers._triageMustache.call(depth0, "component.label", {"name":"_triageMustache","hash":{},"hashTypes":{},"hashContexts":{},"types":["ID"],"contexts":[depth0],"data":data});
  if (stack1 != null) { data.buffer.push(stack1); }
  data.buffer.push("</a> <div class=\"content\"> ");
  data.buffer.push(escapeExpression(helpers.view.call(depth0, "permissionsPartial", {"name":"view","hash":{
    'component': ("component")
  },"hashTypes":{'component': "ID"},"hashContexts":{'component': depth0},"types":["STRING"],"contexts":[depth0],"data":data})));
  data.buffer.push(" </div> </dd> </dl> ");
  return buffer;
},"compiler":[6,">= 2.0.0-beta.1"],"main":function(depth0,helpers,partials,data) {
  var stack1, buffer = '';
  data.buffer.push("<div class=\"umi-permissions\"> ");
  stack1 = helpers.each.call(depth0, "component", "in", "view.meta.resources", {"name":"each","hash":{},"hashTypes":{},"hashContexts":{},"fn":this.program(1, data),"inverse":this.noop,"types":["ID","ID","ID"],"contexts":[depth0,depth0,depth0],"data":data});
  if (stack1 != null) { data.buffer.push(stack1); }
  data.buffer.push(" </div>");
  return buffer;
},"useData":true});

Ember.TEMPLATES["UMI/partials/permissions/partial"] = Ember.Handlebars.template({"1":function(depth0,helpers,partials,data) {
  var stack1, escapeExpression=this.escapeExpression, buffer = '';
  data.buffer.push(" <li class=\"umi-permissions-role-list-item\"> <div class=\"umi-permissions-role\"> ");
  stack1 = helpers['if'].call(depth0, "role.component", {"name":"if","hash":{},"hashTypes":{},"hashContexts":{},"fn":this.program(2, data),"inverse":this.noop,"types":["ID"],"contexts":[depth0],"data":data});
  if (stack1 != null) { data.buffer.push(stack1); }
  data.buffer.push(" <span class=\"umi-permissions-role-label\" ");
  data.buffer.push(escapeExpression(helpers['bind-attr'].call(depth0, {"name":"bind-attr","hash":{
    'data-permissions-component-path': ("view.component.path")
  },"hashTypes":{'data-permissions-component-path': "STRING"},"hashContexts":{'data-permissions-component-path': depth0},"types":[],"contexts":[],"data":data})));
  data.buffer.push("> ");
  data.buffer.push(escapeExpression(helpers.view.call(depth0, "checkboxElement", {"name":"view","hash":{
    'className': ("umi-permissions-role-checkbox"),
    'value': (""),
    'attributeValue': ("role.value"),
    'name': ("role.value"),
    'meta': ("role")
  },"hashTypes":{'className': "STRING",'value': "STRING",'attributeValue': "ID",'name': "ID",'meta': "ID"},"hashContexts":{'className': depth0,'value': depth0,'attributeValue': depth0,'name': depth0,'meta': depth0},"types":["STRING"],"contexts":[depth0],"data":data})));
  data.buffer.push(" </span> </div> ");
  stack1 = helpers['if'].call(depth0, "role.component", {"name":"if","hash":{},"hashTypes":{},"hashContexts":{},"fn":this.program(4, data),"inverse":this.noop,"types":["ID"],"contexts":[depth0],"data":data});
  if (stack1 != null) { data.buffer.push(stack1); }
  data.buffer.push(" </li> ");
  return buffer;
},"2":function(depth0,helpers,partials,data) {
  data.buffer.push(" <span class=\"button tiny square white left s-margin-clear umi-permissions-role-button-expand\"> <i class=\"icon icon-right\"></i> </span> ");
  },"4":function(depth0,helpers,partials,data) {
  var escapeExpression=this.escapeExpression, buffer = '';
  data.buffer.push(" <div class=\"umi-permissions-component\"> ");
  data.buffer.push(escapeExpression(helpers.view.call(depth0, "permissionsPartial", {"name":"view","hash":{
    'component': ("role.component")
  },"hashTypes":{'component': "ID"},"hashContexts":{'component': depth0},"types":["STRING"],"contexts":[depth0],"data":data})));
  data.buffer.push(" </div> ");
  return buffer;
},"compiler":[6,">= 2.0.0-beta.1"],"main":function(depth0,helpers,partials,data) {
  var stack1;
  stack1 = helpers.each.call(depth0, "role", "in", "view.component.roles", {"name":"each","hash":{},"hashTypes":{},"hashContexts":{},"fn":this.program(1, data),"inverse":this.noop,"types":["ID","ID","ID"],"contexts":[depth0,depth0,depth0],"data":data});
  if (stack1 != null) { data.buffer.push(stack1); }
  else { data.buffer.push(''); }
  },"useData":true});

Ember.TEMPLATES["UMI/partials/radioElement"] = Ember.Handlebars.template({"1":function(depth0,helpers,partials,data) {
  var escapeExpression=this.escapeExpression, buffer = '';
  data.buffer.push(" ");
  data.buffer.push(escapeExpression(helpers.view.call(depth0, "view.radioElementView", {"name":"view","hash":{},"hashTypes":{},"hashContexts":{},"types":["ID"],"contexts":[depth0],"data":data})));
  data.buffer.push(" ");
  return buffer;
},"compiler":[6,">= 2.0.0-beta.1"],"main":function(depth0,helpers,partials,data) {
  var stack1;
  stack1 = helpers.each.call(depth0, "view.meta.choices", {"name":"each","hash":{},"hashTypes":{},"hashContexts":{},"fn":this.program(1, data),"inverse":this.noop,"types":["ID"],"contexts":[depth0],"data":data});
  if (stack1 != null) { data.buffer.push(stack1); }
  else { data.buffer.push(''); }
  },"useData":true});

Ember.TEMPLATES["UMI/partials/singleCollectionObjectRelationElement"] = Ember.Handlebars.template({"1":function(depth0,helpers,partials,data) {
  var escapeExpression=this.escapeExpression, buffer = '';
  data.buffer.push(" <li> <span class=\"button flat white square\" ");
  data.buffer.push(escapeExpression(helpers.action.call(depth0, "clearValue", {"name":"action","hash":{
    'target': ("view")
  },"hashTypes":{'target': "STRING"},"hashContexts":{'target': depth0},"types":["STRING"],"contexts":[depth0],"data":data})));
  data.buffer.push("> <i class=\"icon icon-delete\"></i> </span> </li> ");
  return buffer;
},"compiler":[6,">= 2.0.0-beta.1"],"main":function(depth0,helpers,partials,data) {
  var stack1, escapeExpression=this.escapeExpression, buffer = '';
  data.buffer.push("<div ");
  data.buffer.push(escapeExpression(helpers['bind-attr'].call(depth0, {"name":"bind-attr","hash":{
    'class': (":small-2 :columns :umi-columns-fixed view.value:small-2-right:small-1-right")
  },"hashTypes":{'class': "STRING"},"hashContexts":{'class': depth0},"types":[],"contexts":[],"data":data})));
  data.buffer.push("> <span class=\"postfix\"> <span class=\"button-group\"> <li> <span class=\"button flat white square\" ");
  data.buffer.push(escapeExpression(helpers.action.call(depth0, "showPopup", "view.popupParams", {"name":"action","hash":{
    'target': ("view")
  },"hashTypes":{'target': "STRING"},"hashContexts":{'target': depth0},"types":["STRING","ID"],"contexts":[depth0,depth0],"data":data})));
  data.buffer.push("> <i class=\"icon icon-open-folder\"></i> </span> </li> ");
  stack1 = helpers['if'].call(depth0, "view.value", {"name":"if","hash":{},"hashTypes":{},"hashContexts":{},"fn":this.program(1, data),"inverse":this.noop,"types":["ID"],"contexts":[depth0],"data":data});
  if (stack1 != null) { data.buffer.push(stack1); }
  data.buffer.push(" </span> </span> </div> <div class=\"small-10 columns\"> ");
  data.buffer.push(escapeExpression(helpers.view.call(depth0, "textElement", {"name":"view","hash":{
    'meta': ("view.meta"),
    'object': ("view.object")
  },"hashTypes":{'meta': "ID",'object': "ID"},"hashContexts":{'meta': depth0,'object': depth0},"types":["STRING"],"contexts":[depth0],"data":data})));
  data.buffer.push(" </div> ");
  return buffer;
},"useData":true});

Ember.TEMPLATES["UMI/partials/textareaElement"] = Ember.Handlebars.template({"compiler":[6,">= 2.0.0-beta.1"],"main":function(depth0,helpers,partials,data) {
  var escapeExpression=this.escapeExpression, buffer = '';
  data.buffer.push(escapeExpression(helpers.view.call(depth0, "view.textareaView", {"name":"view","hash":{
    'object': ("view.object"),
    'meta': ("view.meta")
  },"hashTypes":{'object': "ID",'meta': "ID"},"hashContexts":{'object': depth0,'meta': depth0},"types":["ID"],"contexts":[depth0],"data":data})));
  data.buffer.push(" <div class=\"umi-element-textarea-resizer\"></div>");
  return buffer;
},"useData":true});

Ember.TEMPLATES["UMI/partials/timeElement"] = Ember.Handlebars.template({"compiler":[6,">= 2.0.0-beta.1"],"main":function(depth0,helpers,partials,data) {
  var escapeExpression=this.escapeExpression, buffer = '';
  data.buffer.push("<div class=\"small-11 columns\"> ");
  data.buffer.push(escapeExpression(helpers.view.call(depth0, "view.inputView", {"name":"view","hash":{},"hashTypes":{},"hashContexts":{},"types":["ID"],"contexts":[depth0],"data":data})));
  data.buffer.push(" </div> <div class=\"small-1 columns\"> <span class=\"postfix\"> <i class=\"icon icon-delete\"></i> </span> </div> <style> .umi-time-picker{ position: absolute; float: left; width: 200px; height: 200px; background: #FFFFFF; } </style>");
  return buffer;
},"useData":true});

Ember.TEMPLATES["UMI/partials/formCollection"] = Ember.Handlebars.template({"1":function(depth0,helpers,partials,data) {
  var escapeExpression=this.escapeExpression, buffer = '';
  data.buffer.push(" <div class=\"s-full-height-before\"> ");
  data.buffer.push(escapeExpression(helpers.view.call(depth0, "toolbar", {"name":"view","hash":{
    'toolbar': ("control.toolbar")
  },"hashTypes":{'toolbar': "ID"},"hashContexts":{'toolbar': depth0},"types":["STRING"],"contexts":[depth0],"data":data})));
  data.buffer.push(" </div> ");
  return buffer;
},"3":function(depth0,helpers,partials,data) {
  var stack1, buffer = '';
  data.buffer.push(" ");
  stack1 = helpers.view.call(depth0, "magellan", {"name":"view","hash":{
    'elements': ("view.fieldsetElements")
  },"hashTypes":{'elements': "ID"},"hashContexts":{'elements': depth0},"fn":this.program(4, data),"inverse":this.noop,"types":["STRING"],"contexts":[depth0],"data":data});
  if (stack1 != null) { data.buffer.push(stack1); }
  data.buffer.push(" ");
  return buffer;
},"4":function(depth0,helpers,partials,data) {
  var stack1, buffer = '';
  data.buffer.push(" ");
  stack1 = helpers.each.call(depth0, "formElement", "in", "view.elements", {"name":"each","hash":{},"hashTypes":{},"hashContexts":{},"fn":this.program(5, data),"inverse":this.noop,"types":["ID","ID","ID"],"contexts":[depth0,depth0,depth0],"data":data});
  if (stack1 != null) { data.buffer.push(stack1); }
  data.buffer.push(" ");
  return buffer;
},"5":function(depth0,helpers,partials,data) {
  var stack1, buffer = '';
  data.buffer.push(" ");
  stack1 = helpers.view.call(depth0, "view.buttonView", {"name":"view","hash":{
    'model': ("formElement")
  },"hashTypes":{'model': "ID"},"hashContexts":{'model': depth0},"fn":this.program(6, data),"inverse":this.noop,"types":["ID"],"contexts":[depth0],"data":data});
  if (stack1 != null) { data.buffer.push(stack1); }
  data.buffer.push(" ");
  return buffer;
},"6":function(depth0,helpers,partials,data) {
  var stack1, buffer = '';
  data.buffer.push(" ");
  stack1 = helpers._triageMustache.call(depth0, "formElement.label", {"name":"_triageMustache","hash":{},"hashTypes":{},"hashContexts":{},"types":["ID"],"contexts":[depth0],"data":data});
  if (stack1 != null) { data.buffer.push(stack1); }
  data.buffer.push(" ");
  return buffer;
},"8":function(depth0,helpers,partials,data) {
  var escapeExpression=this.escapeExpression, buffer = '';
  data.buffer.push(" ");
  data.buffer.push(escapeExpression(helpers.view.call(depth0, "view.elementView", {"name":"view","hash":{
    'objectBinding': ("controller.object"),
    'metaBinding': ("formElement")
  },"hashTypes":{'objectBinding': "STRING",'metaBinding': "STRING"},"hashContexts":{'objectBinding': depth0,'metaBinding': depth0},"types":["ID"],"contexts":[depth0],"data":data})));
  data.buffer.push(" ");
  return buffer;
},"10":function(depth0,helpers,partials,data) {
  var escapeExpression=this.escapeExpression, buffer = '';
  data.buffer.push(" ");
  data.buffer.push(escapeExpression(helpers.view.call(depth0, "submitToolbar", {"name":"view","hash":{
    'elements': ("control.submitToolbar")
  },"hashTypes":{'elements': "ID"},"hashContexts":{'elements': depth0},"types":["STRING"],"contexts":[depth0],"data":data})));
  data.buffer.push(" ");
  return buffer;
},"compiler":[6,">= 2.0.0-beta.1"],"main":function(depth0,helpers,partials,data) {
  var stack1, buffer = '';
  stack1 = helpers['if'].call(depth0, "control.toolbar", {"name":"if","hash":{},"hashTypes":{},"hashContexts":{},"fn":this.program(1, data),"inverse":this.noop,"types":["ID"],"contexts":[depth0],"data":data});
  if (stack1 != null) { data.buffer.push(stack1); }
  data.buffer.push(" <div class=\"s-full-height\"> ");
  stack1 = helpers['if'].call(depth0, "view.hasFieldset", {"name":"if","hash":{},"hashTypes":{},"hashContexts":{},"fn":this.program(3, data),"inverse":this.noop,"types":["ID"],"contexts":[depth0],"data":data});
  if (stack1 != null) { data.buffer.push(stack1); }
  data.buffer.push(" <div class=\"row s-full-height collapse\"> <div class=\"columns small-12 magellan-content\"> ");
  stack1 = helpers.each.call(depth0, "formElement", "in", "formElements", {"name":"each","hash":{},"hashTypes":{},"hashContexts":{},"fn":this.program(8, data),"inverse":this.noop,"types":["ID","ID","ID"],"contexts":[depth0,depth0,depth0],"data":data});
  if (stack1 != null) { data.buffer.push(stack1); }
  data.buffer.push(" </div> </div> </div> ");
  stack1 = helpers['if'].call(depth0, "control.submitToolbar", {"name":"if","hash":{},"hashTypes":{},"hashContexts":{},"fn":this.program(10, data),"inverse":this.noop,"types":["ID"],"contexts":[depth0],"data":data});
  if (stack1 != null) { data.buffer.push(stack1); }
  return buffer;
},"useData":true});

Ember.TEMPLATES["UMI/partials/formSimple"] = Ember.Handlebars.template({"1":function(depth0,helpers,partials,data) {
  var escapeExpression=this.escapeExpression, buffer = '';
  data.buffer.push(" <div class=\"s-full-height-before\"> ");
  data.buffer.push(escapeExpression(helpers.view.call(depth0, "toolbar", {"name":"view","hash":{
    'toolbar': ("control.toolbar")
  },"hashTypes":{'toolbar': "ID"},"hashContexts":{'toolbar': depth0},"types":["STRING"],"contexts":[depth0],"data":data})));
  data.buffer.push(" </div> ");
  return buffer;
},"3":function(depth0,helpers,partials,data) {
  var stack1, buffer = '';
  data.buffer.push(" ");
  stack1 = helpers.view.call(depth0, "magellan", {"name":"view","hash":{
    'elements': ("view.fieldsetElements")
  },"hashTypes":{'elements': "ID"},"hashContexts":{'elements': depth0},"fn":this.program(4, data),"inverse":this.noop,"types":["STRING"],"contexts":[depth0],"data":data});
  if (stack1 != null) { data.buffer.push(stack1); }
  data.buffer.push(" ");
  return buffer;
},"4":function(depth0,helpers,partials,data) {
  var stack1, buffer = '';
  data.buffer.push(" ");
  stack1 = helpers.each.call(depth0, "formElement", "in", "view.elements", {"name":"each","hash":{},"hashTypes":{},"hashContexts":{},"fn":this.program(5, data),"inverse":this.noop,"types":["ID","ID","ID"],"contexts":[depth0,depth0,depth0],"data":data});
  if (stack1 != null) { data.buffer.push(stack1); }
  data.buffer.push(" ");
  return buffer;
},"5":function(depth0,helpers,partials,data) {
  var stack1, buffer = '';
  data.buffer.push(" ");
  stack1 = helpers.view.call(depth0, "view.buttonView", {"name":"view","hash":{
    'model': ("formElement")
  },"hashTypes":{'model': "ID"},"hashContexts":{'model': depth0},"fn":this.program(6, data),"inverse":this.noop,"types":["ID"],"contexts":[depth0],"data":data});
  if (stack1 != null) { data.buffer.push(stack1); }
  data.buffer.push(" ");
  return buffer;
},"6":function(depth0,helpers,partials,data) {
  var stack1, buffer = '';
  data.buffer.push(" ");
  stack1 = helpers._triageMustache.call(depth0, "formElement.label", {"name":"_triageMustache","hash":{},"hashTypes":{},"hashContexts":{},"types":["ID"],"contexts":[depth0],"data":data});
  if (stack1 != null) { data.buffer.push(stack1); }
  data.buffer.push(" ");
  return buffer;
},"8":function(depth0,helpers,partials,data) {
  data.buffer.push(" <br /> ");
  },"10":function(depth0,helpers,partials,data) {
  var escapeExpression=this.escapeExpression, buffer = '';
  data.buffer.push(" ");
  data.buffer.push(escapeExpression(helpers.view.call(depth0, "view.elementView", {"name":"view","hash":{
    'objectBinding': ("formElement"),
    'metaBinding': ("formElement")
  },"hashTypes":{'objectBinding': "STRING",'metaBinding': "STRING"},"hashContexts":{'objectBinding': depth0,'metaBinding': depth0},"types":["ID"],"contexts":[depth0],"data":data})));
  data.buffer.push(" ");
  return buffer;
},"12":function(depth0,helpers,partials,data) {
  var escapeExpression=this.escapeExpression, buffer = '';
  data.buffer.push(" ");
  data.buffer.push(escapeExpression(helpers.view.call(depth0, "view.submitToolbarView", {"name":"view","hash":{
    'elements': ("model.control.submitToolbar")
  },"hashTypes":{'elements': "ID"},"hashContexts":{'elements': depth0},"types":["ID"],"contexts":[depth0],"data":data})));
  data.buffer.push(" ");
  return buffer;
},"compiler":[6,">= 2.0.0-beta.1"],"main":function(depth0,helpers,partials,data) {
  var stack1, buffer = '';
  stack1 = helpers['if'].call(depth0, "control.toolbar", {"name":"if","hash":{},"hashTypes":{},"hashContexts":{},"fn":this.program(1, data),"inverse":this.noop,"types":["ID"],"contexts":[depth0],"data":data});
  if (stack1 != null) { data.buffer.push(stack1); }
  data.buffer.push(" <div class=\"s-full-height\"> ");
  stack1 = helpers['if'].call(depth0, "view.hasFieldset", {"name":"if","hash":{},"hashTypes":{},"hashContexts":{},"fn":this.program(3, data),"inverse":this.noop,"types":["ID"],"contexts":[depth0],"data":data});
  if (stack1 != null) { data.buffer.push(stack1); }
  data.buffer.push(" <div class=\"row s-full-height collapse\"> <div class=\"columns small-12 magellan-content\"> ");
  stack1 = helpers.unless.call(depth0, "view.hasFieldset", {"name":"unless","hash":{},"hashTypes":{},"hashContexts":{},"fn":this.program(8, data),"inverse":this.noop,"types":["ID"],"contexts":[depth0],"data":data});
  if (stack1 != null) { data.buffer.push(stack1); }
  data.buffer.push(" ");
  stack1 = helpers.each.call(depth0, "formElement", "in", "formElements", {"name":"each","hash":{},"hashTypes":{},"hashContexts":{},"fn":this.program(10, data),"inverse":this.noop,"types":["ID","ID","ID"],"contexts":[depth0,depth0,depth0],"data":data});
  if (stack1 != null) { data.buffer.push(stack1); }
  data.buffer.push(" </div> </div> </div> ");
  stack1 = helpers['if'].call(depth0, "model.control.submitToolbar", {"name":"if","hash":{},"hashTypes":{},"hashContexts":{},"fn":this.program(12, data),"inverse":this.noop,"types":["ID"],"contexts":[depth0],"data":data});
  if (stack1 != null) { data.buffer.push(stack1); }
  return buffer;
},"useData":true});

Ember.TEMPLATES["UMI/partials/form/submitToolbar"] = Ember.Handlebars.template({"1":function(depth0,helpers,partials,data) {
  var escapeExpression=this.escapeExpression, buffer = '';
  data.buffer.push(" ");
  data.buffer.push(escapeExpression(helpers.view.call(depth0, "view.elementView", {"name":"view","hash":{},"hashTypes":{},"hashContexts":{},"types":["ID"],"contexts":[depth0],"data":data})));
  data.buffer.push(" ");
  return buffer;
},"compiler":[6,">= 2.0.0-beta.1"],"main":function(depth0,helpers,partials,data) {
  var stack1;
  stack1 = helpers.each.call(depth0, "view.elements", {"name":"each","hash":{},"hashTypes":{},"hashContexts":{},"fn":this.program(1, data),"inverse":this.noop,"types":["ID"],"contexts":[depth0],"data":data});
  if (stack1 != null) { data.buffer.push(stack1); }
  else { data.buffer.push(''); }
  },"useData":true});

Ember.TEMPLATES["UMI/partials/alert-box/close-all"] = Ember.Handlebars.template({"compiler":[6,">= 2.0.0-beta.1"],"main":function(depth0,helpers,partials,data) {
  var escapeExpression=this.escapeExpression;
  data.buffer.push(escapeExpression(helpers._triageMustache.call(depth0, "view.content.content", {"name":"_triageMustache","hash":{
    'unescaped': ("true")
  },"hashTypes":{'unescaped': "STRING"},"hashContexts":{'unescaped': depth0},"types":["ID"],"contexts":[depth0],"data":data})));
  },"useData":true});

Ember.TEMPLATES["UMI/partials/alert-box"] = Ember.Handlebars.template({"1":function(depth0,helpers,partials,data) {
  var stack1, buffer = '';
  data.buffer.push(" <h5 class=\"subheader\">");
  stack1 = helpers._triageMustache.call(depth0, "view.content.title", {"name":"_triageMustache","hash":{},"hashTypes":{},"hashContexts":{},"types":["ID"],"contexts":[depth0],"data":data});
  if (stack1 != null) { data.buffer.push(stack1); }
  data.buffer.push("</h5> ");
  return buffer;
},"3":function(depth0,helpers,partials,data) {
  var escapeExpression=this.escapeExpression, buffer = '';
  data.buffer.push(" <span ");
  data.buffer.push(escapeExpression(helpers.action.call(depth0, "close", "view.content", {"name":"action","hash":{
    'target': ("view")
  },"hashTypes":{'target': "STRING"},"hashContexts":{'target': depth0},"types":["STRING","ID"],"contexts":[depth0,depth0],"data":data})));
  data.buffer.push(" class=\"close\"><i class=\"icon icon-close white\"></i></span> ");
  return buffer;
},"compiler":[6,">= 2.0.0-beta.1"],"main":function(depth0,helpers,partials,data) {
  var stack1, escapeExpression=this.escapeExpression, buffer = '';
  stack1 = helpers['if'].call(depth0, "view.content.title", {"name":"if","hash":{},"hashTypes":{},"hashContexts":{},"fn":this.program(1, data),"inverse":this.noop,"types":["ID"],"contexts":[depth0],"data":data});
  if (stack1 != null) { data.buffer.push(stack1); }
  data.buffer.push(" ");
  data.buffer.push(escapeExpression(helpers._triageMustache.call(depth0, "view.content.content", {"name":"_triageMustache","hash":{
    'unescaped': ("true")
  },"hashTypes":{'unescaped': "STRING"},"hashContexts":{'unescaped': depth0},"types":["ID"],"contexts":[depth0],"data":data})));
  data.buffer.push(" ");
  stack1 = helpers['if'].call(depth0, "view.content.close", {"name":"if","hash":{},"hashTypes":{},"hashContexts":{},"fn":this.program(3, data),"inverse":this.noop,"types":["ID"],"contexts":[depth0],"data":data});
  if (stack1 != null) { data.buffer.push(stack1); }
  return buffer;
},"useData":true});

Ember.TEMPLATES["UMI/partials/popup/fileManager"] = Ember.Handlebars.template({"compiler":[6,">= 2.0.0-beta.1"],"main":function(depth0,helpers,partials,data) {
  var escapeExpression=this.escapeExpression;
  data.buffer.push(escapeExpression(helpers.view.call(depth0, "fileManager", {"name":"view","hash":{
    'templateParams': ("templateParams")
  },"hashTypes":{'templateParams': "ID"},"hashContexts":{'templateParams': depth0},"types":["STRING"],"contexts":[depth0],"data":data})));
  },"useData":true});

Ember.TEMPLATES["UMI/partials/popup"] = Ember.Handlebars.template({"1":function(depth0,helpers,partials,data) {
  var stack1, buffer = '';
  data.buffer.push(" <div class=\"s-scroll-wrap\"> <div> ");
  stack1 = helpers._triageMustache.call(depth0, "yield", {"name":"_triageMustache","hash":{},"hashTypes":{},"hashContexts":{},"types":["ID"],"contexts":[depth0],"data":data});
  if (stack1 != null) { data.buffer.push(stack1); }
  data.buffer.push(" </div> </div> ");
  return buffer;
},"3":function(depth0,helpers,partials,data) {
  var stack1, buffer = '';
  data.buffer.push(" ");
  stack1 = helpers._triageMustache.call(depth0, "yield", {"name":"_triageMustache","hash":{},"hashTypes":{},"hashContexts":{},"types":["ID"],"contexts":[depth0],"data":data});
  if (stack1 != null) { data.buffer.push(stack1); }
  data.buffer.push(" ");
  return buffer;
},"compiler":[6,">= 2.0.0-beta.1"],"main":function(depth0,helpers,partials,data) {
  var stack1, escapeExpression=this.escapeExpression, buffer = '';
  data.buffer.push("<div class=\"umi-popup-header\"> <span class=\"umi-popup-title\">");
  stack1 = helpers._triageMustache.call(depth0, "view.title", {"name":"_triageMustache","hash":{},"hashTypes":{},"hashContexts":{},"types":["ID"],"contexts":[depth0],"data":data});
  if (stack1 != null) { data.buffer.push(stack1); }
  data.buffer.push("</span> <a href=\"#\" class=\"umi-popup-close-button\" ");
  data.buffer.push(escapeExpression(helpers.action.call(depth0, "closePopup", {"name":"action","hash":{
    'target': ("view")
  },"hashTypes":{'target': "STRING"},"hashContexts":{'target': depth0},"types":["STRING"],"contexts":[depth0],"data":data})));
  data.buffer.push("> <i class=\"icon white icon-close\"></i> </a> </div> <div class=\"umi-popup-content\"> ");
  stack1 = helpers['if'].call(depth0, "view.hasScroll", {"name":"if","hash":{},"hashTypes":{},"hashContexts":{},"fn":this.program(1, data),"inverse":this.program(3, data),"types":["ID"],"contexts":[depth0],"data":data});
  if (stack1 != null) { data.buffer.push(stack1); }
  data.buffer.push(" </div> <div class=\"umi-popup-resizer\"></div>");
  return buffer;
},"useData":true});

Ember.TEMPLATES["UMI/partials/popup/objectRelation"] = Ember.Handlebars.template({"compiler":[6,">= 2.0.0-beta.1"],"main":function(depth0,helpers,partials,data) {
  var helperMissing=helpers.helperMissing, escapeExpression=this.escapeExpression;
  data.buffer.push(escapeExpression(((helpers.render || (depth0 && depth0.render) || helperMissing).call(depth0, "objectRelationLayout", "templateParams", {"name":"render","hash":{},"hashTypes":{},"hashContexts":{},"types":["STRING","ID"],"contexts":[depth0,depth0],"data":data}))));
  },"useData":true});

Ember.TEMPLATES["UMI/partials/popup/singleCollectionObjectRelation"] = Ember.Handlebars.template({"compiler":[6,">= 2.0.0-beta.1"],"main":function(depth0,helpers,partials,data) {
  var helperMissing=helpers.helperMissing, escapeExpression=this.escapeExpression;
  data.buffer.push(escapeExpression(((helpers.render || (depth0 && depth0.render) || helperMissing).call(depth0, "singleCollectionObjectRelationLayout", "templateParams", {"name":"render","hash":{},"hashTypes":{},"hashContexts":{},"types":["STRING","ID"],"contexts":[depth0,depth0],"data":data}))));
  },"useData":true});

Ember.TEMPLATES["UMI/partials/sideMenu"] = Ember.Handlebars.template({"1":function(depth0,helpers,partials,data) {
  var stack1, helperMissing=helpers.helperMissing, buffer = '';
  data.buffer.push(" ");
  stack1 = ((helpers['link-to'] || (depth0 && depth0['link-to']) || helperMissing).call(depth0, "context", "object.id", {"name":"link-to","hash":{
    'tagName': ("li")
  },"hashTypes":{'tagName': "STRING"},"hashContexts":{'tagName': depth0},"fn":this.program(2, data),"inverse":this.noop,"types":["STRING","ID"],"contexts":[depth0,depth0],"data":data}));
  if (stack1 != null) { data.buffer.push(stack1); }
  data.buffer.push(" ");
  return buffer;
},"2":function(depth0,helpers,partials,data) {
  var stack1, buffer = '';
  data.buffer.push("<a href=\"javascript:void(0)\">");
  stack1 = helpers._triageMustache.call(depth0, "object.displayName", {"name":"_triageMustache","hash":{},"hashTypes":{},"hashContexts":{},"types":["ID"],"contexts":[depth0],"data":data});
  if (stack1 != null) { data.buffer.push(stack1); }
  data.buffer.push("</a>");
  return buffer;
},"compiler":[6,">= 2.0.0-beta.1"],"main":function(depth0,helpers,partials,data) {
  var stack1, buffer = '';
  data.buffer.push("<ul class=\"side-nav\"> ");
  stack1 = helpers.each.call(depth0, "object", "in", "objects", {"name":"each","hash":{},"hashTypes":{},"hashContexts":{},"fn":this.program(1, data),"inverse":this.noop,"types":["ID","ID","ID"],"contexts":[depth0,depth0,depth0],"data":data});
  if (stack1 != null) { data.buffer.push(stack1); }
  data.buffer.push(" </ul> ");
  return buffer;
},"useData":true});

Ember.TEMPLATES["UMI/partials/table"] = Ember.Handlebars.template({"1":function(depth0,helpers,partials,data) {
  var stack1, buffer = '';
  data.buffer.push(" <p></p> <h3 class=\"text-center\">");
  stack1 = helpers._triageMustache.call(depth0, "view.error", {"name":"_triageMustache","hash":{},"hashTypes":{},"hashContexts":{},"types":["ID"],"contexts":[depth0],"data":data});
  if (stack1 != null) { data.buffer.push(stack1); }
  data.buffer.push("</h3> ");
  return buffer;
},"3":function(depth0,helpers,partials,data) {
  var stack1, escapeExpression=this.escapeExpression, buffer = '';
  data.buffer.push(" ");
  data.buffer.push(escapeExpression(helpers.view.call(depth0, "view.paginationView", {"name":"view","hash":{},"hashTypes":{},"hashContexts":{},"types":["ID"],"contexts":[depth0],"data":data})));
  data.buffer.push(" <div class=\"umi-table-header-wrap\"> <table class=\"umi-table-header\"> <tbody> <tr class=\"umi-table-tr\"> ");
  stack1 = helpers.each.call(depth0, "view.headers", {"name":"each","hash":{},"hashTypes":{},"hashContexts":{},"fn":this.program(4, data),"inverse":this.noop,"types":["ID"],"contexts":[depth0],"data":data});
  if (stack1 != null) { data.buffer.push(stack1); }
  data.buffer.push(" <td class=\"umi-table-empty-column\"></td> </tr> </tbody> </table> </div> <div class=\"umi-table-header-shadow\"></div> <div class=\"s-scroll-wrap\"> <table class=\"umi-table-content\"> <tbody> <tr class=\"umi-table-content-sizer\"> ");
  stack1 = helpers.each.call(depth0, "view.headers", {"name":"each","hash":{},"hashTypes":{},"hashContexts":{},"fn":this.program(6, data),"inverse":this.noop,"types":["ID"],"contexts":[depth0],"data":data});
  if (stack1 != null) { data.buffer.push(stack1); }
  data.buffer.push(" </tr> ");
  stack1 = helpers.each.call(depth0, "row", "in", "view.visibleRows", {"name":"each","hash":{},"hashTypes":{},"hashContexts":{},"fn":this.program(8, data),"inverse":this.program(11, data),"types":["ID","ID","ID"],"contexts":[depth0,depth0,depth0],"data":data});
  if (stack1 != null) { data.buffer.push(stack1); }
  data.buffer.push(" </tbody> </table> </div> ");
  return buffer;
},"4":function(depth0,helpers,partials,data) {
  var stack1, buffer = '';
  data.buffer.push(" <td class=\"umi-table-td\" style=\"width: 200px;\"> <div class=\"umi-table-td-relative-wrap\"> <div class=\"umi-table-cell\">");
  stack1 = helpers._triageMustache.call(depth0, "", {"name":"_triageMustache","hash":{},"hashTypes":{},"hashContexts":{},"types":["ID"],"contexts":[depth0],"data":data});
  if (stack1 != null) { data.buffer.push(stack1); }
  data.buffer.push("</div> <div class=\"umi-table-header-column-resizer\"></div> </div> </td> ");
  return buffer;
},"6":function(depth0,helpers,partials,data) {
  data.buffer.push(" <td class=\"umi-table-td\" style=\"width: 200px;\"></td> ");
  },"8":function(depth0,helpers,partials,data) {
  var stack1, buffer = '';
  data.buffer.push(" <tr class=\"umi-table-content-tr\"> ");
  stack1 = helpers.each.call(depth0, "property", "in", "row", {"name":"each","hash":{},"hashTypes":{},"hashContexts":{},"fn":this.program(9, data),"inverse":this.noop,"types":["ID","ID","ID"],"contexts":[depth0,depth0,depth0],"data":data});
  if (stack1 != null) { data.buffer.push(stack1); }
  data.buffer.push(" <td class=\"umi-table-empty-column\"></td> </tr> ");
  return buffer;
},"9":function(depth0,helpers,partials,data) {
  var stack1, buffer = '';
  data.buffer.push(" <td class=\"umi-table-td\"> <div class=\"umi-table-cell\">");
  stack1 = helpers._triageMustache.call(depth0, "property", {"name":"_triageMustache","hash":{},"hashTypes":{},"hashContexts":{},"types":["ID"],"contexts":[depth0],"data":data});
  if (stack1 != null) { data.buffer.push(stack1); }
  data.buffer.push("</div> </td> ");
  return buffer;
},"11":function(depth0,helpers,partials,data) {
  var helperMissing=helpers.helperMissing, escapeExpression=this.escapeExpression, buffer = '';
  data.buffer.push(" <tr class=\"umi-table-content-tr\"> <td> ");
  data.buffer.push(escapeExpression(((helpers.i18n || (depth0 && depth0.i18n) || helperMissing).call(depth0, "No data", "table", {"name":"i18n","hash":{},"hashTypes":{},"hashContexts":{},"types":["STRING","STRING"],"contexts":[depth0,depth0],"data":data}))));
  data.buffer.push(" </td> </tr> ");
  return buffer;
},"compiler":[6,">= 2.0.0-beta.1"],"main":function(depth0,helpers,partials,data) {
  var stack1;
  stack1 = helpers['if'].call(depth0, "view.error", {"name":"if","hash":{},"hashTypes":{},"hashContexts":{},"fn":this.program(1, data),"inverse":this.program(3, data),"types":["ID"],"contexts":[depth0],"data":data});
  if (stack1 != null) { data.buffer.push(stack1); }
  else { data.buffer.push(''); }
  },"useData":true});

Ember.TEMPLATES["UMI/partials/table/toolbar"] = Ember.Handlebars.template({"1":function(depth0,helpers,partials,data) {
  data.buffer.push(" <i class=\"icon black icon-left-thin\"></i> ");
  },"3":function(depth0,helpers,partials,data) {
  data.buffer.push(" <i class=\"icon black icon-right-thin\"></i> ");
  },"compiler":[6,">= 2.0.0-beta.1"],"main":function(depth0,helpers,partials,data) {
  var stack1, helperMissing=helpers.helperMissing, escapeExpression=this.escapeExpression, buffer = '';
  data.buffer.push("<div class=\"right umi-table-control-pagination\"> <div class=\"right pagination-controls\"> <span class=\"pagination-counter\"> ");
  stack1 = helpers._triageMustache.call(depth0, "view.counter", {"name":"_triageMustache","hash":{},"hashTypes":{},"hashContexts":{},"types":["ID"],"contexts":[depth0],"data":data});
  if (stack1 != null) { data.buffer.push(stack1); }
  data.buffer.push(" </span> ");
  stack1 = helpers.view.call(depth0, "view.prevButtonView", {"name":"view","hash":{},"hashTypes":{},"hashContexts":{},"fn":this.program(1, data),"inverse":this.noop,"types":["ID"],"contexts":[depth0],"data":data});
  if (stack1 != null) { data.buffer.push(stack1); }
  data.buffer.push(" ");
  stack1 = helpers.view.call(depth0, "view.nextButtonView", {"name":"view","hash":{},"hashTypes":{},"hashContexts":{},"fn":this.program(3, data),"inverse":this.noop,"types":["ID"],"contexts":[depth0],"data":data});
  if (stack1 != null) { data.buffer.push(stack1); }
  data.buffer.push(" </div> <div class=\"right pagination-limit\"> <span class=\"pagination-label\">");
  data.buffer.push(escapeExpression(((helpers.i18n || (depth0 && depth0.i18n) || helperMissing).call(depth0, "Rows on page", "table", {"name":"i18n","hash":{},"hashTypes":{},"hashContexts":{},"types":["STRING","STRING"],"contexts":[depth0,depth0],"data":data}))));
  data.buffer.push(":</span> ");
  data.buffer.push(escapeExpression(helpers.view.call(depth0, "view.limitView", {"name":"view","hash":{},"hashTypes":{},"hashContexts":{},"types":["ID"],"contexts":[depth0],"data":data})));
  data.buffer.push(" </div> </div> ");
  return buffer;
},"useData":true});

Ember.TEMPLATES["UMI/partials/popup/tableControl"] = Ember.Handlebars.template({"compiler":[6,">= 2.0.0-beta.1"],"main":function(depth0,helpers,partials,data) {
  var helperMissing=helpers.helperMissing, escapeExpression=this.escapeExpression;
  data.buffer.push(escapeExpression(((helpers.render || (depth0 && depth0.render) || helperMissing).call(depth0, "tableControlPopup", "templateParams", {"name":"render","hash":{},"hashTypes":{},"hashContexts":{},"types":["STRING","ID"],"contexts":[depth0,depth0],"data":data}))));
  },"useData":true});

Ember.TEMPLATES["UMI/partials/tableControl/configuration"] = Ember.Handlebars.template({"1":function(depth0,helpers,partials,data) {
  var escapeExpression=this.escapeExpression, buffer = '';
  data.buffer.push(" <li class=\"umi-list-item\"> ");
  data.buffer.push(escapeExpression(helpers.view.call(depth0, "checkboxElement", {"name":"view","hash":{
    'objectNeedChange': (true),
    'meta': (""),
    'object': ("")
  },"hashTypes":{'objectNeedChange': "BOOLEAN",'meta': "ID",'object': "ID"},"hashContexts":{'objectNeedChange': depth0,'meta': depth0,'object': depth0},"types":["STRING"],"contexts":[depth0],"data":data})));
  data.buffer.push(" </li> ");
  return buffer;
},"compiler":[6,">= 2.0.0-beta.1"],"main":function(depth0,helpers,partials,data) {
  var stack1, escapeExpression=this.escapeExpression, helperMissing=helpers.helperMissing, buffer = '';
  data.buffer.push("<div class=\"umi-popup-content-with-bottom\"> <div class=\"s-scroll-wrap\"> <ul class=\"no-bullet umi-list striped\"> ");
  stack1 = helpers.each.call(depth0, "fieldsList", {"name":"each","hash":{},"hashTypes":{},"hashContexts":{},"fn":this.program(1, data),"inverse":this.noop,"types":["ID"],"contexts":[depth0],"data":data});
  if (stack1 != null) { data.buffer.push(stack1); }
  data.buffer.push(" </ul> </div> </div> <div class=\"umi-popup-bottom\"> <div class=\"right\"> <span class=\"button secondary\" ");
  data.buffer.push(escapeExpression(helpers.action.call(depth0, "setDefault", {"name":"action","hash":{},"hashTypes":{},"hashContexts":{},"types":["STRING"],"contexts":[depth0],"data":data})));
  data.buffer.push(">");
  data.buffer.push(escapeExpression(((helpers.i18n || (depth0 && depth0.i18n) || helperMissing).call(depth0, "Default", "tableControl", {"name":"i18n","hash":{},"hashTypes":{},"hashContexts":{},"types":["STRING","STRING"],"contexts":[depth0,depth0],"data":data}))));
  data.buffer.push("</span> <span ");
  data.buffer.push(escapeExpression(helpers['bind-attr'].call(depth0, {"name":"bind-attr","hash":{
    'class': (":button isDirty::disabled")
  },"hashTypes":{'class': "STRING"},"hashContexts":{'class': depth0},"types":[],"contexts":[],"data":data})));
  data.buffer.push(" ");
  data.buffer.push(escapeExpression(helpers.action.call(depth0, "apply", {"name":"action","hash":{},"hashTypes":{},"hashContexts":{},"types":["STRING"],"contexts":[depth0],"data":data})));
  data.buffer.push(">");
  data.buffer.push(escapeExpression(((helpers.i18n || (depth0 && depth0.i18n) || helperMissing).call(depth0, "Apply", "tableControl", {"name":"i18n","hash":{},"hashTypes":{},"hashContexts":{},"types":["STRING","STRING"],"contexts":[depth0,depth0],"data":data}))));
  data.buffer.push("</span> </div> </div>");
  return buffer;
},"useData":true});

Ember.TEMPLATES["UMI/partials/tableControl"] = Ember.Handlebars.template({"1":function(depth0,helpers,partials,data) {
  var stack1, buffer = '';
  data.buffer.push(" ");
  stack1 = helpers.view.call(depth0, "view.parentView.paginationView", {"name":"view","hash":{},"hashTypes":{},"hashContexts":{},"fn":this.program(2, data),"inverse":this.noop,"types":["ID"],"contexts":[depth0],"data":data});
  if (stack1 != null) { data.buffer.push(stack1); }
  data.buffer.push(" ");
  return buffer;
},"2":function(depth0,helpers,partials,data) {
  var stack1, helperMissing=helpers.helperMissing, escapeExpression=this.escapeExpression, buffer = '';
  data.buffer.push(" <div class=\"right pagination-controls\"> <span class=\"pagination-counter\"> ");
  stack1 = helpers._triageMustache.call(depth0, "view.counter", {"name":"_triageMustache","hash":{},"hashTypes":{},"hashContexts":{},"types":["ID"],"contexts":[depth0],"data":data});
  if (stack1 != null) { data.buffer.push(stack1); }
  data.buffer.push(" </span> ");
  stack1 = helpers.view.call(depth0, "view.prevButtonView", {"name":"view","hash":{},"hashTypes":{},"hashContexts":{},"fn":this.program(3, data),"inverse":this.noop,"types":["ID"],"contexts":[depth0],"data":data});
  if (stack1 != null) { data.buffer.push(stack1); }
  data.buffer.push(" ");
  stack1 = helpers.view.call(depth0, "view.nextButtonView", {"name":"view","hash":{},"hashTypes":{},"hashContexts":{},"fn":this.program(5, data),"inverse":this.noop,"types":["ID"],"contexts":[depth0],"data":data});
  if (stack1 != null) { data.buffer.push(stack1); }
  data.buffer.push(" </div> <div class=\"right pagination-limit\"> <span class=\"pagination-label\">");
  data.buffer.push(escapeExpression(((helpers.i18n || (depth0 && depth0.i18n) || helperMissing).call(depth0, "Rows on page", "tableControl", {"name":"i18n","hash":{},"hashTypes":{},"hashContexts":{},"types":["STRING","STRING"],"contexts":[depth0,depth0],"data":data}))));
  data.buffer.push(":</span> ");
  data.buffer.push(escapeExpression(helpers.view.call(depth0, "view.limitView", {"name":"view","hash":{},"hashTypes":{},"hashContexts":{},"types":["ID"],"contexts":[depth0],"data":data})));
  data.buffer.push(" </div> ");
  return buffer;
},"3":function(depth0,helpers,partials,data) {
  data.buffer.push(" <i class=\"icon black icon-left-thin\"></i> ");
  },"5":function(depth0,helpers,partials,data) {
  data.buffer.push(" <i class=\"icon black icon-right-thin\"></i> ");
  },"7":function(depth0,helpers,partials,data) {
  var stack1, helperMissing=helpers.helperMissing, escapeExpression=this.escapeExpression, buffer = '';
  data.buffer.push(" <td class=\"umi-table-control-header-column column-id-");
  data.buffer.push(escapeExpression(((helpers.filterClassName || (depth0 && depth0.filterClassName) || helperMissing).call(depth0, "column.attributes.name", {"name":"filterClassName","hash":{},"hashTypes":{},"hashContexts":{},"types":["ID"],"contexts":[depth0],"data":data}))));
  data.buffer.push("\" style=\"width: 200px\"> <div class=\"umi-table-control-cell-firefox-fix\"> <div class=\"umi-table-control-header-cell\"> ");
  stack1 = helpers._triageMustache.call(depth0, "column.label", {"name":"_triageMustache","hash":{},"hashTypes":{},"hashContexts":{},"types":["ID"],"contexts":[depth0],"data":data});
  if (stack1 != null) { data.buffer.push(stack1); }
  data.buffer.push(" </div> <div class=\"umi-table-control-column-resizer\"></div> </div> </td> ");
  return buffer;
},"9":function(depth0,helpers,partials,data) {
  var stack1, helperMissing=helpers.helperMissing, escapeExpression=this.escapeExpression, buffer = '';
  data.buffer.push(" <td class=\"umi-table-control-header-column\"> <div class=\"umi-table-control-header-cell filter column-id-");
  data.buffer.push(escapeExpression(((helpers.filterClassName || (depth0 && depth0.filterClassName) || helperMissing).call(depth0, "column.attributes.name", {"name":"filterClassName","hash":{},"hashTypes":{},"hashContexts":{},"types":["ID"],"contexts":[depth0],"data":data}))));
  data.buffer.push("\"> ");
  data.buffer.push(escapeExpression(helpers.view.call(depth0, "view.filterRowView", {"name":"view","hash":{
    'column': ("column")
  },"hashTypes":{'column': "ID"},"hashContexts":{'column': depth0},"types":["ID"],"contexts":[depth0],"data":data})));
  data.buffer.push(" ");
  stack1 = helpers.view.call(depth0, "view.sortHandlerView", {"name":"view","hash":{
    'propertyName': ("column.attributes.name")
  },"hashTypes":{'propertyName': "ID"},"hashContexts":{'propertyName': depth0},"fn":this.program(10, data),"inverse":this.noop,"types":["ID"],"contexts":[depth0],"data":data});
  if (stack1 != null) { data.buffer.push(stack1); }
  data.buffer.push(" </div> </td> ");
  return buffer;
},"10":function(depth0,helpers,partials,data) {
  var escapeExpression=this.escapeExpression, buffer = '';
  data.buffer.push(" <i ");
  data.buffer.push(escapeExpression(helpers['bind-attr'].call(depth0, {"name":"bind-attr","hash":{
    'class': (":icon :black view.sortAscending:icon-bottom-thin:icon-top-thin")
  },"hashTypes":{'class': "STRING"},"hashContexts":{'class': depth0},"types":[],"contexts":[],"data":data})));
  data.buffer.push("></i> ");
  return buffer;
},"12":function(depth0,helpers,partials,data) {
  var escapeExpression=this.escapeExpression, buffer = '';
  data.buffer.push(" <span class=\"button flat tiny\" ");
  data.buffer.push(escapeExpression(helpers.action.call(depth0, "openColumnConfiguration", {"name":"action","hash":{},"hashTypes":{},"hashContexts":{},"types":["STRING"],"contexts":[depth0],"data":data})));
  data.buffer.push("> <i class=\"icon icon-settings\"></i> </span> ");
  return buffer;
},"14":function(depth0,helpers,partials,data) {
  var helperMissing=helpers.helperMissing, escapeExpression=this.escapeExpression, buffer = '';
  data.buffer.push(" <td class=\"umi-table-control-content-cell column-id-");
  data.buffer.push(escapeExpression(((helpers.filterClassName || (depth0 && depth0.filterClassName) || helperMissing).call(depth0, "column.attributes.name", {"name":"filterClassName","hash":{},"hashTypes":{},"hashContexts":{},"types":["ID"],"contexts":[depth0],"data":data}))));
  data.buffer.push("\" style=\"width: 200px\"></td> ");
  return buffer;
},"16":function(depth0,helpers,partials,data) {
  var stack1, buffer = '';
  data.buffer.push(" ");
  stack1 = helpers.each.call(depth0, "object", "in", "objects", {"name":"each","hash":{},"hashTypes":{},"hashContexts":{},"fn":this.program(17, data),"inverse":this.noop,"types":["ID","ID","ID"],"contexts":[depth0,depth0,depth0],"data":data});
  if (stack1 != null) { data.buffer.push(stack1); }
  data.buffer.push(" ");
  return buffer;
},"17":function(depth0,helpers,partials,data) {
  var stack1, buffer = '';
  data.buffer.push(" ");
  stack1 = helpers.view.call(depth0, "view.rowView", {"name":"view","hash":{
    'object': ("object")
  },"hashTypes":{'object': "ID"},"hashContexts":{'object': depth0},"fn":this.program(18, data),"inverse":this.noop,"types":["ID"],"contexts":[depth0],"data":data});
  if (stack1 != null) { data.buffer.push(stack1); }
  data.buffer.push(" ");
  return buffer;
},"18":function(depth0,helpers,partials,data) {
  var stack1, buffer = '';
  data.buffer.push(" ");
  stack1 = helpers.each.call(depth0, "column", "in", "visibleFields", {"name":"each","hash":{},"hashTypes":{},"hashContexts":{},"fn":this.program(19, data),"inverse":this.noop,"types":["ID","ID","ID"],"contexts":[depth0,depth0,depth0],"data":data});
  if (stack1 != null) { data.buffer.push(stack1); }
  data.buffer.push(" <td class=\"umi-table-control-empty-column\"></td> ");
  return buffer;
},"19":function(depth0,helpers,partials,data) {
  var escapeExpression=this.escapeExpression, buffer = '';
  data.buffer.push(" <td class=\"umi-table-control-content-cell\"> ");
  data.buffer.push(escapeExpression(helpers.view.call(depth0, "tableCellContent", {"name":"view","hash":{
    'column': ("column"),
    'objectBinding': ("object")
  },"hashTypes":{'column': "ID",'objectBinding': "STRING"},"hashContexts":{'column': depth0,'objectBinding': depth0},"types":["STRING"],"contexts":[depth0],"data":data})));
  data.buffer.push(" </td> ");
  return buffer;
},"21":function(depth0,helpers,partials,data) {
  var stack1, escapeExpression=this.escapeExpression, buffer = '';
  data.buffer.push(" <div ");
  data.buffer.push(escapeExpression(helpers['bind-attr'].call(depth0, {"name":"bind-attr","hash":{
    'class': (":umi-table-control-column-fixed-cell object.active::umi-inactive isSelected:selected")
  },"hashTypes":{'class': "STRING"},"hashContexts":{'class': depth0},"types":[],"contexts":[],"data":data})));
  data.buffer.push(" data-objectId=\"");
  data.buffer.push(escapeExpression(helpers.unbound.call(depth0, "object.id", {"name":"unbound","hash":{},"hashTypes":{},"hashContexts":{},"types":["ID"],"contexts":[depth0],"data":data})));
  data.buffer.push("\"> ");
  stack1 = helpers['if'].call(depth0, "controller.parentController.contextToolbar", {"name":"if","hash":{},"hashTypes":{},"hashContexts":{},"fn":this.program(22, data),"inverse":this.noop,"types":["ID"],"contexts":[depth0],"data":data});
  if (stack1 != null) { data.buffer.push(stack1); }
  data.buffer.push(" </div> ");
  return buffer;
},"22":function(depth0,helpers,partials,data) {
  var stack1, buffer = '';
  data.buffer.push(" ");
  stack1 = helpers.view.call(depth0, "tableControlContextToolbar", {"name":"view","hash":{
    'elements': ("controller.parentController.contextToolbar")
  },"hashTypes":{'elements': "ID"},"hashContexts":{'elements': depth0},"fn":this.program(23, data),"inverse":this.noop,"types":["STRING"],"contexts":[depth0],"data":data});
  if (stack1 != null) { data.buffer.push(stack1); }
  data.buffer.push(" ");
  return buffer;
},"23":function(depth0,helpers,partials,data) {
  var stack1, buffer = '';
  data.buffer.push(" ");
  stack1 = helpers.each.call(depth0, "view.elements", {"name":"each","hash":{},"hashTypes":{},"hashContexts":{},"fn":this.program(24, data),"inverse":this.noop,"types":["ID"],"contexts":[depth0],"data":data});
  if (stack1 != null) { data.buffer.push(stack1); }
  data.buffer.push(" ");
  return buffer;
},"24":function(depth0,helpers,partials,data) {
  var escapeExpression=this.escapeExpression, buffer = '';
  data.buffer.push(" ");
  data.buffer.push(escapeExpression(helpers.view.call(depth0, "view.elementView", {"name":"view","hash":{},"hashTypes":{},"hashContexts":{},"types":["ID"],"contexts":[depth0],"data":data})));
  data.buffer.push(" ");
  return buffer;
},"compiler":[6,">= 2.0.0-beta.1"],"main":function(depth0,helpers,partials,data) {
  var stack1, buffer = '';
  stack1 = helpers.view.call(depth0, "toolbar", {"name":"view","hash":{
    'toolbar': ("toolbar")
  },"hashTypes":{'toolbar': "ID"},"hashContexts":{'toolbar': depth0},"fn":this.program(1, data),"inverse":this.noop,"types":["STRING"],"contexts":[depth0],"data":data});
  if (stack1 != null) { data.buffer.push(stack1); }
  data.buffer.push(" <div class=\"umi-table-control-header s-unselectable\"> <div class=\"umi-table-control-header-center\"> <table class=\"umi-table-control-header\"> <tbody> <tr class=\"umi-table-control-row\"> ");
  stack1 = helpers.each.call(depth0, "column", "in", "visibleFields", {"name":"each","hash":{},"hashTypes":{},"hashContexts":{},"fn":this.program(7, data),"inverse":this.noop,"types":["ID","ID","ID"],"contexts":[depth0,depth0,depth0],"data":data});
  if (stack1 != null) { data.buffer.push(stack1); }
  data.buffer.push(" <td class=\"umi-table-control-empty-column\"></td> </tr> <tr class=\"umi-table-control-row filters\"> ");
  stack1 = helpers.each.call(depth0, "column", "in", "visibleFields", {"name":"each","hash":{},"hashTypes":{},"hashContexts":{},"fn":this.program(9, data),"inverse":this.noop,"types":["ID","ID","ID"],"contexts":[depth0,depth0,depth0],"data":data});
  if (stack1 != null) { data.buffer.push(stack1); }
  data.buffer.push(" <td class=\"umi-table-control-empty-column\"></td> </tr> </tbody> </table> </div> <div class=\"umi-table-control-header-fixed-right\"> <div class=\"umi-table-control-header-title\"> ");
  stack1 = helpers['if'].call(depth0, "hasPopup", {"name":"if","hash":{},"hashTypes":{},"hashContexts":{},"fn":this.program(12, data),"inverse":this.noop,"types":["ID"],"contexts":[depth0],"data":data});
  if (stack1 != null) { data.buffer.push(stack1); }
  data.buffer.push(" </div> <div class=\"umi-table-control-header-filter\"> </div> </div> </div> <div class=\"umi-table-control-content-wrapper\"> <div class=\"s-scroll-wrap\"> <table class=\"umi-table-control-content\"> <tbody> <tr class=\"umi-table-control-content-row-size\"> ");
  stack1 = helpers.each.call(depth0, "column", "in", "visibleFields", {"name":"each","hash":{},"hashTypes":{},"hashContexts":{},"fn":this.program(14, data),"inverse":this.noop,"types":["ID","ID","ID"],"contexts":[depth0,depth0,depth0],"data":data});
  if (stack1 != null) { data.buffer.push(stack1); }
  data.buffer.push(" <td class=\"umi-table-control-empty-column\"></td> </tr> ");
  stack1 = helpers['if'].call(depth0, "objects.content.isFulfilled", {"name":"if","hash":{},"hashTypes":{},"hashContexts":{},"fn":this.program(16, data),"inverse":this.noop,"types":["ID"],"contexts":[depth0],"data":data});
  if (stack1 != null) { data.buffer.push(stack1); }
  data.buffer.push(" </tbody> </table> </div> <!-- Колонка справа от контента --> <div class=\"umi-table-control-content-fixed-right\"> ");
  stack1 = helpers.each.call(depth0, "object", "in", "objects", {"name":"each","hash":{
    'itemController': ("tableControlContextToolbarItem")
  },"hashTypes":{'itemController': "STRING"},"hashContexts":{'itemController': depth0},"fn":this.program(21, data),"inverse":this.noop,"types":["ID","ID","ID"],"contexts":[depth0,depth0,depth0],"data":data});
  if (stack1 != null) { data.buffer.push(stack1); }
  data.buffer.push(" </div> </div>");
  return buffer;
},"useData":true});

Ember.TEMPLATES["UMI/partials/button"] = Ember.Handlebars.template({"1":function(depth0,helpers,partials,data) {
  var escapeExpression=this.escapeExpression, buffer = '';
  data.buffer.push(" <i ");
  data.buffer.push(escapeExpression(helpers['bind-attr'].call(depth0, {"name":"bind-attr","hash":{
    'class': (":icon view.iconClass")
  },"hashTypes":{'class': "STRING"},"hashContexts":{'class': depth0},"types":[],"contexts":[],"data":data})));
  data.buffer.push("></i> ");
  return buffer;
},"3":function(depth0,helpers,partials,data) {
  var stack1, buffer = '';
  data.buffer.push(" <b class=\"umi-button-label\">");
  stack1 = helpers._triageMustache.call(depth0, "view.label", {"name":"_triageMustache","hash":{},"hashTypes":{},"hashContexts":{},"types":["ID"],"contexts":[depth0],"data":data});
  if (stack1 != null) { data.buffer.push(stack1); }
  data.buffer.push("</b> ");
  return buffer;
},"compiler":[6,">= 2.0.0-beta.1"],"main":function(depth0,helpers,partials,data) {
  var stack1, buffer = '';
  stack1 = helpers['if'].call(depth0, "view.meta.attributes.hasIcon", {"name":"if","hash":{},"hashTypes":{},"hashContexts":{},"fn":this.program(1, data),"inverse":this.noop,"types":["ID"],"contexts":[depth0],"data":data});
  if (stack1 != null) { data.buffer.push(stack1); }
  data.buffer.push(" ");
  stack1 = helpers['if'].call(depth0, "view.label", {"name":"if","hash":{},"hashTypes":{},"hashContexts":{},"fn":this.program(3, data),"inverse":this.noop,"types":["ID"],"contexts":[depth0],"data":data});
  if (stack1 != null) { data.buffer.push(stack1); }
  return buffer;
},"useData":true});

Ember.TEMPLATES["UMI/partials/dropdownButton/backupList"] = Ember.Handlebars.template({"1":function(depth0,helpers,partials,data) {
  var stack1, buffer = '';
  data.buffer.push(" ");
  stack1 = helpers['if'].call(depth0, "view.meta.attributes.hasIcon", {"name":"if","hash":{},"hashTypes":{},"hashContexts":{},"fn":this.program(2, data),"inverse":this.noop,"types":["ID"],"contexts":[depth0],"data":data});
  if (stack1 != null) { data.buffer.push(stack1); }
  data.buffer.push(" ");
  stack1 = helpers['if'].call(depth0, "view.label", {"name":"if","hash":{},"hashTypes":{},"hashContexts":{},"fn":this.program(4, data),"inverse":this.noop,"types":["ID"],"contexts":[depth0],"data":data});
  if (stack1 != null) { data.buffer.push(stack1); }
  data.buffer.push(" ");
  return buffer;
},"2":function(depth0,helpers,partials,data) {
  var escapeExpression=this.escapeExpression, buffer = '';
  data.buffer.push(" <i ");
  data.buffer.push(escapeExpression(helpers['bind-attr'].call(depth0, {"name":"bind-attr","hash":{
    'class': (":icon view.iconClass")
  },"hashTypes":{'class': "STRING"},"hashContexts":{'class': depth0},"types":[],"contexts":[],"data":data})));
  data.buffer.push("></i> ");
  return buffer;
},"4":function(depth0,helpers,partials,data) {
  var stack1, buffer = '';
  data.buffer.push(" <b class=\"umi-button-label\">");
  stack1 = helpers._triageMustache.call(depth0, "view.label", {"name":"_triageMustache","hash":{},"hashTypes":{},"hashContexts":{},"types":["ID"],"contexts":[depth0],"data":data});
  if (stack1 != null) { data.buffer.push(stack1); }
  data.buffer.push("</b> ");
  return buffer;
},"6":function(depth0,helpers,partials,data) {
  var stack1, buffer = '';
  data.buffer.push(" <div class=\"f-dropdown-content-header\">");
  stack1 = helpers._triageMustache.call(depth0, "view.button.displayName", {"name":"_triageMustache","hash":{},"hashTypes":{},"hashContexts":{},"types":["ID"],"contexts":[depth0],"data":data});
  if (stack1 != null) { data.buffer.push(stack1); }
  data.buffer.push("</div> ");
  return buffer;
},"8":function(depth0,helpers,partials,data) {
  var stack1, buffer = '';
  data.buffer.push(" ");
  stack1 = helpers.each.call(depth0, "view.backupList", {"name":"each","hash":{},"hashTypes":{},"hashContexts":{},"fn":this.program(9, data),"inverse":this.program(18, data),"types":["ID"],"contexts":[depth0],"data":data});
  if (stack1 != null) { data.buffer.push(stack1); }
  data.buffer.push(" ");
  return buffer;
},"9":function(depth0,helpers,partials,data) {
  var stack1, escapeExpression=this.escapeExpression, buffer = '';
  data.buffer.push(" <tr ");
  data.buffer.push(escapeExpression(helpers['bind-attr'].call(depth0, {"name":"bind-attr","hash":{
    'class': ("isActive::selectable")
  },"hashTypes":{'class': "STRING"},"hashContexts":{'class': depth0},"types":[],"contexts":[],"data":data})));
  data.buffer.push(" ");
  data.buffer.push(escapeExpression(helpers.action.call(depth0, "applyBackup", "", {"name":"action","hash":{
    'target': ("view")
  },"hashTypes":{'target': "STRING"},"hashContexts":{'target': depth0},"types":["STRING","ID"],"contexts":[depth0,depth0],"data":data})));
  data.buffer.push("> <td> ");
  stack1 = helpers['if'].call(depth0, "isActive", {"name":"if","hash":{},"hashTypes":{},"hashContexts":{},"fn":this.program(10, data),"inverse":this.program(12, data),"types":["ID"],"contexts":[depth0],"data":data});
  if (stack1 != null) { data.buffer.push(stack1); }
  data.buffer.push(" ");
  stack1 = helpers._triageMustache.call(depth0, "created.date", {"name":"_triageMustache","hash":{},"hashTypes":{},"hashContexts":{},"types":["ID"],"contexts":[depth0],"data":data});
  if (stack1 != null) { data.buffer.push(stack1); }
  data.buffer.push(" </td> <td> ");
  stack1 = helpers['if'].call(depth0, "user", {"name":"if","hash":{},"hashTypes":{},"hashContexts":{},"fn":this.program(14, data),"inverse":this.program(16, data),"types":["ID"],"contexts":[depth0],"data":data});
  if (stack1 != null) { data.buffer.push(stack1); }
  data.buffer.push(" </td> </tr> ");
  return buffer;
},"10":function(depth0,helpers,partials,data) {
  data.buffer.push(" <div class=\"button flat tiny square\"> <i class=\"icon icon-accept\"></i> </div> ");
  },"12":function(depth0,helpers,partials,data) {
  data.buffer.push(" <div class=\"button flat tiny square\"> <i class=\"icon icon-renew\"></i> </div> ");
  },"14":function(depth0,helpers,partials,data) {
  var stack1, buffer = '';
  data.buffer.push(" ");
  stack1 = helpers._triageMustache.call(depth0, "user", {"name":"_triageMustache","hash":{},"hashTypes":{},"hashContexts":{},"types":["ID"],"contexts":[depth0],"data":data});
  if (stack1 != null) { data.buffer.push(stack1); }
  data.buffer.push(" ");
  return buffer;
},"16":function(depth0,helpers,partials,data) {
  var helperMissing=helpers.helperMissing, escapeExpression=this.escapeExpression, buffer = '';
  data.buffer.push(" ");
  data.buffer.push(escapeExpression(((helpers.i18n || (depth0 && depth0.i18n) || helperMissing).call(depth0, "User name", "toolbar:dropdownButton", {"name":"i18n","hash":{},"hashTypes":{},"hashContexts":{},"types":["STRING","STRING"],"contexts":[depth0,depth0],"data":data}))));
  data.buffer.push(" ");
  return buffer;
},"18":function(depth0,helpers,partials,data) {
  var stack1, buffer = '';
  data.buffer.push(" <tr> <td colspan=\"2\"> ");
  stack1 = helpers._triageMustache.call(depth0, "view.noBackupsLabel", {"name":"_triageMustache","hash":{},"hashTypes":{},"hashContexts":{},"types":["ID"],"contexts":[depth0],"data":data});
  if (stack1 != null) { data.buffer.push(stack1); }
  data.buffer.push(" </td> </tr> ");
  return buffer;
},"20":function(depth0,helpers,partials,data) {
  var helperMissing=helpers.helperMissing, escapeExpression=this.escapeExpression, buffer = '';
  data.buffer.push(" <tr> <td colspan=\"2\">");
  data.buffer.push(escapeExpression(((helpers.i18n || (depth0 && depth0.i18n) || helperMissing).call(depth0, "Loading", {"name":"i18n","hash":{},"hashTypes":{},"hashContexts":{},"types":["STRING"],"contexts":[depth0],"data":data}))));
  data.buffer.push("..</td> </tr> ");
  return buffer;
},"compiler":[6,">= 2.0.0-beta.1"],"main":function(depth0,helpers,partials,data) {
  var stack1, escapeExpression=this.escapeExpression, buffer = '';
  stack1 = helpers.view.call(depth0, "view.buttonView", {"name":"view","hash":{
    'meta': ("view.meta")
  },"hashTypes":{'meta': "ID"},"hashContexts":{'meta': depth0},"fn":this.program(1, data),"inverse":this.noop,"types":["ID"],"contexts":[depth0],"data":data});
  if (stack1 != null) { data.buffer.push(stack1); }
  data.buffer.push(" <ul ");
  data.buffer.push(escapeExpression(helpers['bind-attr'].call(depth0, {"name":"bind-attr","hash":{
    'class': (":f-dropdown view.dropdownClassName"),
    'id': ("view.dropdownId")
  },"hashTypes":{'class': "STRING",'id': "STRING"},"hashContexts":{'class': depth0,'id': depth0},"types":[],"contexts":[],"data":data})));
  data.buffer.push(" data-dropdown-content> <li> ");
  stack1 = helpers['if'].call(depth0, "view.button.displayName", {"name":"if","hash":{},"hashTypes":{},"hashContexts":{},"fn":this.program(6, data),"inverse":this.noop,"types":["ID"],"contexts":[depth0],"data":data});
  if (stack1 != null) { data.buffer.push(stack1); }
  data.buffer.push(" <div class=\"s-scroll-wrap\"> <table> <tbody> ");
  stack1 = helpers['if'].call(depth0, "view.backupList.isLoaded", {"name":"if","hash":{},"hashTypes":{},"hashContexts":{},"fn":this.program(8, data),"inverse":this.program(20, data),"types":["ID"],"contexts":[depth0],"data":data});
  if (stack1 != null) { data.buffer.push(stack1); }
  data.buffer.push(" </tbody> </table> </div> </li> </ul>");
  return buffer;
},"useData":true});

Ember.TEMPLATES["UMI/partials/dropdownButton/form"] = Ember.Handlebars.template({"1":function(depth0,helpers,partials,data) {
  var stack1, buffer = '';
  data.buffer.push(" ");
  stack1 = helpers['if'].call(depth0, "view.meta.attributes.hasIcon", {"name":"if","hash":{},"hashTypes":{},"hashContexts":{},"fn":this.program(2, data),"inverse":this.noop,"types":["ID"],"contexts":[depth0],"data":data});
  if (stack1 != null) { data.buffer.push(stack1); }
  data.buffer.push(" ");
  stack1 = helpers['if'].call(depth0, "view.label", {"name":"if","hash":{},"hashTypes":{},"hashContexts":{},"fn":this.program(4, data),"inverse":this.noop,"types":["ID"],"contexts":[depth0],"data":data});
  if (stack1 != null) { data.buffer.push(stack1); }
  data.buffer.push(" ");
  return buffer;
},"2":function(depth0,helpers,partials,data) {
  var escapeExpression=this.escapeExpression, buffer = '';
  data.buffer.push(" <i ");
  data.buffer.push(escapeExpression(helpers['bind-attr'].call(depth0, {"name":"bind-attr","hash":{
    'class': (":icon view.iconClass")
  },"hashTypes":{'class': "STRING"},"hashContexts":{'class': depth0},"types":[],"contexts":[],"data":data})));
  data.buffer.push("></i> ");
  return buffer;
},"4":function(depth0,helpers,partials,data) {
  var stack1, buffer = '';
  data.buffer.push(" <b class=\"umi-button-label\">");
  stack1 = helpers._triageMustache.call(depth0, "view.label", {"name":"_triageMustache","hash":{},"hashTypes":{},"hashContexts":{},"types":["ID"],"contexts":[depth0],"data":data});
  if (stack1 != null) { data.buffer.push(stack1); }
  data.buffer.push("</b> ");
  return buffer;
},"6":function(depth0,helpers,partials,data) {
  var stack1, buffer = '';
  data.buffer.push(" ");
  stack1 = helpers['if'].call(depth0, "view.form", {"name":"if","hash":{},"hashTypes":{},"hashContexts":{},"fn":this.program(7, data),"inverse":this.noop,"types":["ID"],"contexts":[depth0],"data":data});
  if (stack1 != null) { data.buffer.push(stack1); }
  data.buffer.push(" ");
  return buffer;
},"7":function(depth0,helpers,partials,data) {
  var escapeExpression=this.escapeExpression, buffer = '';
  data.buffer.push(" ");
  data.buffer.push(escapeExpression(helpers.view.call(depth0, "view.formView", {"name":"view","hash":{
    'form': ("view.form")
  },"hashTypes":{'form': "ID"},"hashContexts":{'form': depth0},"types":["ID"],"contexts":[depth0],"data":data})));
  data.buffer.push(" ");
  return buffer;
},"9":function(depth0,helpers,partials,data) {
  var helperMissing=helpers.helperMissing, escapeExpression=this.escapeExpression, buffer = '';
  data.buffer.push(" <div class=\"form-loading\">");
  data.buffer.push(escapeExpression(((helpers.i18n || (depth0 && depth0.i18n) || helperMissing).call(depth0, "Loading", {"name":"i18n","hash":{},"hashTypes":{},"hashContexts":{},"types":["STRING"],"contexts":[depth0],"data":data}))));
  data.buffer.push("..</div> ");
  return buffer;
},"compiler":[6,">= 2.0.0-beta.1"],"main":function(depth0,helpers,partials,data) {
  var stack1, escapeExpression=this.escapeExpression, buffer = '';
  stack1 = helpers.view.call(depth0, "view.buttonView", {"name":"view","hash":{
    'meta': ("view.meta")
  },"hashTypes":{'meta': "ID"},"hashContexts":{'meta': depth0},"fn":this.program(1, data),"inverse":this.noop,"types":["ID"],"contexts":[depth0],"data":data});
  if (stack1 != null) { data.buffer.push(stack1); }
  data.buffer.push(" <ul ");
  data.buffer.push(escapeExpression(helpers['bind-attr'].call(depth0, {"name":"bind-attr","hash":{
    'class': (":f-dropdown view.dropdownClassName"),
    'id': ("view.dropdownId")
  },"hashTypes":{'class': "STRING",'id': "STRING"},"hashContexts":{'class': depth0,'id': depth0},"types":[],"contexts":[],"data":data})));
  data.buffer.push(" data-dropdown-content> <li> ");
  stack1 = helpers['if'].call(depth0, "view.form.isLoaded", {"name":"if","hash":{},"hashTypes":{},"hashContexts":{},"fn":this.program(6, data),"inverse":this.program(9, data),"types":["ID"],"contexts":[depth0],"data":data});
  if (stack1 != null) { data.buffer.push(stack1); }
  data.buffer.push(" </li> </ul>");
  return buffer;
},"useData":true});

Ember.TEMPLATES["UMI/partials/dropdownButton/formLayout"] = Ember.Handlebars.template({"1":function(depth0,helpers,partials,data) {
  var escapeExpression=this.escapeExpression, buffer = '';
  data.buffer.push(" <div class=\"row collapse\"> ");
  data.buffer.push(escapeExpression(helpers.view.call(depth0, "view.elementView", {"name":"view","hash":{
    'objectBinding': ("view.object"),
    'metaBinding': ("formElement")
  },"hashTypes":{'objectBinding': "STRING",'metaBinding': "STRING"},"hashContexts":{'objectBinding': depth0,'metaBinding': depth0},"types":["STRING"],"contexts":[depth0],"data":data})));
  data.buffer.push(" </div> ");
  return buffer;
},"compiler":[6,">= 2.0.0-beta.1"],"main":function(depth0,helpers,partials,data) {
  var stack1, buffer = '';
  stack1 = helpers.each.call(depth0, "formElement", "in", "view.form.elements", {"name":"each","hash":{},"hashTypes":{},"hashContexts":{},"fn":this.program(1, data),"inverse":this.noop,"types":["ID","ID","ID"],"contexts":[depth0,depth0,depth0],"data":data});
  if (stack1 != null) { data.buffer.push(stack1); }
  data.buffer.push(" ");
  return buffer;
},"useData":true});

Ember.TEMPLATES["UMI/partials/dropdownButton"] = Ember.Handlebars.template({"1":function(depth0,helpers,partials,data) {
  var stack1, buffer = '';
  data.buffer.push(" ");
  stack1 = helpers['if'].call(depth0, "view.meta.attributes.hasIcon", {"name":"if","hash":{},"hashTypes":{},"hashContexts":{},"fn":this.program(2, data),"inverse":this.noop,"types":["ID"],"contexts":[depth0],"data":data});
  if (stack1 != null) { data.buffer.push(stack1); }
  data.buffer.push(" ");
  stack1 = helpers['if'].call(depth0, "view.label", {"name":"if","hash":{},"hashTypes":{},"hashContexts":{},"fn":this.program(4, data),"inverse":this.noop,"types":["ID"],"contexts":[depth0],"data":data});
  if (stack1 != null) { data.buffer.push(stack1); }
  data.buffer.push(" ");
  return buffer;
},"2":function(depth0,helpers,partials,data) {
  var escapeExpression=this.escapeExpression, buffer = '';
  data.buffer.push(" <i ");
  data.buffer.push(escapeExpression(helpers['bind-attr'].call(depth0, {"name":"bind-attr","hash":{
    'class': (":icon view.iconClass")
  },"hashTypes":{'class': "STRING"},"hashContexts":{'class': depth0},"types":[],"contexts":[],"data":data})));
  data.buffer.push("></i> ");
  return buffer;
},"4":function(depth0,helpers,partials,data) {
  var stack1, buffer = '';
  data.buffer.push(" <b class=\"umi-button-label\">");
  stack1 = helpers._triageMustache.call(depth0, "view.label", {"name":"_triageMustache","hash":{},"hashTypes":{},"hashContexts":{},"types":["ID"],"contexts":[depth0],"data":data});
  if (stack1 != null) { data.buffer.push(stack1); }
  data.buffer.push("</b> ");
  return buffer;
},"6":function(depth0,helpers,partials,data) {
  var stack1, escapeExpression=this.escapeExpression, buffer = '';
  data.buffer.push(" <li> <a href=\"javascript:void(0);\" ");
  data.buffer.push(escapeExpression(helpers.action.call(depth0, "sendActionForBehaviour", "behaviour", {"name":"action","hash":{
    'target': ("view")
  },"hashTypes":{'target': "STRING"},"hashContexts":{'target': depth0},"types":["STRING","ID"],"contexts":[depth0,depth0],"data":data})));
  data.buffer.push("> ");
  stack1 = helpers._triageMustache.call(depth0, "attributes.label", {"name":"_triageMustache","hash":{},"hashTypes":{},"hashContexts":{},"types":["ID"],"contexts":[depth0],"data":data});
  if (stack1 != null) { data.buffer.push(stack1); }
  data.buffer.push(" </a> </li> ");
  return buffer;
},"compiler":[6,">= 2.0.0-beta.1"],"main":function(depth0,helpers,partials,data) {
  var stack1, escapeExpression=this.escapeExpression, buffer = '';
  stack1 = helpers.view.call(depth0, "view.buttonView", {"name":"view","hash":{
    'meta': ("view.meta")
  },"hashTypes":{'meta': "ID"},"hashContexts":{'meta': depth0},"fn":this.program(1, data),"inverse":this.noop,"types":["ID"],"contexts":[depth0],"data":data});
  if (stack1 != null) { data.buffer.push(stack1); }
  data.buffer.push(" <ul ");
  data.buffer.push(escapeExpression(helpers['bind-attr'].call(depth0, {"name":"bind-attr","hash":{
    'class': (":f-dropdown view.dropdownClassName"),
    'id': ("view.dropdownId")
  },"hashTypes":{'class': "STRING",'id': "STRING"},"hashContexts":{'class': depth0,'id': depth0},"types":[],"contexts":[],"data":data})));
  data.buffer.push(" data-dropdown-content> ");
  stack1 = helpers.each.call(depth0, "view.meta.choices", {"name":"each","hash":{},"hashTypes":{},"hashContexts":{},"fn":this.program(6, data),"inverse":this.noop,"types":["ID"],"contexts":[depth0],"data":data});
  if (stack1 != null) { data.buffer.push(stack1); }
  data.buffer.push(" </ul> ");
  return buffer;
},"useData":true});

Ember.TEMPLATES["UMI/partials/splitButton"] = Ember.Handlebars.template({"1":function(depth0,helpers,partials,data) {
  var stack1, escapeExpression=this.escapeExpression, buffer = '';
  data.buffer.push(" ");
  stack1 = helpers['if'].call(depth0, "view.meta.attributes.hasIcon", {"name":"if","hash":{},"hashTypes":{},"hashContexts":{},"fn":this.program(2, data),"inverse":this.noop,"types":["ID"],"contexts":[depth0],"data":data});
  if (stack1 != null) { data.buffer.push(stack1); }
  data.buffer.push(" ");
  stack1 = helpers['if'].call(depth0, "view.label", {"name":"if","hash":{},"hashTypes":{},"hashContexts":{},"fn":this.program(4, data),"inverse":this.noop,"types":["ID"],"contexts":[depth0],"data":data});
  if (stack1 != null) { data.buffer.push(stack1); }
  data.buffer.push(" <span class=\"dropdown-toggler\" ");
  data.buffer.push(escapeExpression(helpers.action.call(depth0, "open", {"name":"action","hash":{
    'target': ("view")
  },"hashTypes":{'target': "STRING"},"hashContexts":{'target': depth0},"types":["STRING"],"contexts":[depth0],"data":data})));
  data.buffer.push(" ");
  data.buffer.push(escapeExpression(helpers['bind-attr'].call(depth0, {"name":"bind-attr","hash":{
    'data-dropdown': ("view.parentView.dropdownId"),
    'data-options': ("view.dataOptions")
  },"hashTypes":{'data-dropdown': "STRING",'data-options': "STRING"},"hashContexts":{'data-dropdown': depth0,'data-options': depth0},"types":[],"contexts":[],"data":data})));
  data.buffer.push("></span> ");
  return buffer;
},"2":function(depth0,helpers,partials,data) {
  var escapeExpression=this.escapeExpression, buffer = '';
  data.buffer.push(" <i ");
  data.buffer.push(escapeExpression(helpers['bind-attr'].call(depth0, {"name":"bind-attr","hash":{
    'class': (":icon view.parentView.defaultBehaviourIcon")
  },"hashTypes":{'class': "STRING"},"hashContexts":{'class': depth0},"types":[],"contexts":[],"data":data})));
  data.buffer.push("></i> ");
  return buffer;
},"4":function(depth0,helpers,partials,data) {
  var stack1, buffer = '';
  data.buffer.push(" <b class=\"umi-button-label\">");
  stack1 = helpers._triageMustache.call(depth0, "view.label", {"name":"_triageMustache","hash":{},"hashTypes":{},"hashContexts":{},"types":["ID"],"contexts":[depth0],"data":data});
  if (stack1 != null) { data.buffer.push(stack1); }
  data.buffer.push("</b> ");
  return buffer;
},"6":function(depth0,helpers,partials,data) {
  var stack1, buffer = '';
  data.buffer.push(" ");
  stack1 = helpers.view.call(depth0, "view.itemView", {"name":"view","hash":{},"hashTypes":{},"hashContexts":{},"fn":this.program(7, data),"inverse":this.noop,"types":["ID"],"contexts":[depth0],"data":data});
  if (stack1 != null) { data.buffer.push(stack1); }
  data.buffer.push(" ");
  return buffer;
},"7":function(depth0,helpers,partials,data) {
  var stack1, escapeExpression=this.escapeExpression, buffer = '';
  data.buffer.push(" <a ");
  data.buffer.push(escapeExpression(helpers.action.call(depth0, "sendActionForBehaviour", "behaviour", {"name":"action","hash":{
    'target': ("view.parentView")
  },"hashTypes":{'target': "STRING"},"hashContexts":{'target': depth0},"types":["STRING","ID"],"contexts":[depth0,depth0],"data":data})));
  data.buffer.push("> <i ");
  data.buffer.push(escapeExpression(helpers['bind-attr'].call(depth0, {"name":"bind-attr","hash":{
    'class': (":icon view.icon")
  },"hashTypes":{'class': "STRING"},"hashContexts":{'class': depth0},"types":[],"contexts":[],"data":data})));
  data.buffer.push("></i> <div>");
  stack1 = helpers._triageMustache.call(depth0, "view.label", {"name":"_triageMustache","hash":{},"hashTypes":{},"hashContexts":{},"types":["ID"],"contexts":[depth0],"data":data});
  if (stack1 != null) { data.buffer.push(stack1); }
  data.buffer.push("</div> <i ");
  data.buffer.push(escapeExpression(helpers['bind-attr'].call(depth0, {"name":"bind-attr","hash":{
    'class': (":icon :icon-accept :split-default-button view.isDefaultBehaviour::white")
  },"hashTypes":{'class': "STRING"},"hashContexts":{'class': depth0},"types":[],"contexts":[],"data":data})));
  data.buffer.push(" ");
  data.buffer.push(escapeExpression(helpers.action.call(depth0, "toggleDefaultBehaviour", "view._parentView.contentIndex", {"name":"action","hash":{
    'bubbles': (false),
    'target': ("view.parentView")
  },"hashTypes":{'bubbles': "BOOLEAN",'target': "STRING"},"hashContexts":{'bubbles': depth0,'target': depth0},"types":["STRING","ID"],"contexts":[depth0,depth0],"data":data})));
  data.buffer.push("></i> </a> ");
  return buffer;
},"compiler":[6,">= 2.0.0-beta.1"],"main":function(depth0,helpers,partials,data) {
  var stack1, escapeExpression=this.escapeExpression, buffer = '';
  stack1 = helpers.view.call(depth0, "view.buttonView", {"name":"view","hash":{
    'defaultBehaviour': ("view.defaultBehaviour"),
    'meta': ("view.meta")
  },"hashTypes":{'defaultBehaviour': "ID",'meta': "ID"},"hashContexts":{'defaultBehaviour': depth0,'meta': depth0},"fn":this.program(1, data),"inverse":this.noop,"types":["ID"],"contexts":[depth0],"data":data});
  if (stack1 != null) { data.buffer.push(stack1); }
  data.buffer.push(" <ul ");
  data.buffer.push(escapeExpression(helpers['bind-attr'].call(depth0, {"name":"bind-attr","hash":{
    'id': ("view.dropdownId")
  },"hashTypes":{'id': "STRING"},"hashContexts":{'id': depth0},"types":[],"contexts":[],"data":data})));
  data.buffer.push(" class=\"f-dropdown f-dropdown-composite\" data-dropdown-content> ");
  stack1 = helpers.each.call(depth0, "view.meta.behaviour.choices", {"name":"each","hash":{},"hashTypes":{},"hashContexts":{},"fn":this.program(6, data),"inverse":this.noop,"types":["ID"],"contexts":[depth0],"data":data});
  if (stack1 != null) { data.buffer.push(stack1); }
  data.buffer.push(" </ul> ");
  return buffer;
},"useData":true});

Ember.TEMPLATES["UMI/partials/toolbar"] = Ember.Handlebars.template({"1":function(depth0,helpers,partials,data) {
  var escapeExpression=this.escapeExpression, buffer = '';
  data.buffer.push(" ");
  data.buffer.push(escapeExpression(helpers.view.call(depth0, "view.elementView", {"name":"view","hash":{},"hashTypes":{},"hashContexts":{},"types":["ID"],"contexts":[depth0],"data":data})));
  data.buffer.push(" ");
  return buffer;
},"compiler":[6,">= 2.0.0-beta.1"],"main":function(depth0,helpers,partials,data) {
  var stack1, buffer = '';
  data.buffer.push("<ul class=\"button-group left\"> ");
  stack1 = helpers.each.call(depth0, "view.toolbar", {"name":"each","hash":{},"hashTypes":{},"hashContexts":{},"fn":this.program(1, data),"inverse":this.noop,"types":["ID"],"contexts":[depth0],"data":data});
  if (stack1 != null) { data.buffer.push(stack1); }
  data.buffer.push(" </ul> <div class=\"right\"> ");
  stack1 = helpers._triageMustache.call(depth0, "yield", {"name":"_triageMustache","hash":{},"hashTypes":{},"hashContexts":{},"types":["ID"],"contexts":[depth0],"data":data});
  if (stack1 != null) { data.buffer.push(stack1); }
  data.buffer.push(" </div>");
  return buffer;
},"useData":true});

Ember.TEMPLATES["UMI/partials/topBar"] = Ember.Handlebars.template({"1":function(depth0,helpers,partials,data) {
  var stack1, helperMissing=helpers.helperMissing, buffer = '';
  data.buffer.push(" <li> ");
  stack1 = ((helpers['link-to'] || (depth0 && depth0['link-to']) || helperMissing).call(depth0, "module", "name", {"name":"link-to","hash":{
    'class': ("umi-top-bar-dropdown-modules-item")
  },"hashTypes":{'class': "STRING"},"hashContexts":{'class': depth0},"fn":this.program(2, data),"inverse":this.noop,"types":["STRING","ID"],"contexts":[depth0,depth0],"data":data}));
  if (stack1 != null) { data.buffer.push(stack1); }
  data.buffer.push(" </li> ");
  return buffer;
},"2":function(depth0,helpers,partials,data) {
  var stack1, escapeExpression=this.escapeExpression, buffer = '';
  data.buffer.push(" <i class=\"umi-top-bar-module-icon umi-dock-module-");
  data.buffer.push(escapeExpression(helpers.unbound.call(depth0, "name", {"name":"unbound","hash":{},"hashTypes":{},"hashContexts":{},"types":["ID"],"contexts":[depth0],"data":data})));
  data.buffer.push("\"></i> <span>");
  stack1 = helpers._triageMustache.call(depth0, "displayName", {"name":"_triageMustache","hash":{},"hashTypes":{},"hashContexts":{},"types":["ID"],"contexts":[depth0],"data":data});
  if (stack1 != null) { data.buffer.push(stack1); }
  data.buffer.push("</span> ");
  return buffer;
},"4":function(depth0,helpers,partials,data) {
  var helperMissing=helpers.helperMissing, escapeExpression=this.escapeExpression, buffer = '';
  data.buffer.push(" <span class=\"umi-top-bar-label umi-top-bar-loader\"> ");
  data.buffer.push(escapeExpression(((helpers.i18n || (depth0 && depth0.i18n) || helperMissing).call(depth0, "Loading", {"name":"i18n","hash":{},"hashTypes":{},"hashContexts":{},"types":["STRING"],"contexts":[depth0],"data":data}))));
  data.buffer.push("... </span> <div class=\"umi-overlay\"></div> ");
  return buffer;
},"compiler":[6,">= 2.0.0-beta.1"],"main":function(depth0,helpers,partials,data) {
  var stack1, escapeExpression=this.escapeExpression, helperMissing=helpers.helperMissing, buffer = '';
  data.buffer.push("<nav class=\"umi-top-bar\"> <ul class=\"umi-top-bar-list left\"> <li> <span class=\"button tiny flat dropdown without-arrow umi-top-bar-button\" data-dropdown data-options=\"selectorById: false;\"> <i class=\"icon icon-butterfly\"></i> </span> <ul class=\"f-dropdown f-dropdown-double\" data-dropdown-content> ");
  stack1 = helpers.each.call(depth0, "view.modules", {"name":"each","hash":{},"hashTypes":{},"hashContexts":{},"fn":this.program(1, data),"inverse":this.noop,"types":["ID"],"contexts":[depth0],"data":data});
  if (stack1 != null) { data.buffer.push(stack1); }
  data.buffer.push(" </ul> </li> <li> <span class=\"umi-top-bar-label\">");
  stack1 = helpers._triageMustache.call(depth0, "view.activeProject", {"name":"_triageMustache","hash":{},"hashTypes":{},"hashContexts":{},"types":["ID"],"contexts":[depth0],"data":data});
  if (stack1 != null) { data.buffer.push(stack1); }
  data.buffer.push("</span> </li> <li> <a href=\"");
  data.buffer.push(escapeExpression(helpers.unbound.call(depth0, "view.siteUrl", {"name":"unbound","hash":{},"hashTypes":{},"hashContexts":{},"types":["ID"],"contexts":[depth0],"data":data})));
  data.buffer.push("\" class=\"button tiny flat umi-top-bar-button\"> <i class=\"icon white icon-viewOnSite\"></i> </a> </li> </ul> ");
  stack1 = helpers['if'].call(depth0, "routeIsTransition", {"name":"if","hash":{},"hashTypes":{},"hashContexts":{},"fn":this.program(4, data),"inverse":this.noop,"types":["ID"],"contexts":[depth0],"data":data});
  if (stack1 != null) { data.buffer.push(stack1); }
  data.buffer.push(" <ul class=\"umi-top-bar-list right\"> <li> <span class=\"button tiny flat dropdown umi-top-bar-button\" data-dropdown data-options=\"selectorById: false;\"> ");
  stack1 = helpers._triageMustache.call(depth0, "view.userName", {"name":"_triageMustache","hash":{},"hashTypes":{},"hashContexts":{},"types":["ID"],"contexts":[depth0],"data":data});
  if (stack1 != null) { data.buffer.push(stack1); }
  data.buffer.push(" </span> <ul class=\"f-dropdown\" data-dropdown-content> <li> <a href=\"javascript:void(0)\" ");
  data.buffer.push(escapeExpression(helpers.action.call(depth0, "logout", {"name":"action","hash":{},"hashTypes":{},"hashContexts":{},"types":["STRING"],"contexts":[depth0],"data":data})));
  data.buffer.push("> <i class=\"icon icon-exit\"></i> ");
  data.buffer.push(escapeExpression(((helpers.i18n || (depth0 && depth0.i18n) || helperMissing).call(depth0, "Logout", {"name":"i18n","hash":{},"hashTypes":{},"hashContexts":{},"types":["STRING"],"contexts":[depth0],"data":data}))));
  data.buffer.push(" </a> </li> </ul> </li> ");
  data.buffer.push(escapeExpression(helpers.view.call(depth0, "notificationList", {"name":"view","hash":{},"hashTypes":{},"hashContexts":{},"types":["STRING"],"contexts":[depth0],"data":data})));
  data.buffer.push(" </ul> </nav>");
  return buffer;
},"useData":true});

Ember.TEMPLATES["UMI/partials/treeControl"] = Ember.Handlebars.template({"1":function(depth0,helpers,partials,data) {
  data.buffer.push(" <div class=\"umi-tree-loader\"><i class=\"animate animate-loader-60\"></i></div> ");
  },"compiler":[6,">= 2.0.0-beta.1"],"main":function(depth0,helpers,partials,data) {
  var stack1, escapeExpression=this.escapeExpression, buffer = '';
  data.buffer.push("<div class=\"columns small-12\"> <div class=\"row s-full-height umi-tree-wrapper\"> <ul class=\"umi-tree-list umi-tree\"> ");
  data.buffer.push(escapeExpression(helpers.view.call(depth0, "treeControlItem", {"name":"view","hash":{
    'treeControlView': ("view"),
    'item': ("root")
  },"hashTypes":{'treeControlView': "ID",'item': "ID"},"hashContexts":{'treeControlView': depth0,'item': depth0},"types":["STRING"],"contexts":[depth0],"data":data})));
  data.buffer.push(" </ul> ");
  stack1 = helpers['if'].call(depth0, "isLoading", {"name":"if","hash":{},"hashTypes":{},"hashContexts":{},"fn":this.program(1, data),"inverse":this.noop,"types":["ID"],"contexts":[depth0],"data":data});
  if (stack1 != null) { data.buffer.push(stack1); }
  data.buffer.push(" </div> </div>");
  return buffer;
},"useData":true});

Ember.TEMPLATES["UMI/partials/treeControl/treeItem"] = Ember.Handlebars.template({"1":function(depth0,helpers,partials,data) {
  var stack1, escapeExpression=this.escapeExpression, buffer = '';
  data.buffer.push(" <span ");
  data.buffer.push(escapeExpression(helpers.action.call(depth0, "expanded", {"name":"action","hash":{
    'target': ("view")
  },"hashTypes":{'target': "STRING"},"hashContexts":{'target': depth0},"types":["STRING"],"contexts":[depth0],"data":data})));
  data.buffer.push(" ");
  data.buffer.push(escapeExpression(helpers['bind-attr'].call(depth0, {"name":"bind-attr","hash":{
    'class': (":umi-expand")
  },"hashTypes":{'class': "STRING"},"hashContexts":{'class': depth0},"types":[],"contexts":[],"data":data})));
  data.buffer.push("> ");
  stack1 = helpers['if'].call(depth0, "view.isExpanded", {"name":"if","hash":{},"hashTypes":{},"hashContexts":{},"fn":this.program(2, data),"inverse":this.program(3, data),"types":["ID"],"contexts":[depth0],"data":data});
  if (stack1 != null) { data.buffer.push(stack1); }
  data.buffer.push(" </span> ");
  return buffer;
},"2":function(depth0,helpers,partials,data) {
  var stack1, buffer = '';
  data.buffer.push(" ");
  stack1 = helpers['if'].call(depth0, "view.childrenList", {"name":"if","hash":{},"hashTypes":{},"hashContexts":{},"fn":this.program(3, data),"inverse":this.program(5, data),"types":["ID"],"contexts":[depth0],"data":data});
  if (stack1 != null) { data.buffer.push(stack1); }
  data.buffer.push(" ");
  return buffer;
},"3":function(depth0,helpers,partials,data) {
  var escapeExpression=this.escapeExpression, buffer = '';
  data.buffer.push(" <i ");
  data.buffer.push(escapeExpression(helpers['bind-attr'].call(depth0, {"name":"bind-attr","hash":{
    'class': (":icon view.isExpanded:icon-bottom:icon-right")
  },"hashTypes":{'class': "STRING"},"hashContexts":{'class': depth0},"types":[],"contexts":[],"data":data})));
  data.buffer.push("></i> ");
  return buffer;
},"5":function(depth0,helpers,partials,data) {
  data.buffer.push(" <i class=\"animate animate-loader-20\"></i> ");
  },"7":function(depth0,helpers,partials,data) {
  var stack1, helperMissing=helpers.helperMissing, buffer = '';
  data.buffer.push(" ");
  stack1 = ((helpers['link-to'] || (depth0 && depth0['link-to']) || helperMissing).call(depth0, "action", "view.item.id", "editForm", {"name":"link-to","hash":{
    'class': ("tree-item-link")
  },"hashTypes":{'class': "STRING"},"hashContexts":{'class': depth0},"fn":this.program(8, data),"inverse":this.noop,"types":["STRING","ID","STRING"],"contexts":[depth0,depth0,depth0],"data":data}));
  if (stack1 != null) { data.buffer.push(stack1); }
  data.buffer.push(" ");
  return buffer;
},"8":function(depth0,helpers,partials,data) {
  var stack1, buffer = '';
  data.buffer.push(" ");
  stack1 = helpers._triageMustache.call(depth0, "view.savedDisplayName", {"name":"_triageMustache","hash":{},"hashTypes":{},"hashContexts":{},"types":["ID"],"contexts":[depth0],"data":data});
  if (stack1 != null) { data.buffer.push(stack1); }
  data.buffer.push(" ");
  return buffer;
},"10":function(depth0,helpers,partials,data) {
  var stack1, helperMissing=helpers.helperMissing, buffer = '';
  data.buffer.push(" ");
  stack1 = ((helpers['link-to'] || (depth0 && depth0['link-to']) || helperMissing).call(depth0, "context", "view.item.id", {"name":"link-to","hash":{
    'class': ("tree-item-link")
  },"hashTypes":{'class': "STRING"},"hashContexts":{'class': depth0},"fn":this.program(8, data),"inverse":this.noop,"types":["STRING","ID"],"contexts":[depth0,depth0],"data":data}));
  if (stack1 != null) { data.buffer.push(stack1); }
  data.buffer.push(" ");
  return buffer;
},"12":function(depth0,helpers,partials,data) {
  var stack1, helperMissing=helpers.helperMissing, buffer = '';
  data.buffer.push(" ");
  stack1 = ((helpers.render || (depth0 && depth0.render) || helperMissing).call(depth0, "treeControlContextToolbar", "view.item", {"name":"render","hash":{},"hashTypes":{},"hashContexts":{},"fn":this.program(13, data),"inverse":this.noop,"types":["STRING","ID"],"contexts":[depth0,depth0],"data":data}));
  if (stack1 != null) { data.buffer.push(stack1); }
  data.buffer.push(" ");
  return buffer;
},"13":function(depth0,helpers,partials,data) {
  var stack1, buffer = '';
  data.buffer.push(" ");
  stack1 = helpers.each.call(depth0, "parentController.contextToolbar", {"name":"each","hash":{},"hashTypes":{},"hashContexts":{},"fn":this.program(14, data),"inverse":this.noop,"types":["ID"],"contexts":[depth0],"data":data});
  if (stack1 != null) { data.buffer.push(stack1); }
  data.buffer.push(" ");
  return buffer;
},"14":function(depth0,helpers,partials,data) {
  var escapeExpression=this.escapeExpression, buffer = '';
  data.buffer.push(" ");
  data.buffer.push(escapeExpression(helpers.view.call(depth0, "view.elementView", {"name":"view","hash":{},"hashTypes":{},"hashContexts":{},"types":["ID"],"contexts":[depth0],"data":data})));
  data.buffer.push(" ");
  return buffer;
},"16":function(depth0,helpers,partials,data) {
  var stack1, buffer = '';
  data.buffer.push(" ");
  stack1 = helpers['if'].call(depth0, "view.isExpanded", {"name":"if","hash":{},"hashTypes":{},"hashContexts":{},"fn":this.program(17, data),"inverse":this.noop,"types":["ID"],"contexts":[depth0],"data":data});
  if (stack1 != null) { data.buffer.push(stack1); }
  data.buffer.push(" ");
  return buffer;
},"17":function(depth0,helpers,partials,data) {
  var stack1, escapeExpression=this.escapeExpression, buffer = '';
  data.buffer.push(" <ul class=\"umi-tree-list\" data-parent-id=\"");
  data.buffer.push(escapeExpression(helpers.unbound.call(depth0, "view.item.id", {"name":"unbound","hash":{},"hashTypes":{},"hashContexts":{},"types":["ID"],"contexts":[depth0],"data":data})));
  data.buffer.push("\"> ");
  stack1 = helpers.each.call(depth0, "item", "in", "view.childrenList", {"name":"each","hash":{},"hashTypes":{},"hashContexts":{},"fn":this.program(18, data),"inverse":this.noop,"types":["ID","ID","ID"],"contexts":[depth0,depth0,depth0],"data":data});
  if (stack1 != null) { data.buffer.push(stack1); }
  data.buffer.push(" </ul> ");
  return buffer;
},"18":function(depth0,helpers,partials,data) {
  var escapeExpression=this.escapeExpression, buffer = '';
  data.buffer.push(" ");
  data.buffer.push(escapeExpression(helpers.view.call(depth0, "treeControlItem", {"name":"view","hash":{
    'treeControlView': ("view.treeControlView"),
    'item': ("item")
  },"hashTypes":{'treeControlView': "ID",'item': "ID"},"hashContexts":{'treeControlView': depth0,'item': depth0},"types":["STRING"],"contexts":[depth0],"data":data})));
  data.buffer.push(" ");
  return buffer;
},"compiler":[6,">= 2.0.0-beta.1"],"main":function(depth0,helpers,partials,data) {
  var stack1, escapeExpression=this.escapeExpression, buffer = '';
  data.buffer.push("<div ");
  data.buffer.push(escapeExpression(helpers['bind-attr'].call(depth0, {"name":"bind-attr","hash":{
    'class': (":umi-item view.item.type view.isActiveContext:active view.inActive")
  },"hashTypes":{'class': "STRING"},"hashContexts":{'class': depth0},"types":[],"contexts":[],"data":data})));
  data.buffer.push("> ");
  stack1 = helpers['if'].call(depth0, "view.hasChildren", {"name":"if","hash":{},"hashTypes":{},"hashContexts":{},"fn":this.program(1, data),"inverse":this.noop,"types":["ID"],"contexts":[depth0],"data":data});
  if (stack1 != null) { data.buffer.push(stack1); }
  data.buffer.push(" <i ");
  data.buffer.push(escapeExpression(helpers['bind-attr'].call(depth0, {"name":"bind-attr","hash":{
    'class': (":icon :umi-tree-type-icon view.iconTypeClass view.allowMove:move")
  },"hashTypes":{'class': "STRING"},"hashContexts":{'class': depth0},"types":[],"contexts":[],"data":data})));
  data.buffer.push("></i> ");
  stack1 = helpers['if'].call(depth0, "editLink", {"name":"if","hash":{},"hashTypes":{},"hashContexts":{},"fn":this.program(7, data),"inverse":this.program(10, data),"types":["ID"],"contexts":[depth0],"data":data});
  if (stack1 != null) { data.buffer.push(stack1); }
  data.buffer.push(" ");
  stack1 = helpers['if'].call(depth0, "controller.contextToolbar", {"name":"if","hash":{},"hashTypes":{},"hashContexts":{},"fn":this.program(12, data),"inverse":this.noop,"types":["ID"],"contexts":[depth0],"data":data});
  if (stack1 != null) { data.buffer.push(stack1); }
  data.buffer.push(" </div> ");
  stack1 = helpers['if'].call(depth0, "view.hasChildren", {"name":"if","hash":{},"hashTypes":{},"hashContexts":{},"fn":this.program(16, data),"inverse":this.noop,"types":["ID"],"contexts":[depth0],"data":data});
  if (stack1 != null) { data.buffer.push(stack1); }
  return buffer;
},"useData":true});

Ember.TEMPLATES["UMI/partials/treeSimple/item"] = Ember.Handlebars.template({"1":function(depth0,helpers,partials,data) {
  var stack1, buffer = '';
  data.buffer.push(" ");
  stack1 = helpers['if'].call(depth0, "components", {"name":"if","hash":{},"hashTypes":{},"hashContexts":{},"fn":this.program(2, data),"inverse":this.program(4, data),"types":["ID"],"contexts":[depth0],"data":data});
  if (stack1 != null) { data.buffer.push(stack1); }
  data.buffer.push(" ");
  stack1 = helpers['if'].call(depth0, "resource", {"name":"if","hash":{},"hashTypes":{},"hashContexts":{},"fn":this.program(6, data),"inverse":this.program(7, data),"types":["ID"],"contexts":[depth0],"data":data});
  if (stack1 != null) { data.buffer.push(stack1); }
  data.buffer.push(" ");
  return buffer;
},"2":function(depth0,helpers,partials,data) {
  var escapeExpression=this.escapeExpression, buffer = '';
  data.buffer.push(" <span ");
  data.buffer.push(escapeExpression(helpers.action.call(depth0, "expanded", {"name":"action","hash":{
    'target': ("view")
  },"hashTypes":{'target': "STRING"},"hashContexts":{'target': depth0},"types":["STRING"],"contexts":[depth0],"data":data})));
  data.buffer.push(" ");
  data.buffer.push(escapeExpression(helpers['bind-attr'].call(depth0, {"name":"bind-attr","hash":{
    'class': (":umi-expand")
  },"hashTypes":{'class': "STRING"},"hashContexts":{'class': depth0},"types":[],"contexts":[],"data":data})));
  data.buffer.push("> <i ");
  data.buffer.push(escapeExpression(helpers['bind-attr'].call(depth0, {"name":"bind-attr","hash":{
    'class': (":icon view.isExpanded:icon-bottom:icon-right")
  },"hashTypes":{'class': "STRING"},"hashContexts":{'class': depth0},"types":[],"contexts":[],"data":data})));
  data.buffer.push("></i> </span> <i ");
  data.buffer.push(escapeExpression(helpers['bind-attr'].call(depth0, {"name":"bind-attr","hash":{
    'class': (":icon :umi-tree-type-icon :icon-document")
  },"hashTypes":{'class': "STRING"},"hashContexts":{'class': depth0},"types":[],"contexts":[],"data":data})));
  data.buffer.push("></i> ");
  return buffer;
},"4":function(depth0,helpers,partials,data) {
  var escapeExpression=this.escapeExpression, buffer = '';
  data.buffer.push(" <i ");
  data.buffer.push(escapeExpression(helpers['bind-attr'].call(depth0, {"name":"bind-attr","hash":{
    'class': (":icon :umi-tree-type-icon :icon-document")
  },"hashTypes":{'class': "STRING"},"hashContexts":{'class': depth0},"types":[],"contexts":[],"data":data})));
  data.buffer.push("></i> ");
  return buffer;
},"6":function(depth0,helpers,partials,data) {
  var stack1, helperMissing=helpers.helperMissing, buffer = '';
  data.buffer.push(" ");
  stack1 = ((helpers['link-to'] || (depth0 && depth0['link-to']) || helperMissing).call(depth0, "settings.component", "view.nestedSlug", {"name":"link-to","hash":{
    'class': ("tree-item-link")
  },"hashTypes":{'class': "STRING"},"hashContexts":{'class': depth0},"fn":this.program(7, data),"inverse":this.noop,"types":["STRING","ID"],"contexts":[depth0,depth0],"data":data}));
  if (stack1 != null) { data.buffer.push(stack1); }
  data.buffer.push(" ");
  return buffer;
},"7":function(depth0,helpers,partials,data) {
  var stack1, buffer = '';
  data.buffer.push(" ");
  stack1 = helpers._triageMustache.call(depth0, "displayName", {"name":"_triageMustache","hash":{},"hashTypes":{},"hashContexts":{},"types":["ID"],"contexts":[depth0],"data":data});
  if (stack1 != null) { data.buffer.push(stack1); }
  data.buffer.push(" ");
  return buffer;
},"9":function(depth0,helpers,partials,data) {
  var stack1, buffer = '';
  data.buffer.push(" <ul class=\"umi-tree-list\"> ");
  stack1 = helpers.each.call(depth0, "components", {"name":"each","hash":{},"hashTypes":{},"hashContexts":{},"fn":this.program(10, data),"inverse":this.noop,"types":["ID"],"contexts":[depth0],"data":data});
  if (stack1 != null) { data.buffer.push(stack1); }
  data.buffer.push(" </ul> ");
  return buffer;
},"10":function(depth0,helpers,partials,data) {
  var escapeExpression=this.escapeExpression, buffer = '';
  data.buffer.push(" ");
  data.buffer.push(escapeExpression(helpers.view.call(depth0, "treeSimpleItem", {"name":"view","hash":{},"hashTypes":{},"hashContexts":{},"types":["STRING"],"contexts":[depth0],"data":data})));
  data.buffer.push(" ");
  return buffer;
},"compiler":[6,">= 2.0.0-beta.1"],"main":function(depth0,helpers,partials,data) {
  var stack1, helperMissing=helpers.helperMissing, buffer = '';
  stack1 = ((helpers['link-to'] || (depth0 && depth0['link-to']) || helperMissing).call(depth0, "settings.component", "view.nestedSlug", {"name":"link-to","hash":{
    'bubbles': (false),
    'disabled': (true),
    'class': ("umi-item"),
    'tagName': ("div")
  },"hashTypes":{'bubbles': "BOOLEAN",'disabled': "BOOLEAN",'class': "STRING",'tagName': "STRING"},"hashContexts":{'bubbles': depth0,'disabled': depth0,'class': depth0,'tagName': depth0},"fn":this.program(1, data),"inverse":this.noop,"types":["STRING","ID"],"contexts":[depth0,depth0],"data":data}));
  if (stack1 != null) { data.buffer.push(stack1); }
  data.buffer.push(" ");
  stack1 = helpers['if'].call(depth0, "view.isExpanded", {"name":"if","hash":{},"hashTypes":{},"hashContexts":{},"fn":this.program(9, data),"inverse":this.noop,"types":["ID"],"contexts":[depth0],"data":data});
  if (stack1 != null) { data.buffer.push(stack1); }
  data.buffer.push(" ");
  return buffer;
},"useData":true});

Ember.TEMPLATES["UMI/partials/treeSimple/list"] = Ember.Handlebars.template({"1":function(depth0,helpers,partials,data) {
  var escapeExpression=this.escapeExpression, buffer = '';
  data.buffer.push(" ");
  data.buffer.push(escapeExpression(helpers.view.call(depth0, "treeSimpleItem", {"name":"view","hash":{},"hashTypes":{},"hashContexts":{},"types":["STRING"],"contexts":[depth0],"data":data})));
  data.buffer.push(" ");
  return buffer;
},"compiler":[6,">= 2.0.0-beta.1"],"main":function(depth0,helpers,partials,data) {
  var stack1, buffer = '';
  data.buffer.push("<div class=\"columns small-12\"> <div class=\"row s-full-height umi-tree-wrapper\"> <ul class=\"umi-tree-list umi-tree\"> ");
  stack1 = helpers.each.call(depth0, "view.collection", {"name":"each","hash":{},"hashTypes":{},"hashContexts":{},"fn":this.program(1, data),"inverse":this.noop,"types":["ID"],"contexts":[depth0],"data":data});
  if (stack1 != null) { data.buffer.push(stack1); }
  data.buffer.push(" </ul> </div> </div>");
  return buffer;
},"useData":true});

Ember.TEMPLATES["UMI/partials/updateLayout"] = Ember.Handlebars.template({"1":function(depth0,helpers,partials,data) {
  var stack1, helperMissing=helpers.helperMissing, escapeExpression=this.escapeExpression, buffer = '';
  data.buffer.push(" <h5>");
  data.buffer.push(escapeExpression(((helpers.i18n || (depth0 && depth0.i18n) || helperMissing).call(depth0, "Latest version", "updateLayout", {"name":"i18n","hash":{},"hashTypes":{},"hashContexts":{},"types":["STRING","STRING"],"contexts":[depth0,depth0],"data":data}))));
  data.buffer.push(" ");
  stack1 = helpers._triageMustache.call(depth0, "view.data.control.params.latestVersion.version", {"name":"_triageMustache","hash":{},"hashTypes":{},"hashContexts":{},"types":["ID"],"contexts":[depth0],"data":data});
  if (stack1 != null) { data.buffer.push(stack1); }
  data.buffer.push("</h5> <br/> <span class=\"button large\" ");
  data.buffer.push(escapeExpression(helpers.action.call(depth0, "update", {"name":"action","hash":{
    'target': ("view")
  },"hashTypes":{'target': "STRING"},"hashContexts":{'target': depth0},"types":["STRING"],"contexts":[depth0],"data":data})));
  data.buffer.push(">");
  stack1 = helpers._triageMustache.call(depth0, "view.buttonLabel", {"name":"_triageMustache","hash":{},"hashTypes":{},"hashContexts":{},"types":["ID"],"contexts":[depth0],"data":data});
  if (stack1 != null) { data.buffer.push(stack1); }
  data.buffer.push("</span> ");
  return buffer;
},"3":function(depth0,helpers,partials,data) {
  var helperMissing=helpers.helperMissing, escapeExpression=this.escapeExpression, buffer = '';
  data.buffer.push(" <br/> <span class=\"button large disabled\">");
  data.buffer.push(escapeExpression(((helpers.i18n || (depth0 && depth0.i18n) || helperMissing).call(depth0, "Nothing update", "updateLayout", {"name":"i18n","hash":{},"hashTypes":{},"hashContexts":{},"types":["STRING","STRING"],"contexts":[depth0,depth0],"data":data}))));
  data.buffer.push("</span> ");
  return buffer;
},"compiler":[6,">= 2.0.0-beta.1"],"main":function(depth0,helpers,partials,data) {
  var stack1, helperMissing=helpers.helperMissing, escapeExpression=this.escapeExpression, buffer = '';
  data.buffer.push("<div class=\"columns small-12\"> <div class=\"umi-update-layout\"> <span class=\"icon icon-butterfly umi-update-layout-logo\"></span> <span class=\"umi-update-layout-content\"> <h2>UMI.CMS Lite</h2> <h5>");
  data.buffer.push(escapeExpression(((helpers.i18n || (depth0 && depth0.i18n) || helperMissing).call(depth0, "Current version", "updateLayout", {"name":"i18n","hash":{},"hashTypes":{},"hashContexts":{},"types":["STRING","STRING"],"contexts":[depth0,depth0],"data":data}))));
  data.buffer.push(" ");
  stack1 = helpers._triageMustache.call(depth0, "view.data.control.params.currentVersion.version", {"name":"_triageMustache","hash":{},"hashTypes":{},"hashContexts":{},"types":["ID"],"contexts":[depth0],"data":data});
  if (stack1 != null) { data.buffer.push(stack1); }
  data.buffer.push("</h5> ");
  stack1 = helpers['if'].call(depth0, "view.data.control.params.latestVersion", {"name":"if","hash":{},"hashTypes":{},"hashContexts":{},"fn":this.program(1, data),"inverse":this.program(3, data),"types":["ID"],"contexts":[depth0],"data":data});
  if (stack1 != null) { data.buffer.push(stack1); }
  data.buffer.push(" </span> </div> </div>");
  return buffer;
},"useData":true});

});