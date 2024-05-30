var numpad = {
  // (A) PROPERTIES
  // (A1) HTML ELEMENTS
  hWrap: null,    // numpad wrapper container
  hDisplay: null, // number display
  hDisplayFrac: null, // number display
  now : null,     // current active instance

  // (B) INIT - CREATE NUMPAD HTML
  init: () => {
    // (B1) NUMPAD WRAPPER
	// <input type="text" id="numDisplay" disabled="true" value="0">
    numpad.hWrap = document.createElement("div");
    numpad.hWrap.id = "numPadWrap";
    numpad.hWrap.innerHTML = `<div id="numPad">
      <div id="numDisplay"><span id="full">0</span><span id="frac"></span></div>
	  <div id="numSwitch"></div>
	  <div id="numWrap">
		  <div id="full" class="hide show"></div>
		  <div id="frac" class="hide"></div>
		  <div id="frac_more" class="hide"></div>
	  </div>
	  <div id="actWrap"></div>
    </div>`;
    document.body.appendChild(numpad.hWrap);
    numpad.hDisplay = document.querySelector("#numDisplay #full");
	numpad.hDisplayFrac = document.querySelector("#numDisplay #frac");


	let nsWrap = document.querySelector("#numSwitch"),
    nsButtonator = (txt, css, fn) => {
      let button = document.createElement("button");
      button.innerHTML = txt;
      button.classList.add(css);
//      button.id = "";
      button.onclick = fn;
      nsWrap.appendChild(button);
    };
	let nsButtons = ['X', 'x/x', 'x/xx'];
	nsButtons.forEach(i=>{ nsButtonator(i, (i=="X"? "active":"btn"), () => numpad.switchpad(i)); });

    // (B2) ATTACH BUTTONS
    let hbWrap = document.querySelector("#numWrap #full"),
    buttonator = (txt, css, fn) => {
      let button = document.createElement("div");
      button.innerHTML = txt;
      button.classList.add(css);
      button.onclick = fn;
      hbWrap.appendChild(button);
    };
	let keypadButtons = ['1', '2', '3', '4', '5', '6', '7', '8', '9', 'clr', '0', 'del'];
	keypadButtons.forEach(i=>{
		if(i=="cx") buttonator("&#10006;", "cx", () => numpad.hide(1));
		else if(i=="clr") buttonator("CLR", "clr", numpad.reset);
		else if(i=="del") buttonator("DEL", "del", numpad.delete);
//		else if(i=="del") buttonator("&#10502;", "del", numpad.delete);
		else if(i=="ok") buttonator("&#10004;", "ok", numpad.select);
		else buttonator(i, "num", () => numpad.digit(i));
	});
	
	    // (B2) ATTACH BUTTONS
    let actWrap = document.querySelector("#actWrap"),
    actButtonator = (txt, css, fn) => {
      let button = document.createElement("div");
      button.innerHTML = txt;
      button.classList.add(css);
      button.onclick = fn;
      actWrap.appendChild(button);
    };
	let actButtons = ['cx', 'ok'];
	actButtons.forEach(i=>{
		if(i=="cx") actButtonator("&#10006;", "cx", () => numpad.hide(1));
		else if(i=="ok") actButtonator("&#10004;", "ok", numpad.select);
		else actButtonator(i, "num", () => numpad.digit(i));
	});

	numpad.init_fracpad();
  },
  init_fracpad: () => {
    // (B1) NUMPAD WRAPPER

	let fbWrap = document.querySelector("#numWrap #frac"),
    fButtonator = (txt, css, fn) => {
      let button = document.createElement("div");
      button.innerHTML = txt;
      button.classList.add(css);
      button.onclick = fn;
      fbWrap.appendChild(button);
    };
	let fracButtons = ['1/8', '1/4', '3/8', '1/2', '5/8', '3/4', 'clr', '7/8', 'del'];
	fracButtons.forEach(i=>{
		if(i=="cx") fButtonator("&#10006;", "cx", () => numpad.hide(1));
		else if(i=="clr") fButtonator("CLR", "clr", numpad.reset);
		else if(i=="del") fButtonator("DEL", "del", numpad.delete);
		else if(i=="ok") fButtonator("&#10004;", "ok", numpad.select);
		else fButtonator(i, "num", () => numpad.digit_frac(i));
	});

	let fMbWrap = document.querySelector("#numWrap #frac_more"),
    fmButtonator = (txt, css, fn) => {
      let button = document.createElement("div");
      button.innerHTML = txt;
      button.classList.add(css);
	  if(txt == "") button.classList.add("empty");
      button.onclick = fn;
      fMbWrap.appendChild(button);
    };
	let fracButtonsMore = ['1/16', '3/16', '5/16', '7/16', '9/16', '11/16', '13/16', '15/16', '', 'clr', '', 'del'];
	fracButtonsMore.forEach(i=>{
		if(i=="cx") fmButtonator("&#10006;", "cx", () => numpad.hide(1));
		else if(i=="clr") fmButtonator("CLR", "clr", numpad.reset);
		else if(i=="del") fmButtonator("DEL", "del", numpad.delete);
		else if(i=="ok") fmButtonator("&#10004;", "ok", numpad.select);
		else fmButtonator(i, "num", () => numpad.digit_frac(i));
	});
  },

  // (C) BUTTON ACTIONS
  // (C1) NUMBER (0 TO 9)
  digit: num => {
    // (C1-1) CURRENT VALUE
    let v = numpad.hDisplay.innerText;

    // (C1-2) WHOLE NUMBER (NO DECIMAL POINT)
    if (v.length < numpad.now.maxDig) {
      if (v=="0") { numpad.hDisplay.innerText = num; }
      else { numpad.hDisplay.innerText += num; }
    }
  },
  digit_frac: num => {
    // (C1-1) CURRENT VALUE
    numpad.hDisplayFrac.innerHTML = num;
  },

  // (C2) ADD DECIMAL POINT
  dot: () => { if (numpad.hDisplay.value.indexOf(".") == -1) {
    if (numpad.hDisplay.value=="0") { numpad.hDisplay.value = "0."; }
    else { numpad.hDisplay.value += "."; }
  }},

  // (C3) BACKSPACE
  delete: () => {
	let numWrap = document.querySelector("#numWrap > div.show");
	  
	if(numWrap.id == "full") {
		var length = numpad.hDisplay.innerText.length;
		if (length == 1) { numpad.hDisplay.innerText = 0; }
		else { numpad.hDisplay.innerText = numpad.hDisplay.innerText.substring(0, length - 1); }
	} else {
		numpad.hDisplayFrac.innerText = "";
	}
  },

  // (C4) CLEAR ALL
  reset: () => {numpad.hDisplay.innerText = "0", numpad.hDisplayFrac.innerText = ""},

  // (C5) OK - SET VALUE
  select: () => {
    let v = Number(numpad.hDisplay.innerText)+(eval(numpad.hDisplayFrac.innerText)? eval(numpad.hDisplayFrac.innerText):0);
    let type = { min: 'minW', max: 'maxW' };
    if((numpad.now.target.id).indexOf('height')!=-1) {
        type.min='minH';
        type.max='maxH';
    }
    if((numpad.now.target.id).indexOf('depth')!=-1){
      type.min='minD';
      type.max='maxD';
    }
	let ext_val = numpad.now.target.value;
    console.log('selectedItem',Number(selectedItem.metadata.measurements[type.min])/2.54, Number(selectedItem.metadata.measurements[type.max])/2.54)
    if(Number(selectedItem.metadata.measurements[type.min])/2.54<=v && Number(selectedItem.metadata.measurements[type.max])/2.54 >= v){
		numpad.now.target.value = v;
		qty('#'+numpad.now.target.id,'','').then(res => {
			if(!res) numpad.now.target.value = ext_val;
			else {
				numpad.hide();
				if (numpad.now.onselect) { numpad.now.onselect(v); }
			}
		})
    } else
		alert('Measurement for the selected item must be in between from ' +Number(selectedItem.metadata.measurements[type.min])/2.54+"\""+" to "+Number(selectedItem.metadata.measurements[type.max])/2.54+"\"");
  },
  switchpad: (v) => {
	  let swWrap = document.querySelectorAll("#numWrap > div");
	  let nsWrap = document.querySelectorAll("#numSwitch > button");

	  swWrap.forEach((ele, index) => {
		ele.classList.remove("show");
		nsWrap[index].classList.remove("active");

		if(v=="x/x" && index==1) {ele.classList.add("show"); nsWrap[index].classList.add("active");}
		else if(v=="x/xx" && index==2) {ele.classList.add("show"); nsWrap[index].classList.add("active");}
		else if(v=="X" && index==0) {ele.classList.add("show"); nsWrap[index].classList.add("active");};
	  });
  },
  // (D) SHOW NUMPAD
  show: instance => {
	instance.maxDig = 3; instance.maxDec = 4;

    // (D1) SET CURRENT INSTANCE + DISPLAY VALUE
	numpad.hDisplay.innerText = numpad.hDisplayFrac.innerText = "";
	numpad.switchpad("X");
    numpad.now = instance;
    let cv = instance.target.value;
    
    cv = fracPlaintoDec(cv);
    console.log('cv',cv)
    if (cv=="") { cv = "0"; }
    
	if(cv && cv.indexOf(".") != -1) {
		let num_parts = decToFracPlain(cv).split(" ");
		numpad.hDisplay.innerText = num_parts[0];
		numpad.hDisplayFrac.innerText = (num_parts.length>1? num_parts[1]: "");
	} else numpad.hDisplay.innerText = cv;
   
    // (D3) SHOW NUMPAD
    numpad.hWrap.classList.add("open");
  },

  // (E) HIDE/CLOSE NUMPAD
  hide: manual => {
    if (manual && numpad.now.oncancel) { numpad.now.oncancel(); }
    numpad.hWrap.classList.remove("open");
  },

  // (F) ATTACH NUMPAD TO INPUT FIELD
  //  target: required, target field.
  //  maxDig: optional, maximum number of digits, default 10.
  //  maxDec: optional, maximum number of decimal places, default 2.
  //  onselect: optional, function to call after selecting number.
  //  oncancel: optional, function to call after canceling.
  attach: instance => {
    // (F1) DEFAULT OPTIONS
    if (instance.maxDig === undefined) { instance.maxDig = 3; }
    if (instance.maxDec === undefined) { instance.maxDec = 4; }

    // (F2) GET + SET TARGET OPTIONS
    instance.target.readOnly = true; // prevent onscreen keyboard
    instance.target.addEventListener("click", () => numpad.show(instance));
  }
};

var decToFracPlain = function(decimal) {
  if (Number(decimal)) {
    var integerPart = Math.floor(decimal);
    var decimalPart = decimal - integerPart;

    var denominator = 1;

    while (decimalPart % 1 !== 0) {
      decimalPart *= 10;
      denominator *= 10;
    }

    var factor = highestCommonFactor(decimalPart, denominator);
    denominator = denominator / factor;
    var numerator = decimalPart / factor;

    if (integerPart > 0) {
      if (numerator)
        return `${integerPart} ${numerator}/${denominator}`;
      else
        return `${integerPart}`;
    } else {
      return `${numerator}/${denominator}`;
    }
  }
}

function highestCommonFactor(a, b) {
  if (b == 0) return a;
  return highestCommonFactor(b, a % b);
}
var fracPlaintoDec = function(fVal){
let val = fVal.split(" ");
let res = Number(val[0])+Number(val.length>1?fractoDec(val[1]):0);
if(val.length==1 && val[0].indexOf('/')!=-1)
    res=fractoDec(val[0]);						 
console.log('result',res)
return ""+res;
}
function fractoDec(frac){
  let num = frac.split("/")
return((num[0]/num[1]))
}
window.addEventListener("DOMContentLoaded", numpad.init);