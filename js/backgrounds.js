// On load
$.post("./server/get.php", { action: "getTypes" }).then((data)=>{
    try {
        types = JSON.parse(data)

        types.forEach(type => {
            type = (type.productType[0].toUpperCase()) + (type.productType).substring(1)
            option = `<option value="${type.productType}">${type}</option>`
            $("select[name='bgType']").append(option)
        });
    } catch (error) {
        console.log(data);   
    }
})

// After
$("input[name='bgAdd']").change(()=>{
    fd = getFilesParam($("input[name='bgAdd']"))
    fd.append("action", "previewBg")

    ajaxOptions = {
        url: './server/fileUpload.php',
        data: fd,
        processData: false,
        contentType: false,
        type: 'POST',
    }

    $.ajax(ajaxOptions).then((data)=>{
        demo = JSON.parse(data)
    })
})