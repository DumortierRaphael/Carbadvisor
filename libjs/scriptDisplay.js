function genericDisplay(e, classe){
    e.preventDefault();
    document.querySelectorAll("body>div").forEach((div)=>{
        div.hidden = true;
    });
    document.querySelector("div." + classe).hidden = false;
}