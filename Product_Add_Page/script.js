let inputs;
window.onload = async function(){

    //Event for refreshing additionals input fields (i tried to do it with scss but failed :( ))
    let select = document.getElementById("productType").addEventListener('change', (e) => {
        document.querySelectorAll("#product_form #additional-fields > div").forEach(element => {
            element.style.display = "none";
        });
        switch(e.target.value){
            case "dvd":
                document.getElementById("DVD").style.display = "block";
                break;
            case "book":
                document.getElementById("Book").style.display = "block";
                break;
            case "furniture":
                document.getElementById("Furniture").style.display = "block";
                break;
        }
    });

    //Save button, event
    document.getElementById("save-product-btn").addEventListener('click', async (e) => {
        document.querySelectorAll(".error").forEach(element => {//-//  
            element.parentNode.removeChild(element);              // Cleaning errors
        });                    //--------------------------------//

        const isFormFilled = await checkForm(); // <-  Checks if Form is correct 

        if(isFormFilled){
            postArray = {};
            inputs.forEach(element => { //---------------------------// Removing unnecessary whitespaces          <-|||
                    postArray[element.id] = element.value.trim();   //  and adding ready values to array           
            });                 //---------------------------------//                                             

            let selctor = document.getElementById('productType'); //-----------------// Adding selected option       ^
            postArray[selctor.id] = selctor.options[selctor.selectedIndex].value;//-//  to made before input array - ^ 
            ThePepeWorker(JSON.stringify(postArray)).then((res) => { //Sending "ThePepeWorker" (Fetch) to go grab some response
                console.log(res);
                if(res.trim() != "true"){ //-----------------------------//typeof(res) is because "ThePepeWorker" (Fetch) can return true value 
                    try{     //---------------------------//                // that mean everythin went right or JSON with list of errors that could accour with input fields
                        HandOverErrors(JSON.parse(res)); //
                    }                                   //
                    catch{}     //---------------------//Try and catch is not really necessary but usefull for debugging
                }
                else{
                    
                    window.location.replace('http://task.squirrel.net.pl/');
                }

            });
        }

    });
}

/**
 * This function is responsible for collecting
 * all wrong filled form fields
 * @returns {(true|HandOverErrors)} true stands for correctly made form and the other for handing over errors to fields
 */
async function checkForm(){
    // JSON list of errors                               
    let errorCode = JSON.parse(`{ "elements":[] }`);
    let errorType = "";

    inputs = document.querySelectorAll("input");
    let selector = document.getElementById("productType");

        //Checking if any option was selected
        if( selector.value == "null"){
            errorType = "This needs to be selected!";
            errorCode.elements.push(await JSON.parse(`{ "element_id":"${selector.id}", "error":"${errorType}" }`));
            inputs = [inputs[0],inputs[1], inputs[2]]
        }
        else{
            errorType = "This input can't be empty!";
            //select elements that needs to be filled in certain option
            let specificTypeInputs;
            switch(selector.value){
                case "dvd":
                    specificTypeInputs = document.querySelectorAll("#DVD > input");
                break;
                case "book":
                    specificTypeInputs = document.querySelectorAll("#Book > input");
                break;
                case "furniture":
                    specificTypeInputs = document.querySelectorAll("#Furniture > input");
                break;
            }
            inputs = [inputs[0],inputs[1], inputs[2] , ...specificTypeInputs];
        }

    // Chceck if first static inputs are empty and if the ones with type = "number" are infact a number
    for(let element of inputs){
            if(element.value == "" || element.value == null){
                errorType = "This input can't be empty!";
                errorCode.elements.push(await JSON.parse(`{ "element_id":"${element.id}", "error":"${errorType}" }`));
            }else{
                if(element.type == "number"){
                    if(isNaN(element.value)){
                        errorType = "This input can only have numbers!";
                        errorCode.elements.push(await JSON.parse(`{ "element_id":"${element.id}", "error":"${errorType}" }`));
                    }
                    else
                        if(parseInt(element.value) <= 0){
                            errorType = "Number can't be negative!";
                            errorCode.elements.push(await JSON.parse(`{ "element_id":"${element.id}", "error":"${errorType}" }`));
                        }

                }
            }

        
    };

    if(errorCode.elements.length){
        HandOverErrors(errorCode);
        return await false;
    }


    return await true;
}

/**
 * @constructor
 * @param {JSON} errorCode - List errors. 
 */
function HandOverErrors(errorCode){
    console.log(errorCode.elements)

    errorCode.elements.forEach(element => { //-----------------------------//
        let span = document.createElement("span");                        //
        span.style.color = "red"                                         //That monster here is just finding DOM element by id 
        span.innerHTML = element['error'];                              // and giving corresponding error
        span.classList += "error";                                     //  
        document.getElementById(element['element_id']).after(span);//-//
    });

}


/**
 * Fetch for additional value checking and making insert to database 
 * @constructor
 * @param {Array} array - ["elements"=>[  ]].
 * @returns {JSON}
 */
function ThePepeWorker(array){ 
    return fetch("http://task.squirrel.net.pl/Product_Add_Page/addProduct.php", {
        method: "POST",
        headers: {
          "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8",
        },
        body: `array=${array}`,
      })
      .then((response) => response.text())
      .then((res) => {return res;}); 
      
}