
/** 
 * SimpleAjax class
 * @author sans.pds@hotmail.com
 *
 **/

var ajax = new function()
{
    this.req = null;
    this.url = null;
    this.method = "GET";
    this.container = null;
    this.responseFormat = 'text';
    this.handleResp = null;
    this.loader = "<b style='color:#FDB11A;'>carregando...</b>";
    this.LOADED = [];

    this.init = function(url, container)
    {
        var self = this;
        if (!this.req)
            self.req = self.GetXMLHttp();
        if (!this.req) {
            alert('Ajax parece desabilitado em seu Navegador.');
            return;
        }
        self.url = url;
        self.container = container;
        //self.openPage();
        self.doGet(self.url, self.container, true);
    };

    this.openPage = function( )
    {
        var self = this;
        self.ge("results").innerHTML = "";
        self.req.open(self.method, self.url, true);
        this.req.onreadystatechange = function( )
        {
            if (self.req.readyState === 1)
            {
                self.ge(self.container).innerHTML = self.loader;
            }
            if (self.req.readyState === 4)
            {   
                self.ge(self.container).innerHTML = self.req.responseText;                                
                self.loadJS(self.req.responseText);
            }
        };
        self.req.send(null);

        return self.url;

    };

    this.ge = function(id)
    {
        return document.getElementById(id);
    };

    this.GetXMLHttp = function()
    {
        var xmlHttp;
        try
        {
            xmlHttp = new XMLHttpRequest();
        }
        catch (ee)
        {
            try
            {
                xmlHttp = new ActiveXObject("Msxml2.XMLHTTP");
            }
            catch (e)
            {
                try
                {
                    xmlHttp = new ActiveXObject("Microsoft.XMLHTTP");
                }
                catch (E)
                {
                    xmlHttp = false;
                }
            }
        }
        return xmlHttp;
    };

    this.doPost = function(url, postData, container, use_a, format)
    {
        var self = this;
        self.url = url;
        self.responseFormat = format || 'text';
        self.container = container;
        self.postData = postData;
        self.req.open("POST", self.url, use_a);
        self.req.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

        self.req.onreadystatechange = function()
        {
            if (self.req.readyState === 4)
            {
                self.ge(container).innerHTML = self.req.responseText;
                self.loadJS(self.req.responseText);
            }
        };
        if (self.req.readyState === 1)
        {
            self.ge(container).innerHTML = self.loader;
        }
        self.req.send(self.postData);
    };

    this.doGet = function(url, container, modo)
    {
        var self = this;
        self.url = url;
        self.req.open('GET', self.url, modo);
        this.req.onreadystatechange = function() {
            self.req.onreadystatechange = function() {
                if (self.req.readyState === 1)
                {
                    self.ge(container).innerHTML = self.loader;
                }
                if (self.req.readyState === 4)
                {
                    self.ge(container).innerHTML = self.req.responseText;                    
                    self.loadJS(self.req.responseText);
                }
            };
        };
        self.req.send(null);
        return self.url;
    };
    
    this.loadJS = function (html) {                

        var xml = this.loadXMLString(html);
        var src = xml.getElementsByTagName('SCRIPT');                
        for (i = 0; i < src.length; i++) {            
            var node = src[i];
            if (node.hasChildNodes()) {
                var txt = node.childNodes[0].data;
                if (txt !== null && txt !== "") {
                    var att = node.getAttributeNode('ID');                    
                    var id = (att !== null ? att.nodeValue : null);                    
                    var ex = false;
                    if (id !== null) {                        
                        for (var k = 0; k < this.LOADED.length; k++) {                            
                            if (this.LOADED[k] === id) {
                                ex = true;
                                break;
                            }
                        }
                    }
                    if (ex === false) {                        
                        if (id !== null) {
                            this.LOADED[this.LOADED.length] = id;
                        };
                        var nsrc = document.createElement('script');
                        nsrc.type = 'text/javascript';
                        nsrc.language = 'javascript';
                        nsrc.text = txt;
                        /*nsrc.src = txt;*/
                        document.body.appendChild(nsrc);
                    }
                }
            }
        }
        
    };
    
    this.loadXMLString = function(txt) {
        
        txt = '<XML>' + this.getScript(txt.replace(/&/gi,'&amp;')) + '</XML>';
        try {
            xmlDoc = ActiveXObject("Microsoft.XMLDOM");            
            xmlDoc.async = false;
            xmlDoc.loadXML(txt);
            return xmlDoc;
        } catch(e) {            
            try {
                parser = new DOMParser();
                xmlDoc = parser.parseFromString(txt, 'text/html');
                return xmlDoc;
            } catch(e) {
                alert(e.message);
            }
        }
        return null;
        
    };
    
    this.getScript = function(txt) {
              
        var onlysrc = "";
        while (txt !== "") {            
            var pos = txt.search(/<SCRIPT/i);
            if (pos === -1) {
                txt = "";              
            } else {
                var end = txt.search(/<\/SCRIPT/i);
                onlysrc += txt.substring(pos, end+9);
                txt = txt.substr(end+9);
            };
        };
        return onlysrc;
        
    };

};