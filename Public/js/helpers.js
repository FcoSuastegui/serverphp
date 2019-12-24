const baseUrl = window.location.protocol + "//" + window.location.host + "/";

const helper = {

    post: async function(config = { url: '', data: '', formdata: false, extra: '' }) {
        config.url = config.url ? config.url : baseUrl;
        config.data = config.data ? config.data : '';
        config.formdata = config.formdata ? config.formdata : false
        config.extra = config.extra ? config.extra : '';

        let datos;
        if (!config.formdata) {
            datos = new FormData();
            if (Object.keys(config.data).length > 0) {
                for (let item in config.data) {
                    datos.append(item, config.data[item])
                }
            }
        } else {
            datos = new FormData(config.data)
            if (Object.keys(config.extra).length > 0) {
                for (let item in config.extra) {
                    datos.append(item, config.extra[item])
                }
            }
        }

        let res = await fetch(config.url, { method: 'POST', body: datos });
        if (!res.ok)
            throw new Error(res.status)

        return await res.json()
    },

    html: async function(config = { url: '' }) {
        config.url = config.url ? config.url : baseUrl;
        let res = await fetch(config.url, { method: 'GET' });
        if (!res.ok)
            res = await fetch(baseUrl + 'propietarios/site_media/proximamente.html', { method: 'GET' });

        return await res.text()
    }

}