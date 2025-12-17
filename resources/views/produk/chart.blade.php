

@extends('layouts.master')
@section('title', 'Aplikasi Toko Skincare')
@section('content')

<div class="container">
    <h1>Chart Produk</h1>
    <div class="card-body">
        <div id="chartProduk"></div>
        <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

        <script>
            var labelProduk = {!! json_encode($dataLabel) !!};
            var dataStock = {!! json_encode($dataStock) !!};

            var options = {
                series: [
                    {
                        name: 'Total Produk',
                        data: dataStock
                    }
                ],
                chart: {
                    type: 'bar',
                    height: 350
                },
                legend: {
                    show: false
                },
                plotOptions: {
                    bar: {
                    horizontal: false,
                    columnWidth: '55%',
                    endingShape: 'rounded',
                    distributed: true
                }
            },
            dataLabels: {
                enabled: false
            },
            stroke: {
                show: true,
                width: 2,
                colors: ['transparent']
            },
            xaxis: {
                title: {
                    text: 'Produk'
                },
                categories: labelProduk,
            },
            yaxis: {
                title: {
                    text: 'Stok'
                }
            },
            fill: {
                opacity: 1
            },
            tooltip: {
                y: {
                    formatter: function (val) {
                        return val + "pcs"
                    }
                }
            }
        };

        var chart = new ApexCharts(document.querySelector("#chartProduk"), options);
        chart.render();
    </script>
    @endsection


    </div>
</div>