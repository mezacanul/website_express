// TEMPLATES
// TO DO: Figure out how to put these code on their respective file
var menuItem = (el) => { 
    return `
        <div class="item" onclick="location.assign('preview.html?sku=${el.sku}')">
        <!-- <div class="item" onclick="previewItem(${el.sku})"> -->
            <div class="itemFlexHalf">
                <p class="itemName">${el.name}</p>
                <p class="itemPriceRange">$${el.min} - $${el.max}</p>
            </div>
            <div class="itemFlexHalf">
                <img src="files/img/products/${el.fileId}">
            </div>
        </div>
    `
}

function capFirstLetter(string) {
    return (string.charAt(0).toUpperCase()) + (string.slice(1))
}

var itemDetails = (el, productTypes, priceRanges) => {
    typeOptions = ""
    productTypes.forEach(type => {
        typeOptions += `<option value="${type.productType}" ${el.type == type.productType ? "selected" : ""}>${capFirstLetter(type.productType)}</option>`
    })

    priceOptions = ""
    priceRanges.forEach(price => {
        priceOptions += `<option value="${price.priceId}" ${el.priceId == price.priceId ? "selected" : ""}>$${price.min} - $${price.max}</option>`
    })

    if(el.type == "jewelry"){
        ringSelect = `
            <div class="itemDetail typeDetail">
                    <label for="isRing">Is Ring: </label> 
                    <select>
                        <option value="0">No</option>
                        <option value="1" ${ el.isRing == 1 ? "selected" : "" }>Yes</option>
                    </select>
            </div>
        `
    } else { ringSelect = "" }

    if(el.isBundle == 1){
        bundleInput = `
            <input type="number" value=""${el.bundleAmount}">
        `
    } else { bundleInput = "" }
    
    description = (el.description).substring(1);
    description = description.substring(0, description.length - 1);
    description = JSON.parse(description)
    descText = ""
    description.forEach(desc => {
        if(desc.title){
            descText += (desc.title + "\n")
        }
        descText += (desc.text + "\n\n")
    });
    
    return `
        <div class="itemDetails">
            <div class="itemTextDetails">
                <div class="itemDetail">
                    <label for="name">Name: </label> 
                    <input type="text" value="${el.name}">
                </div>
                <div class="itemDetail">
                    <label for="description">Description: </label> 
                    <textarea>${descText}</textarea>
                </div>
                <div class="itemDetail typeDetail">
                    <label for="type">Type: </label> 
                    <select>
                        ${typeOptions}
                    </select>
                </div>
                <div class="itemDetail typeDetail">
                    <label for="priceRange">Price Range: </label> 
                    <select>
                        ${priceOptions}
                    </select>
                </div>
                <div class="itemDetail">
                    <label for="sku">SKU: </label> 
                    <input type="text" value="${el.sku}" disabled>
                </div>
                <div class="itemDetail">
                    <label for="fileId">File ID: </label> 
                    <input type="text" value="${el.fileId}" disabled>
                </div>
                ${ ringSelect }
                ${ bundleInput }
                <div class="actionsBlock">
                    <button disabled>Save</button>
                    <button>Delete</button>
                </div>
            </div>

            <img src="files/img/products/${el.fileId}">
        </div>
    `
}

// Begins Current File
function search(currentPage = 0) {
    // Clean results table
    $(".results").html("")
    
    // Define search parameters
    var searchParams = {}
    userInput = $("#search").serializeArray()
    userInput.forEach(param => {
        searchParams[param.name] = param.value
    });
    searchParams.action = "search"
    searchParams.offset = currentPage

    // Get price ranges
    $.post("get.php", {action: "getPriceRanges"}).then((data)=>{
        priceRanges = JSON.parse(data)

        // Get the results
        $.post("get.php", searchParams).then((data)=>{
            // console.log(data);
            results = JSON.parse(data);

            // console.log(priceRanges);
            results.map(el=>{
                var priceId = priceRanges.find(range => range.priceId == el.priceId)
                el.min = priceId.min
                el.max = priceId.max
                return el
            })

            // Add results to document
            results.forEach(el => {
                $(".results").append(menuItem(el))
            });

            // Count results and add pagination details
            searchParams.action = "countResults"
            $.post("get.php", searchParams).then((data)=>{
                total = (JSON.parse(data)[0].total)

                addPaginationDetails(total, currentPage)
                if(total > 20){
                    updatePaginationControls(total, currentPage)
                }
            })
        })
    })
}

function updatePaginationControls(total, currentPage) {
    lastPage = parseInt(total / 20)
    // console.log(currentPage, lastPage);
    // console.log(currentPage);
    $(".paginationControls .next").removeAttr("disabled")
    $(".paginationControls .next").attr("onclick", "search("+(currentPage+1)+")")
    $(".paginationControls .previous").attr("onclick", "search("+(currentPage-1)+")")

    if(currentPage > 0){
        if(currentPage == lastPage){
            $(".paginationControls .next").attr("disabled", "disabled")
            $(".paginationControls .previous").removeAttr("disabled")
        } else {
            $(".paginationControls .previous").removeAttr("disabled")
        }
    } else {
        $(".paginationControls .previous").attr("disabled", "disabled")
    }
}

function addPaginationDetails(total, currentPage) {
    lastPage = parseInt(total / 20)
    if(total > 0){
        if(total > 20){
            initialRange = (currentPage * 20)+1
            if(currentPage == lastPage){
                finalRange = total
            } else {
                finalRange = (currentPage * 20)+20
            }
            $(".paginationControls .info").html(`Showing ${initialRange} to ${finalRange} of ${total}`)
        } else {
            $(".paginationControls .info").html(`Showing ${total}`)
        }
    } else {
        $(".paginationControls .info").html(`Showing 0`)
    }
}

function previewAndEditItem() {
    // console.log("test");
    var url_string = window.location.href; 
    var url = new URL(url_string);
    var sku = url.searchParams.get("sku");

    $.post("get.php", {action: "getTypes"}).then((data)=>{
        productTypes = JSON.parse(data)
        
        $.post("get.php", {action: "getPriceRanges"}).then((data)=>{
            priceRanges = JSON.parse(data)

            $.post("get.php", { action: "getItemDetails", sku: sku }).then((data) => {
                product = JSON.parse(data)

                if(product.length == 1){
                    $(".itemBox").append(itemDetails(product[0], productTypes, priceRanges))
                }
            })
        })
    })
}