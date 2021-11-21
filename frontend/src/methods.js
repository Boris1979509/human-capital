export default {
    $createYearsRange(range) {
        const yearsRange = [];
        let obj = {};

        let yearFrom = (new Date().getFullYear() + 5) - range;

        for (let i = 1; i <= range; i++) {
            obj = {};
            obj.id = i;
            obj.name = yearFrom + i;

            yearsRange.push(obj);
        }

        return yearsRange;
    },

    $createYearsRangeFromTo(from, to) {
        const yearsRange = [];
        let obj = {};

        // let yearFrom = (new Date().getFullYear() + 5) - range;

        for (let i = from; i <= to; i++) {
            obj = {};
            obj.id = i;
            obj.name = i;

            yearsRange.push(obj);
        }

        return yearsRange;
    },

    async $uploadAvatar(blob, apiUrl, userId, fileType) {
        const formData = new FormData();
        const url = `${process.env.VUE_APP_DEFAULT_DEVELOP_HOST}${apiUrl}`;
        const config = {headers: {'Content-Type': 'multipart/form-data'}};
        let fileName;

        if (fileType !== null) {
            fileName = fileType.split('/');
            fileName = fileName[fileName.length - 1];
        }

        formData.append('file', blob, `avatar.${fileName}`);
        if (userId) formData.append('id', userId);

        const response = await this.$http.post(url, formData, config)
          .then((resolve) => resolve.data.data.avatar.url)
          .catch((error) => {
            console.log(error);
        });

        return response;
    },

    $getMimeType(file, fallback = null) {
        const byteArray = (new Uint8Array(file)).subarray(0, 4);
        let header = '';
        for (let i = 0; i < byteArray.length; i++) {
            header += byteArray[i].toString(16);
        }
        switch (header) {
            case "89504e47":
                return "image/png";
            case "47494638":
                return "image/gif";
            case "ffd8ffe0":
            case "ffd8ffe1":
            case "ffd8ffe2":
            case "ffd8ffe3":
            case "ffd8ffe8":
                return "image/jpeg";
            default:
                return fallback;
        }
    },

    $getRandomColor(length) {
        return Math.floor(Math.random() * length);
    },

    /**
     * http://jsfiddle.net/dizzy2/XXvtZ/
     *
     * @param n integer количество
     * @param text_forms array варианты склонений [для одного, для двух-четырёх, больше четырёх]
     * @returns string
     */
    $num2str(n, text_forms) {
        n = Math.abs(n) % 100;
        let n1 = n % 10;

        if (n > 10 && n < 20) {
            return text_forms[2];
        }

        if (n1 > 1 && n1 < 5) {
            return text_forms[1];
        }

        if (n1 === 1) {
            return text_forms[0];
        }
        return text_forms[2];
    },
}
