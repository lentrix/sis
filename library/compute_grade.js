function computeGrade(quiz, participation, exam, term){
    
    qweight = document.getElementById('qw').value*1;
    pweight = document.getElementById('pw').value*1;
    eweight = document.getElementById('ew').value*1;
    
    tq = document.getElementById('q' + term).value;
    tp = document.getElementById('p' + term).value;
    te = document.getElementById('e' + term).value;
    
    qr = getRating((quiz/tq)*100);
    pr = getRating((participation/tp) * 100);
    er = getRating((exam/te)*100);
	
    st = (qr*qweight) + (pr*pweight) + (er*eweight);
	tw = qweight+pweight+eweight;
	
	rating = st/tw;
    
	if(quiz=="dr" || participation=="dr" || exam=="dr")
		return "dr";
	else if(quiz=="wd" || participation=="wd" || exam=="wd")
		return "wd";
	else if(quiz=="ng" || participation=="ng" || exam=="ng")
		return "ng";
	else if(quiz=="-" || participation=="-" || exam=="-")
		return "-";	
	else if(!isNaN(rating))
    	return rating.toFixed(1);
    else
        return "-";
    
}

function getRating(percentage){
    if(percentage>=99) return '1.0';
    else if(percentage>=98) return '1.04';
    else if(percentage>=97) return '1.08';
    else if(percentage>=96) return '1.11';
    else if(percentage>=95) return '1.14';
    else if(percentage>=94) return '1.17';
    else if(percentage>=93) return '1.20';
    else if(percentage>=92) return '1.30';
    else if(percentage>=91) return '1.40';
    else if(percentage>=90) return '1.50';
    else if(percentage>=89) return '1.60';
    else if(percentage>=88) return '1.70';
    else if(percentage>=87) return '1.80';
    else if(percentage>=86) return '1.90';
    else if(percentage>=85) return '2.00';
    else if(percentage>=84) return '2.10';
    else if(percentage>=83) return '2.20';
    else if(percentage>=82) return '2.30';
    else if(percentage>=81) return '2.40';
    else if(percentage>=80) return '2.50';
    else if(percentage>=79) return '2.60';
    else if(percentage>=78) return '2.70';   
    else if(percentage>=77) return '2.80';   
    else if(percentage>=76) return '2.90';   
    else if(percentage>=75) return '3.00';
    else if(percentage>=71) return '3.10';   
    else if(percentage>=67) return '3.20';   
    else if(percentage>=63) return '3.30';   
    else if(percentage>=59) return '3.40';   
    else if(percentage>=58) return '3.50';   
    else if(percentage>=51) return '3.60';   
    else if(percentage>=47) return '3.70';   
    else if(percentage>=43) return '3.80';   
    else if(percentage>=39) return '3.90';   
    else if(percentage>=35) return '4.00';   
    else if(percentage>=31) return '4.10';   
    else if(percentage>=27) return '4.20';   
    else if(percentage>=23) return '4.30';   
    else if(percentage>=19) return '4.40';   
    else if(percentage>=15) return '4.50';   
    else if(percentage>=12) return '4.60';   
    else if(percentage>=9) return '4.70';   
    else if(percentage>=6) return '4.80';   
    else if(percentage>=3) return '4.90';   
    else if(percentage>=0) return '5.00';   
	else if(percentage=="ng") return "ng";
	else if(percentage=="wd") return "wd";
	else if(percentage=="dr") return "dr";
	else return '-';
}
