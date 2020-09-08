import Vue from 'vue';
import axios from 'axios';
import Chart from 'chart.js';

new Vue({
    el: "#dw",
    data: {
        //format data yang akan digunakan ke chart.js
        ChartData: {
            //type chart line
            type: 'line',
            data: {
                //membuat label yang nilainya dinamis
                labels: [],
                datasets: [
                    {
                        label: 'Total Penjualan',
                        //nilai data dinamis tergantung data yang diterima dari server
                        data: [],
                        backgroundColor: [
                            'rgba(71, 183, 132, .5)',
                            'rgba(71, 183, 132, .5)',
                            'rgba(71, 183, 132, .5)',
                            'rgba(71, 183, 132, .5)',
                            'rgba(71, 183, 132, .5)',
                            'rgba(71, 183, 132, .5)',
                            'rgba(71, 183, 132, .5)',
                        ],
                        borderColor: [
                            '#47b784',
                            '#47b784',
                            '#47b784',
                            '#47b784',
                            '#47b784',
                            '#47b784',
                            '#47b784',
                        ],
                        borderWidth: 3
                    }
                ]
            },
            options: {
                responsive: true,
                lineTension: 1,
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero: true,
                            padding: 25,
                        }
                    }]
                }
            }
        }
    },
    mounted() {
        //ketika aplikasi di-load, maka akan menjalankan method getData
        this.getData()
        //dan method createChart dengan parameter dan format dari chartData
        this.createChart('dw-chart', this.ChartData)
    },
    methods: {
        //method createChart dengan 2 parameter, dengan id dan data
        createChart(chartId, chartData){
            //mencari elemen dengan id sesuai parameter chartId
            const ctx = document.getElementById(chartId)

            //mendefinisikan chart js
            const myChart = new Chart (ctx, {
                type: chartData.type,
                data: chartData.data,
                options: chartData.options,
            });
        },
        //method getData() meminta data dari server
        getData(){
            //mengirimkan permintaan dengan endpoint /api/chart
            axios.get('api/chart')
            //responsenya
                .then((response) => {
                    //looping, pisahkan key dan value
                    Object.entries(response.data).forEach(
                        ([key, value]) => {
                            //dimana key (index) adalah tanggal
                            //masukkan ke dalam chartData > data > labels
                            this.ChartData.data.labels.push(key)
                            //value, dalam hal ini adalah total pesanan
                            //masukkan ke dalam chartData > data > datasets[0] > data
                            this.ChartData.data.datasets[0].data.push(value)
                        }
                    )
                })
        }
    }
})
