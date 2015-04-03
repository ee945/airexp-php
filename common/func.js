var $menu=function(id) {
	return document.getElementById(id);
}

function show_menu(meval){
	var left_n=eval(meval);
	if (left_n.style.display=="none"){
		eval(meval+".style.display='';");
	}else{
		eval(meval+".style.display='none';");
	}
}

function show_menuB(numB){
	for(j=0;j<100;j++){
		 if(j!=numB){
			if($menu('Bli0'+j)){
				$menu('Bli0'+j).style.display='none';
				$menu('Bf0'+j).style.background='url(images/html/01.gif)';
			}
		}
	}
	if($('Bli0'+numB)){
		if($menu('Bli0'+numB).style.display=='block'){
			$menu('Bli0'+numB).style.display='none';
			$menu('Bf0'+numB).style.background='url(images/html/01.gif)';
		}else{
			$menu('Bli0'+numB).style.display='block';
			$menu('Bf0'+numB).style.background='url(images/html/02.gif)';
		}
	}
}


var temp=0;
function show_menuC(){
	if (temp==0){
		document.getElementById('LeftBox').style.display='none';
		document.getElementById('RightBox').style.marginLeft='0';
		document.getElementById('Mobile').style.background='url(images/html/center.gif)';

		temp=1;
	}else{
		document.getElementById('RightBox').style.marginLeft='222px';
	   	document.getElementById('LeftBox').style.display='block';
		document.getElementById('Mobile').style.background='url(images/html/center0.gif)';

		temp=0;
	}
}

function ismawb(){
	mawb=document.getElementById('mawb').value;
	if(document.getElementById('mawb').value!=""){
		if(mawb.substr(3,1)!="-"||mawb.length!=12){
			alert("Error Format");
			document.getElementById('mawb').focus();
		}else if(mawb.substr(4,8)!=""){
			a=mawb.substr(4,7);
			b=mawb.substr(11,1);
			if(a%7!=b){
				alert("Error MAWB no.");
				document.getElementById('mawb').focus();
			}
		}
	}
}

function ispaymt(){
	paymt=document.getElementById('paymt').value.toLocaleUpperCase();
	if(document.getElementById('paymt').value!=""){
		if(!(paymt=="CP"||paymt=="PP"||paymt=="CC"||paymt=="PC")){
			alert("ERROR Paymt");
			document.getElementById('paymt').focus();
		}
	}
}
