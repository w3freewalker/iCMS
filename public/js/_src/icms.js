window.iCMS = {
    version : "6.2.0",
    plugins : {},
    modules : {},
    UI:{},
    API:require("api"),
    init: function(options) {
        var config      = require("config");
        iCMS.CONFIG     = $.extend(config,options);
        iCMS.CONFIG.API = iCMS.CONFIG.PUBLIC + '/api.php';
        iCMS.UI         = require("ui");
        iCMS.dialog     = iCMS.UI.dialog;
        iCMS.alert      = iCMS.UI.alert;
    },
    run:function (id,callback) {
        var mod = require(id);
        if (typeof callback === "function") {
            return callback(mod);
        }else{
            return mod;
        }
    }
};