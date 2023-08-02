// const 
const descriptorInput = $('input[name="descriptor"')
const target = $("#target")

// vars
var userForm = []

function getCookie(cname) {
    let name = cname + "=";
    let decodedCookie = decodeURIComponent(document.cookie);
    let ca = decodedCookie.split(';');
    for(let i = 0; i <ca.length; i++) {
        let c = ca[i];
        while (c.charAt(0) == ' ') {
            c = c.substring(1);
        }
        if (c.indexOf(name) == 0) {
            return c.substring(name.length, c.length);
        }
    }
    return "";
}

function addToDemosLog(action, error = null) {
    browserData = {
        url : $("input[name='url']").val(),
        corp : $("input[name='corp']").val(),
        type : $("select[name='type']").val(),
        template : ($("select[name='template'] option:checked").val()).replace("https://github.com/YanaEgorova/", ""),
        products : $("select[name='products']").val(),
        error,
        action,
        log: "demos",
    }

    $.post("server/log.php", browserData, (data)=>{
        // console.log(data);
    })
}

function create() {
    current_session = getCookie("current_session");

    // GUI handling
    $(".createDemoBtn").attr("disabled", "disabled")
    $(".downloadDemoBtn").attr("disabled", "disabled")
    $(".demoLinkContainer a").html("")
    $(".statusAnim").css("display", "block")
    $(".console").css("display", "none")
    $(".console p").html("")


    infoData = $("#mainForm").serializeArray()
    // console.log(infoData);
    // return
    infoData.forEach(form => {
        userForm[form.name] = form.value
    });
    websiteData = getResult(userForm)

    type = $("select[name='type']").val()
    nicheType = $("select[name='type']").find(":selected").attr("data-niche")
    prices = $("select[name='prices']").val()
    templateId = $("select[name='template'] option:checked").attr("data-id")
    toExpress = {
        current_session,
        template: userForm.template,
        websiteData: websiteData, 
        templateId, type, nicheType, prices,
        returnAddressId: userForm["returnAddressSelect"]
    }
    // console.log(toExpress); return;

    $.post("./server/express.php", toExpress).then((data)=>{
        try {
            demo = JSON.parse(data)
            demoUrl = (demo.serverPath).replace("../", "")
            siteName = (userForm["url"]).replace(".com", "")

            switch (userForm["products"]) {
                case "upload":
                    fd = getFilesParam($("input[name='productsFiles']"))
                    fd.append("action", "uploadProducts")
                    fd.append("newSitePath", demo.serverPath)
                    
                    ajaxOptions = {
                        url: './server/fileUpload.php',
                        data: fd,
                        processData: false,
                        contentType: false,
                        type: 'POST',
                    }
        
                    console.log(ajaxOptions);
                    
                    $.ajax(ajaxOptions).then((data)=>{
                        // return
                        // setTimeout(() => {
                        //     console.log(data);
                        // }, 4500);
                        demo = JSON.parse(data)
                        console.log(demo);
                    })
                    break;
                case "include":
                    // console.log("include");
                    // // DEV VAR
                    // // demo = { demo: "YanaEgorova-new-template-157-0c00355" }

                    // items = Object.values($(".results").children())
                    // items.pop(); items.pop();
                    // items = items.map(item=>{
                    //     return $(item).attr("data-sku")
                    // })

                    // params = {
                    //     items, demoPath: demo.serverPath, action: "includeProducts"
                    // }

                    // $.post("./server/fileUpload.php", params).then((data)=>{
                    //     try {
                    //         res = JSON.parse(data)
                    //     } catch (error) {
                    //         console.log(data);
                    //         console.log(error);
                    //     }
                    // })
                    // console.log("include");
                    break;
                case "default":
                    console.log("default");
                    break;
                default:
                    break;
            }

            addToDemosLog("create");
    
            $(".statusAnim").css( "display", "none" )
            $(".demoLinkContainer a").attr( "href", (demoUrl) )
            $(".demoLinkContainer a").html(userForm["url"])
            $(".createDemoBtn").removeAttr("disabled")
            $(".downloadDemoBtn").removeAttr("disabled")
            $(".downloadDemoBtn").attr( "onclick", `download("${demoUrl}", "${siteName}", "${current_session}")` )


        } catch (e) {
            addToDemosLog("create", data)
            console.log(data);
            displayDomConsole(e, data)
            return   
        }
    })
}

function displayDomConsole(error, serverResponse) {
    console.log(error, serverResponse)
    $(".console p").append(error+"<br>")
    $(".console p").append("(CHECK CONSOLE FOR MORE INFO)<br>")
    $(".console p").append(serverResponse)
    $(".console").css("display", "block")
    $(".statusAnim").css("display", "none")
    $(".createDemoBtn").removeAttr("disabled")
}

function getResult(data) {
    var text
    returnAddressTag = $("select[name='returnAddressSelect'] option:selected").html()
    data["dba"] = (data["url"].replace(/([A-Z])/g, ' $1').trim()).replace(/.com/g,'')

    text = `const WEBSITE_NAME = "${ data["dba"] }";
const WEBSITE_URL = "${ data["url"] }";
const WEBSITE_EMAIL = "support@${ data["url"] }";
const WEBSITE_DESCRIPTOR = "${ data["descriptor"] }";
const WEBSITE_PHONE = "${ data["phone"] }";
const WEBSITE_CORP = "${ data["corp"] }";
const WEBSITE_ADDRESS = "${ data["address"] }, USA";
const WEBSITE_RETURN_ADDRESS = "${ data["returnAddress"] }";
const WEBSITE_FULFILLMENT = "${ returnAddressTag }";`

    return text
}

// -- TO DO
// function getBatch() {
//     var searchParams = {
//         action: "getBatch",
//         type: $("select[name='type']").val(),
//         prices: $("select[name='prices']").val(),
//     }

//     $.post("./server/get.php", searchParams).then((data)=>{
//         console.log(data);
//     })
// }

function getFilesParam(input) {
    files = input.prop('files')
    files = Array.from(files)

    var fd = new FormData();    
    files.forEach((f, i) => {
        fd.append("f"+i, f)
    });
    return fd
}

function download(demoPath, siteName, session_id) {
    $(".createDemoBtn").attr("disabled", "disabled")
    $(".downloadDemoBtn").attr("disabled", "disabled")

    params = {
        session_id,
        action: "downloadDemo",
        demoPath: demoPath,
        siteName: siteName
    }
    
    $.post("./server/zip.php", params).then((data)=>{
        try {
            pathToZipFile = JSON.parse(data)
            window.open(pathToZipFile); 
            $(".createDemoBtn").removeAttr("disabled")
            $(".downloadDemoBtn").removeAttr("disabled")   

            addToDemosLog("download")
        } catch (error) {
            console.log(data);
        }
    })
}

function getPriceRanges() {
    return new Promise((resolve, reject)=>{
        $.post("./server/get.php", { action : "getPriceRanges" }).then((data)=>{
            resolve(data);
        })
    })
}

function getPriceList(bank) {
    return new Promise((resolve, reject)=>{
        $.post("./server/get.php", { action : "getPriceList", bank }).then((data)=>{
            resolve(data);
        })
    })
}

function getPriceParams(priceRanges, priceList) {
    result = priceList.map((p)=>{
        var priceId = 0
        var price = parseFloat(p.price)

        priceRanges.forEach(pR => {
            if(price >= parseFloat(pR.min) && price <= parseFloat(pR.max) ){
                priceId = parseInt(pR.priceId)
            }
        });
        return { price: price, priceId: priceId }
    })

    return result
}

function getBatch() {
    $(".results").html("")
    
    typeInput = $("select[name='type']").val()
    priceInput = $("select[name='prices']").val()
    
    Promise.all([getPriceRanges(), getPriceList(priceInput)])
    .then((data)=>{
        priceRanges = JSON.parse(data[0])
        priceList = JSON.parse(data[1])

        priceParams = getPriceParams(priceRanges, priceList)
        batchParams = {
            action: "getBatch",
            priceParams: priceParams, 
            type: typeInput
        }
        // console.log(JSON.stringify(priceParams));
        
        $.post("./server/get.php", batchParams )
        .then((data)=>{
            products = JSON.parse(data)
            products = products.map((p, i)=>{
                p[0].price = priceParams[i].price
                return p[0]
            })

            products.forEach(el=>{
                $(".results").append(batchItem(el))
            })
            // console.log(products, priceParams);
        })
    })
    
}

$("#productsSelect").change(()=>{
    $(".results").html("")
    switch (productsSelect.val()) {
        case "dont":
            break;
        case "include":
            getBatch()
            break;
        case "upload":
            break;
        default:
            break;
    }
})

function clearTemplateFields() {
    // $("input[name='main']").is(":checked") ? $("input[name='main']").click() : ""
    // $("input[name='sub']").is(":checked") ? $("input[name='sub']").click() : ""

    if($("input[name='main']").is(":checked")){
        $("input[name='main']").click()
    }
    if($("input[name='sub']").is(":checked")){
        $("input[name='sub']").click()
    }
    // $("input[name='main']").removeAttr("checked")
    // $("input[name='sub']").removeAttr("checked")   
    $(".bg_container").remove()
    $(".color_container").remove()
    $("textarea[name='css']").val("")
    $("textarea[name='js']").val("")
}

function getTemplateInfo(id) {
    clearTemplateFields()
    searchParams = {
        action: "getTemplateInfo",
        templateId: id
    }
    
    $.post("./server/get.php", searchParams).then((data)=>{
        template = JSON.parse(data)[0]
        
        $("input[name='templateId']").val(template.id)
        $("input[name='preview']").val(template.preview)
        $("input[name='url']").val(template.url)

        if(template.taglines != ""){
            taglines = JSON.parse(template.taglines)
            // console.log(taglines);
            if(!$("input[name='main']").is(":checked") && $.inArray("main", taglines) != -1) {
                $("input[name='main']").click()
            }
            if(!$("input[name='sub']").is(":checked") && $.inArray("sub", taglines) != -1) {
                $("input[name='sub']").click()
            }
            if(!$("input[name='second_main']").is(":checked") && $.inArray("second_main", taglines) != -1) {
                $("input[name='second_main']").click()
            }
            if(!$("input[name='second_sub']").is(":checked") && $.inArray("second_sub", taglines) != -1) {
                $("input[name='second_sub']").click()
            }
            // $("input[name='main']").attr("checked", ($.inArray("main", taglines) != -1 ? true : false))
            // $("input[name='sub']").attr("checked", ($.inArray("sub", taglines) != -1 ? true : false))
        } else {
            $("input[name='main']").is(":checked") ? $("input[name='main']").click() : ""
            $("input[name='sub']").is(":checked") ? $("input[name='sub']").click() : ""
            $("input[name='second_main']").is(":checked") ? $("input[name='second_main']").click() : ""
            $("input[name='second_sub']").is(":checked") ? $("input[name='second_sub']").click() : ""
        }

        if(template.bgs){
            bgs = JSON.parse(template.bgs)
            bgs.forEach(bg => {
                addBackground(bg)
            })
        } else {
            $(".bg_container").remove()
        }

        if(template.colors){
            colors = JSON.parse(template.colors)
            colors.forEach(color => {
                addColor(color)
            })
        } else {
            $(".color_container").remove()
        }

        $("textarea[name='css']").val(template.css)
        $("textarea[name='js']").val(template.js)

        if(template.type == "special"){
            $(".special_taglines").css("display", "block")
        } else {
            $(".special_taglines").css("display", "none")
        }

        $(".deleteBtn").attr("onclick", `deleteTemplate("${template.id}")`)
        // console.log(template);
    })
}

function addBackground(bg) {
    bgOption = `
    <div class="bg_container">
        <input class="bg" type="text" placeholder='${ bg ? bg : "File name" }' value="${ bg ? bg : "" }">
        <button type="button" onclick="deleteBg(this)">Delete</button>
    </div>`
    $(".bgs").append(bgOption)
}

function deleteBg(btn) {
    btn.closest("div").remove()
}

function addColor(color = "") {
    colorOption = `
    <div class="color_container">
        <input class="color" type="text" placeholder='${ color.color ? color.color : "Color id" }' value="${ color.color ? color.color : "" }" onkeyup="updateColor(this)">
        <select class="colorType">
            <option value="high" ${color.colorType == "high" ? "selected" : ""}>High Contrast</option>
            <option value="low" ${color.colorType == "low" ? "selected" : ""}>Low Contrast</option>
        </select>
        <button type="button" onclick="deleteColor(this)">Delete</button>
        <input type="color" value="${color.color}" oninput="updateColorField(this)">
    </div>
    `
    $(".colors").append(colorOption)
}

function deleteColor(btn) {
    btn.closest("div").remove()
}

function updateTemplate() {
    var bgs = []
    var colors = []
    var taglines = []

    $('.bg_container').each(function(i, obj) {
        bg = obj.querySelector("input").value
        if(bg != ""){
            bgs.push(bg)
        }
    });

    $('.color_container').each(function(i, obj) {
        color = obj.querySelector("input").value
        colorType = obj.querySelector("select").value
        if(color != "") {
            colors.push( { color, colorType } )
        }
    });

    if($("input[name='main']").is(":checked")){ taglines.push("main") }
    if($("input[name='sub']").is(":checked")){ taglines.push("sub") }
    if($("input[name='second_main']").is(":checked")){ taglines.push("second_main") }
    if($("input[name='second_sub']").is(":checked")){ taglines.push("second_sub") }

    formData = {
        id: $("input[name='templateId']").val(),
        url: $("input[name='url']").val(),
        preview: $("input[name='preview']").val(),
        taglines: JSON.stringify(taglines),
        bgs: JSON.stringify(bgs),
        colors: JSON.stringify(colors),
        css: $("textarea[name='css']").val(),
        js: $("textarea[name='js']").val(),
    }

    updateParams = {
        action: "updateTemplate",
        templateInfo: formData
    }

    $.post("./server/update.php", updateParams).then((data)=>{
        // console.log(data);
        if(JSON.parse(data)){
            requestStatus = JSON.parse(data)
            $(".updateSuccess").html("Template updated!")
            $(".updateSuccess").css("color", "green")
            $(".updateSuccess").css("opacity", 1)
        } else {
            $(".updateSuccess").html(data)
            $(".updateSuccess").css("color", "red")
            $(".updateSuccess").css("opacity", 1)
        }

        setTimeout(() => {
            $(".updateSuccess").css("opacity", 0)
        }, 3000);
    })
}

function updateColor(textInput) {
    colorPicker = textInput.parentNode.querySelector("input[type='color']")
    colorPicker.value = textInput.value
}

function updateColorField(colorInput){
    colorTextField = colorInput.parentNode.querySelector("input[type='text']")
    colorTextField.value = colorInput.value
}

function deleteTemplate(templateId) {
    params = {
        action: "deleteTemplate",
        templateId
    }
    $.post("./server/delete.php", params).then((data)=>{
        // $(".updateSuccess").html(data)
        // $(".updateSuccess").css("color", "red")
        // $(".updateSuccess").css("opacity", 1)

        if(JSON.parse(data) && (JSON.parse(data) == "ok")){
            // -- TO DO: Make function for update user message status
            $(".updateSuccess").html("Template deleted...")
            $(".updateSuccess").css("color", "green")
            $(".updateSuccess").css("opacity", 1)
        } else {
            $(".updateSuccess").html(data)
            $(".updateSuccess").css("color", "red")
            $(".updateSuccess").css("opacity", 1)
        }

        setTimeout(() => {
            $(".updateSuccess").css("opacity", 0)
        }, 3000);
    })
}

function selectRandTemplate() {
    templatesRef = $("select[name='template']").children()
    templatesRef[randNum(templatesRef.length, 0)].setAttribute("selected", "selected")

    previewUrl = $("select[name='template'] option:checked").attr("data-preview")
    $(".templatePreviewLink").attr("href", previewUrl)
    $(".templatePreviewLink").html($("select[name='template'] option:checked").text())
}

function switchThis(e) {
    item = $(e).closest(".item")
    priceId = item.attr("data-priceId")
    type = $("select[name='type']").val()
    
    params = { priceId, type, action: "switchProduct" }
    
    $.post("./server/get.php", params).then((data)=>{
        try {
            product = JSON.parse(data)

            item.attr("data-sku", product.sku)
            item.find("img").attr("src", `files/img/products/${product.fileId}`)
            item.find(".itemName").html(product.name)
            item.find(".itemName").attr("onclick", `location.assign('preview.html?sku=${product.sku}')`)
        } catch (error) {
            displayDomConsole(error, data)
        }
    })
}

function showAddTemplate() {
    $(".templateAdd").css("display", "block")
    $(".templateEdit").css("display", "none")
}

function showEditTemplate() {
    $(".templateEdit").css("display", "block")
    $(".templateAdd").css("display", "none")
}

function addTemplate() {
    preview = $("input[name='previewAdd']").val()
    url = $("input[name='urlAdd']").val()

    options = {
        action: "addTemplate",
        preview, url
    }

    $.post("./server/insert.php", options).then((data)=>{
        try {
            requestStatus = JSON.parse(data)
            if(requestStatus == "ok"){
                $("input[name='previewAdd']").val("")
                $("input[name='urlAdd']").val("")

                $(".templateAdd span").css("opacity", 1)
                setTimeout(() => {
                    location.reload()
                }, 2000);
            }
        } catch (error) {
            console.log(data);
        }
    })
}