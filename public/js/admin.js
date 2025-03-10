let buttonOpen = document.getElementsByClassName("showSidebar");
let buttonClose = document.getElementsByClassName("hideSidebar");

let text = document.getElementById("randomText");

for(let i=0; i < buttonOpen.length; i++){
    buttonOpen[i].addEventListener("click", ()=>{
        mainBox.style.visibility = "visible";
        if(buttonOpen[i].value != 0){
            text.innerHTML = "Edit Data";
            input.changeInputValue(buttonOpen[i].value);
        }
        else{
            text.innerHTML = "Create New Data";
            input.clearInputValue();
        }
    });
}

for(let i = 0; i < buttonClose.length; i++){
    buttonClose[i].addEventListener("click", ()=>{
        mainBox.style.visibility = "hidden";
    });
}

const input = {
    name : document.getElementById("name-inp"),
    desc : document.getElementById("desc-inp"),
    stock : document.getElementById("stock-inp"),
    price : document.getElementById("price-inp"),
    image : document.getElementById("image-inp"),
    display : document.getElementById("display-inp"),
    changeInputValue : function(item_id){
        let initialValue;
        const res = fetch("pages.admin.admin", {
            method: 'POST',
            headers: {
                "Content-Type": "application/json",
                "Accept": "application/json",
                "X-Requested-With": "XMLHttpRequest",
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
            },
            credentials: "same-origin",
            body: JSON.stringify({
                'id': initialValue.id,
                'name': initialValue.name,
                'stock': initialValue.stock,
                'price': initialValue.price,
                'description': initialValue.desc,
                'img': initialValue.image,
            })
        })
        .then((response) => response.json());

        this.name.value = initialValue.name;
        this.desc.value = initialValue.desc;
        this.stock.value = initialValue.stock;
        this.price.value = initialValue.price;
        this.image.value = initialValue.image;
        this.display.src = initialValue.image;
    },
    clearInputValue : function(){
        this.name.value = "";
        this.desc.value = "";
        this.stock.value = "";
        this.price.value = "";
        this.image.value = "";
        this.display.src = "";
    },
};

