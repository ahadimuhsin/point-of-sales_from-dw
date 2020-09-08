import Vue from 'vue'
import axios from 'axios'

//import sweetalert library
import VueSweetalert2 from 'vue-sweetalert2';

Vue.filter('currency', function (money){
    return accounting.formatMoney(money, "Rp ", 2, '.', ',')
})

//use sweetalert2
Vue.use(VueSweetalert2);
new Vue({
    el: "#dw",
    data: {
        product: {
            id: '',
            qty: '',
            price: '',
            name: '',
            photo: ''
        },
        //untuk menyimpan cart
        cart:{
            product_id: '',
            qty: 1
        },
        customer: {
            email: ''
        },
        //untuk menampung list cart
        shoppingCart: [],
        submitCart: false,
        formCustomer: false,
        resultStatus: false,
        submitForm: false,
        errorMessage: '',
        message: ''
    },
    //untuk memeriksa perubahan
    watch: {
        //apabia nildi dari product.id_product berubah maka
        'product.id': function (){
            //mengecek jika nilai dari product.id_product ada
            if(this.product.id){
                //jalankan method getProduct
                this.getProduct()
            }
        },
        'cart.product_id': function (){
            if(this.cart.product_id){
                this.getProduct()
            }
        },
        //apabila nilai dari customer.email berubah,
        //maka formCustomer diset false dan properti
        //customer jadi kosong
        'customer.email': function (){
          this.formCustomer = false
          if(this.customer.name !== ''){
              this.customer = {
                  name: '',
                  phone: '',
                  address: ''
              }
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
            this.cart.product_id = $("#product_id").val();
        });

        //memanggil method getCart untuk me-load cookie cart
        this.getCart()
    },
    methods: {
        //fetch ke server menggunakan axios dengan mengirimkan
        //parameter id dengan url /api/product/{id}
        getProduct(){
            axios.get(`api/product/${this.cart.product_id}`)
                .then((response) => {
                    //assign data yang diterima dari server ke var production
                    this.product = response.data
                })
        },

        //method untuk menambahkan product yang dipilih ke dalam keranjang
        addToCart(){
            this.submitCart = true;
            //send data ke server
            axios.post('api/cart', this.cart)
                .then((response) => {
                    setTimeout(() => {
                        //apabila berhasil, data disimpan ke dalam shoppingCart
                        this.shoppingCart = response.data
                        //mengosongkan var
                        this.cart.product_id = ''
                        this.cart.qty = 1
                        this.product = {
                            id: '',
                            price: '',
                            name: '',
                            photo: ''
                        }
                        $('#product_id').val('')
                        this.submitCart = false
                    }, 2000)
                })
                .catch((error) =>{

                })
        },
        //mengambil list cart yang telah disimpan
        getCart(){
            //ambil data dari server
            axios.get('api/cart')
                .then((response) =>{
                    //disimpan ke dalam keranjang
                    this.shoppingCart = response.data
                })
        },

        //menghapus Cart
        removeCart(id){
            //menampilkan konfirmasi sweetalert
            this.$swal({
                title: 'Kamu Yakin?',
                text: 'Kamu tidak dapat mengembalikan tindakan ini!',
                type: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Lanjutkan!',
                cancelButtonText: 'Batal',
                showCloseButton: true,
                showLoaderOnConfirm: true,
                preConfirm: () => {
                    return new Promise((resolve) => {
                        setTimeout(()=> {
                            resolve()
                        }, 2000)
                    })
                },
                allowOutsideClick: () => !this.$swal.isLoading()
            }).then((result) => {
                //apabila disetujui
                if(result.value){
                    //kirim data ke server
                    axios.delete(`api/cart/${id}`)
                        .then((response) => {
                            //load cart yang baru
                            this.getCart();
                        })
                        .catch((error) => {
                            console.log(error)
                        })
                }
            })
        },

        searchCustomer(){
            axios.post('api/customer/search', {
                email: this.customer.email
            })
                .then((response) => {
                    if(response.data.status == 'success'){
                        this.customer = response.data.data
                        this.resultStatus = true
                    }
                    this.formCustomer= true
                })
                .catch((error) => {
                    console.log(error)
                })
        },

        sendOrder(){
            //mengosongkan var errorMessage dan message
            this.errorMessage = ''
            this.message = ''

            //jika var customer.email dan kawan2nya tidak kosong
            if(this.customer.email != '' && this.customer.name != '' && this.customer.phone != '' && this.customer.address != ''){
                //tampilkan dialog konfirmasi
                this.$swal({
                    title: 'Kamu Yakin?',
                    text: 'Kamu tidak dapat mengembalikan tindakan ini!',
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Lanjutkan!',
                    cancelButtonText: 'Batalkan!',
                    showCloseButton: true,
                    showLoaderOnConfirm: true,
                    preConfirm: () => {
                        return new Promise((resolve) => {
                            setTimeout(() => {
                                resolve()
                            }, 2000)
                        })
                    },
                    allowOutsideClick: () => !this.$swal.isLoading()
                }).then((result) => {
                    //jika disetujui
                    if(result.value){
                        //set submitForm jadi true
                        this.submitForm = true
                        //kirimkan datanya ke uri checkout
                        axios.post('checkout', this.customer)
                            .then((response) => {
                                setTimeout(() => {
                                    //jika responsenya berhasil, cart direload
                                    this.getCart();
                                    //message diset untuk ditampilkan
                                    this.message = response.data.message
                                    //form customer dikosongkan
                                    this.customer = {
                                        name: '',
                                        phone: '',
                                        address: ''
                                    }
                                    //submit form diset lagi jadi false
                                    this.submitForm = false
                                }, 1000)
                            })
                            .catch((error) => {
                                console.log(error)
                            })
                    }
                })
            }
            else{
                //jika form ada yg kosong
                this.errorMessage = 'Masih ada inputan yang kosong'
            }
        }
    }
})
