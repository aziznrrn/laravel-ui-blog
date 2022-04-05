const formatDate = (date, format = "Y/m/d") => {
    let d = new Date(date),
        month = "" + (d.getMonth() + 1),
        day = "" + d.getDate(),
        year = d.getFullYear(),
        hours = d.getHours(),
        minutes = d.getMinutes();

    if (month.length < 2) month = "0" + month;
    if (day.length < 2) day = "0" + day;

    const formats ={
        "Y/m/d": [year, month, day].join("/"),
        "d/m/Y": [day, month, year].join("/"),
        "m/d/Y": [month, day, year].join("/"),
        "Y/m/d H:i": [year, month, day].join("/") + " " + [hours, minutes].join(":"),
        "d/m/Y H:i": [day, month, year].join("/") + " " + [hours, minutes].join(":"),
        "m/d/Y H:i": [month, day, year].join("/") + " " + [hours, minutes].join(":"),
    }

    if (formats[format]) {
        return formats[format];
    }

    return date;
}