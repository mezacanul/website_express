export var menuItem = (el) => {
    return `
        <div class="item" onclick="previewItem(${el.sku})">
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