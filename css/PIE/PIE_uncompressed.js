(function(){
var doc = document;var PIE = window['PIE'];
if( !PIE ) {
    PIE = window['PIE'] = {
        CSS_PREFIX: '-pie-',
        STYLE_PREFIX: 'Pie',
        CLASS_PREFIX: 'pie_',
        tableCellTags: {
            'TD': 1,
            'TH': 1
        },
        childlessElements: {
            'TABLE':1,
            'THEAD':1,
            'TBODY':1,
            'TFOOT':1,
            'TR':1,
            'INPUT':1,
            'TEXTAREA':1,
            'SELECT':1,
            'OPTION':1,
            'IMG':1,
            'HR':1
        },
        focusableElements: {
            'A':1,
            'INPUT':1,
            'TEXTAREA':1,
            'SELECT':1,
            'BUTTON':1
        },
        inputButtonTypes: {
            'submit':1,
            'button':1,
            'reset':1
        },
        emptyFn: function() {}
    };
    try {
        doc.execCommand( 'BackgroundImageCache', false, true );
    } catch(e) {}
    (function() {
        var ieVersion = 4,
            div = doc.createElement('div'),
            all = div.getElementsByTagName('i'),
            shape;
        while (
            div.innerHTML = '<!--[if gt IE ' + (++ieVersion) + ']><i></i><![endif]-->',
            all[0]
        ) {}
        PIE.ieVersion = ieVersion;
        if( ieVersion === 6 ) {
            PIE.CSS_PREFIX = PIE.CSS_PREFIX.replace( /^-/, '' );
        }
        PIE.ieDocMode = doc.documentMode || PIE.ieVersion;
        div.innerHTML = '<v:shape adj="1"/>';
        shape = div.firstChild;
        shape.style['behavior'] = 'url(#default#VML)';
        PIE.supportsVML = (typeof shape['adj'] === "object");
    }());
(function() {
    var vmlCreatorDoc,
        idNum = 0,
        imageSizes = {};
    PIE.Util = {
        createVmlElement: function( tag ) {
            var vmlPrefix = 'css3vml';
            if( !vmlCreatorDoc ) {
                vmlCreatorDoc = doc.createDocumentFragment();
                vmlCreatorDoc.namespaces.add( vmlPrefix, 'urn:schemas-microsoft-com:vml' );
            }
            return vmlCreatorDoc.createElement( vmlPrefix + ':' + tag );
        },
        getUID: function( obj ) {
            return obj && obj[ '_pieId' ] || ( obj[ '_pieId' ] = '_' + ++idNum );
        },
        merge: function( obj1 ) {
            var i, len, p, objN, args = arguments;
            for( i = 1, len = args.length; i < len; i++ ) {
                objN = args[i];
                for( p in objN ) {
                    if( objN.hasOwnProperty( p ) ) {
                        obj1[ p ] = objN[ p ];
                    }
                }
            }
            return obj1;
        },
        withImageSize: function( src, func, ctx ) {
            var size = imageSizes[ src ], img, queue;
            if( size ) {
                if( Object.prototype.toString.call( size ) === '[object Array]' ) {
                    size.push( [ func, ctx ] );
                }
                else {
                    func.call( ctx, size );
                }
            } else {
                queue = imageSizes[ src ] = [ [ func, ctx ] ]; 
                img = new Image();
                img.onload = function() {
                    size = imageSizes[ src ] = { w: img.width, h: img.height };
                    for( var i = 0, len = queue.length; i < len; i++ ) {
                        queue[ i ][ 0 ].call( queue[ i ][ 1 ], size );
                    }
                    img.onload = null;
                };
                img.src = src;
            }
        }
    };
})();
PIE.GradientUtil = {
    getGradientMetrics: function( el, width, height, gradientInfo ) {
        var angle = gradientInfo.angle,
            startPos = gradientInfo.gradientStart,
            startX, startY,
            endX, endY,
            startCornerX, startCornerY,
            endCornerX, endCornerY,
            deltaX, deltaY,
            p, UNDEF;
        function findCorners() {
            startCornerX = ( angle >= 90 && angle < 270 ) ? width : 0;
            startCornerY = angle < 180 ? height : 0;
            endCornerX = width - startCornerX;
            endCornerY = height - startCornerY;
        }
        function normalizeAngle() {
            while( angle < 0 ) {
                angle += 360;
            }
            angle = angle % 360;
        }
        if( startPos ) {
            startPos = startPos.coords( el, width, height );
            startX = startPos.x;
            startY = startPos.y;
        }
        if( angle ) {
            angle = angle.degrees();
            normalizeAngle();
            findCorners();
            if( !startPos ) {
                startX = startCornerX;
                startY = startCornerY;
            }
            p = PIE.GradientUtil.perpendicularIntersect( startX, startY, angle, endCornerX, endCornerY );
            endX = p[0];
            endY = p[1];
        }
        else if( startPos ) {
            endX = width - startX;
            endY = height - startY;
        }
        else {
            startX = startY = endX = 0;
            endY = height;
        }
        deltaX = endX - startX;
        deltaY = endY - startY;
        if( angle === UNDEF ) {
            angle = ( !deltaX ? ( deltaY < 0 ? 90 : 270 ) :
                        ( !deltaY ? ( deltaX < 0 ? 180 : 0 ) :
                            -Math.atan2( deltaY, deltaX ) / Math.PI * 180
                        )
                    );
            normalizeAngle();
            findCorners();
        }
        return {
            angle: angle,
            startX: startX,
            startY: startY,
            endX: endX,
            endY: endY,
            startCornerX: startCornerX,
            startCornerY: startCornerY,
            endCornerX: endCornerX,
            endCornerY: endCornerY,
            deltaX: deltaX,
            deltaY: deltaY,
            lineLength: PIE.GradientUtil.distance( startX, startY, endX, endY )
        }
    },
    perpendicularIntersect: function( x1, y1, angle, x2, y2 ) {
        if( angle === 0 || angle === 180 ) {
            return [ x2, y1 ];
        }
        else if( angle === 90 || angle === 270 ) {
            return [ x1, y2 ];
        }
        else {
            var a1 = Math.tan( -angle * Math.PI / 180 ),
                c1 = a1 * x1 - y1,
                a2 = -1 / a1,
                c2 = a2 * x2 - y2,
                d = a2 - a1,
                endX = ( c2 - c1 ) / d,
                endY = ( a1 * c2 - a2 * c1 ) / d;
            return [ endX, endY ];
        }
    },
    distance: function( p1x, p1y, p2x, p2y ) {
        var dx = p2x - p1x,
            dy = p2y - p1y;
        return Math.abs(
            dx === 0 ? dy :
            dy === 0 ? dx :
            Math.sqrt( dx * dx + dy * dy )
        );
    }
};
PIE.Observable = function() {
    this.observers = [];
    this.indexes = {};
};
PIE.Observable.prototype = {
    observe: function( fn ) {
        var id = PIE.Util.getUID( fn ),
            indexes = this.indexes,
            observers = this.observers;
        if( !( id in indexes ) ) {
            indexes[ id ] = observers.length;
            observers.push( fn );
        }
    },
    unobserve: function( fn ) {
        var id = PIE.Util.getUID( fn ),
            indexes = this.indexes;
        if( id && id in indexes ) {
            delete this.observers[ indexes[ id ] ];
            delete indexes[ id ];
        }
    },
    fire: function() {
        var o = this.observers,
            i = o.length;
        while( i-- ) {
            o[ i ] && o[ i ]();
        }
    }
};
PIE.Heartbeat = new PIE.Observable();
PIE.Heartbeat.run = function() {
    var me = this;
    if( !me.running ) {
        setInterval( function() { me.fire() }, 250 );
        me.running = 1;
    }
};
(function() {
    PIE.OnUnload = new PIE.Observable();
    function handleUnload() {
        PIE.OnUnload.fire();
        window.detachEvent( 'onunload', handleUnload );
        window[ 'PIE' ] = null;
    }
    window.attachEvent( 'onunload', handleUnload );
    PIE.OnUnload.attachManagedEvent = function( target, name, handler ) {
        target.attachEvent( name, handler );
        this.observe( function() {
            target.detachEvent( name, handler );
        } );
    };
})()
PIE.OnResize = new PIE.Observable();
PIE.OnUnload.attachManagedEvent( window, 'onresize', function() { PIE.OnResize.fire(); } );
(function() {
    PIE.OnScroll = new PIE.Observable();
    function scrolled() {
        PIE.OnScroll.fire();
    }
    PIE.OnUnload.attachManagedEvent( window, 'onscroll', scrolled );
    PIE.OnResize.observe( scrolled );
})();
(function() {
    var elements;
    function beforePrint() {
        elements = PIE.Element.destroyAll();
    }
    function afterPrint() {
        if( elements ) {
            for( var i = 0, len = elements.length; i < len; i++ ) {
                PIE[ 'attach' ]( elements[i] );
            }
            elements = 0;
        }
    }
    PIE.OnUnload.attachManagedEvent( window, 'onbeforeprint', beforePrint );
    PIE.OnUnload.attachManagedEvent( window, 'onafterprint', afterPrint );
})();
PIE.OnMouseup = new PIE.Observable();
PIE.OnUnload.attachManagedEvent( doc, 'onmouseup', function() { PIE.OnMouseup.fire(); } );
PIE.Length = (function() {
    var lengthCalcEl = doc.createElement( 'length-calc' ),
        parent = doc.documentElement,
        s = lengthCalcEl.style,
        conversions = {},
        units = [ 'mm', 'cm', 'in', 'pt', 'pc' ],
        i = units.length,
        instances = {};
    s.position = 'absolute';
    s.top = s.left = '-9999px';
    parent.appendChild( lengthCalcEl );
    while( i-- ) {
        lengthCalcEl.style.width = '100' + units[i];
        conversions[ units[i] ] = lengthCalcEl.offsetWidth / 100;
    }
    parent.removeChild( lengthCalcEl );
    lengthCalcEl.style.width = '1em';
    function Length( val ) {
        this.val = val;
    }
    Length.prototype = {
        unitRE: /(px|em|ex|mm|cm|in|pt|pc|%)$/,
        getNumber: function() {
            var num = this.num,
                UNDEF;
            if( num === UNDEF ) {
                num = this.num = parseFloat( this.val );
            }
            return num;
        },
        getUnit: function() {
            var unit = this.unit,
                m;
            if( !unit ) {
                m = this.val.match( this.unitRE );
                unit = this.unit = ( m && m[0] ) || 'px';
            }
            return unit;
        },
        isPercentage: function() {
            return this.getUnit() === '%';
        },
        pixels: function( el, pct100 ) {
            var num = this.getNumber(),
                unit = this.getUnit();
            switch( unit ) {
                case "px":
                    return num;
                case "%":
                    return num * ( typeof pct100 === 'function' ? pct100() : pct100 ) / 100;
                case "em":
                    return num * this.getEmPixels( el );
                case "ex":
                    return num * this.getEmPixels( el ) / 2;
                default:
                    return num * conversions[ unit ];
            }
        },
        getEmPixels: function( el ) {
            var fs = el.currentStyle.fontSize,
                px, parent, me;
            if( fs.indexOf( 'px' ) > 0 ) {
                return parseFloat( fs );
            }
            else if( el.tagName in PIE.childlessElements ) {
                me = this;
                parent = el.parentNode;
                return PIE.getLength( fs ).pixels( parent, function() {
                    return me.getEmPixels( parent );
                } );
            }
            else {
                el.appendChild( lengthCalcEl );
                px = lengthCalcEl.offsetWidth;
                if( lengthCalcEl.parentNode === el ) { 
                    el.removeChild( lengthCalcEl );
                }
                return px;
            }
        }
    };
    PIE.getLength = function( val ) {
        return instances[ val ] || ( instances[ val ] = new Length( val ) );
    };
    return Length;
})();
PIE.BgPosition = (function() {
    var length_fifty = PIE.getLength( '50%' ),
        vert_idents = { 'top': 1, 'center': 1, 'bottom': 1 },
        horiz_idents = { 'left': 1, 'center': 1, 'right': 1 };
    function BgPosition( tokens ) {
        this.tokens = tokens;
    }
    BgPosition.prototype = {
        getValues: function() {
            if( !this._values ) {
                var tokens = this.tokens,
                    len = tokens.length,
                    Tokenizer = PIE.Tokenizer,
                    identType = Tokenizer.Type,
                    length_zero = PIE.getLength( '0' ),
                    type_ident = identType.IDENT,
                    type_length = identType.LENGTH,
                    type_percent = identType.PERCENT,
                    type, value,
                    vals = [ 'left', length_zero, 'top', length_zero ];
                if( len === 1 ) {
                    tokens.push( new Tokenizer.Token( type_ident, 'center' ) );
                    len++;
                }
                if( len === 2 ) {
                    if( type_ident & ( tokens[0].tokenType | tokens[1].tokenType ) &&
                        tokens[0].tokenValue in vert_idents && tokens[1].tokenValue in horiz_idents ) {
                        tokens.push( tokens.shift() );
                    }
                    if( tokens[0].tokenType & type_ident ) {
                        if( tokens[0].tokenValue === 'center' ) {
                            vals[1] = length_fifty;
                        } else {
                            vals[0] = tokens[0].tokenValue;
                        }
                    }
                    else if( tokens[0].isLengthOrPercent() ) {
                        vals[1] = PIE.getLength( tokens[0].tokenValue );
                    }
                    if( tokens[1].tokenType & type_ident ) {
                        if( tokens[1].tokenValue === 'center' ) {
                            vals[3] = length_fifty;
                        } else {
                            vals[2] = tokens[1].tokenValue;
                        }
                    }
                    else if( tokens[1].isLengthOrPercent() ) {
                        vals[3] = PIE.getLength( tokens[1].tokenValue );
                    }
                }
                else {
                }
                this._values = vals;
            }
            return this._values;
        },
        coords: function( el, width, height ) {
            var vals = this.getValues(),
                pxX = vals[1].pixels( el, width ),
                pxY = vals[3].pixels( el, height );
            return {
                x: vals[0] === 'right' ? width - pxX : pxX,
                y: vals[2] === 'bottom' ? height - pxY : pxY
            };
        }
    };
    return BgPosition;
})();
PIE.BgSize = (function() {
    var CONTAIN = 'contain',
        COVER = 'cover',
        AUTO = 'auto';
    function BgSize( w, h ) {
        this.w = w;
        this.h = h;
    }
    BgSize.prototype = {
        pixels: function( el, areaW, areaH, imgW, imgH ) {
            var me = this,
                w = me.w,
                h = me.h,
                areaRatio = areaW / areaH,
                imgRatio = imgW / imgH;
            if ( w === CONTAIN ) {
                w = imgRatio > areaRatio ? areaW : areaH * imgRatio;
                h = imgRatio > areaRatio ? areaW / imgRatio : areaH;
            }
            else if ( w === COVER ) {
                w = imgRatio < areaRatio ? areaW : areaH * imgRatio;
                h = imgRatio < areaRatio ? areaW / imgRatio : areaH;
            }
            else if ( w === AUTO ) {
                h = ( h === AUTO ? imgH : h.pixels( el, areaH ) );
                w = h * imgRatio;
            }
            else {
                w = w.pixels( el, areaW );
                h = ( h === AUTO ? w / imgRatio : h.pixels( el, areaH ) );
            }
            return { w: w, h: h };
        }
    };
    BgSize.DEFAULT = new BgSize( AUTO, AUTO );
    return BgSize;
})();
PIE.Angle = (function() {
    function Angle( val ) {
        this.val = val;
    }
    Angle.prototype = {
        unitRE: /[a-z]+$/i,
        getUnit: function() {
            return this._unit || ( this._unit = this.val.match( this.unitRE )[0].toLowerCase() );
        },
        degrees: function() {
            var deg = this._deg, u, n;
            if( deg === undefined ) {
                u = this.getUnit();
                n = parseFloat( this.val, 10 );
                deg = this._deg = ( u === 'deg' ? n : u === 'rad' ? n / Math.PI * 180 : u === 'grad' ? n / 400 * 360 : u === 'turn' ? n * 360 : 0 );
            }
            return deg;
        }
    };
    return Angle;
})();
PIE.Color = (function() {
    var instances = {};
    function Color( val ) {
        this.val = val;
    }
    Color.rgbaRE = /\s*rgba\(\s*(\d{1,3})\s*,\s*(\d{1,3})\s*,\s*(\d{1,3})\s*,\s*(\d+|\d*\.\d+)\s*\)\s*/;
    Color.names = {
        "aliceblue":"F0F8FF", "antiquewhite":"FAEBD7", "aqua":"0FF",
        "aquamarine":"7FFFD4", "azure":"F0FFFF", "beige":"F5F5DC",
        "bisque":"FFE4C4", "black":"000", "blanchedalmond":"FFEBCD",
        "blue":"00F", "blueviolet":"8A2BE2", "brown":"A52A2A",
        "burlywood":"DEB887", "cadetblue":"5F9EA0", "chartreuse":"7FFF00",
        "chocolate":"D2691E", "coral":"FF7F50", "cornflowerblue":"6495ED",
        "cornsilk":"FFF8DC", "crimson":"DC143C", "cyan":"0FF",
        "darkblue":"00008B", "darkcyan":"008B8B", "darkgoldenrod":"B8860B",
        "darkgray":"A9A9A9", "darkgreen":"006400", "darkkhaki":"BDB76B",
        "darkmagenta":"8B008B", "darkolivegreen":"556B2F", "darkorange":"FF8C00",
        "darkorchid":"9932CC", "darkred":"8B0000", "darksalmon":"E9967A",
        "darkseagreen":"8FBC8F", "darkslateblue":"483D8B", "darkslategray":"2F4F4F",
        "darkturquoise":"00CED1", "darkviolet":"9400D3", "deeppink":"FF1493",
        "deepskyblue":"00BFFF", "dimgray":"696969", "dodgerblue":"1E90FF",
        "firebrick":"B22222", "floralwhite":"FFFAF0", "forestgreen":"228B22",
        "fuchsia":"F0F", "gainsboro":"DCDCDC", "ghostwhite":"F8F8FF",
        "gold":"FFD700", "goldenrod":"DAA520", "gray":"808080",
        "green":"008000", "greenyellow":"ADFF2F", "honeydew":"F0FFF0",
        "hotpink":"FF69B4", "indianred":"CD5C5C", "indigo":"4B0082",
        "ivory":"FFFFF0", "khaki":"F0E68C", "lavender":"E6E6FA",
        "lavenderblush":"FFF0F5", "lawngreen":"7CFC00", "lemonchiffon":"FFFACD",
        "lightblue":"ADD8E6", "lightcoral":"F08080", "lightcyan":"E0FFFF",
        "lightgoldenrodyellow":"FAFAD2", "lightgreen":"90EE90", "lightgrey":"D3D3D3",
        "lightpink":"FFB6C1", "lightsalmon":"FFA07A", "lightseagreen":"20B2AA",
        "lightskyblue":"87CEFA", "lightslategray":"789", "lightsteelblue":"B0C4DE",
        "lightyellow":"FFFFE0", "lime":"0F0", "limegreen":"32CD32",
        "linen":"FAF0E6", "magenta":"F0F", "maroon":"800000",
        "mediumauqamarine":"66CDAA", "mediumblue":"0000CD", "mediumorchid":"BA55D3",
        "mediumpurple":"9370D8", "mediumseagreen":"3CB371", "mediumslateblue":"7B68EE",
        "mediumspringgreen":"00FA9A", "mediumturquoise":"48D1CC", "mediumvioletred":"C71585",
        "midnightblue":"191970", "mintcream":"F5FFFA", "mistyrose":"FFE4E1",
        "moccasin":"FFE4B5", "navajowhite":"FFDEAD", "navy":"000080",
        "oldlace":"FDF5E6", "olive":"808000", "olivedrab":"688E23",
        "orange":"FFA500", "orangered":"FF4500", "orchid":"DA70D6",
        "palegoldenrod":"EEE8AA", "palegreen":"98FB98", "paleturquoise":"AFEEEE",
        "palevioletred":"D87093", "papayawhip":"FFEFD5", "peachpuff":"FFDAB9",
        "peru":"CD853F", "pink":"FFC0CB", "plum":"DDA0DD",
        "powderblue":"B0E0E6", "purple":"800080", "red":"F00",
        "rosybrown":"BC8F8F", "royalblue":"4169E1", "saddlebrown":"8B4513",
        "salmon":"FA8072", "sandybrown":"F4A460", "seagreen":"2E8B57",
        "seashell":"FFF5EE", "sienna":"A0522D", "silver":"C0C0C0",
        "skyblue":"87CEEB", "slateblue":"6A5ACD", "slategray":"708090",
        "snow":"FFFAFA", "springgreen":"00FF7F", "steelblue":"4682B4",
        "tan":"D2B48C", "teal":"008080", "thistle":"D8BFD8",
        "tomato":"FF6347", "turquoise":"40E0D0", "violet":"EE82EE",
        "wheat":"F5DEB3", "white":"FFF", "whitesmoke":"F5F5F5",
        "yellow":"FF0", "yellowgreen":"9ACD32"
    };
    Color.prototype = {
        parse: function() {
            if( !this._color ) {
                var me = this,
                    v = me.val,
                    vLower,
                    m = v.match( Color.rgbaRE );
                if( m ) {
                    me._color = 'rgb(' + m[1] + ',' + m[2] + ',' + m[3] + ')';
                    me._alpha = parseFloat( m[4] );
                }
                else {
                    if( ( vLower = v.toLowerCase() ) in Color.names ) {
                        v = '#' + Color.names[vLower];
                    }
                    me._color = v;
                    me._alpha = ( v === 'transparent' ? 0 : 1 );
                }
            }
        },
        colorValue: function( el ) {
            this.parse();
            return this._color === 'currentColor' ? el.currentStyle.color : this._color;
        },
        alpha: function() {
            this.parse();
            return this._alpha;
        }
    };
    PIE.getColor = function(val) {
        return instances[ val ] || ( instances[ val ] = new Color( val ) );
    };
    return Color;
})();
PIE.Tokenizer = (function() {
    function Tokenizer( css ) {
        this.css = css;
        this.ch = 0;
        this.tokens = [];
        this.tokenIndex = 0;
    }
    var Type = Tokenizer.Type = {
        ANGLE: 1,
        CHARACTER: 2,
        COLOR: 4,
        DIMEN: 8,
        FUNCTION: 16,
        IDENT: 32,
        LENGTH: 64,
        NUMBER: 128,
        OPERATOR: 256,
        PERCENT: 512,
        STRING: 1024,
        URL: 2048
    };
    Tokenizer.Token = function( type, value ) {
        this.tokenType = type;
        this.tokenValue = value;
    };
    Tokenizer.Token.prototype = {
        isLength: function() {
            return this.tokenType & Type.LENGTH || ( this.tokenType & Type.NUMBER && this.tokenValue === '0' );
        },
        isLengthOrPercent: function() {
            return this.isLength() || this.tokenType & Type.PERCENT;
        }
    };
    Tokenizer.prototype = {
        whitespace: /\s/,
        number: /^[\+\-]?(\d*\.)?\d+/,
        url: /^url\(\s*("([^"]*)"|'([^']*)'|([!#$%&*-~]*))\s*\)/i,
        ident: /^\-?[_a-z][\w-]*/i,
        string: /^("([^"]*)"|'([^']*)')/,
        operator: /^[\/,]/,
        hash: /^#[\w]+/,
        hashColor: /^#([\da-f]{6}|[\da-f]{3})/i,
        unitTypes: {
            'px': Type.LENGTH, 'em': Type.LENGTH, 'ex': Type.LENGTH,
            'mm': Type.LENGTH, 'cm': Type.LENGTH, 'in': Type.LENGTH,
            'pt': Type.LENGTH, 'pc': Type.LENGTH,
            'deg': Type.ANGLE, 'rad': Type.ANGLE, 'grad': Type.ANGLE
        },
        colorFunctions: {
            'rgb': 1, 'rgba': 1, 'hsl': 1, 'hsla': 1
        },
        next: function( forget ) {
            var css, ch, firstChar, match, val,
                me = this;
            function newToken( type, value ) {
                var tok = new Tokenizer.Token( type, value );
                if( !forget ) {
                    me.tokens.push( tok );
                    me.tokenIndex++;
                }
                return tok;
            }
            function failure() {
                me.tokenIndex++;
                return null;
            }
            if( this.tokenIndex < this.tokens.length ) {
                return this.tokens[ this.tokenIndex++ ];
            }
            while( this.whitespace.test( this.css.charAt( this.ch ) ) ) {
                this.ch++;
            }
            if( this.ch >= this.css.length ) {
                return failure();
            }
            ch = this.ch;
            css = this.css.substring( this.ch );
            firstChar = css.charAt( 0 );
            switch( firstChar ) {
                case '#':
                    if( match = css.match( this.hashColor ) ) {
                        this.ch += match[0].length;
                        return newToken( Type.COLOR, match[0] );
                    }
                    break;
                case '"':
                case "'":
                    if( match = css.match( this.string ) ) {
                        this.ch += match[0].length;
                        return newToken( Type.STRING, match[2] || match[3] || '' );
                    }
                    break;
                case "/":
                case ",":
                    this.ch++;
                    return newToken( Type.OPERATOR, firstChar );
                case 'u':
                    if( match = css.match( this.url ) ) {
                        this.ch += match[0].length;
                        return newToken( Type.URL, match[2] || match[3] || match[4] || '' );
                    }
            }
            if( match = css.match( this.number ) ) {
                val = match[0];
                this.ch += val.length;
                if( css.charAt( val.length ) === '%' ) {
                    this.ch++;
                    return newToken( Type.PERCENT, val + '%' );
                }
                if( match = css.substring( val.length ).match( this.ident ) ) {
                    val += match[0];
                    this.ch += match[0].length;
                    return newToken( this.unitTypes[ match[0].toLowerCase() ] || Type.DIMEN, val );
                }
                return newToken( Type.NUMBER, val );
            }
            if( match = css.match( this.ident ) ) {
                val = match[0];
                this.ch += val.length;
                if( val.toLowerCase() in PIE.Color.names || val === 'currentColor' || val === 'transparent' ) {
                    return newToken( Type.COLOR, val );
                }
                if( css.charAt( val.length ) === '(' ) {
                    this.ch++;
                    if( val.toLowerCase() in this.colorFunctions ) {
                        function isNum( tok ) {
                            return tok && tok.tokenType & Type.NUMBER;
                        }
                        function isNumOrPct( tok ) {
                            return tok && ( tok.tokenType & ( Type.NUMBER | Type.PERCENT ) );
                        }
                        function isValue( tok, val ) {
                            return tok && tok.tokenValue === val;
                        }
                        function next() {
                            return me.next( 1 );
                        }
                        if( ( val.charAt(0) === 'r' ? isNumOrPct( next() ) : isNum( next() ) ) &&
                            isValue( next(), ',' ) &&
                            isNumOrPct( next() ) &&
                            isValue( next(), ',' ) &&
                            isNumOrPct( next() ) &&
                            ( val === 'rgb' || val === 'hsa' || (
                                isValue( next(), ',' ) &&
                                isNum( next() )
                            ) ) &&
                            isValue( next(), ')' ) ) {
                            return newToken( Type.COLOR, this.css.substring( ch, this.ch ) );
                        }
                        return failure();
                    }
                    return newToken( Type.FUNCTION, val );
                }
                return newToken( Type.IDENT, val );
            }
            this.ch++;
            return newToken( Type.CHARACTER, firstChar );
        },
        hasNext: function() {
            var next = this.next();
            this.prev();
            return !!next;
        },
        prev: function() {
            return this.tokens[ this.tokenIndex-- - 2 ];
        },
        all: function() {
            while( this.next() ) {}
            return this.tokens;
        },
        until: function( func, require ) {
            var list = [], t, hit;
            while( t = this.next() ) {
                if( func( t ) ) {
                    hit = true;
                    this.prev();
                    break;
                }
                list.push( t );
            }
            return require && !hit ? null : list;
        }
    };
    return Tokenizer;
})();
PIE.BoundsInfo = function( el ) {
    this.targetElement = el;
};
PIE.BoundsInfo.prototype = {
    _locked: 0,
    positionChanged: function() {
        var last = this._lastBounds,
            bounds;
        return !last || ( ( bounds = this.getBounds() ) && ( last.x !== bounds.x || last.y !== bounds.y ) );
    },
    sizeChanged: function() {
        var last = this._lastBounds,
            bounds;
        return !last || ( ( bounds = this.getBounds() ) && ( last.w !== bounds.w || last.h !== bounds.h ) );
    },
    getLiveBounds: function() {
        var el = this.targetElement,
            rect = el.getBoundingClientRect(),
            isIE9 = PIE.ieDocMode === 9;
        return {
            x: rect.left,
            y: rect.top,
            w: isIE9 ? el.offsetWidth : rect.right - rect.left,
            h: isIE9 ? el.offsetHeight : rect.bottom - rect.top
        };
    },
    getBounds: function() {
        return this._locked ? 
                ( this._lockedBounds || ( this._lockedBounds = this.getLiveBounds() ) ) :
                this.getLiveBounds();
    },
    hasBeenQueried: function() {
        return !!this._lastBounds;
    },
    lock: function() {
        ++this._locked;
    },
    unlock: function() {
        if( !--this._locked ) {
            if( this._lockedBounds ) this._lastBounds = this._lockedBounds;
            this._lockedBounds = null;
        }
    }
};
(function() {
function cacheWhenLocked( fn ) {
    var uid = PIE.Util.getUID( fn );
    return function() {
        if( this._locked ) {
            var cache = this._lockedValues || ( this._lockedValues = {} );
            return ( uid in cache ) ? cache[ uid ] : ( cache[ uid ] = fn.call( this ) );
        } else {
            return fn.call( this );
        }
    }
}
PIE.StyleInfoBase = {
    _locked: 0,
    newStyleInfo: function( proto ) {
        function StyleInfo( el ) {
            this.targetElement = el;
            this._lastCss = this.getCss();
        }
        PIE.Util.merge( StyleInfo.prototype, PIE.StyleInfoBase, proto );
        StyleInfo._propsCache = {};
        return StyleInfo;
    },
    getProps: function() {
        var css = this.getCss(),
            cache = this.constructor._propsCache;
        return css ? ( css in cache ? cache[ css ] : ( cache[ css ] = this.parseCss( css ) ) ) : null;
    },
    getCss: cacheWhenLocked( function() {
        var el = this.targetElement,
            ctor = this.constructor,
            s = el.style,
            cs = el.currentStyle,
            cssProp = this.cssProperty,
            styleProp = this.styleProperty,
            prefixedCssProp = ctor._prefixedCssProp || ( ctor._prefixedCssProp = PIE.CSS_PREFIX + cssProp ),
            prefixedStyleProp = ctor._prefixedStyleProp || ( ctor._prefixedStyleProp = PIE.STYLE_PREFIX + styleProp.charAt(0).toUpperCase() + styleProp.substring(1) );
        return s[ prefixedStyleProp ] || cs.getAttribute( prefixedCssProp ) || s[ styleProp ] || cs.getAttribute( cssProp );
    } ),
    isActive: cacheWhenLocked( function() {
        return !!this.getProps();
    } ),
    changed: cacheWhenLocked( function() {
        var currentCss = this.getCss(),
            changed = currentCss !== this._lastCss;
        this._lastCss = currentCss;
        return changed;
    } ),
    cacheWhenLocked: cacheWhenLocked,
    lock: function() {
        ++this._locked;
    },
    unlock: function() {
        if( !--this._locked ) {
            delete this._lockedValues;
        }
    }
};
})();
PIE.BackgroundStyleInfo = PIE.StyleInfoBase.newStyleInfo( {
    cssProperty: PIE.CSS_PREFIX + 'background',
    styleProperty: PIE.STYLE_PREFIX + 'Background',
    attachIdents: { 'scroll':1, 'fixed':1, 'local':1 },
    repeatIdents: { 'repeat-x':1, 'repeat-y':1, 'repeat':1, 'no-repeat':1 },
    originAndClipIdents: { 'padding-box':1, 'border-box':1, 'content-box':1 },
    positionIdents: { 'top':1, 'right':1, 'bottom':1, 'left':1, 'center':1 },
    sizeIdents: { 'contain':1, 'cover':1 },
    propertyNames: {
        CLIP: 'backgroundClip',
        COLOR: 'backgroundColor',
        IMAGE: 'backgroundImage',
        ORIGIN: 'backgroundOrigin',
        POSITION: 'backgroundPosition',
        REPEAT: 'backgroundRepeat',
        SIZE: 'backgroundSize'
    },
    parseCss: function( css ) {
        var el = this.targetElement,
            cs = el.currentStyle,
            tokenizer, token, image,
            tok_type = PIE.Tokenizer.Type,
            type_operator = tok_type.OPERATOR,
            type_ident = tok_type.IDENT,
            type_color = tok_type.COLOR,
            tokType, tokVal,
            beginCharIndex = 0,
            positionIdents = this.positionIdents,
            gradient, stop, width, height,
            props = { bgImages: [] };
        function isBgPosToken( token ) {
            return token && token.isLengthOrPercent() || ( token.tokenType & type_ident && token.tokenValue in positionIdents );
        }
        function sizeToken( token ) {
            return token && ( ( token.isLengthOrPercent() && PIE.getLength( token.tokenValue ) ) || ( token.tokenValue === 'auto' && 'auto' ) );
        }
        if( this.getCss3() ) {
            tokenizer = new PIE.Tokenizer( css );
            image = {};
            while( token = tokenizer.next() ) {
                tokType = token.tokenType;
                tokVal = token.tokenValue;
                if( !image.imgType && tokType & tok_type.FUNCTION && tokVal === 'linear-gradient' ) {
                    gradient = { stops: [], imgType: tokVal };
                    stop = {};
                    while( token = tokenizer.next() ) {
                        tokType = token.tokenType;
                        tokVal = token.tokenValue;
                        if( tokType & tok_type.CHARACTER && tokVal === ')' ) {
                            if( stop.color ) {
                                gradient.stops.push( stop );
                            }
                            if( gradient.stops.length > 1 ) {
                                PIE.Util.merge( image, gradient );
                            }
                            break;
                        }
                        if( tokType & type_color ) {
                            if( gradient.angle || gradient.gradientStart ) {
                                token = tokenizer.prev();
                                if( token.tokenType !== type_operator ) {
                                    break; 
                                }
                                tokenizer.next();
                            }
                            stop = {
                                color: PIE.getColor( tokVal )
                            };
                            token = tokenizer.next();
                            if( token.isLengthOrPercent() ) {
                                stop.offset = PIE.getLength( token.tokenValue );
                            } else {
                                tokenizer.prev();
                            }
                        }
                        else if( tokType & tok_type.ANGLE && !gradient.angle && !stop.color && !gradient.stops.length ) {
                            gradient.angle = new PIE.Angle( token.tokenValue );
                        }
                        else if( isBgPosToken( token ) && !gradient.gradientStart && !stop.color && !gradient.stops.length ) {
                            tokenizer.prev();
                            gradient.gradientStart = new PIE.BgPosition(
                                tokenizer.until( function( t ) {
                                    return !isBgPosToken( t );
                                }, false )
                            );
                        }
                        else if( tokType & type_operator && tokVal === ',' ) {
                            if( stop.color ) {
                                gradient.stops.push( stop );
                                stop = {};
                            }
                        }
                        else {
                            break;
                        }
                    }
                }
                else if( !image.imgType && tokType & tok_type.URL ) {
                    image.imgUrl = tokVal;
                    image.imgType = 'image';
                }
                else if( isBgPosToken( token ) && !image.bgPosition ) {
                    tokenizer.prev();
                    image.bgPosition = new PIE.BgPosition(
                        tokenizer.until( function( t ) {
                            return !isBgPosToken( t );
                        }, false )
                    );
                }
                else if( tokType & type_ident ) {
                    if( tokVal in this.repeatIdents && !image.imgRepeat ) {
                        image.imgRepeat = tokVal;
                    }
                    else if( tokVal in this.originAndClipIdents && !image.bgOrigin ) {
                        image.bgOrigin = tokVal;
                        if( ( token = tokenizer.next() ) && ( token.tokenType & type_ident ) &&
                            token.tokenValue in this.originAndClipIdents ) {
                            image.bgClip = token.tokenValue;
                        } else {
                            image.bgClip = tokVal;
                            tokenizer.prev();
                        }
                    }
                    else if( tokVal in this.attachIdents && !image.bgAttachment ) {
                        image.bgAttachment = tokVal;
                    }
                    else {
                        return null;
                    }
                }
                else if( tokType & type_color && !props.color ) {
                    props.color = PIE.getColor( tokVal );
                }
                else if( tokType & type_operator && tokVal === '/' && !image.bgSize && image.bgPosition ) {
                    token = tokenizer.next();
                    if( token.tokenType & type_ident && token.tokenValue in this.sizeIdents ) {
                        image.bgSize = new PIE.BgSize( token.tokenValue );
                    }
                    else if( width = sizeToken( token ) ) {
                        height = sizeToken( tokenizer.next() );
                        if ( !height ) {
                            height = width;
                            tokenizer.prev();
                        }
                        image.bgSize = new PIE.BgSize( width, height );
                    }
                    else {
                        return null;
                    }
                }
                else if( tokType & type_operator && tokVal === ',' && image.imgType ) {
                    image.origString = css.substring( beginCharIndex, tokenizer.ch - 1 );
                    beginCharIndex = tokenizer.ch;
                    props.bgImages.push( image );
                    image = {};
                }
                else {
                    return null;
                }
            }
            if( image.imgType ) {
                image.origString = css.substring( beginCharIndex );
                props.bgImages.push( image );
            }
        }
        else {
            this.withActualBg( PIE.ieDocMode < 9 ?
                function() {
                    var propNames = this.propertyNames,
                        posX = cs[propNames.POSITION + 'X'],
                        posY = cs[propNames.POSITION + 'Y'],
                        img = cs[propNames.IMAGE],
                        color = cs[propNames.COLOR];
                    if( color !== 'transparent' ) {
                        props.color = PIE.getColor( color )
                    }
                    if( img !== 'none' ) {
                        props.bgImages = [ {
                            imgType: 'image',
                            imgUrl: new PIE.Tokenizer( img ).next().tokenValue,
                            imgRepeat: cs[propNames.REPEAT],
                            bgPosition: new PIE.BgPosition( new PIE.Tokenizer( posX + ' ' + posY ).all() )
                        } ];
                    }
                } :
                function() {
                    var propNames = this.propertyNames,
                        splitter = /\s*,\s*/,
                        images = cs[propNames.IMAGE].split( splitter ),
                        color = cs[propNames.COLOR],
                        repeats, positions, origins, clips, sizes, i, len, image, sizeParts;
                    if( color !== 'transparent' ) {
                        props.color = PIE.getColor( color )
                    }
                    len = images.length;
                    if( len && images[0] !== 'none' ) {
                        repeats = cs[propNames.REPEAT].split( splitter );
                        positions = cs[propNames.POSITION].split( splitter );
                        origins = cs[propNames.ORIGIN].split( splitter );
                        clips = cs[propNames.CLIP].split( splitter );
                        sizes = cs[propNames.SIZE].split( splitter );
                        props.bgImages = [];
                        for( i = 0; i < len; i++ ) {
                            image = images[ i ];
                            if( image && image !== 'none' ) {
                                sizeParts = sizes[i].split( ' ' );
                                props.bgImages.push( {
                                    origString: image + ' ' + repeats[ i ] + ' ' + positions[ i ] + ' / ' + sizes[ i ] + ' ' +
                                                origins[ i ] + ' ' + clips[ i ],
                                    imgType: 'image',
                                    imgUrl: new PIE.Tokenizer( image ).next().tokenValue,
                                    imgRepeat: repeats[ i ],
                                    bgPosition: new PIE.BgPosition( new PIE.Tokenizer( positions[ i ] ).all() ),
                                    bgOrigin: origins[ i ],
                                    bgClip: clips[ i ],
                                    bgSize: new PIE.BgSize( sizeParts[ 0 ], sizeParts[ 1 ] )
                                } );
                            }
                        }
                    }
                }
            );
        }
        return ( props.color || props.bgImages[0] ) ? props : null;
    },
    withActualBg: function( fn ) {
        var isIE9 = PIE.ieDocMode > 8,
            propNames = this.propertyNames,
            rs = this.targetElement.runtimeStyle,
            rsImage = rs[propNames.IMAGE],
            rsColor = rs[propNames.COLOR],
            rsRepeat = rs[propNames.REPEAT],
            rsClip, rsOrigin, rsSize, rsPosition, ret;
        if( rsImage ) rs[propNames.IMAGE] = '';
        if( rsColor ) rs[propNames.COLOR] = '';
        if( rsRepeat ) rs[propNames.REPEAT] = '';
        if( isIE9 ) {
            rsClip = rs[propNames.CLIP];
            rsOrigin = rs[propNames.ORIGIN];
            rsPosition = rs[propNames.POSITION];
            rsSize = rs[propNames.SIZE];
            if( rsClip ) rs[propNames.CLIP] = '';
            if( rsOrigin ) rs[propNames.ORIGIN] = '';
            if( rsPosition ) rs[propNames.POSITION] = '';
            if( rsSize ) rs[propNames.SIZE] = '';
        }
        ret = fn.call( this );
        if( rsImage ) rs[propNames.IMAGE] = rsImage;
        if( rsColor ) rs[propNames.COLOR] = rsColor;
        if( rsRepeat ) rs[propNames.REPEAT] = rsRepeat;
        if( isIE9 ) {
            if( rsClip ) rs[propNames.CLIP] = rsClip;
            if( rsOrigin ) rs[propNames.ORIGIN] = rsOrigin;
            if( rsPosition ) rs[propNames.POSITION] = rsPosition;
            if( rsSize ) rs[propNames.SIZE] = rsSize;
        }
        return ret;
    },
    getCss: PIE.StyleInfoBase.cacheWhenLocked( function() {
        return this.getCss3() ||
               this.withActualBg( function() {
                   var cs = this.targetElement.currentStyle,
                       propNames = this.propertyNames;
                   return cs[propNames.COLOR] + ' ' + cs[propNames.IMAGE] + ' ' + cs[propNames.REPEAT] + ' ' +
                   cs[propNames.POSITION + 'X'] + ' ' + cs[propNames.POSITION + 'Y'];
               } );
    } ),
    getCss3: PIE.StyleInfoBase.cacheWhenLocked( function() {
        var el = this.targetElement;
        return el.style[ this.styleProperty ] || el.currentStyle.getAttribute( this.cssProperty );
    } ),
    isPngFix: function() {
        var val = 0, el;
        if( PIE.ieVersion < 7 ) {
            el = this.targetElement;
            val = ( '' + ( el.style[ PIE.STYLE_PREFIX + 'PngFix' ] || el.currentStyle.getAttribute( PIE.CSS_PREFIX + 'png-fix' ) ) === 'true' );
        }
        return val;
    },
    isActive: PIE.StyleInfoBase.cacheWhenLocked( function() {
        return (this.getCss3() || this.isPngFix()) && !!this.getProps();
    } )
} );
PIE.BorderStyleInfo = PIE.StyleInfoBase.newStyleInfo( {
    sides: [ 'Top', 'Right', 'Bottom', 'Left' ],
    namedWidths: {
        'thin': '1px',
        'medium': '3px',
        'thick': '5px'
    },
    parseCss: function( css ) {
        var w = {},
            s = {},
            c = {},
            active = false,
            colorsSame = true,
            stylesSame = true,
            widthsSame = true;
        this.withActualBorder( function() {
            var el = this.targetElement,
                cs = el.currentStyle,
                i = 0,
                style, color, width, lastStyle, lastColor, lastWidth, side, ltr;
            for( ; i < 4; i++ ) {
                side = this.sides[ i ];
                ltr = side.charAt(0).toLowerCase();
                style = s[ ltr ] = cs[ 'border' + side + 'Style' ];
                color = cs[ 'border' + side + 'Color' ];
                width = cs[ 'border' + side + 'Width' ];
                if( i > 0 ) {
                    if( style !== lastStyle ) { stylesSame = false; }
                    if( color !== lastColor ) { colorsSame = false; }
                    if( width !== lastWidth ) { widthsSame = false; }
                }
                lastStyle = style;
                lastColor = color;
                lastWidth = width;
                c[ ltr ] = PIE.getColor( color );
                width = w[ ltr ] = PIE.getLength( s[ ltr ] === 'none' ? '0' : ( this.namedWidths[ width ] || width ) );
                if( width.pixels( this.targetElement ) > 0 ) {
                    active = true;
                }
            }
        } );
        return active ? {
            widths: w,
            styles: s,
            colors: c,
            widthsSame: widthsSame,
            colorsSame: colorsSame,
            stylesSame: stylesSame
        } : null;
    },
    getCss: PIE.StyleInfoBase.cacheWhenLocked( function() {
        var el = this.targetElement,
            cs = el.currentStyle,
            css;
        if( !( el.tagName in PIE.tableCellTags && el.offsetParent.currentStyle.borderCollapse === 'collapse' ) ) {
            this.withActualBorder( function() {
                css = cs.borderWidth + '|' + cs.borderStyle + '|' + cs.borderColor;
            } );
        }
        return css;
    } ),
    withActualBorder: function( fn ) {
        var rs = this.targetElement.runtimeStyle,
            rsWidth = rs.borderWidth,
            rsColor = rs.borderColor,
            ret;
        if( rsWidth ) rs.borderWidth = '';
        if( rsColor ) rs.borderColor = '';
        ret = fn.call( this );
        if( rsWidth ) rs.borderWidth = rsWidth;
        if( rsColor ) rs.borderColor = rsColor;
        return ret;
    }
} );
(function() {
PIE.BorderRadiusStyleInfo = PIE.StyleInfoBase.newStyleInfo( {
    cssProperty: 'border-radius',
    styleProperty: 'borderRadius',
    parseCss: function( css ) {
        var p = null, x, y,
            tokenizer, token, length,
            hasNonZero = false;
        if( css ) {
            tokenizer = new PIE.Tokenizer( css );
            function collectLengths() {
                var arr = [], num;
                while( ( token = tokenizer.next() ) && token.isLengthOrPercent() ) {
                    length = PIE.getLength( token.tokenValue );
                    num = length.getNumber();
                    if( num < 0 ) {
                        return null;
                    }
                    if( num > 0 ) {
                        hasNonZero = true;
                    }
                    arr.push( length );
                }
                return arr.length > 0 && arr.length < 5 ? {
                        'tl': arr[0],
                        'tr': arr[1] || arr[0],
                        'br': arr[2] || arr[0],
                        'bl': arr[3] || arr[1] || arr[0]
                    } : null;
            }
            if( x = collectLengths() ) {
                if( token ) {
                    if( token.tokenType & PIE.Tokenizer.Type.OPERATOR && token.tokenValue === '/' ) {
                        y = collectLengths();
                    }
                } else {
                    y = x;
                }
                if( hasNonZero && x && y ) {
                    p = { x: x, y : y };
                }
            }
        }
        return p;
    }
} );
var zero = PIE.getLength( '0' ),
    zeros = { 'tl': zero, 'tr': zero, 'br': zero, 'bl': zero };
PIE.BorderRadiusStyleInfo.ALL_ZERO = { x: zeros, y: zeros };
})();
PIE.BorderImageStyleInfo = PIE.StyleInfoBase.newStyleInfo( {
    cssProperty: 'border-image',
    styleProperty: 'borderImage',
    repeatIdents: { 'stretch':1, 'round':1, 'repeat':1, 'space':1 },
    parseCss: function( css ) {
        var p = null, tokenizer, token, type, value,
            slices, widths, outsets,
            slashCount = 0,
            Type = PIE.Tokenizer.Type,
            IDENT = Type.IDENT,
            NUMBER = Type.NUMBER,
            PERCENT = Type.PERCENT;
        if( css ) {
            tokenizer = new PIE.Tokenizer( css );
            p = {};
            function isSlash( token ) {
                return token && ( token.tokenType & Type.OPERATOR ) && ( token.tokenValue === '/' );
            }
            function isFillIdent( token ) {
                return token && ( token.tokenType & IDENT ) && ( token.tokenValue === 'fill' );
            }
            function collectSlicesEtc() {
                slices = tokenizer.until( function( tok ) {
                    return !( tok.tokenType & ( NUMBER | PERCENT ) );
                } );
                if( isFillIdent( tokenizer.next() ) && !p.fill ) {
                    p.fill = true;
                } else {
                    tokenizer.prev();
                }
                if( isSlash( tokenizer.next() ) ) {
                    slashCount++;
                    widths = tokenizer.until( function( token ) {
                        return !token.isLengthOrPercent() && !( ( token.tokenType & IDENT ) && token.tokenValue === 'auto' );
                    } );
                    if( isSlash( tokenizer.next() ) ) {
                        slashCount++;
                        outsets = tokenizer.until( function( token ) {
                            return !token.isLength();
                        } );
                    }
                } else {
                    tokenizer.prev();
                }
            }
            while( token = tokenizer.next() ) {
                type = token.tokenType;
                value = token.tokenValue;
                if( type & ( NUMBER | PERCENT ) && !slices ) {
                    tokenizer.prev();
                    collectSlicesEtc();
                }
                else if( isFillIdent( token ) && !p.fill ) {
                    p.fill = true;
                    collectSlicesEtc();
                }
                else if( ( type & IDENT ) && this.repeatIdents[value] && !p.repeat ) {
                    p.repeat = { h: value };
                    if( token = tokenizer.next() ) {
                        if( ( token.tokenType & IDENT ) && this.repeatIdents[token.tokenValue] ) {
                            p.repeat.v = token.tokenValue;
                        } else {
                            tokenizer.prev();
                        }
                    }
                }
                else if( ( type & Type.URL ) && !p.src ) {
                    p.src =  value;
                }
                else {
                    return null;
                }
            }
            if( !p.src || !slices || slices.length < 1 || slices.length > 4 ||
                ( widths && widths.length > 4 ) || ( slashCount === 1 && widths.length < 1 ) ||
                ( outsets && outsets.length > 4 ) || ( slashCount === 2 && outsets.length < 1 ) ) {
                return null;
            }
            if( !p.repeat ) {
                p.repeat = { h: 'stretch' };
            }
            if( !p.repeat.v ) {
                p.repeat.v = p.repeat.h;
            }
            function distributeSides( tokens, convertFn ) {
                return {
                    't': convertFn( tokens[0] ),
                    'r': convertFn( tokens[1] || tokens[0] ),
                    'b': convertFn( tokens[2] || tokens[0] ),
                    'l': convertFn( tokens[3] || tokens[1] || tokens[0] )
                };
            }
            p.slice = distributeSides( slices, function( tok ) {
                return PIE.getLength( ( tok.tokenType & NUMBER ) ? tok.tokenValue + 'px' : tok.tokenValue );
            } );
            if( widths && widths[0] ) {
                p.widths = distributeSides( widths, function( tok ) {
                    return tok.isLengthOrPercent() ? PIE.getLength( tok.tokenValue ) : tok.tokenValue;
                } );
            }
            if( outsets && outsets[0] ) {
                p.outset = distributeSides( outsets, function( tok ) {
                    return tok.isLength() ? PIE.getLength( tok.tokenValue ) : tok.tokenValue;
                } );
            }
        }
        return p;
    }
} );
PIE.BoxShadowStyleInfo = PIE.StyleInfoBase.newStyleInfo( {
    cssProperty: 'box-shadow',
    styleProperty: 'boxShadow',
    parseCss: function( css ) {
        var props,
            getLength = PIE.getLength,
            Type = PIE.Tokenizer.Type,
            tokenizer;
        if( css ) {
            tokenizer = new PIE.Tokenizer( css );
            props = { outset: [], inset: [] };
            function parseItem() {
                var token, type, value, color, lengths, inset, len;
                while( token = tokenizer.next() ) {
                    value = token.tokenValue;
                    type = token.tokenType;
                    if( type & Type.OPERATOR && value === ',' ) {
                        break;
                    }
                    else if( token.isLength() && !lengths ) {
                        tokenizer.prev();
                        lengths = tokenizer.until( function( token ) {
                            return !token.isLength();
                        } );
                    }
                    else if( type & Type.COLOR && !color ) {
                        color = value;
                    }
                    else if( type & Type.IDENT && value === 'inset' && !inset ) {
                        inset = true;
                    }
                    else { 
                        return false;
                    }
                }
                len = lengths && lengths.length;
                if( len > 1 && len < 5 ) {
                    ( inset ? props.inset : props.outset ).push( {
                        xOffset: getLength( lengths[0].tokenValue ),
                        yOffset: getLength( lengths[1].tokenValue ),
                        blur: getLength( lengths[2] ? lengths[2].tokenValue : '0' ),
                        spread: getLength( lengths[3] ? lengths[3].tokenValue : '0' ),
                        color: PIE.getColor( color || 'currentColor' )
                    } );
                    return true;
                }
                return false;
            }
            while( parseItem() ) {}
        }
        return props && ( props.inset.length || props.outset.length ) ? props : null;
    }
} );
PIE.VisibilityStyleInfo = PIE.StyleInfoBase.newStyleInfo( {
    getCss: PIE.StyleInfoBase.cacheWhenLocked( function() {
        var cs = this.targetElement.currentStyle;
        return cs.visibility + '|' + cs.display;
    } ),
    parseCss: function() {
        var el = this.targetElement,
            rs = el.runtimeStyle,
            cs = el.currentStyle,
            rsVis = rs.visibility,
            csVis;
        rs.visibility = '';
        csVis = cs.visibility;
        rs.visibility = rsVis;
        return {
            visible: csVis !== 'hidden',
            displayed: cs.display !== 'none'
        }
    },
    isActive: function() {
        return false;
    }
} );
PIE.RendererBase = {
    newRenderer: function( proto ) {
        function Renderer( el, boundsInfo, styleInfos, parent ) {
            this.targetElement = el;
            this.boundsInfo = boundsInfo;
            this.styleInfos = styleInfos;
            this.parent = parent;
        }
        PIE.Util.merge( Renderer.prototype, PIE.RendererBase, proto );
        return Renderer;
    },
    isPositioned: false,
    needsUpdate: function() {
        return false;
    },
    prepareUpdate: PIE.emptyFn,
    updateProps: function() {
        this.destroy();
        if( this.isActive() ) {
            this.draw();
        }
    },
    updatePos: function() {
        this.isPositioned = true;
    },
    updateSize: function() {
        if( this.isActive() ) {
            this.draw();
        } else {
            this.destroy();
        }
    },
    addLayer: function( index, el ) {
        this.removeLayer( index );
        for( var layers = this._layers || ( this._layers = [] ), i = index + 1, len = layers.length, layer; i < len; i++ ) {
            layer = layers[i];
            if( layer ) {
                break;
            }
        }
        layers[index] = el;
        this.getBox().insertBefore( el, layer || null );
    },
    getLayer: function( index ) {
        var layers = this._layers;
        return layers && layers[index] || null;
    },
    removeLayer: function( index ) {
        var layer = this.getLayer( index ),
            box = this._box;
        if( layer && box ) {
            box.removeChild( layer );
            this._layers[index] = null;
        }
    },
    getShape: function( name, subElName, parent, group ) {
        var shapes = this._shapes || ( this._shapes = {} ),
            shape = shapes[ name ],
            s;
        if( !shape ) {
            shape = shapes[ name ] = PIE.Util.createVmlElement( 'shape' );
            if( subElName ) {
                shape.appendChild( shape[ subElName ] = PIE.Util.createVmlElement( subElName ) );
            }
            if( group ) {
                parent = this.getLayer( group );
                if( !parent ) {
                    this.addLayer( group, doc.createElement( 'group' + group ) );
                    parent = this.getLayer( group );
                }
            }
            parent.appendChild( shape );
            s = shape.style;
            s.position = 'absolute';
            s.left = s.top = 0;
            s['behavior'] = 'url(#default#VML)';
        }
        return shape;
    },
    deleteShape: function( name ) {
        var shapes = this._shapes,
            shape = shapes && shapes[ name ];
        if( shape ) {
            shape.parentNode.removeChild( shape );
            delete shapes[ name ];
        }
        return !!shape;
    },
    getRadiiPixels: function( radii ) {
        var el = this.targetElement,
            bounds = this.boundsInfo.getBounds(),
            w = bounds.w,
            h = bounds.h,
            tlX, tlY, trX, trY, brX, brY, blX, blY, f;
        tlX = radii.x['tl'].pixels( el, w );
        tlY = radii.y['tl'].pixels( el, h );
        trX = radii.x['tr'].pixels( el, w );
        trY = radii.y['tr'].pixels( el, h );
        brX = radii.x['br'].pixels( el, w );
        brY = radii.y['br'].pixels( el, h );
        blX = radii.x['bl'].pixels( el, w );
        blY = radii.y['bl'].pixels( el, h );
        f = Math.min(
            w / ( tlX + trX ),
            h / ( trY + brY ),
            w / ( blX + brX ),
            h / ( tlY + blY )
        );
        if( f < 1 ) {
            tlX *= f;
            tlY *= f;
            trX *= f;
            trY *= f;
            brX *= f;
            brY *= f;
            blX *= f;
            blY *= f;
        }
        return {
            x: {
                'tl': tlX,
                'tr': trX,
                'br': brX,
                'bl': blX
            },
            y: {
                'tl': tlY,
                'tr': trY,
                'br': brY,
                'bl': blY
            }
        }
    },
    getBoxPath: function( shrink, mult, radii ) {
        mult = mult || 1;
        var r, str,
            bounds = this.boundsInfo.getBounds(),
            w = bounds.w * mult,
            h = bounds.h * mult,
            radInfo = this.styleInfos.borderRadiusInfo,
            floor = Math.floor, ceil = Math.ceil,
            shrinkT = shrink ? shrink.t * mult : 0,
            shrinkR = shrink ? shrink.r * mult : 0,
            shrinkB = shrink ? shrink.b * mult : 0,
            shrinkL = shrink ? shrink.l * mult : 0,
            tlX, tlY, trX, trY, brX, brY, blX, blY;
        if( radii || radInfo.isActive() ) {
            r = this.getRadiiPixels( radii || radInfo.getProps() );
            tlX = r.x['tl'] * mult;
            tlY = r.y['tl'] * mult;
            trX = r.x['tr'] * mult;
            trY = r.y['tr'] * mult;
            brX = r.x['br'] * mult;
            brY = r.y['br'] * mult;
            blX = r.x['bl'] * mult;
            blY = r.y['bl'] * mult;
            str = 'm' + floor( shrinkL ) + ',' + floor( tlY ) +
                'qy' + floor( tlX ) + ',' + floor( shrinkT ) +
                'l' + ceil( w - trX ) + ',' + floor( shrinkT ) +
                'qx' + ceil( w - shrinkR ) + ',' + floor( trY ) +
                'l' + ceil( w - shrinkR ) + ',' + ceil( h - brY ) +
                'qy' + ceil( w - brX ) + ',' + ceil( h - shrinkB ) +
                'l' + floor( blX ) + ',' + ceil( h - shrinkB ) +
                'qx' + floor( shrinkL ) + ',' + ceil( h - blY ) + ' x e';
        } else {
            str = 'm' + floor( shrinkL ) + ',' + floor( shrinkT ) +
                  'l' + ceil( w - shrinkR ) + ',' + floor( shrinkT ) +
                  'l' + ceil( w - shrinkR ) + ',' + ceil( h - shrinkB ) +
                  'l' + floor( shrinkL ) + ',' + ceil( h - shrinkB ) +
                  'xe';
        }
        return str;
    },
    getBox: function() {
        var box = this.parent.getLayer( this.boxZIndex ), s;
        if( !box ) {
            box = doc.createElement( this.boxName );
            s = box.style;
            s.position = 'absolute';
            s.top = s.left = 0;
            this.parent.addLayer( this.boxZIndex, box );
        }
        return box;
    },
    hideBorder: function() {
        var el = this.targetElement,
            cs = el.currentStyle,
            rs = el.runtimeStyle,
            tag = el.tagName,
            isIE6 = PIE.ieVersion === 6,
            sides, side, i;
        if( ( isIE6 && ( tag in PIE.childlessElements || tag === 'FIELDSET' ) ) ||
                tag === 'BUTTON' || ( tag === 'INPUT' && el.type in PIE.inputButtonTypes ) ) {
            rs.borderWidth = '';
            sides = this.styleInfos.borderInfo.sides;
            for( i = sides.length; i--; ) {
                side = sides[ i ];
                rs[ 'padding' + side ] = '';
                rs[ 'padding' + side ] = ( PIE.getLength( cs[ 'padding' + side ] ) ).pixels( el ) +
                                         ( PIE.getLength( cs[ 'border' + side + 'Width' ] ) ).pixels( el ) +
                                         ( PIE.ieVersion !== 8 && i % 2 ? 1 : 0 ); 
            }
            rs.borderWidth = 0;
        }
        else if( isIE6 ) {
            if( el.childNodes.length !== 1 || el.firstChild.tagName !== 'ie6-mask' ) {
                var cont = doc.createElement( 'ie6-mask' ),
                    s = cont.style, child;
                s.visibility = 'visible';
                s.zoom = 1;
                while( child = el.firstChild ) {
                    cont.appendChild( child );
                }
                el.appendChild( cont );
                rs.visibility = 'hidden';
            }
        }
        else {
            rs.borderColor = 'transparent';
        }
    },
    unhideBorder: function() {
    },
    destroy: function() {
        this.parent.removeLayer( this.boxZIndex );
        delete this._shapes;
        delete this._layers;
    }
};
PIE.RootRenderer = PIE.RendererBase.newRenderer( {
    isActive: function() {
        var children = this.childRenderers;
        for( var i in children ) {
            if( children.hasOwnProperty( i ) && children[ i ].isActive() ) {
                return true;
            }
        }
        return false;
    },
    needsUpdate: function() {
        return this.styleInfos.visibilityInfo.changed();
    },
    updatePos: function() {
        if( this.isActive() ) {
            var el = this.getPositioningElement(),
                par = el,
                docEl,
                parRect,
                tgtCS = el.currentStyle,
                tgtPos = tgtCS.position,
                boxPos,
                s = this.getBox().style, cs,
                x = 0, y = 0,
                elBounds = this.boundsInfo.getBounds();
            if( tgtPos === 'fixed' && PIE.ieVersion > 6 ) {
                x = elBounds.x;
                y = elBounds.y;
                boxPos = tgtPos;
            } else {
                do {
                    par = par.offsetParent;
                } while( par && ( par.currentStyle.position === 'static' ) );
                if( par ) {
                    parRect = par.getBoundingClientRect();
                    cs = par.currentStyle;
                    x = elBounds.x - parRect.left - ( parseFloat(cs.borderLeftWidth) || 0 );
                    y = elBounds.y - parRect.top - ( parseFloat(cs.borderTopWidth) || 0 );
                } else {
                    docEl = doc.documentElement;
                    x = elBounds.x + docEl.scrollLeft - docEl.clientLeft;
                    y = elBounds.y + docEl.scrollTop - docEl.clientTop;
                }
                boxPos = 'absolute';
            }
            s.position = boxPos;
            s.left = x;
            s.top = y;
            s.zIndex = tgtPos === 'static' ? -1 : tgtCS.zIndex;
            this.isPositioned = true;
        }
    },
    updateSize: PIE.emptyFn,
    updateVisibility: function() {
        var vis = this.styleInfos.visibilityInfo.getProps();
        this.getBox().style.display = ( vis.visible && vis.displayed ) ? '' : 'none';
    },
    updateProps: function() {
        if( this.isActive() ) {
            this.updateVisibility();
        } else {
            this.destroy();
        }
    },
    getPositioningElement: function() {
        var el = this.targetElement;
        return el.tagName in PIE.tableCellTags ? el.offsetParent : el;
    },
    getBox: function() {
        var box = this._box, el;
        if( !box ) {
            el = this.getPositioningElement();
            box = this._box = doc.createElement( 'css3-container' );
            box.style['direction'] = 'ltr'; 
            this.updateVisibility();
            el.parentNode.insertBefore( box, el );
        }
        return box;
    },
    finishUpdate: PIE.emptyFn,
    destroy: function() {
        var box = this._box, par;
        if( box && ( par = box.parentNode ) ) {
            par.removeChild( box );
        }
        delete this._box;
        delete this._layers;
    }
} );
PIE.BackgroundRenderer = PIE.RendererBase.newRenderer( {
    boxZIndex: 2,
    boxName: 'background',
    needsUpdate: function() {
        var si = this.styleInfos;
        return si.backgroundInfo.changed() || si.borderRadiusInfo.changed();
    },
    isActive: function() {
        var si = this.styleInfos;
        return si.borderImageInfo.isActive() ||
               si.borderRadiusInfo.isActive() ||
               si.backgroundInfo.isActive() ||
               ( si.boxShadowInfo.isActive() && si.boxShadowInfo.getProps().inset );
    },
    draw: function() {
        var bounds = this.boundsInfo.getBounds();
        if( bounds.w && bounds.h ) {
            this.drawBgColor();
            this.drawBgImages();
        }
    },
    drawBgColor: function() {
        var props = this.styleInfos.backgroundInfo.getProps(),
            bounds = this.boundsInfo.getBounds(),
            el = this.targetElement,
            color = props && props.color,
            shape, w, h, s, alpha;
        if( color && color.alpha() > 0 ) {
            this.hideBackground();
            shape = this.getShape( 'bgColor', 'fill', this.getBox(), 1 );
            w = bounds.w;
            h = bounds.h;
            shape.stroked = false;
            shape.coordsize = w * 2 + ',' + h * 2;
            shape.coordorigin = '1,1';
            shape.path = this.getBoxPath( null, 2 );
            s = shape.style;
            s.width = w;
            s.height = h;
            shape.fill.color = color.colorValue( el );
            alpha = color.alpha();
            if( alpha < 1 ) {
                shape.fill.opacity = alpha;
            }
        } else {
            this.deleteShape( 'bgColor' );
        }
    },
    drawBgImages: function() {
        var props = this.styleInfos.backgroundInfo.getProps(),
            bounds = this.boundsInfo.getBounds(),
            images = props && props.bgImages,
            img, shape, w, h, s, i;
        if( images ) {
            this.hideBackground();
            w = bounds.w;
            h = bounds.h;
            i = images.length;
            while( i-- ) {
                img = images[i];
                shape = this.getShape( 'bgImage' + i, 'fill', this.getBox(), 2 );
                shape.stroked = false;
                shape.fill.type = 'tile';
                shape.fillcolor = 'none';
                shape.coordsize = w * 2 + ',' + h * 2;
                shape.coordorigin = '1,1';
                shape.path = this.getBoxPath( 0, 2 );
                s = shape.style;
                s.width = w;
                s.height = h;
                if( img.imgType === 'linear-gradient' ) {
                    this.addLinearGradient( shape, img );
                }
                else {
                    shape.fill.src = img.imgUrl;
                    this.positionBgImage( shape, i );
                }
            }
        }
        i = images ? images.length : 0;
        while( this.deleteShape( 'bgImage' + i++ ) ) {}
    },
    positionBgImage: function( shape, index ) {
        var me = this;
        PIE.Util.withImageSize( shape.fill.src, function( size ) {
            var el = me.targetElement,
                bounds = me.boundsInfo.getBounds(),
                elW = bounds.w,
                elH = bounds.h;
            if( elW && elH ) {
                var fill = shape.fill,
                    si = me.styleInfos,
                    border = si.borderInfo.getProps(),
                    bw = border && border.widths,
                    bwT = bw ? bw['t'].pixels( el ) : 0,
                    bwR = bw ? bw['r'].pixels( el ) : 0,
                    bwB = bw ? bw['b'].pixels( el ) : 0,
                    bwL = bw ? bw['l'].pixels( el ) : 0,
                    bg = si.backgroundInfo.getProps().bgImages[ index ],
                    bgPos = bg.bgPosition ? bg.bgPosition.coords( el, elW - size.w - bwL - bwR, elH - size.h - bwT - bwB ) : { x:0, y:0 },
                    repeat = bg.imgRepeat,
                    pxX, pxY,
                    clipT = 0, clipL = 0,
                    clipR = elW + 1, clipB = elH + 1, 
                    clipAdjust = PIE.ieVersion === 8 ? 0 : 1; 
                pxX = Math.round( bgPos.x ) + bwL + 0.5;
                pxY = Math.round( bgPos.y ) + bwT + 0.5;
                fill.position = ( pxX / elW ) + ',' + ( pxY / elH );
                if( repeat && repeat !== 'repeat' ) {
                    if( repeat === 'repeat-x' || repeat === 'no-repeat' ) {
                        clipT = pxY + 1;
                        clipB = pxY + size.h + clipAdjust;
                    }
                    if( repeat === 'repeat-y' || repeat === 'no-repeat' ) {
                        clipL = pxX + 1;
                        clipR = pxX + size.w + clipAdjust;
                    }
                    shape.style.clip = 'rect(' + clipT + 'px,' + clipR + 'px,' + clipB + 'px,' + clipL + 'px)';
                }
            }
        } );
    },
    addLinearGradient: function( shape, info ) {
        var el = this.targetElement,
            bounds = this.boundsInfo.getBounds(),
            w = bounds.w,
            h = bounds.h,
            fill = shape.fill,
            stops = info.stops,
            stopCount = stops.length,
            PI = Math.PI,
            GradientUtil = PIE.GradientUtil,
            perpendicularIntersect = GradientUtil.perpendicularIntersect,
            distance = GradientUtil.distance,
            metrics = GradientUtil.getGradientMetrics( el, w, h, info ),
            angle = metrics.angle,
            startX = metrics.startX,
            startY = metrics.startY,
            startCornerX = metrics.startCornerX,
            startCornerY = metrics.startCornerY,
            endCornerX = metrics.endCornerX,
            endCornerY = metrics.endCornerY,
            deltaX = metrics.deltaX,
            deltaY = metrics.deltaY,
            lineLength = metrics.lineLength,
            vmlAngle, vmlGradientLength, vmlColors,
            stopPx, vmlOffsetPct,
            p, i, j, before, after;
        vmlAngle = ( angle % 90 ) ? Math.atan2( deltaX * w / h, deltaY ) / PI * 180 : ( angle + 90 );
        vmlAngle += 180;
        vmlAngle = vmlAngle % 360;
        p = perpendicularIntersect( startCornerX, startCornerY, angle, endCornerX, endCornerY );
        vmlGradientLength = distance( startCornerX, startCornerY, p[0], p[1] );
        vmlColors = [];
        p = perpendicularIntersect( startX, startY, angle, startCornerX, startCornerY );
        vmlOffsetPct = distance( startX, startY, p[0], p[1] ) / vmlGradientLength * 100;
        stopPx = [];
        for( i = 0; i < stopCount; i++ ) {
            stopPx.push( stops[i].offset ? stops[i].offset.pixels( el, lineLength ) :
                         i === 0 ? 0 : i === stopCount - 1 ? lineLength : null );
        }
        for( i = 1; i < stopCount; i++ ) {
            if( stopPx[ i ] === null ) {
                before = stopPx[ i - 1 ];
                j = i;
                do {
                    after = stopPx[ ++j ];
                } while( after === null );
                stopPx[ i ] = before + ( after - before ) / ( j - i + 1 );
            }
            stopPx[ i ] = Math.max( stopPx[ i ], stopPx[ i - 1 ] );
        }
        for( i = 0; i < stopCount; i++ ) {
            vmlColors.push(
                ( vmlOffsetPct + ( stopPx[ i ] / vmlGradientLength * 100 ) ) + '% ' + stops[i].color.colorValue( el )
            );
        }
        fill['angle'] = vmlAngle;
        fill['type'] = 'gradient';
        fill['method'] = 'sigma';
        fill['color'] = stops[0].color.colorValue( el );
        fill['color2'] = stops[stopCount - 1].color.colorValue( el );
        if( fill['colors'] ) { 
            fill['colors'].value = vmlColors.join( ',' );
        } else {
            fill['colors'] = vmlColors.join( ',' );
        }
    },
    hideBackground: function() {
        var rs = this.targetElement.runtimeStyle;
        rs.backgroundImage = 'url(about:blank)'; 
        rs.backgroundColor = 'transparent';
    },
    destroy: function() {
        PIE.RendererBase.destroy.call( this );
        var rs = this.targetElement.runtimeStyle;
        rs.backgroundImage = rs.backgroundColor = '';
    }
} );
PIE.BorderRenderer = PIE.RendererBase.newRenderer( {
    boxZIndex: 4,
    boxName: 'border',
    needsUpdate: function() {
        var si = this.styleInfos;
        return si.borderInfo.changed() || si.borderRadiusInfo.changed();
    },
    isActive: function() {
        var si = this.styleInfos;
        return ( si.borderRadiusInfo.isActive() ||
                 si.backgroundInfo.isActive() ) &&
               !si.borderImageInfo.isActive() &&
               si.borderInfo.isActive(); 
    },
    draw: function() {
        var el = this.targetElement,
            props = this.styleInfos.borderInfo.getProps(),
            bounds = this.boundsInfo.getBounds(),
            w = bounds.w,
            h = bounds.h,
            shape, stroke, s,
            segments, seg, i, len;
        if( props ) {
            this.hideBorder();
            segments = this.getBorderSegments( 2 );
            for( i = 0, len = segments.length; i < len; i++) {
                seg = segments[i];
                shape = this.getShape( 'borderPiece' + i, seg.stroke ? 'stroke' : 'fill', this.getBox() );
                shape.coordsize = w * 2 + ',' + h * 2;
                shape.coordorigin = '1,1';
                shape.path = seg.path;
                s = shape.style;
                s.width = w;
                s.height = h;
                shape.filled = !!seg.fill;
                shape.stroked = !!seg.stroke;
                if( seg.stroke ) {
                    stroke = shape.stroke;
                    stroke['weight'] = seg.weight + 'px';
                    stroke.color = seg.color.colorValue( el );
                    stroke['dashstyle'] = seg.stroke === 'dashed' ? '2 2' : seg.stroke === 'dotted' ? '1 1' : 'solid';
                    stroke['linestyle'] = seg.stroke === 'double' && seg.weight > 2 ? 'ThinThin' : 'Single';
                } else {
                    shape.fill.color = seg.fill.colorValue( el );
                }
            }
            while( this.deleteShape( 'borderPiece' + i++ ) ) {}
        }
    },
    getBorderSegments: function( mult ) {
        var el = this.targetElement,
            bounds, elW, elH,
            borderInfo = this.styleInfos.borderInfo,
            segments = [],
            floor, ceil, wT, wR, wB, wL,
            round = Math.round,
            borderProps, radiusInfo, radii, widths, styles, colors;
        if( borderInfo.isActive() ) {
            borderProps = borderInfo.getProps();
            widths = borderProps.widths;
            styles = borderProps.styles;
            colors = borderProps.colors;
            if( borderProps.widthsSame && borderProps.stylesSame && borderProps.colorsSame ) {
                if( colors['t'].alpha() > 0 ) {
                    wT = widths['t'].pixels( el ); 
                    wR = wT / 2; 
                    segments.push( {
                        path: this.getBoxPath( { t: wR, r: wR, b: wR, l: wR }, mult ),
                        stroke: styles['t'],
                        color: colors['t'],
                        weight: wT
                    } );
                }
            }
            else {
                mult = mult || 1;
                bounds = this.boundsInfo.getBounds();
                elW = bounds.w;
                elH = bounds.h;
                wT = round( widths['t'].pixels( el ) );
                wR = round( widths['r'].pixels( el ) );
                wB = round( widths['b'].pixels( el ) );
                wL = round( widths['l'].pixels( el ) );
                var pxWidths = {
                    't': wT,
                    'r': wR,
                    'b': wB,
                    'l': wL
                };
                radiusInfo = this.styleInfos.borderRadiusInfo;
                if( radiusInfo.isActive() ) {
                    radii = this.getRadiiPixels( radiusInfo.getProps() );
                }
                floor = Math.floor;
                ceil = Math.ceil;
                function radius( xy, corner ) {
                    return radii ? radii[ xy ][ corner ] : 0;
                }
                function curve( corner, shrinkX, shrinkY, startAngle, ccw, doMove ) {
                    var rx = radius( 'x', corner),
                        ry = radius( 'y', corner),
                        deg = 65535,
                        isRight = corner.charAt( 1 ) === 'r',
                        isBottom = corner.charAt( 0 ) === 'b';
                    return ( rx > 0 && ry > 0 ) ?
                                ( doMove ? 'al' : 'ae' ) +
                                ( isRight ? ceil( elW - rx ) : floor( rx ) ) * mult + ',' + 
                                ( isBottom ? ceil( elH - ry ) : floor( ry ) ) * mult + ',' + 
                                ( floor( rx ) - shrinkX ) * mult + ',' + 
                                ( floor( ry ) - shrinkY ) * mult + ',' + 
                                ( startAngle * deg ) + ',' + 
                                ( 45 * deg * ( ccw ? 1 : -1 ) 
                            ) : (
                                ( doMove ? 'm' : 'l' ) +
                                ( isRight ? elW - shrinkX : shrinkX ) * mult + ',' +
                                ( isBottom ? elH - shrinkY : shrinkY ) * mult
                            );
                }
                function line( side, shrink, ccw, doMove ) {
                    var
                        start = (
                            side === 't' ?
                                floor( radius( 'x', 'tl') ) * mult + ',' + ceil( shrink ) * mult :
                            side === 'r' ?
                                ceil( elW - shrink ) * mult + ',' + floor( radius( 'y', 'tr') ) * mult :
                            side === 'b' ?
                                ceil( elW - radius( 'x', 'br') ) * mult + ',' + floor( elH - shrink ) * mult :
                                floor( shrink ) * mult + ',' + ceil( elH - radius( 'y', 'bl') ) * mult
                        ),
                        end = (
                            side === 't' ?
                                ceil( elW - radius( 'x', 'tr') ) * mult + ',' + ceil( shrink ) * mult :
                            side === 'r' ?
                                ceil( elW - shrink ) * mult + ',' + ceil( elH - radius( 'y', 'br') ) * mult :
                            side === 'b' ?
                                floor( radius( 'x', 'bl') ) * mult + ',' + floor( elH - shrink ) * mult :
                                floor( shrink ) * mult + ',' + floor( radius( 'y', 'tl') ) * mult
                        );
                    return ccw ? ( doMove ? 'm' + end : '' ) + 'l' + start :
                                 ( doMove ? 'm' + start : '' ) + 'l' + end;
                }
                function addSide( side, sideBefore, sideAfter, cornerBefore, cornerAfter, baseAngle ) {
                    var vert = side === 'l' || side === 'r',
                        sideW = pxWidths[ side ],
                        beforeX, beforeY, afterX, afterY;
                    if( sideW > 0 && styles[ side ] !== 'none' && colors[ side ].alpha() > 0 ) {
                        beforeX = pxWidths[ vert ? side : sideBefore ];
                        beforeY = pxWidths[ vert ? sideBefore : side ];
                        afterX = pxWidths[ vert ? side : sideAfter ];
                        afterY = pxWidths[ vert ? sideAfter : side ];
                        if( styles[ side ] === 'dashed' || styles[ side ] === 'dotted' ) {
                            segments.push( {
                                path: curve( cornerBefore, beforeX, beforeY, baseAngle + 45, 0, 1 ) +
                                      curve( cornerBefore, 0, 0, baseAngle, 1, 0 ),
                                fill: colors[ side ]
                            } );
                            segments.push( {
                                path: line( side, sideW / 2, 0, 1 ),
                                stroke: styles[ side ],
                                weight: sideW,
                                color: colors[ side ]
                            } );
                            segments.push( {
                                path: curve( cornerAfter, afterX, afterY, baseAngle, 0, 1 ) +
                                      curve( cornerAfter, 0, 0, baseAngle - 45, 1, 0 ),
                                fill: colors[ side ]
                            } );
                        }
                        else {
                            segments.push( {
                                path: curve( cornerBefore, beforeX, beforeY, baseAngle + 45, 0, 1 ) +
                                      line( side, sideW, 0, 0 ) +
                                      curve( cornerAfter, afterX, afterY, baseAngle, 0, 0 ) +
                                      ( styles[ side ] === 'double' && sideW > 2 ?
                                              curve( cornerAfter, afterX - floor( afterX / 3 ), afterY - floor( afterY / 3 ), baseAngle - 45, 1, 0 ) +
                                              line( side, ceil( sideW / 3 * 2 ), 1, 0 ) +
                                              curve( cornerBefore, beforeX - floor( beforeX / 3 ), beforeY - floor( beforeY / 3 ), baseAngle, 1, 0 ) +
                                              'x ' +
                                              curve( cornerBefore, floor( beforeX / 3 ), floor( beforeY / 3 ), baseAngle + 45, 0, 1 ) +
                                              line( side, floor( sideW / 3 ), 1, 0 ) +
                                              curve( cornerAfter, floor( afterX / 3 ), floor( afterY / 3 ), baseAngle, 0, 0 )
                                          : '' ) +
                                      curve( cornerAfter, 0, 0, baseAngle - 45, 1, 0 ) +
                                      line( side, 0, 1, 0 ) +
                                      curve( cornerBefore, 0, 0, baseAngle, 1, 0 ),
                                fill: colors[ side ]
                            } );
                        }
                    }
                }
                addSide( 't', 'l', 'r', 'tl', 'tr', 90 );
                addSide( 'r', 't', 'b', 'tr', 'br', 0 );
                addSide( 'b', 'r', 'l', 'br', 'bl', -90 );
                addSide( 'l', 'b', 't', 'bl', 'tl', -180 );
            }
        }
        return segments;
    },
    destroy: function() {
        var me = this;
        if (me.finalized || !me.styleInfos.borderImageInfo.isActive()) {
            me.targetElement.runtimeStyle.borderColor = '';
        }
        PIE.RendererBase.destroy.call( me );
    }
} );
PIE.BorderImageRenderer = PIE.RendererBase.newRenderer( {
    boxZIndex: 5,
    pieceNames: [ 't', 'tr', 'r', 'br', 'b', 'bl', 'l', 'tl', 'c' ],
    needsUpdate: function() {
        return this.styleInfos.borderImageInfo.changed();
    },
    isActive: function() {
        return this.styleInfos.borderImageInfo.isActive();
    },
    draw: function() {
        this.getBox(); 
        var props = this.styleInfos.borderImageInfo.getProps(),
            borderProps = this.styleInfos.borderInfo.getProps(),
            bounds = this.boundsInfo.getBounds(),
            el = this.targetElement,
            pieces = this.pieces;
        PIE.Util.withImageSize( props.src, function( imgSize ) {
            var elW = bounds.w,
                elH = bounds.h,
                zero = PIE.getLength( '0' ),
                widths = props.widths || ( borderProps ? borderProps.widths : { 't': zero, 'r': zero, 'b': zero, 'l': zero } ),
                widthT = widths['t'].pixels( el ),
                widthR = widths['r'].pixels( el ),
                widthB = widths['b'].pixels( el ),
                widthL = widths['l'].pixels( el ),
                slices = props.slice,
                sliceT = slices['t'].pixels( el ),
                sliceR = slices['r'].pixels( el ),
                sliceB = slices['b'].pixels( el ),
                sliceL = slices['l'].pixels( el );
            function setSizeAndPos( piece, w, h, x, y ) {
                var s = pieces[piece].style,
                    max = Math.max;
                s.width = max(w, 0);
                s.height = max(h, 0);
                s.left = x;
                s.top = y;
            }
            setSizeAndPos( 'tl', widthL, widthT, 0, 0 );
            setSizeAndPos( 't', elW - widthL - widthR, widthT, widthL, 0 );
            setSizeAndPos( 'tr', widthR, widthT, elW - widthR, 0 );
            setSizeAndPos( 'r', widthR, elH - widthT - widthB, elW - widthR, widthT );
            setSizeAndPos( 'br', widthR, widthB, elW - widthR, elH - widthB );
            setSizeAndPos( 'b', elW - widthL - widthR, widthB, widthL, elH - widthB );
            setSizeAndPos( 'bl', widthL, widthB, 0, elH - widthB );
            setSizeAndPos( 'l', widthL, elH - widthT - widthB, 0, widthT );
            setSizeAndPos( 'c', elW - widthL - widthR, elH - widthT - widthB, widthL, widthT );
            function setCrops( sides, crop, val ) {
                for( var i=0, len=sides.length; i < len; i++ ) {
                    pieces[ sides[i] ]['imagedata'][ crop ] = val;
                }
            }
            setCrops( [ 'tl', 't', 'tr' ], 'cropBottom', ( imgSize.h - sliceT ) / imgSize.h );
            setCrops( [ 'tl', 'l', 'bl' ], 'cropRight', ( imgSize.w - sliceL ) / imgSize.w );
            setCrops( [ 'bl', 'b', 'br' ], 'cropTop', ( imgSize.h - sliceB ) / imgSize.h );
            setCrops( [ 'tr', 'r', 'br' ], 'cropLeft', ( imgSize.w - sliceR ) / imgSize.w );
                setCrops( [ 'l', 'r', 'c' ], 'cropTop', sliceT / imgSize.h );
                setCrops( [ 'l', 'r', 'c' ], 'cropBottom', sliceB / imgSize.h );
                setCrops( [ 't', 'b', 'c' ], 'cropLeft', sliceL / imgSize.w );
                setCrops( [ 't', 'b', 'c' ], 'cropRight', sliceR / imgSize.w );
            pieces['c'].style.display = props.fill ? '' : 'none';
        }, this );
    },
    getBox: function() {
        var box = this.parent.getLayer( this.boxZIndex ),
            s, piece, i,
            pieceNames = this.pieceNames,
            len = pieceNames.length;
        if( !box ) {
            box = doc.createElement( 'border-image' );
            s = box.style;
            s.position = 'absolute';
            this.pieces = {};
            for( i = 0; i < len; i++ ) {
                piece = this.pieces[ pieceNames[i] ] = PIE.Util.createVmlElement( 'rect' );
                piece.appendChild( PIE.Util.createVmlElement( 'imagedata' ) );
                s = piece.style;
                s['behavior'] = 'url(#default#VML)';
                s.position = "absolute";
                s.top = s.left = 0;
                piece['imagedata'].src = this.styleInfos.borderImageInfo.getProps().src;
                piece.stroked = false;
                piece.filled = false;
                box.appendChild( piece );
            }
            this.parent.addLayer( this.boxZIndex, box );
        }
        return box;
    },
    prepareUpdate: function() {
        if (this.isActive()) {
            var me = this,
                el = me.targetElement,
                rs = el.runtimeStyle,
                widths = me.styleInfos.borderImageInfo.getProps().widths;
            rs.borderStyle = 'solid';
            if ( widths ) {
                rs.borderTopWidth = widths['t'].pixels( el ) + 'px';
                rs.borderRightWidth = widths['r'].pixels( el ) + 'px';
                rs.borderBottomWidth = widths['b'].pixels( el ) + 'px';
                rs.borderLeftWidth = widths['l'].pixels( el ) + 'px';
            }
            me.hideBorder();
        }
    },
    destroy: function() {
        var me = this,
            rs = me.targetElement.runtimeStyle;
        rs.borderStyle = '';
        if (me.finalized || !me.styleInfos.borderInfo.isActive()) {
            rs.borderColor = rs.borderWidth = '';
        }
        PIE.RendererBase.destroy.call( this );
    }
} );
PIE.BoxShadowOutsetRenderer = PIE.RendererBase.newRenderer( {
    boxZIndex: 1,
    boxName: 'outset-box-shadow',
    needsUpdate: function() {
        var si = this.styleInfos;
        return si.boxShadowInfo.changed() || si.borderRadiusInfo.changed();
    },
    isActive: function() {
        var boxShadowInfo = this.styleInfos.boxShadowInfo;
        return boxShadowInfo.isActive() && boxShadowInfo.getProps().outset[0];
    },
    draw: function() {
        var me = this,
            el = this.targetElement,
            box = this.getBox(),
            styleInfos = this.styleInfos,
            shadowInfos = styleInfos.boxShadowInfo.getProps().outset,
            radii = styleInfos.borderRadiusInfo.getProps(),
            len = shadowInfos.length,
            i = len, j,
            bounds = this.boundsInfo.getBounds(),
            w = bounds.w,
            h = bounds.h,
            clipAdjust = PIE.ieVersion === 8 ? 1 : 0, 
            corners = [ 'tl', 'tr', 'br', 'bl' ], corner,
            shadowInfo, shape, fill, ss, xOff, yOff, spread, blur, shrink, color, alpha, path,
            totalW, totalH, focusX, focusY, isBottom, isRight;
        function getShadowShape( index, corner, xOff, yOff, color, blur, path ) {
            var shape = me.getShape( 'shadow' + index + corner, 'fill', box, len - index ),
                fill = shape.fill;
            shape['coordsize'] = w * 2 + ',' + h * 2;
            shape['coordorigin'] = '1,1';
            shape['stroked'] = false;
            shape['filled'] = true;
            fill.color = color.colorValue( el );
            if( blur ) {
                fill['type'] = 'gradienttitle'; 
                fill['color2'] = fill.color;
                fill['opacity'] = 0;
            }
            shape.path = path;
            ss = shape.style;
            ss.left = xOff;
            ss.top = yOff;
            ss.width = w;
            ss.height = h;
            return shape;
        }
        while( i-- ) {
            shadowInfo = shadowInfos[ i ];
            xOff = shadowInfo.xOffset.pixels( el );
            yOff = shadowInfo.yOffset.pixels( el );
            spread = shadowInfo.spread.pixels( el ),
            blur = shadowInfo.blur.pixels( el );
            color = shadowInfo.color;
            shrink = -spread - blur;
            if( !radii && blur ) {
                radii = PIE.BorderRadiusStyleInfo.ALL_ZERO;
            }
            path = this.getBoxPath( { t: shrink, r: shrink, b: shrink, l: shrink }, 2, radii );
            if( blur ) {
                totalW = ( spread + blur ) * 2 + w;
                totalH = ( spread + blur ) * 2 + h;
                focusX = blur * 2 / totalW;
                focusY = blur * 2 / totalH;
                if( blur - spread > w / 2 || blur - spread > h / 2 ) {
                    for( j = 4; j--; ) {
                        corner = corners[j];
                        isBottom = corner.charAt( 0 ) === 'b';
                        isRight = corner.charAt( 1 ) === 'r';
                        shape = getShadowShape( i, corner, xOff, yOff, color, blur, path );
                        fill = shape.fill;
                        fill['focusposition'] = ( isRight ? 1 - focusX : focusX ) + ',' +
                                                ( isBottom ? 1 - focusY : focusY );
                        fill['focussize'] = '0,0';
                        shape.style.clip = 'rect(' + ( ( isBottom ? totalH / 2 : 0 ) + clipAdjust ) + 'px,' +
                                                     ( isRight ? totalW : totalW / 2 ) + 'px,' +
                                                     ( isBottom ? totalH : totalH / 2 ) + 'px,' +
                                                     ( ( isRight ? totalW / 2 : 0 ) + clipAdjust ) + 'px)';
                    }
                } else {
                    shape = getShadowShape( i, '', xOff, yOff, color, blur, path );
                    fill = shape.fill;
                    fill['focusposition'] = focusX + ',' + focusY;
                    fill['focussize'] = ( 1 - focusX * 2 ) + ',' + ( 1 - focusY * 2 );
                }
            } else {
                shape = getShadowShape( i, '', xOff, yOff, color, blur, path );
                alpha = color.alpha();
                if( alpha < 1 ) {
                    shape.fill.opacity = alpha;
                }
            }
        }
    }
} );
PIE.ImgRenderer = PIE.RendererBase.newRenderer( {
    boxZIndex: 6,
    boxName: 'imgEl',
    needsUpdate: function() {
        var si = this.styleInfos;
        return this.targetElement.src !== this._lastSrc || si.borderRadiusInfo.changed();
    },
    isActive: function() {
        var si = this.styleInfos;
        return si.borderRadiusInfo.isActive() || si.backgroundInfo.isPngFix();
    },
    draw: function() {
        this._lastSrc = src;
        this.hideActualImg();
        var shape = this.getShape( 'img', 'fill', this.getBox() ),
            fill = shape.fill,
            bounds = this.boundsInfo.getBounds(),
            w = bounds.w,
            h = bounds.h,
            borderProps = this.styleInfos.borderInfo.getProps(),
            borderWidths = borderProps && borderProps.widths,
            el = this.targetElement,
            src = el.src,
            round = Math.round,
            cs = el.currentStyle,
            getLength = PIE.getLength,
            s, zero;
        if( !borderWidths || PIE.ieVersion < 7 ) {
            zero = PIE.getLength( '0' );
            borderWidths = { 't': zero, 'r': zero, 'b': zero, 'l': zero };
        }
        shape.stroked = false;
        fill.type = 'frame';
        fill.src = src;
        fill.position = (w ? 0.5 / w : 0) + ',' + (h ? 0.5 / h : 0);
        shape.coordsize = w * 2 + ',' + h * 2;
        shape.coordorigin = '1,1';
        shape.path = this.getBoxPath( {
            t: round( borderWidths['t'].pixels( el ) + getLength( cs.paddingTop ).pixels( el ) ),
            r: round( borderWidths['r'].pixels( el ) + getLength( cs.paddingRight ).pixels( el ) ),
            b: round( borderWidths['b'].pixels( el ) + getLength( cs.paddingBottom ).pixels( el ) ),
            l: round( borderWidths['l'].pixels( el ) + getLength( cs.paddingLeft ).pixels( el ) )
        }, 2 );
        s = shape.style;
        s.width = w;
        s.height = h;
    },
    hideActualImg: function() {
        this.targetElement.runtimeStyle.filter = 'alpha(opacity=0)';
    },
    destroy: function() {
        PIE.RendererBase.destroy.call( this );
        this.targetElement.runtimeStyle.filter = '';
    }
} );
PIE.IE9RootRenderer = PIE.RendererBase.newRenderer( {
    updatePos: PIE.emptyFn,
    updateSize: PIE.emptyFn,
    updateVisibility: PIE.emptyFn,
    updateProps: PIE.emptyFn,
    outerCommasRE: /^,+|,+$/g,
    innerCommasRE: /,+/g,
    setBackgroundLayer: function(zIndex, bg) {
        var me = this,
            bgLayers = me._bgLayers || ( me._bgLayers = [] ),
            undef;
        bgLayers[zIndex] = bg || undef;
    },
    finishUpdate: function() {
        var me = this,
            bgLayers = me._bgLayers,
            bg;
        if( bgLayers && ( bg = bgLayers.join( ',' ).replace( me.outerCommasRE, '' ).replace( me.innerCommasRE, ',' ) ) !== me._lastBg ) {
            me._lastBg = me.targetElement.runtimeStyle.background = bg;
        }
    },
    destroy: function() {
        this.targetElement.runtimeStyle.background = '';
        delete this._bgLayers;
    }
} );
PIE.IE9BackgroundRenderer = PIE.RendererBase.newRenderer( {
    bgLayerZIndex: 1,
    needsUpdate: function() {
        var si = this.styleInfos;
        return si.backgroundInfo.changed();
    },
    isActive: function() {
        var si = this.styleInfos;
        return si.backgroundInfo.isActive() || si.borderImageInfo.isActive();
    },
    draw: function() {
        var me = this,
            props = me.styleInfos.backgroundInfo.getProps(),
            bg, images, i = 0, img, bgAreaSize, bgSize;
        if ( props ) {
            bg = [];
            images = props.bgImages;
            if ( images ) {
                while( img = images[ i++ ] ) {
                    if (img.imgType === 'linear-gradient' ) {
                        bgAreaSize = me.getBgAreaSize( img.bgOrigin );
                        bgSize = ( img.bgSize || PIE.BgSize.DEFAULT ).pixels(
                            me.targetElement, bgAreaSize.w, bgAreaSize.h, bgAreaSize.w, bgAreaSize.h
                        ),
                        bg.push(
                            'url(data:image/svg+xml,' + escape( me.getGradientSvg( img, bgSize.w, bgSize.h ) ) + ') ' +
                            me.bgPositionToString( img.bgPosition ) + ' / ' + bgSize.w + 'px ' + bgSize.h + 'px ' +
                            ( img.bgAttachment || '' ) + ' ' + ( img.bgOrigin || '' ) + ' ' + ( img.bgClip || '' )
                        );
                    } else {
                        bg.push( img.origString );
                    }
                }
            }
            if ( props.color ) {
                bg.push( props.color.val );
            }
            me.parent.setBackgroundLayer(me.bgLayerZIndex, bg.join(','));
        }
    },
    bgPositionToString: function( bgPosition ) {
        return bgPosition ? bgPosition.tokens.map(function(token) {
            return token.tokenValue;
        }).join(' ') : '0 0';
    },
    getBgAreaSize: function( bgOrigin ) {
        var me = this,
            el = me.targetElement,
            bounds = me.boundsInfo.getBounds(),
            elW = bounds.w,
            elH = bounds.h,
            w = elW,
            h = elH,
            borders, getLength, cs;
        if( bgOrigin !== 'border-box' ) {
            borders = me.styleInfos.borderInfo.getProps();
            if( borders && ( borders = borders.widths ) ) {
                w -= borders[ 'l' ].pixels( el ) + borders[ 'l' ].pixels( el );
                h -= borders[ 't' ].pixels( el ) + borders[ 'b' ].pixels( el );
            }
        }
        if ( bgOrigin === 'content-box' ) {
            getLength = PIE.getLength;
            cs = el.currentStyle;
            w -= getLength( cs.paddingLeft ).pixels( el ) + getLength( cs.paddingRight ).pixels( el );
            h -= getLength( cs.paddingTop ).pixels( el ) + getLength( cs.paddingBottom ).pixels( el );
        }
        return { w: w, h: h };
    },
    getGradientSvg: function( info, bgWidth, bgHeight ) {
        var el = this.targetElement,
            stopsInfo = info.stops,
            stopCount = stopsInfo.length,
            metrics = PIE.GradientUtil.getGradientMetrics( el, bgWidth, bgHeight, info ),
            startX = metrics.startX,
            startY = metrics.startY,
            endX = metrics.endX,
            endY = metrics.endY,
            lineLength = metrics.lineLength,
            stopPx,
            i, j, before, after,
            svg;
        stopPx = [];
        for( i = 0; i < stopCount; i++ ) {
            stopPx.push( stopsInfo[i].offset ? stopsInfo[i].offset.pixels( el, lineLength ) :
                         i === 0 ? 0 : i === stopCount - 1 ? lineLength : null );
        }
        for( i = 1; i < stopCount; i++ ) {
            if( stopPx[ i ] === null ) {
                before = stopPx[ i - 1 ];
                j = i;
                do {
                    after = stopPx[ ++j ];
                } while( after === null );
                stopPx[ i ] = before + ( after - before ) / ( j - i + 1 );
            }
        }
        svg = [
            '<svg width="' + bgWidth + '" height="' + bgHeight + '" xmlns="http:
                '<defs>' +
                    '<linearGradient id="g" gradientUnits="userSpaceOnUse"' +
                    ' x1="' + ( startX / bgWidth * 100 ) + '%" y1="' + ( startY / bgHeight * 100 ) + '%" x2="' + ( endX / bgWidth * 100 ) + '%" y2="' + ( endY / bgHeight * 100 ) + '%">'
        ];
        for( i = 0; i < stopCount; i++ ) {
            svg.push(
                '<stop offset="' + ( stopPx[ i ] / lineLength ) +
                    '" stop-color="' + stopsInfo[i].color.colorValue( el ) +
                    '" stop-opacity="' + stopsInfo[i].color.alpha() + '"/>'
            );
        }
        svg.push(
                    '</linearGradient>' +
                '</defs>' +
                '<rect width="100%" height="100%" fill="url(#g)"/>' +
            '</svg>'
        );
        return svg.join( '' );
    },
    destroy: function() {
        this.parent.setBackgroundLayer( this.bgLayerZIndex );
    }
} );
PIE.IE9BorderImageRenderer = PIE.RendererBase.newRenderer( {
    REPEAT: 'repeat',
    STRETCH: 'stretch',
    ROUND: 'round',
    bgLayerZIndex: 0,
    needsUpdate: function() {
        return this.styleInfos.borderImageInfo.changed();
    },
    isActive: function() {
        return this.styleInfos.borderImageInfo.isActive();
    },
    draw: function() {
        var me = this,
            props = me.styleInfos.borderImageInfo.getProps(),
            borderProps = me.styleInfos.borderInfo.getProps(),
            bounds = me.boundsInfo.getBounds(),
            repeat = props.repeat,
            repeatH = repeat.h,
            repeatV = repeat.v,
            el = me.targetElement,
            isAsync = 0;
        PIE.Util.withImageSize( props.src, function( imgSize ) {
            var elW = bounds.w,
                elH = bounds.h,
                imgW = imgSize.w,
                imgH = imgSize.h,
                imgSrc = me.imageToDataURI( props.src, imgW, imgH ),
                REPEAT = me.REPEAT,
                STRETCH = me.STRETCH,
                ROUND = me.ROUND,
                ceil = Math.ceil,
                zero = PIE.getLength( '0' ),
                widths = props.widths || ( borderProps ? borderProps.widths : { 't': zero, 'r': zero, 'b': zero, 'l': zero } ),
                widthT = widths['t'].pixels( el ),
                widthR = widths['r'].pixels( el ),
                widthB = widths['b'].pixels( el ),
                widthL = widths['l'].pixels( el ),
                slices = props.slice,
                sliceT = slices['t'].pixels( el ),
                sliceR = slices['r'].pixels( el ),
                sliceB = slices['b'].pixels( el ),
                sliceL = slices['l'].pixels( el ),
                centerW = elW - widthL - widthR,
                middleH = elH - widthT - widthB,
                imgCenterW = imgW - sliceL - sliceR,
                imgMiddleH = imgH - sliceT - sliceB,
                tileSizeT = repeatH === STRETCH ? centerW : imgCenterW * widthT / sliceT,
                tileSizeR = repeatV === STRETCH ? middleH : imgMiddleH * widthR / sliceR,
                tileSizeB = repeatH === STRETCH ? centerW : imgCenterW * widthB / sliceB,
                tileSizeL = repeatV === STRETCH ? middleH : imgMiddleH * widthL / sliceL,
                svg,
                patterns = [],
                rects = [],
                i = 0;
            if (repeatH === ROUND) {
                tileSizeT -= (tileSizeT - (centerW % tileSizeT || tileSizeT)) / ceil(centerW / tileSizeT);
                tileSizeB -= (tileSizeB - (centerW % tileSizeB || tileSizeB)) / ceil(centerW / tileSizeB);
            }
            if (repeatV === ROUND) {
                tileSizeR -= (tileSizeR - (middleH % tileSizeR || tileSizeR)) / ceil(middleH / tileSizeR);
                tileSizeL -= (tileSizeL - (middleH % tileSizeL || tileSizeL)) / ceil(middleH / tileSizeL);
            }
            svg = [
                '<svg width="' + elW + '" height="' + elH + '" xmlns="http:
            ];
            function addImage( x, y, w, h, cropX, cropY, cropW, cropH, tileW, tileH ) {
                patterns.push(
                    '<pattern patternUnits="userSpaceOnUse" id="pattern' + i + '" ' +
                            'x="' + (repeatH === REPEAT ? x + w / 2 - tileW / 2 : x) + '" ' +
                            'y="' + (repeatV === REPEAT ? y + h / 2 - tileH / 2 : y) + '" ' +
                            'width="' + tileW + '" height="' + tileH + '">' +
                        '<svg width="' + tileW + '" height="' + tileH + '" viewBox="' + cropX + ' ' + cropY + ' ' + cropW + ' ' + cropH + '" preserveAspectRatio="none">' +
                            '<image xlink:href="' + imgSrc + '" x="0" y="0" width="' + imgW + '" height="' + imgH + '" />' +
                        '</svg>' +
                    '</pattern>'
                );
                rects.push(
                    '<rect x="' + x + '" y="' + y + '" width="' + w + '" height="' + h + '" fill="url(#pattern' + i + ')" />'
                );
                i++;
            }
            addImage( 0, 0, widthL, widthT, 0, 0, sliceL, sliceT, widthL, widthT ); 
            addImage( widthL, 0, centerW, widthT, sliceL, 0, imgCenterW, sliceT, tileSizeT, widthT ); 
            addImage( elW - widthR, 0, widthR, widthT, imgW - sliceR, 0, sliceR, sliceT, widthR, widthT ); 
            addImage( 0, widthT, widthL, middleH, 0, sliceT, sliceL, imgMiddleH, widthL, tileSizeL ); 
            if ( props.fill ) { 
                addImage( widthL, widthT, centerW, middleH, sliceL, sliceT, imgCenterW, imgMiddleH, 
                          tileSizeT || tileSizeB || imgCenterW, tileSizeL || tileSizeR || imgMiddleH );
            }
            addImage( elW - widthR, widthT, widthR, middleH, imgW - sliceR, sliceT, sliceR, imgMiddleH, widthR, tileSizeR ); 
            addImage( 0, elH - widthB, widthL, widthB, 0, imgH - sliceB, sliceL, sliceB, widthL, widthB ); 
            addImage( widthL, elH - widthB, centerW, widthB, sliceL, imgH - sliceB, imgCenterW, sliceB, tileSizeB, widthB ); 
            addImage( elW - widthR, elH - widthB, widthR, widthB, imgW - sliceR, imgH - sliceB, sliceR, sliceB, widthR, widthB ); 
            svg.push(
                    '<defs>' +
                        patterns.join('\n') +
                    '</defs>' +
                    rects.join('\n') +
                '</svg>'
            );
            me.parent.setBackgroundLayer( me.bgLayerZIndex, 'url(data:image/svg+xml,' + escape( svg.join( '' ) ) + ') no-repeat border-box border-box' );
            if( isAsync ) {
                me.parent.finishUpdate();
            }
        }, me );
        isAsync = 1;
    },
    imageToDataURI: (function() {
        var uris = {};
        return function( src, width, height ) {
            var uri = uris[ src ],
                image, canvas;
            if ( !uri ) {
                image = new Image();
                canvas = doc.createElement( 'canvas' );
                image.src = src;
                canvas.width = width;
                canvas.height = height;
                canvas.getContext( '2d' ).drawImage( image, 0, 0 );
                uri = uris[ src ] = canvas.toDataURL();
            }
            return uri;
        }
    })(),
    prepareUpdate: PIE.BorderImageRenderer.prototype.prepareUpdate,
    destroy: function() {
        var me = this,
            rs = me.targetElement.runtimeStyle;
        me.parent.setBackgroundLayer( me.bgLayerZIndex );
        rs.borderColor = rs.borderStyle = rs.borderWidth = '';
    }
} );
PIE.Element = (function() {
    var wrappers = {},
        lazyInitCssProp = PIE.CSS_PREFIX + 'lazy-init',
        pollCssProp = PIE.CSS_PREFIX + 'poll',
        hoverClass = PIE.CLASS_PREFIX + 'hover',
        activeClass = PIE.CLASS_PREFIX + 'active',
        focusClass = PIE.CLASS_PREFIX + 'focus',
        firstChildClass = PIE.CLASS_PREFIX + 'first-child',
        ignorePropertyNames = { 'background':1, 'bgColor':1, 'display': 1 },
        classNameRegExes = {},
        dummyArray = [];
    function addClass( el, className ) {
        el.className += ' ' + className;
    }
    function removeClass( el, className ) {
        var re = classNameRegExes[ className ] ||
            ( classNameRegExes[ className ] = new RegExp( '\\b' + className + '\\b', 'g' ) );
        el.className = el.className.replace( re, '' );
    }
    function delayAddClass( el, className  ) {
        var classes = dummyArray.slice.call( arguments, 1 ),
            i = classes.length;
        setTimeout( function() {
            while( i-- ) {
                addClass( el, classes[ i ] );
            }
        }, 0 );
    }
    function delayRemoveClass( el, className  ) {
        var classes = dummyArray.slice.call( arguments, 1 ),
            i = classes.length;
        setTimeout( function() {
            while( i-- ) {
                removeClass( el, classes[ i ] );
            }
        }, 0 );
    }
    function Element( el ) {
        var renderers,
            rootRenderer,
            boundsInfo = new PIE.BoundsInfo( el ),
            styleInfos,
            styleInfosArr,
            initializing,
            initialized,
            eventsAttached,
            eventListeners = [],
            delayed,
            destroyed,
            poll;
        function init() {
            if( !initialized ) {
                var docEl,
                    bounds,
                    ieDocMode = PIE.ieDocMode,
                    cs = el.currentStyle,
                    lazy = cs.getAttribute( lazyInitCssProp ) === 'true',
                    childRenderers;
                poll = cs.getAttribute( pollCssProp );
                poll = ieDocMode > 7 ? poll !== 'false' : poll === 'true';
                if( !initializing ) {
                    initializing = 1;
                    el.runtimeStyle.zoom = 1;
                    initFirstChildPseudoClass();
                }
                boundsInfo.lock();
                if( lazy && ( bounds = boundsInfo.getBounds() ) && ( docEl = doc.documentElement || doc.body ) &&
                        ( bounds.y > docEl.clientHeight || bounds.x > docEl.clientWidth || bounds.y + bounds.h < 0 || bounds.x + bounds.w < 0 ) ) {
                    if( !delayed ) {
                        delayed = 1;
                        PIE.OnScroll.observe( init );
                    }
                } else {
                    initialized = 1;
                    delayed = initializing = 0;
                    PIE.OnScroll.unobserve( init );
                    if ( ieDocMode === 9 ) {
                        styleInfos = {
                            backgroundInfo: new PIE.BackgroundStyleInfo( el ),
                            borderImageInfo: new PIE.BorderImageStyleInfo( el ),
                            borderInfo: new PIE.BorderStyleInfo( el )
                        };
                        styleInfosArr = [
                            styleInfos.backgroundInfo,
                            styleInfos.borderImageInfo
                        ];
                        rootRenderer = new PIE.IE9RootRenderer( el, boundsInfo, styleInfos );
                        childRenderers = [
                            new PIE.IE9BackgroundRenderer( el, boundsInfo, styleInfos, rootRenderer ),
                            new PIE.IE9BorderImageRenderer( el, boundsInfo, styleInfos, rootRenderer )
                        ];
                    } else {
                        styleInfos = {
                            backgroundInfo: new PIE.BackgroundStyleInfo( el ),
                            borderInfo: new PIE.BorderStyleInfo( el ),
                            borderImageInfo: new PIE.BorderImageStyleInfo( el ),
                            borderRadiusInfo: new PIE.BorderRadiusStyleInfo( el ),
                            boxShadowInfo: new PIE.BoxShadowStyleInfo( el ),
                            visibilityInfo: new PIE.VisibilityStyleInfo( el )
                        };
                        styleInfosArr = [
                            styleInfos.backgroundInfo,
                            styleInfos.borderInfo,
                            styleInfos.borderImageInfo,
                            styleInfos.borderRadiusInfo,
                            styleInfos.boxShadowInfo,
                            styleInfos.visibilityInfo
                        ];
                        rootRenderer = new PIE.RootRenderer( el, boundsInfo, styleInfos );
                        childRenderers = [
                            new PIE.BoxShadowOutsetRenderer( el, boundsInfo, styleInfos, rootRenderer ),
                            new PIE.BackgroundRenderer( el, boundsInfo, styleInfos, rootRenderer ),
                            new PIE.BorderRenderer( el, boundsInfo, styleInfos, rootRenderer ),
                            new PIE.BorderImageRenderer( el, boundsInfo, styleInfos, rootRenderer )
                        ];
                        if( el.tagName === 'IMG' ) {
                            childRenderers.push( new PIE.ImgRenderer( el, boundsInfo, styleInfos, rootRenderer ) );
                        }
                        rootRenderer.childRenderers = childRenderers; 
                    }
                    renderers = [ rootRenderer ].concat( childRenderers );
                    initAncestorEventListeners();
                    if( poll ) {
                        PIE.Heartbeat.observe( update );
                        PIE.Heartbeat.run();
                    }
                    update( 1 );
                }
                if( !eventsAttached ) {
                    eventsAttached = 1;
                    if( ieDocMode < 9 ) {
                        addListener( el, 'onmove', handleMoveOrResize );
                    }
                    addListener( el, 'onresize', handleMoveOrResize );
                    addListener( el, 'onpropertychange', propChanged );
                    addListener( el, 'onmouseenter', mouseEntered );
                    addListener( el, 'onmouseleave', mouseLeft );
                    addListener( el, 'onmousedown', mousePressed );
                    if( el.tagName in PIE.focusableElements ) {
                        addListener( el, 'onfocus', focused );
                        addListener( el, 'onblur', blurred );
                    }
                    PIE.OnResize.observe( handleMoveOrResize );
                    PIE.OnUnload.observe( removeEventListeners );
                }
                boundsInfo.unlock();
            }
        }
        function handleMoveOrResize() {
            if( boundsInfo && boundsInfo.hasBeenQueried() ) {
                update();
            }
        }
        function update( force ) {
            if( !destroyed ) {
                if( initialized ) {
                    var i, len = renderers.length;
                    lockAll();
                    for( i = 0; i < len; i++ ) {
                        renderers[i].prepareUpdate();
                    }
                    if( force || boundsInfo.positionChanged() ) {
                        for( i = 0; i < len; i++ ) {
                            renderers[i].updatePos();
                        }
                    }
                    if( force || boundsInfo.sizeChanged() ) {
                        for( i = 0; i < len; i++ ) {
                            renderers[i].updateSize();
                        }
                    }
                    rootRenderer.finishUpdate();
                    unlockAll();
                }
                else if( !initializing ) {
                    init();
                }
            }
        }
        function propChanged() {
            var i, len = renderers.length,
                renderer,
                e = event;
            if( !destroyed && !( e && e.propertyName in ignorePropertyNames ) ) {
                if( initialized ) {
                    lockAll();
                    for( i = 0; i < len; i++ ) {
                        renderers[i].prepareUpdate();
                    }
                    for( i = 0; i < len; i++ ) {
                        renderer = renderers[i];
                        if( !renderer.isPositioned ) {
                            renderer.updatePos();
                        }
                        if( renderer.needsUpdate() ) {
                            renderer.updateProps();
                        }
                    }
                    rootRenderer.finishUpdate();
                    unlockAll();
                }
                else if( !initializing ) {
                    init();
                }
            }
        }
        function mouseEntered() {
            delayAddClass( el, hoverClass );
        }
        function mouseLeft() {
            delayRemoveClass( el, hoverClass, activeClass );
        }
        function mousePressed() {
            delayAddClass( el, activeClass );
            PIE.OnMouseup.observe( mouseReleased );
        }
        function mouseReleased() {
            delayRemoveClass( el, activeClass );
            PIE.OnMouseup.unobserve( mouseReleased );
        }
        function focused() {
            delayAddClass( el, focusClass );
        }
        function blurred() {
            delayRemoveClass( el, focusClass );
        }
        function ancestorPropChanged() {
            var name = event.propertyName;
            if( name === 'className' || name === 'id' ) {
                propChanged();
            }
        }
        function lockAll() {
            boundsInfo.lock();
            for( var i = styleInfosArr.length; i--; ) {
                styleInfosArr[i].lock();
            }
        }
        function unlockAll() {
            for( var i = styleInfosArr.length; i--; ) {
                styleInfosArr[i].unlock();
            }
            boundsInfo.unlock();
        }
        function addListener( targetEl, type, handler ) {
            targetEl.attachEvent( type, handler );
            eventListeners.push( [ targetEl, type, handler ] );
        }
        function removeEventListeners() {
            if (eventsAttached) {
                var i = eventListeners.length,
                    listener;
                while( i-- ) {
                    listener = eventListeners[ i ];
                    listener[ 0 ].detachEvent( listener[ 1 ], listener[ 2 ] );
                }
                PIE.OnUnload.unobserve( removeEventListeners );
                eventsAttached = 0;
                eventListeners = [];
            }
        }
        function destroy() {
            if( !destroyed ) {
                var i, len;
                removeEventListeners();
                destroyed = 1;
                if( renderers ) {
                    for( i = 0, len = renderers.length; i < len; i++ ) {
                        renderers[i].finalized = 1;
                        renderers[i].destroy();
                    }
                }
                if( poll ) {
                    PIE.Heartbeat.unobserve( update );
                }
                PIE.OnResize.unobserve( update );
                renderers = boundsInfo = styleInfos = styleInfosArr = el = null;
            }
        }
        function initAncestorEventListeners() {
            var watch = el.currentStyle.getAttribute( PIE.CSS_PREFIX + 'watch-ancestors' ),
                i, a;
            if( watch ) {
                watch = parseInt( watch, 10 );
                i = 0;
                a = el.parentNode;
                while( a && ( watch === 'NaN' || i++ < watch ) ) {
                    addListener( a, 'onpropertychange', ancestorPropChanged );
                    addListener( a, 'onmouseenter', mouseEntered );
                    addListener( a, 'onmouseleave', mouseLeft );
                    addListener( a, 'onmousedown', mousePressed );
                    if( a.tagName in PIE.focusableElements ) {
                        addListener( a, 'onfocus', focused );
                        addListener( a, 'onblur', blurred );
                    }
                    a = a.parentNode;
                }
            }
        }
        function initFirstChildPseudoClass() {
            var tmpEl = el,
                isFirst = 1;
            while( tmpEl = tmpEl.previousSibling ) {
                if( tmpEl.nodeType === 1 ) {
                    isFirst = 0;
                    break;
                }
            }
            if( isFirst ) {
                addClass( el, firstChildClass );
            }
        }
        this.init = init;
        this.update = update;
        this.destroy = destroy;
        this.el = el;
    }
    Element.getInstance = function( el ) {
        var id = PIE.Util.getUID( el );
        return wrappers[ id ] || ( wrappers[ id ] = new Element( el ) );
    };
    Element.destroy = function( el ) {
        var id = PIE.Util.getUID( el ),
            wrapper = wrappers[ id ];
        if( wrapper ) {
            wrapper.destroy();
            delete wrappers[ id ];
        }
    };
    Element.destroyAll = function() {
        var els = [], wrapper;
        if( wrappers ) {
            for( var w in wrappers ) {
                if( wrappers.hasOwnProperty( w ) ) {
                    wrapper = wrappers[ w ];
                    els.push( wrapper.el );
                    wrapper.destroy();
                }
            }
            wrappers = {};
        }
        return els;
    };
    return Element;
})();
PIE[ 'supportsVML' ] = PIE.supportsVML;
PIE[ 'attach' ] = function( el ) {
    if (PIE.ieDocMode < 10 && PIE.supportsVML) {
        PIE.Element.getInstance( el ).init();
    }
};
PIE[ 'detach' ] = function( el ) {
    PIE.Element.destroy( el );
};
} 
})();
