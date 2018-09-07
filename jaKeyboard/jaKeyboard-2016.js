//global variables
var controlFocused;
var str;
var n;
var cursorPosition;
var buttonCode;


function jaProcessClick(control){
    //alert(control);
    var key = control.innerHTML;
    str = controlFocused.value;
    //alert("You just pressed " + key);
    switch (key) {
        case "&amp;":
            controlFocused.value += '&';
            break;
        case "&lt;":
            controlFocused.value += '<';
            break;
        case "&gt;":
            controlFocused.value += '>';
            break;
        case "Done":
            document.getElementById("JANumPad1").style.visibility="hidden"; 
            document.getElementById("JAKeyboard1").style.visibility="hidden"; 
            document.getElementById("JAKeyboard1Shift").style.visibility="hidden"; 
            document.getElementById("JANumPad1Top").style.visibility="hidden"; 
            document.getElementById("JAKeyboard1Top").style.visibility="hidden"; 
            document.getElementById("JAKeyboard1ShiftTop").style.visibility="hidden"; 
            break;
//        case "NEXT FIELD &gt;&gt;":
//            tabToNextField(controlFocused);
//            break;
        case "Enter":
            //tabToNextField(controlFocused);
            document.getElementById("JANumPad1").style.visibility="hidden"; 
            document.getElementById("JAKeyboard1").style.visibility="hidden"; 
            document.getElementById("JAKeyboard1Shift").style.visibility="hidden";
            document.getElementById("JANumPad1Top").style.visibility="hidden"; 
            document.getElementById("JAKeyboard1Top").style.visibility="hidden"; 
            document.getElementById("JAKeyboard1ShiftTop").style.visibility="hidden";
            break;
//        case "&amp;123":
//            if (cursorPosition > 330) {
//                document.getElementById("JAKeyboard1Top").style.visibility="hidden"; 
//                document.getElementById("JAKeyboard1ShiftTop").style.visibility="hidden";
//                document.getElementById("JANumPad1Top").style.visibility="visible";
//                //break;
//            } else {
//                document.getElementById("JAKeyboard1").style.visibility="hidden"; 
//                document.getElementById("JAKeyboard1Shift").style.visibility="hidden";
//                document.getElementById("JANumPad1").style.visibility="visible";
//                
//            }
//            break;
        //case "Shift":
        case "ABC":
            
            if (document.getElementById("JAKeyboard1Shift").style.visibility=="visible") {
                alert('hi');
                document.getElementById("JAKeyboard1Shift").style.visibility="hidden"; 
                document.getElementById("JAKeyboard1").style.visibility="visible";
            } else if (document.getElementById("JAKeyboard1").style.visibility=="visible") {
                document.getElementById("JAKeyboard1").style.visibility="hidden"; 
                document.getElementById("JAKeyboard1Shift").style.visibility="visible";
            } else if (document.getElementById("JAKeyboard1ShiftTop").style.visibility=="visible") {
                alert('hi3');
                document.getElementById("JAKeyboard1ShiftTop").style.visibility="hidden"; 
                document.getElementById("JAKeyboard1Top").style.visibility="visible";
            } else if (document.getElementById("JAKeyboard1Top").style.visibility=="visible") {
                alert('hi4');
                document.getElementById("JAKeyboard1Top").style.visibility="hidden"; 
                document.getElementById("JAKeyboard1ShiftTop").style.visibility="visible";
            }
            break;
//        case "ABC":
//            if (cursorPosition > 330) {
//                document.getElementById("JANumPad1Top").style.visibility="hidden"; 
//                document.getElementById("JAKeyboard1Top").style.visibility="visible";
//                //break;
//            } else {
//                document.getElementById("JANumPad1").style.visibility="hidden"; 
//                document.getElementById("JAKeyboard1").style.visibility="visible";
//                
//            }
//            break;
    
        //case "X":
//        case "<img src=\"hidekeyboard.png\">":   
//            if (document.getElementById("JANumPad1").style.visibility=="visible") {     
//                document.getElementById("JANumPad1").style.visibility="hidden";
//            } else if (document.getElementById("JANumPad1Top").style.visibility=="visible") {     
//                document.getElementById("JANumPad1Top").style.visibility="hidden";    
//            } else {
//                controlFocused.value += key;
//            }
//            break;
        //case "Backspace":
        //case "<img src=\"deleteback.png\">":
        case "Delete":
            var newString;
            newString = controlFocused.value;
            newString = str.substring(0, (str.length -1));
            controlFocused.value = newString;
            break;
//        case "&lt;X":
//            var newString2;
//            newString2 = controlFocused.value;
//            newString2 = str.substring(0, (str.length -1));
//            controlFocused.value = newString2;
//            break;
        //case "Next Field":
        case "Next":    
            //alert(cursorPosition);
            //if (cursorPosition > 320) {
//            if (document.getElementById("JAKeyboardChar").style.visibility=="visible") {
//                document.getElementById("JANumPad1").style.visibility="hidden"; 
//                document.getElementById("JAKeyboard1").style.visibility="hidden";
//            } else {
//                //document.getElementById("JANumPad1").style.visibility="hidden"; 
//                //document.getElementById("JAKeyboard1").style.visibility="visible";
//            }
            tabToNextField(controlFocused);
            break;
        case "Space":    
        //case "": 
        //case "&nbsp;":
            controlFocused.value += " ";
            break;
        case "# + =":
        //case "Num Pad":
            //alert('hi');
            if (document.getElementById("JAKeyboard1Shift").style.visibility=="visible") {
                document.getElementById("JAKeyboard1Shift").style.visibility="hidden"; 
                document.getElementById("JAKeyboardChar").style.visibility="visible";
            } else if (document.getElementById("JAKeyboard1").style.visibility=="visible") {
                document.getElementById("JAKeyboard1").style.visibility="hidden"; 
                document.getElementById("JAKeyboardChar").style.visibility="visible";
            } else if (document.getElementById("JAKeyboard1ShiftTop").style.visibility=="visible") {
                document.getElementById("JAKeyboard1ShiftTop").style.visibility="hidden"; 
                document.getElementById("JAKeyboardCharTop").style.visibility="visible";
            } else if (document.getElementById("JAKeyboard1Top").style.visibility=="visible") {
                document.getElementById("JAKeyboard1Top").style.visibility="hidden"; 
                document.getElementById("JAKeyboardCharTop").style.visibility="visible";
            } else if (document.getElementById("JAKeyboardChar").style.visibility=="visible") {
                document.getElementById("JAKeyboardChar").style.visibility="hidden"; 
                document.getElementById("JAKeyboard1").style.visibility="visible";
            } else if (document.getElementById("JAKeyboardCharTop").style.visibility=="visible") {
                document.getElementById("JAKeyboardCharTop").style.visibility="hidden"; 
                document.getElementById("JAKeyboard1Top").style.visibility="visible";
            }
            break;
        case "abc":
            if (document.getElementById("JAKeyboardChar").style.visibility=="visible") {
                document.getElementById("JAKeyboardChar").style.visibility="hidden"; 
                document.getElementById("JAKeyboard1").style.visibility="visible";
            } else if (document.getElementById("JAKeyboardCharTop").style.visibility=="visible") {
                document.getElementById("JAKeyboardCharTop").style.visibility="hidden"; 
                document.getElementById("JAKeyboard1Top").style.visibility="visible";
            } else if (document.getElementById("JAKeyboard1Shift").style.visibility=="visible") {
                document.getElementById("JAKeyboard1Shift").style.visibility="hidden"; 
                document.getElementById("JAKeyboard1").style.visibility="visible";
            } else if (document.getElementById("JAKeyboard1ShiftTop").style.visibility=="visible") {
                document.getElementById("JAKeyboard1ShiftTop").style.visibility="hidden"; 
                document.getElementById("JAKeyboard1Top").style.visibility="visible";
            }
            
            break;  
        case "Full Keyboard":
            if (document.getElementById("JANumPad1").style.visibility=="visible") {
                document.getElementById("JANumPad1").style.visibility="hidden"; 
                document.getElementById("JAKeyboard1").style.visibility="visible";
            } else if (document.getElementById("JANumPad1Top").style.visibility=="visible") {
                document.getElementById("JANumPad1Top").style.visibility="hidden"; 
                document.getElementById("JAKeyboard1Top").style.visibility="visible";
            }
            break;   
        default: 
            controlFocused.value += key;
    }
}

function tabToNextField(control){
    //window.event.keyCode = 9;
    //alert ('control:' + control);
    
    //alert('control.name:' + control.name);
    var controlById = document.getElementById(control.name);
    
    var all = document.getElementsByTagName("*");
    var foundControl = false;
    for (var i=0, max=all.length; i < max; i++) {
        //alert(all[i].tagName);
        if (all[i].tagName == 'INPUT'){
            //alert('found an input: ' + all[i].id);
            if (foundControl){
                //alert('about to focus on ' + all[i].id);
                //controlFocused = all[i];
                all[i].focus();
                jaShowKeyboard(all[i], 'Keyboard1');
                return;
            }
            //alert('all[i].id=' + all[i].id);
            //alert('control.id=' + controlById.id);
            if (all[i].id == controlById.id){
                //alert('found matching');
                foundControl = true;
            }
        }
    }
    
}

function hideAllKeyboards(){
            document.getElementById("JANumPad1").style.visibility="hidden"; 
            document.getElementById("JAKeyboard1").style.visibility="hidden"; 
            document.getElementById("JAKeyboard1Shift").style.visibility="hidden"; 
            document.getElementById("JAKeyboardChar").style.visibility="hidden"; 
            document.getElementById("JANumPad1Top").style.visibility="hidden"; 
            document.getElementById("JAKeyboard1Top").style.visibility="hidden"; 
            document.getElementById("JAKeyboard1ShiftTop").style.visibility="hidden"; 
            document.getElementById("JAKeyboardCharTop").style.visibility="hidden"; 
}




function jaShowKeyboard(control, keyboardtype){
    //alert(control);
    controlFocused = control;
    
    getTextboxCoordinates(control);
    //alert(cursorPosition);
    hideAllKeyboards();
    switch (keyboardtype) {
        //case "Keyboard1":   if (cursorPosition > 330) {
        case "Keyboard1":   if (cursorPosition > 560) {
                                document.getElementById("JAKeyboard1Top").style.visibility="visible";
                                document.getElementById("JANumPad1Top").style.visibility="hidden";  
                                break;
                            }
                            else {
                                document.getElementById("JAKeyboard1").style.visibility="visible";
                                document.getElementById("JANumPad1").style.visibility="hidden";  
                                break;
                            }
        //case "NumPad1":     if (cursorPosition > 330) {
        case "NumPad1":     if (cursorPosition > 591) {
                                document.getElementById("JANumPad1Top").style.visibility="visible";
                                document.getElementById("JAKeyboard1Top").style.visibility="hidden"; 
                                break;
                            } 
                            else {
                                document.getElementById("JANumPad1").style.visibility="visible";
                                document.getElementById("JAKeyboard1").style.visibility="hidden"; 
                                break;
                            }
    }
}            

function jaHideKeyboard(){
    document.getElementById("JANumPad1").style.visibility="hidden";
    document.getElementById("JAKeyboard1").style.visibility="hidden";
    document.getElementById("JAKeyboard1Shift").style.visibility="hidden";
    document.getElementById("JAKeyboardChar").style.visibility="hidden"; 
    document.getElementById("JANumPad1Top").style.visibility="hidden";
    document.getElementById("JAKeyboard1Top").style.visibility="hidden";
    document.getElementById("JAKeyboard1ShiftTop").style.visibility="hidden";
    document.getElementById("JAKeyboardCharTop").style.visibility="hidden"; 
}

function getScreenCoordinates(obj) {
    var p = {};
    p.x = obj.offsetLeft;
    p.y = obj.offsetTop;
    while (obj.offsetParent) {
        p.x = p.x + obj.offsetParent.offsetLeft;
        p.y = p.y + obj.offsetParent.offsetTop;
        if (obj == document.getElementsByTagName("body")[0]) {
            break;
        }
        else {
            obj = obj.offsetParent;
        }
    }
    return p;
}


function getTextboxCoordinates(control) {
    var p = getScreenCoordinates(control);
    cursorPosition = p.y;
    //alert(cursorPosition);
}

buttonCode = "OnClick=\"jaProcessClick(this);\" class=\"jaNumPad1Button\"";

document.write('<div id="JANumPad1" class="jaNumPad1">');       
document.write('<center>');  
document.write('<table>'); 
document.write('<tr><td colspan=13 height=3px></td></tr>'); 
document.write('<tr>');
document.write('<td colspan=5></td>');
document.write('<td ' + buttonCode + '>7</td>');
document.write('<td ' + buttonCode + '>8</td>');
document.write('<td ' + buttonCode + '>9</td>');
document.write('<td colspan=5></td>');
document.write('</tr>');   
document.write('<tr>');
document.write('<td colspan=5></td>');
document.write('<td ' + buttonCode + '>4</td>');
document.write('<td ' + buttonCode + '>5</td>');
document.write('<td ' + buttonCode + '>6</td>');
document.write('<td colspan=5></td>');
document.write('</tr>'); 
document.write('<tr>');
//document.write('<td colspan=5></td>');
document.write('<td colspan=3></td>');
//document.write('<td colspan=2 style="background:#636363;width:224px" ' + buttonCode + '>Full Keyboard</td>'); 
document.write('<td colspan=2 style="background:#767676;width:224px" ' + buttonCode + '>Delete</td>'); 
document.write('<td ' + buttonCode + '>1</td>');
document.write('<td ' + buttonCode + '>2</td>');
document.write('<td ' + buttonCode + '>3</td>');
document.write('<td style="background:#dd2435;width:224px" ' + buttonCode + '>Done</td>');
document.write('<td colspan=3></td>');
document.write('</tr>'); 
document.write('<tr>');
document.write('<td colspan=6></td>');
document.write('<td ' + buttonCode + '>0</td>');
document.write('<td colspan=6></td>');
document.write('</tr>'); 
document.write('</table>');
document.write('</center>');  
document.write('</div>');  

buttonCode = "OnClick=\"jaProcessClick(this);\" class=\"jaNumPad1Button\"";

document.write('<div id="JANumPad1Top" class="jaNumPad1Top">');   
document.write('<center>');  
document.write('<table>'); 
document.write('<tr><td colspan=13 height=3px></td></tr>'); 
document.write('<tr>');
document.write('<td colspan=5></td>');
document.write('<td ' + buttonCode + '>7</td>');
document.write('<td ' + buttonCode + '>8</td>');
document.write('<td ' + buttonCode + '>9</td>');
document.write('<td colspan=5></td>');
document.write('</tr>');   
document.write('<tr>');
document.write('<td colspan=5></td>');
document.write('<td ' + buttonCode + '>4</td>');
document.write('<td ' + buttonCode + '>5</td>');
document.write('<td ' + buttonCode + '>6</td>');
document.write('<td colspan=5></td>');
document.write('</tr>'); 
document.write('<tr>');
//document.write('<td colspan=5></td>');
document.write('<td colspan=3></td>');
//document.write('<td colspan=2 style="background:#636363;width:224px" ' + buttonCode + '>Full Keyboard</td>'); 
document.write('<td colspan=2 style="background:#767676;width:224px" ' + buttonCode + '>Delete</td>'); 
document.write('<td ' + buttonCode + '>1</td>');
document.write('<td ' + buttonCode + '>2</td>');
document.write('<td ' + buttonCode + '>3</td>');
document.write('<td style="background:#dd2435;width:224px" ' + buttonCode + '>Done</td>');
document.write('<td colspan=3></td>');
document.write('</tr>'); 
document.write('<tr>');
document.write('<td colspan=6></td>');
document.write('<td ' + buttonCode + '>0</td>');
document.write('<td colspan=6></td>');
document.write('</tr>'); 
document.write('</table>');
document.write('</center>');  
document.write('</div>');    

buttonCode = "OnClick=\"jaProcessClick(this);\" class=\"jaKeyboardCharButton\"";

document.write('<div id="JAKeyboardChar" class="jaKeyboardChar">');    
document.write('<center>'); 
document.write('<table>');  
document.write('<tr><td colspan=16 height=3px></td></tr>'); 
document.write('<tr>'); 
document.write('<td colspan=2></td>'); 
document.write('<td ' + buttonCode + '>`</td>'); 
document.write('<td ' + buttonCode + '>#</td>'); 
document.write('<td ' + buttonCode + '>$</td>'); 
document.write('<td ' + buttonCode + '>%</td>'); 
document.write('<td ' + buttonCode + '>^</td>'); 
document.write('<td ' + buttonCode + '>&amp;</td>'); 
document.write('<td ' + buttonCode + '>*</td>');  
document.write('<td ' + buttonCode + '>(</td>'); 
document.write('<td ' + buttonCode + '>)</td>'); 
document.write('<td ' + buttonCode + '>-</td>');  
document.write('<td ' + buttonCode + '>+</td>'); 
document.write('<td ' + buttonCode + '>:</td>'); 
document.write('<td colspan=2></td>'); 
document.write('</tr>'); 
document.write('<tr>'); 
document.write('<td colspan=2></td>'); 
document.write('<td ' + buttonCode + '>~</td>'); 
document.write('<td ' + buttonCode + '>1</td>'); 
document.write('<td ' + buttonCode + '>2</td>'); 
document.write('<td ' + buttonCode + '>3</td>'); 
document.write('<td ' + buttonCode + '>4</td>'); 
document.write('<td ' + buttonCode + '>5</td>'); 
document.write('<td ' + buttonCode + '>6</td>');  
document.write('<td ' + buttonCode + '>7</td>'); 
document.write('<td ' + buttonCode + '>8</td>'); 
document.write('<td ' + buttonCode + '>9</td>');  
document.write('<td ' + buttonCode + '>0</td>');    
document.write('<td ' + buttonCode + '>;</td>'); 
document.write('<td colspan=2></td>'); 
document.write('</tr>'); 
document.write('<tr>'); 
document.write('<td colspan=1></td>'); 
document.write('<td colspan=2 style="background:#767676;width:224px" ' + buttonCode + '>abc</td>'); 
document.write('<td ' + buttonCode + '>&#8226;</td>'); 
document.write('<td ' + buttonCode + '>\'</td>'); 
document.write('<td ' + buttonCode + '>?</td>'); 
document.write('<td ' + buttonCode + '>!</td>'); 
document.write('<td ' + buttonCode + '>&lt;</td>'); 
document.write('<td ' + buttonCode + '>&gt;</td>');  
document.write('<td ' + buttonCode + '>[</td>'); 
document.write('<td ' + buttonCode + '>]</td>'); 
document.write('<td ' + buttonCode + '>&bsol;</td>');  
document.write('<td ' + buttonCode + '>|</td>');    
document.write('<td colspan=2 style="background:#767676;width:224px" ' + buttonCode + '>Delete</td>'); 
document.write('<td colspan=1></td>'); 
document.write('</tr>'); 
document.write('<tr>'); 
document.write('<td colspan=3></td>'); 
document.write('<td ' + buttonCode + '>_</td>'); 
document.write('<td ' + buttonCode + '>{</td>'); 
document.write('<td ' + buttonCode + '>}</td>'); 
document.write('<td ' + buttonCode + '>&pound;</td>'); 
document.write('<td ' + buttonCode + '>&yen;</td>'); 
document.write('<td ' + buttonCode + '>&euro;</td>');  
document.write('<td ' + buttonCode + '>=</td>'); 
document.write('<td ' + buttonCode + '>@</td>'); 
document.write('<td ' + buttonCode + '>,</td>');  
document.write('<td ' + buttonCode + '>.</td>');  
document.write('<td colspan=2 style="background:#767676;width:224px" ' + buttonCode + '>Next</td>');  
document.write('<td colspan=1></td>'); 
document.write('</tr>'); 
document.write('<tr>'); 
document.write('<td colspan=3></td>'); 
document.write('<td colspan=10 style="width:900px;background:#767676" ' + buttonCode + '>Space</td>'); 
document.write('<td colspan=2 style="background:#dd2435;width:224px;" onclick="jaHideKeyboard()" ' + buttonCode + '>Done</td>'); 
document.write('<td colspan=1></td>'); 
document.write('</tr>'); 
document.write('</table>');             
document.write('</center>');     
document.write('</div>');    

buttonCode = "OnClick=\"jaProcessClick(this);\" class=\"jaKeyboardCharButton\"";

document.write('<div id="JAKeyboardCharTop" class="jaKeyboardCharTop">');     
document.write('<center>'); 
document.write('<table>');  
document.write('<tr>'); 
document.write('<td colspan=2></td>'); 
document.write('<td ' + buttonCode + '>`</td>'); 
document.write('<td ' + buttonCode + '>#</td>'); 
document.write('<td ' + buttonCode + '>$</td>'); 
document.write('<td ' + buttonCode + '>%</td>'); 
document.write('<td ' + buttonCode + '>^</td>'); 
document.write('<td ' + buttonCode + '>&amp;</td>'); 
document.write('<td ' + buttonCode + '>*</td>');  
document.write('<td ' + buttonCode + '>(</td>'); 
document.write('<td ' + buttonCode + '>)</td>'); 
document.write('<td ' + buttonCode + '>-</td>');  
document.write('<td ' + buttonCode + '>+</td>'); 
document.write('<td ' + buttonCode + '>:</td>'); 
document.write('<td colspan=2></td>'); 
document.write('</tr>'); 
document.write('<tr>'); 
document.write('<td colspan=2></td>'); 
document.write('<td ' + buttonCode + '>~</td>'); 
document.write('<td ' + buttonCode + '>1</td>'); 
document.write('<td ' + buttonCode + '>2</td>'); 
document.write('<td ' + buttonCode + '>3</td>'); 
document.write('<td ' + buttonCode + '>4</td>'); 
document.write('<td ' + buttonCode + '>5</td>'); 
document.write('<td ' + buttonCode + '>6</td>');  
document.write('<td ' + buttonCode + '>7</td>'); 
document.write('<td ' + buttonCode + '>8</td>'); 
document.write('<td ' + buttonCode + '>9</td>');  
document.write('<td ' + buttonCode + '>0</td>');    
document.write('<td ' + buttonCode + '>;</td>'); 
document.write('<td colspan=2></td>'); 
document.write('</tr>'); 
document.write('<tr>'); 
document.write('<td colspan=1></td>'); 
document.write('<td colspan=2 style="background:#767676;width:224px" ' + buttonCode + '>abc</td>'); 
document.write('<td ' + buttonCode + '>&#8226;</td>'); 
document.write('<td ' + buttonCode + '>\'</td>'); 
document.write('<td ' + buttonCode + '>?</td>'); 
document.write('<td ' + buttonCode + '>!</td>'); 
document.write('<td ' + buttonCode + '>&lt;</td>'); 
document.write('<td ' + buttonCode + '>&gt;</td>');  
document.write('<td ' + buttonCode + '>[</td>'); 
document.write('<td ' + buttonCode + '>]</td>'); 
document.write('<td ' + buttonCode + '>&bsol;</td>');  
document.write('<td ' + buttonCode + '>|</td>');    
document.write('<td colspan=2 style="background:#767676;width:224px" ' + buttonCode + '>Delete</td>'); 
document.write('<td colspan=1></td>'); 
document.write('</tr>'); 
document.write('<tr>'); 
document.write('<td colspan=3></td>'); 
document.write('<td ' + buttonCode + '>_</td>'); 
document.write('<td ' + buttonCode + '>{</td>'); 
document.write('<td ' + buttonCode + '>}</td>'); 
document.write('<td ' + buttonCode + '>&pound;</td>'); 
document.write('<td ' + buttonCode + '>&yen;</td>'); 
document.write('<td ' + buttonCode + '>&euro;</td>');  
document.write('<td ' + buttonCode + '>=</td>'); 
document.write('<td ' + buttonCode + '>@</td>'); 
document.write('<td ' + buttonCode + '>,</td>');  
document.write('<td ' + buttonCode + '>.</td>');  
document.write('<td colspan=2 style="background:#767676;width:224px" ' + buttonCode + '>Next</td>');  
document.write('<td colspan=1></td>'); 
document.write('</tr>'); 
document.write('<tr>'); 
document.write('<td colspan=3></td>'); 
document.write('<td colspan=10 style="width:900px;background:#767676" ' + buttonCode + '>Space</td>'); 
document.write('<td colspan=2 style="background:#dd2435;width:224px;" onclick="jaHideKeyboard()" ' + buttonCode + '>Done</td>'); 
document.write('<td colspan=1></td>'); 
document.write('</tr>'); 
document.write('</table>');             
document.write('</center>');     
document.write('</div>');      


buttonCode = "OnClick=\"jaProcessClick(this);\" class=\"jaKeyboard1Button\"";

document.write('<div id="JAKeyboard1" class="jaKeyboard1">');    
document.write('<center>'); 
document.write('<table>');  
document.write('<tr><td colspan=16 height=3px></td></tr>'); 
document.write('<tr>'); 
document.write('<td colspan=3></td>'); 
document.write('<td ' + buttonCode + '>1</td>'); 
document.write('<td ' + buttonCode + '>2</td>'); 
document.write('<td ' + buttonCode + '>3</td>'); 
document.write('<td ' + buttonCode + '>4</td>'); 
document.write('<td ' + buttonCode + '>5</td>'); 
document.write('<td ' + buttonCode + '>6</td>');  
document.write('<td ' + buttonCode + '>7</td>'); 
document.write('<td ' + buttonCode + '>8</td>'); 
document.write('<td ' + buttonCode + '>9</td>');  
document.write('<td ' + buttonCode + '>0</td>'); 
document.write('<td colspan=3></td>'); 
document.write('</tr>'); 
document.write('<tr>'); 
document.write('<td colspan=3 style="width:224px;"></td>'); 
document.write('<td ' + buttonCode + '>q</td>'); 
document.write('<td ' + buttonCode + '>w</td>'); 
document.write('<td ' + buttonCode + '>e</td>'); 
document.write('<td ' + buttonCode + '>r</td>'); 
document.write('<td ' + buttonCode + '>t</td>'); 
document.write('<td ' + buttonCode + '>y</td>');  
document.write('<td ' + buttonCode + '>u</td>'); 
document.write('<td ' + buttonCode + '>i</td>'); 
document.write('<td ' + buttonCode + '>o</td>');  
document.write('<td ' + buttonCode + '>p</td>');    
document.write('<td colspan=3 style="width:224px;"></td>'); 
document.write('</tr>'); 
document.write('<tr>'); 
document.write('<td colspan=1></td>'); 
document.write('<td colspan=2 style="background:#767676;width:224px;"" ' + buttonCode + '># + =</td>'); 
document.write('<td ' + buttonCode + '>a</td>'); 
document.write('<td ' + buttonCode + '>s</td>'); 
document.write('<td ' + buttonCode + '>d</td>'); 
document.write('<td ' + buttonCode + '>f</td>'); 
document.write('<td ' + buttonCode + '>g</td>'); 
document.write('<td ' + buttonCode + '>h</td>');  
document.write('<td ' + buttonCode + '>j</td>'); 
document.write('<td ' + buttonCode + '>k</td>'); 
document.write('<td ' + buttonCode + '>l</td>');  
document.write('<td ' + buttonCode + '>\'</td>'); 
document.write('<td colspan=2 style="background:#767676;width:224px;"" ' + buttonCode + '>Delete</td>'); 
document.write('<td colspan=1></td>'); 
document.write('</tr>'); 
document.write('<tr>'); 
document.write('<td colspan=1></td>'); 
document.write('<td colspan=2 style="background:#767676;width:224px;" ' + buttonCode + '>ABC</td>'); 
document.write('<td ' + buttonCode + '>z</td>'); 
document.write('<td ' + buttonCode + '>x</td>'); 
document.write('<td ' + buttonCode + '>c</td>'); 
document.write('<td ' + buttonCode + '>v</td>'); 
document.write('<td ' + buttonCode + '>b</td>'); 
document.write('<td ' + buttonCode + '>n</td>');  
document.write('<td ' + buttonCode + '>m</td>'); 
document.write('<td ' + buttonCode + '>,</td>'); 
document.write('<td ' + buttonCode + '>.</td>');  
document.write('<td ' + buttonCode + '>@</td>');  
document.write('<td colspan=2 style="background:#767676;width:224px" ' + buttonCode + '>Next</td>'); 
document.write('<td colspan=1></td>'); 
document.write('</tr>'); 
document.write('<tr>'); 
document.write('<td colspan=3></td>'); 
document.write('<td colspan=10 style="width:890px;background:#767676" ' + buttonCode + '>Space</td>'); 
document.write('<td colspan=2 style="background:#dd2435;width:224px;" onclick="jaHideKeyboard()" ' + buttonCode + '>Done</td>'); 
document.write('<td colspan=1></td>'); 
document.write('</tr>'); 
document.write('</table>');  
document.write('</center>');     
document.write('</div>');      

buttonCode = "OnClick=\"jaProcessClick(this);\" class=\"jaKeyboard1Button\"";

document.write('<div id="JAKeyboard1Top" class="jaKeyboard1Top">');     
document.write('<center>'); 
document.write('<table>');  
document.write('<tr><td colspan=16 height=3px></td></tr>'); 
document.write('<tr>'); 
document.write('<td colspan=3></td>'); 
document.write('<td ' + buttonCode + '>1</td>'); 
document.write('<td ' + buttonCode + '>2</td>'); 
document.write('<td ' + buttonCode + '>3</td>'); 
document.write('<td ' + buttonCode + '>4</td>'); 
document.write('<td ' + buttonCode + '>5</td>'); 
document.write('<td ' + buttonCode + '>6</td>');  
document.write('<td ' + buttonCode + '>7</td>'); 
document.write('<td ' + buttonCode + '>8</td>'); 
document.write('<td ' + buttonCode + '>9</td>');  
document.write('<td ' + buttonCode + '>0</td>'); 
document.write('<td colspan=3></td>'); 
document.write('</tr>'); 
document.write('<tr>'); 
document.write('<td colspan=3 style="width:224px;"></td>'); 
document.write('<td ' + buttonCode + '>q</td>'); 
document.write('<td ' + buttonCode + '>w</td>'); 
document.write('<td ' + buttonCode + '>e</td>'); 
document.write('<td ' + buttonCode + '>r</td>'); 
document.write('<td ' + buttonCode + '>t</td>'); 
document.write('<td ' + buttonCode + '>y</td>');  
document.write('<td ' + buttonCode + '>u</td>'); 
document.write('<td ' + buttonCode + '>i</td>'); 
document.write('<td ' + buttonCode + '>o</td>');  
document.write('<td ' + buttonCode + '>p</td>');    
document.write('<td colspan=3 style="width:224px;"></td>'); 
document.write('</tr>'); 
document.write('<tr>'); 
document.write('<td colspan=1></td>'); 
document.write('<td colspan=2 style="background:#767676;width:224px;"" ' + buttonCode + '># + =</td>'); 
document.write('<td ' + buttonCode + '>a</td>'); 
document.write('<td ' + buttonCode + '>s</td>'); 
document.write('<td ' + buttonCode + '>d</td>'); 
document.write('<td ' + buttonCode + '>f</td>'); 
document.write('<td ' + buttonCode + '>g</td>'); 
document.write('<td ' + buttonCode + '>h</td>');  
document.write('<td ' + buttonCode + '>j</td>'); 
document.write('<td ' + buttonCode + '>k</td>'); 
document.write('<td ' + buttonCode + '>l</td>');  
document.write('<td ' + buttonCode + '>\'</td>'); 
document.write('<td colspan=2 style="background:#767676;width:224px;"" ' + buttonCode + '>Delete</td>'); 
document.write('<td colspan=1></td>'); 
document.write('</tr>'); 
document.write('<tr>'); 
document.write('<td colspan=1></td>'); 
document.write('<td colspan=2 style="background:#767676;width:224px;" ' + buttonCode + '>ABC</td>'); 
document.write('<td ' + buttonCode + '>z</td>'); 
document.write('<td ' + buttonCode + '>x</td>'); 
document.write('<td ' + buttonCode + '>c</td>'); 
document.write('<td ' + buttonCode + '>v</td>'); 
document.write('<td ' + buttonCode + '>b</td>'); 
document.write('<td ' + buttonCode + '>n</td>');  
document.write('<td ' + buttonCode + '>m</td>'); 
document.write('<td ' + buttonCode + '>,</td>'); 
document.write('<td ' + buttonCode + '>.</td>');  
document.write('<td ' + buttonCode + '>@</td>');  
document.write('<td colspan=2 style="background:#767676;width:224px" ' + buttonCode + '>Next</td>'); 
document.write('<td colspan=1></td>'); 
document.write('</tr>'); 
document.write('<tr>'); 
document.write('<td colspan=3></td>'); 
document.write('<td colspan=10 style="width:890px;background:#767676" ' + buttonCode + '>Space</td>'); 
document.write('<td colspan=2 style="background:#dd2435;width:224px;" onclick="jaHideKeyboard()" ' + buttonCode + '>Done</td>'); 
document.write('<td colspan=1></td>'); 
document.write('</tr>'); 
document.write('</table>');  
document.write('</center>');     
document.write('</div>');      




buttonCode = "OnClick=\"jaProcessClick(this);\" class=\"jaKeyboard1ShiftButton\"";        

document.write('<div id="JAKeyboard1Shift" class="jaKeyboard1Shift">');    
document.write('<center>'); 
document.write('<table>');  
document.write('<tr><td colspan=16 height=3px></td></tr>'); 
document.write('<tr>'); 
document.write('<td colspan=3></td>'); 
document.write('<td ' + buttonCode + '>1</td>'); 
document.write('<td ' + buttonCode + '>2</td>'); 
document.write('<td ' + buttonCode + '>3</td>'); 
document.write('<td ' + buttonCode + '>4</td>'); 
document.write('<td ' + buttonCode + '>5</td>'); 
document.write('<td ' + buttonCode + '>6</td>');  
document.write('<td ' + buttonCode + '>7</td>'); 
document.write('<td ' + buttonCode + '>8</td>'); 
document.write('<td ' + buttonCode + '>9</td>');  
document.write('<td ' + buttonCode + '>0</td>'); 
document.write('<td colspan=3></td>'); 
document.write('</tr>'); 
document.write('<tr>'); 
document.write('<td colspan=3></td>'); 
document.write('<td ' + buttonCode + '>Q</td>'); 
document.write('<td ' + buttonCode + '>W</td>'); 
document.write('<td ' + buttonCode + '>E</td>'); 
document.write('<td ' + buttonCode + '>R</td>'); 
document.write('<td ' + buttonCode + '>T</td>'); 
document.write('<td ' + buttonCode + '>Y</td>');  
document.write('<td ' + buttonCode + '>U</td>'); 
document.write('<td ' + buttonCode + '>I</td>'); 
document.write('<td ' + buttonCode + '>O</td>');  
document.write('<td ' + buttonCode + '>P</td>');    
document.write('<td colspan=3></td>'); 
document.write('</tr>'); 
document.write('<tr>'); 
document.write('<td colspan=1></td>'); 
document.write('<td colspan=2 style="background:#767676;width:224px;"" ' + buttonCode + '># + =</td>'); 
document.write('<td ' + buttonCode + '>A</td>'); 
document.write('<td ' + buttonCode + '>S</td>'); 
document.write('<td ' + buttonCode + '>D</td>'); 
document.write('<td ' + buttonCode + '>F</td>'); 
document.write('<td ' + buttonCode + '>G</td>'); 
document.write('<td ' + buttonCode + '>H</td>');  
document.write('<td ' + buttonCode + '>J</td>'); 
document.write('<td ' + buttonCode + '>K</td>'); 
document.write('<td ' + buttonCode + '>L</td>');  
document.write('<td ' + buttonCode + '>\'</td>'); 
document.write('<td colspan=2 style="background:#767676;width:224px;"" ' + buttonCode + '>Delete</td>'); 
document.write('<td colspan=1></td>'); 
document.write('</tr>'); 
document.write('<tr>'); 
document.write('<td colspan=1></td>'); 
document.write('<td colspan=2 style="background:#767676;width:224px;" ' + buttonCode + '>abc</td>'); 
document.write('<td ' + buttonCode + '>Z</td>'); 
document.write('<td ' + buttonCode + '>X</td>'); 
document.write('<td ' + buttonCode + '>C</td>'); 
document.write('<td ' + buttonCode + '>V</td>'); 
document.write('<td ' + buttonCode + '>B</td>'); 
document.write('<td ' + buttonCode + '>N</td>');  
document.write('<td ' + buttonCode + '>M</td>'); 
document.write('<td ' + buttonCode + '>,</td>'); 
document.write('<td ' + buttonCode + '>.</td>');  
document.write('<td ' + buttonCode + '>@</td>');  
document.write('<td colspan=2 style="background:#767676;width:224px" ' + buttonCode + '>Next</td>'); 
document.write('<td colspan=1></td>'); 
document.write('</tr>'); 
document.write('<tr>'); 
document.write('<td colspan=3></td>'); 
document.write('<td colspan=10 style="width:890px;background:#767676" ' + buttonCode + '>Space</td>'); 
document.write('<td colspan=2 style="background:#dd2435;width:230px;" onclick="jaHideKeyboard()" ' + buttonCode + '>Done</td>'); 
document.write('<td colspan=1></td>'); 
document.write('</tr>'); 
document.write('</table>');  
document.write('</center>');     
document.write('</div>');      


buttonCode = "OnClick=\"jaProcessClick(this);\" class=\"jaKeyboard1ShiftButton\"";        

document.write('<div id="JAKeyboard1ShiftTop" class="jaKeyboard1ShiftTop">');    
document.write('<center>'); 
document.write('<table>');  
document.write('<tr><td colspan=16 height=3px></td></tr>'); 
document.write('<tr>'); 
document.write('<td colspan=3></td>'); 
document.write('<td ' + buttonCode + '>1</td>'); 
document.write('<td ' + buttonCode + '>2</td>'); 
document.write('<td ' + buttonCode + '>3</td>'); 
document.write('<td ' + buttonCode + '>4</td>'); 
document.write('<td ' + buttonCode + '>5</td>'); 
document.write('<td ' + buttonCode + '>6</td>');  
document.write('<td ' + buttonCode + '>7</td>'); 
document.write('<td ' + buttonCode + '>8</td>'); 
document.write('<td ' + buttonCode + '>9</td>');  
document.write('<td ' + buttonCode + '>0</td>'); 
document.write('<td colspan=3></td>'); 
document.write('</tr>'); 
document.write('<tr>'); 
document.write('<td colspan=3></td>'); 
document.write('<td ' + buttonCode + '>Q</td>'); 
document.write('<td ' + buttonCode + '>W</td>'); 
document.write('<td ' + buttonCode + '>E</td>'); 
document.write('<td ' + buttonCode + '>R</td>'); 
document.write('<td ' + buttonCode + '>T</td>'); 
document.write('<td ' + buttonCode + '>Y</td>');  
document.write('<td ' + buttonCode + '>U</td>'); 
document.write('<td ' + buttonCode + '>I</td>'); 
document.write('<td ' + buttonCode + '>O</td>');  
document.write('<td ' + buttonCode + '>P</td>');    
document.write('<td colspan=3></td>'); 
document.write('</tr>'); 
document.write('<tr>'); 
document.write('<td colspan=1></td>'); 
document.write('<td colspan=2 style="background:#767676;width:224px;"" ' + buttonCode + '># + =</td>'); 
document.write('<td ' + buttonCode + '>A</td>'); 
document.write('<td ' + buttonCode + '>S</td>'); 
document.write('<td ' + buttonCode + '>D</td>'); 
document.write('<td ' + buttonCode + '>F</td>'); 
document.write('<td ' + buttonCode + '>G</td>'); 
document.write('<td ' + buttonCode + '>H</td>');  
document.write('<td ' + buttonCode + '>J</td>'); 
document.write('<td ' + buttonCode + '>K</td>'); 
document.write('<td ' + buttonCode + '>L</td>');  
document.write('<td ' + buttonCode + '>\'</td>'); 
document.write('<td colspan=2 style="background:#767676;width:224px;"" ' + buttonCode + '>Delete</td>'); 
document.write('<td colspan=1></td>'); 
document.write('</tr>'); 
document.write('<tr>'); 
document.write('<td colspan=1></td>'); 
document.write('<td colspan=2 style="background:#767676;width:224px;" ' + buttonCode + '>abc</td>'); 
document.write('<td ' + buttonCode + '>Z</td>'); 
document.write('<td ' + buttonCode + '>X</td>'); 
document.write('<td ' + buttonCode + '>C</td>'); 
document.write('<td ' + buttonCode + '>V</td>'); 
document.write('<td ' + buttonCode + '>B</td>'); 
document.write('<td ' + buttonCode + '>N</td>');  
document.write('<td ' + buttonCode + '>M</td>'); 
document.write('<td ' + buttonCode + '>,</td>'); 
document.write('<td ' + buttonCode + '>.</td>');  
document.write('<td ' + buttonCode + '>@</td>');  
document.write('<td colspan=2 style="background:#767676;width:224px" ' + buttonCode + '>Next</td>'); 
document.write('<td colspan=1></td>'); 
document.write('</tr>'); 
document.write('<tr>'); 
document.write('<td colspan=3></td>'); 
document.write('<td colspan=10 style="width:890px;background:#767676" ' + buttonCode + '>Space</td>'); 
document.write('<td colspan=2 style="background:#dd2435;width:230px;" onclick="jaHideKeyboard()" ' + buttonCode + '>Done</td>'); 
document.write('<td colspan=1></td>'); 
document.write('</tr>'); 
document.write('</table>');  
document.write('</center>');     
document.write('</div>');      
 
