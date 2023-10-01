export default {
    data(){
        return {

        }
    },
    methods: {
        async callApi(method, url, dataObj) {
            try {
               return await axios({
                    method: method,
                    url: url,
                    data: dataObj
                })
            } catch (e) {
                return e.response
            }
        },
        i (desc, title = 'Яхши!') {
            this.$Notice.info({
                desc: desc,
                title: title
            });
        },
        s (desc, title = 'Зўр!') {
            this.$Notice.success({
                desc: desc,
                title: title
            });
        },
        w (desc, title = 'Oops!') {
            this.$Notice.warning({
                desc: desc,
                title: title
            });
        },
        err (desc, title = 'Хатолик!') {
            this.$Notice.error({
                desc: desc,
                title: title
            });
        },
        swr (desc = 'Нимадир нитто кетти! Қайта текшириб кўрингчи.', title = 'Oops') {
            this.$Notice.error({
                desc: desc,
                title: title
            });
        },
        formatPrice(value, lenght = 3){
            let number = parseFloat(value)
            return Number((number).toFixed(lenght))
        },
        myDateFormat(date, format){
            let myDate  = new Date(date)
            let year    = myDate.getFullYear()
            let month   = myDate.getMonth() + 1
            let day     = myDate.getDate()
            let hour    = myDate.getHours()
            let minut   = myDate.getMinutes()

            let formattedDate = day.toString().padStart(2, '0')+"."+month.toString().padStart(2, '0')+"."+year;
            if(format == "yyyy-mm-dd") {
                formattedDate = year+"-"+month.toString().padStart(2, '0')+"-"+day.toString().padStart(2, '0');
            } else if(format == "dd.mm.yyyy hh:mm") {
                formattedDate = day.toString().padStart(2, '0')+"."+month.toString().padStart(2, '0')+"."+year+" "+hour.toString().padStart(2, '0')+":"+minut.toString().padStart(2, '0');
            } else if(format == "mm.dd.yyyy hh:mm") {
                formattedDate = month.toString().padStart(2, '0')+"."+day.toString().padStart(2, '0')+"."+year+" "+hour.toString().padStart(2, '0')+":"+minut.toString().padStart(2, '0');
            } else if(format == "mm.dd.yyyy") {
                formattedDate = day.toString().padStart(2, '0')+"."+month.toString().padStart(2, '0')+"."+year;
            } else if(format == "yyyy-mm-dd hh:mm") {
                formattedDate = year+"-"+month.toString().padStart(2, '0')+"-"+day.toString().padStart(2, '0')+" "+hour.toString().padStart(2, '0')+":"+minut.toString().padStart(2, '0');
            }

            return formattedDate;
        },
        getYears () {
            return [
                {id: 2022, value: '2022', label: '2022'},
                {id: 2023, value: '2023', label: '2023'},
                {id: 2024, value: '2024', label: '2024'},
                {id: 2025, value: '2025', label: '2025'},
            ]
        },

        getMonthes() {
            return [
                {id: 1, value:'01', label: 'Январь'},
                {id: 2, value:'02', label: 'Февраль'},
                {id: 3, value:'03', label: 'Март'},
                {id: 4, value:'04', label: 'Апрель'},
                {id: 5, value:'05', label: 'Май'},
                {id: 6, value:'06', label: 'Июнь'},
                {id: 7, value:'07', label: 'Июль'},
                {id: 8, value:'08', label: 'Август'},
                {id: 9, value:'09', label: 'Сентябрь'},
                {id: 10, value:'10', label: 'Октябрь'},
                {id: 11, value:'11', label: 'Ноябрь'},
                {id: 12, value:'12', label: 'Декабрь'},
            ]
        }
    }
}
