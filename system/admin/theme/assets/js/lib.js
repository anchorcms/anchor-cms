/**
	Objects
*/

Object.prototype.extend = function(extended) {
	for(var key in extended) {
		this[key] = extended[key];
	}
	return this;
};

Object.prototype.each = function(func) {
	for(var key in this) {
		if(this.hasOwnProperty(key)) {
			func(this[key], key);
		}
	}
};

/**
	Elements
*/

(function(global) {

	// window Element Object
	var element = global.Element;

	// overwrite with new Element constructor
	global.Element = function(tag) {
		var props = arguments[1] || {};
		var el = document.createElement(tag);
		props.each(function(val, key) {
			el.set(key, val);
		});
		return el;
	};

	// inherit native window Element prototype
	global.Element.prototype = element.prototype;

}(this));

Element.prototype.append = function(node) {
	var position = arguments[1] || 'bottom';

	if(position == 'top' && this.hasChildNodes()) {
		var child = this.childNodes[0];
		return this.insertBefore(node, child);
	}

	return this.appendChild(node);
};

Element.prototype.parent = function() {
	return this.parentNode;
};

Element.prototype.remove = function() {
	return this.parentNode.removeChild(this);
};

Element.prototype.bind = function(event, func) {
	return this.addEventListener(event, func, false);
};

Element.prototype.unbind = function(event, func) {
	return this.removeEventListener(event, func, false);
};

Element.prototype.append = function(node) {
	return this.appendChild(node);
};

Element.prototype.get = function(key) {
	if(['checked', 'value', 'selected'].indexOf(key) !== -1) return this[key];
	return this.hasAttribute(key) ? this.getAttribute(key) : null;
};

Element.prototype.has = function(key) {
	return this.hasAttribute(key);
};

Element.prototype.set = function(key, value) {
	return this.setAttribute(key, value);
};

Element.prototype.erase = function(key) {
	return this.removeAttribute(key);
};

Element.prototype.addClass = function(name) {
	this.className = (this.className.length ? this.className + ' ' + name : name);
};

Element.prototype.removeClass = function(name) {
	if(this.className.length) {
		var grp = [];
		this.className.split(' ').each(function(itm) {
			if(itm != name) grp.push(itm);
		});
		this.className = grp.join(' ');
	}
};

Element.prototype.css = function(prop) {
	if(arguments.length == 1) {
		return this.style[prop];
	}
	return this.style[prop] = arguments[1];
};

Element.prototype.html = function() {
	if(arguments.length) {
		return this.innerHTML = arguments[0];
	}

	return this.innerHTML;
};

Element.prototype.val = function() {
	if(arguments.length) {
		return this.value = arguments[0];
	}

	return this.value;
};

Element.prototype.empty = function() {
	return this.html('');
};

Element.prototype.find = function(selector) {
	return this.querySelector(selector);
};

Element.prototype.scroll = function() {
	return {x: this.scrollLeft, y: this.scrollTop};
};

Element.prototype.size = function() {
	return {x: this.clientWidth, y: this.clientHeight};
};

/**
	Arrays
*/

Array.prototype.each = function(func) {
	for(var i = 0; i < this.length; i++) {
		func(this[i], i);
	}
};

/**
	Events
*/

Event.prototype.end = function() {
	this.stopPropagation();
	this.preventDefault();
};

/**
	Selectors
*/
var $ = function(selector) {
	return document.querySelector(selector);
};

var $$ = function(selector) {
	return Array.prototype.slice.call(document.querySelectorAll(selector));
};

/**
	Util
*/
document.ready = function(func) {
	this.addEventListener('DOMContentLoaded', func, false);
};

/**
	Request
*/
var Request = (function() {

	var xhr = function() {
		return new XMLHttpRequest();
	};

	var state = function() {
		if(this.readyState == 4) {
			if(this.status === 200) {
				this.callback(this.responseText);
			} else if(console.log) {
				console.log(this.status, this.statusText, this.url);  
			} 
		}
	};

	var parse = function(item) {
		var query = [];

		item.each(function(itm, key) {
			query.push(key + '=' + itm);
		});

		return query.join('&');
	};

	return {
		post: function(url, data, func) {
			var request = xhr();
			request.url = url;
			request.open('POST', url, true);
			request.setRequestHeader('Content-type','application/x-www-form-urlencoded');
			request.callback = func;
			request.onreadystatechange = state;
			request.send(parse(data));
		},
		get: function(url, func) {
			var request = xhr();
			request.url = url;
			request.open('GET', url, true);
			request.callback = func;
			request.onreadystatechange = state;
			request.send(null);
		}
	};

}());

/**
	JSON
*/
if(typeof JSON == 'undefined') this.JSON = {};

JSON.decode = JSON.decode || function(string) {
	if(!string || typeof string != 'string') return null;
	return eval('(' + string + ')');
};