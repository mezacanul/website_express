function generateId(length) { 
    const abc = Array.from("abcdefghijklmnopqrstuvwxyz1234567890")
    var id = ""

    for (index = 0; index < length; index++) {
        id += abc[randNum(abc.length, 0)]
    }
    
    return id
}

function randNum (min, max) {
    return Math.floor(Math.random() * (max - min) ) + min;
}