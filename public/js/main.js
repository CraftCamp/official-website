const formToJson = form => {
    let jsonObject = {};

    for (const [key, value]  of form.entries()) {
        jsonObject[key] = value;
    }
    return JSON.stringify(jsonObject);
};