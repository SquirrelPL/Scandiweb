window.onload = async () => {
    res = JSON.parse(await getProducts());
    console.log(res)
    displayProducts(res);

    document.getElementById("delete-product-btn").addEventListener("click", async ()=>{
        let array = [];
        document.querySelectorAll(".delete-checkbox:checked").forEach(element => {
            array.push(element.parentElement.parentElement.children[1].children[0].innerHTML);

        });

        res = await removeProducts(array);

        if(res.trim() != ""){
            res2 = JSON.parse(await getProducts());
            console.log(res2);
            displayProducts(res2);
        }
    });

    
}

function displayProducts(res){
    res.forEach(element => {
        document.getElementById("product-list").innerHTML += 
        `
        <div class="item-box">
            <div class="head-base"><input type="checkbox" class="delete-checkbox"></div>
            <div class="item-box-content">
                <div class="item-box-data">${element.sku}</div>
                <div class="item-box-data">${element.name}</div>
                <div class="item-box-data">${element.price}</div>
                <div class="item-box-data">${element.strValue}</div>
            </div>
        </div>
        `;
    });

}




function getProducts(){ 
    document.querySelectorAll(".item-box").forEach(element => {
        element.remove();
    });
    return fetch("http://task.squirrel.net.pl/getProducts.php", {
        method: "POST",
        headers: {
          "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8",
        },
        body: ``,
      })
      .then((response) => response.text())
      .then((res) => {return res;}); 
      
}

function removeProducts(array){ 
    return fetch("http://task.squirrel.net.pl/removeProducts.php", {
        method: "POST",
        headers: {
          "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8",
        },
        body: `array=${array}`,
      })
      .then((response) => response.text())
      .then((res) => {return res;}); 
      
}