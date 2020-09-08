import Vue from 'vue'
import axios from 'axios'

Vue.filter('currency', function (money){
    return accounting.formatMoney(money, "Rp ", 2, '.', ',')
})

new Vue({
    el: "#dw",
    data: {
        product: {
            id: '',
            qty: '',
            price: '',
            name: '',
            photo: ''
        }
    },
    //untuk memeriksa perubahan
    watch: {
        //apabia nildi dari product > id berubah maka
        'product.id': function (){
            //mengecek jika nilai dari product id ada
            if(this.product.id){
                //jalankan method getProduct
                this.getProduct()
            }
        }
    },
    //menggunakan select2 ketika file di-load
    mounted(){
        $('#product_id').select2({
            width: '100%'
        }).on('change', () => {
            //apabila terjadi perubahan nilai yang dipilih,
            //maka nilai tersebut akan disimpan di dalam
            //variabel product.id_product
            this.product.id = $("#product_id").val();
        })
    },
    methods: {
        //fetch ke server menggunakan axios dengan mengirimkan
        //parameter id dengan url /api/product/{id}
        getProduct(){
            axios.get(`/api/product/${this.product.id}`)
                .then((response) => {
                    //assign data yang diterima dari server ke var production
                    this.product = response.data
                })
        }
    }
})
