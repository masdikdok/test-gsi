<!-- Menghubungkan dengan view template layouts/app -->
@extends('admin.layouts.app')

@section('title', 'Dashboard - Admin')

<!-- isi bagian konten -->
<!-- cara penulisan isi section yang panjang -->
@section('content')

<div class="row">
    <div class="col-12 col-sm-12">
        <div class="card mt-4">
            <div class="card-body">
                <div class="panel-body content-echarts content-is-loading" id="chart-produksi" style="min-height: 400px;"></div>
            </div>
        </div>
    </div>

    <div class="col-12 col-sm-6">
        <div class="card mt-4">
            <div class="card-body">
                <div class="content-echarts content-is-loading" id="chart-best-item" style="min-height: 400px;"></div>
            </div>
        </div>
    </div>
    <div class="col-12 col-sm-6">
        <div class="card mt-4">
            <div class="card-body">
                <div class="content-echarts content-is-loading" id="chart-best-lokasi" style="min-height: 400px;"></div>
            </div>
        </div>
    </div>

</div>

@endsection

@push('additional-js-end')

<script type="text/javascript" src="{{ asset("js/echarts-en.min.js") }}"></script>

<script type="text/javascript">
$(function(){
    let cvForm = {
        chartProduksi: function(params){
            var url = "{{ route('admin.chart.produksi') }}",
                targetElement = document.querySelector('#chart-produksi');

            // set content is loading in div
            targetElement.classList.toggle('content-is-loading', true);

            Ajax.call(params, url, (resp) => {
                if (resp.result) {
                    // based on prepared DOM, initialize echarts instance
                    var model = resp.model,
                        myChart = echarts.init(targetElement),
                        arraySeries = [],
                        arrayLegend = [],
                        arrayDate = [];

                    // prepare data
                    model.data.forEach(function(item, index){
                        var tempDate = [],
                            tempGrandTotal = [];

                        item.data.forEach(function(t, i){
                            tempDate.push(t.DATE);
                            tempGrandTotal.push(t.qty);
                        });

                        arraySeries.push({
                            name: item.kode,
                            type: 'bar',
                            stack: '广告',
                            data: tempGrandTotal,
                            markLine: {
                                data: [
                                    {type: 'average', name: item.kode}
                                ]
                            }
                        });

                        arrayLegend.push(item.kode);
                        arrayDate = tempDate;
                    });

                    // use configuration item and data specified to show chart
                    myChart.setOption({
                        baseOption: {
                            title: {
                                text: model.title,
                                subtext: model.subtitle
                            },
                            tooltip: {
                                trigger: 'axis',
                                axisPointer: {
                                    type: 'cross',
                                }
                            },
                            legend: {
                                type: 'scroll',
                                bottom: 0,
                                data: arrayLegend
                            },
                            toolbox: {
                                show: true,
                                feature: {
                                    magicType: {show: true, type: ['line', 'bar', 'stack']},
                                }
                            },
                            calculable: true,
                            xAxis: [
                                {
                                    type: 'category',
                                    data: arrayDate
                                }
                            ],
                            yAxis: [
                                {
                                    type: 'value'
                                }
                            ],
                            grid: {
                                top: '20%',
                                left: '5.5%',
                                right: '5%',
                            },
                            series: arraySeries
                        },
                        media: []
                    });
                }else{
                    Alert.toast(resp.alert.type, resp.alert.message);
                }

                // set content is loading in div
                targetElement.classList.toggle('content-is-loading', false);
            }, 'GET', true);
        },
        chartBestItem: function(params){
            var url = "{{ route('admin.chart.best.item') }}",
                targetElement = document.querySelector('#chart-best-item');

            // set content is loading in div
            targetElement.classList.toggle('content-is-loading', true);

            Ajax.call(params, url, (resp) => {
                if (resp.result) {
                    // based on prepared DOM, initialize echarts instance
                    var model = resp.model,
                        myChart = echarts.init(targetElement),
                        arraySeries = [],
                        arrayLegend = [],
                        arrayDate = [];

                    resp.model.data.forEach(function(item, index){
                        arrayLegend.push(item.nama);
                        arraySeries.push({
                            'value' : item.total_transaksi,
                            'name' : item.nama,
                        })
                    })

                    // use configuration item and data specified to show chart
                    myChart.setOption({
                        baseOption: {
                            title: {
                                text: model.title,
                                subtext: model.subtitle
                            },
                            legend: {
                                type: 'scroll',
                                bottom: 0,
                                data: arrayLegend
                            },
                            tooltip: {
                                trigger: 'item',
                                formatter: model.title + ' <br/>{b} : {c} ({d}%)'
                            },
                            toolbox: {
                                show: true,
                                feature: {
                                    mark: {show: true},
                                    dataView: {show: true, readOnly: false},
                                    restore: {show: true},
                                    saveAsImage: {show: true}
                                }
                            },
                            series: [
                                {
                                   name: '面积模式',
                                   type: 'pie',
                                   radius: [30, 80],
                                   center: ['50%', '50%'],
                                   itemStyle: {
                                       borderRadius: 8
                                   },
                                   data: arraySeries,
                                   emphasis: {
                                       itemStyle: {
                                           shadowBlur: 10,
                                           shadowOffsetX: 0,
                                           shadowColor: 'rgba(0, 0, 0, 0.5)'
                                       }
                                   }
                               }
                            ]
                        },
                        media: []
                    });
                }else{
                    Alert.toast(resp.alert.type, resp.alert.message);
                }

                // set content is loading in div
                targetElement.classList.toggle('content-is-loading', false);
            }, 'GET', true);
        },
        chartBestLokasi: function(params){
            var url = "{{ route('admin.chart.best.lokasi') }}",
                targetElement = document.querySelector('#chart-best-lokasi');

            // set content is loading in div
            targetElement.classList.toggle('content-is-loading', true);

            Ajax.call(params, url, (resp) => {
                if (resp.result) {
                    // based on prepared DOM, initialize echarts instance
                    var model = resp.model,
                        myChart = echarts.init(targetElement),
                        arraySeries = [],
                        arrayLegend = [];

                    resp.model.data.forEach(function(item, index){
                        arrayLegend.push(item.nama);
                        arraySeries.push({
                            'value' : item.total_transaksi,
                            'name' : item.nama,
                        })
                    })

                    // use configuration item and data specified to show chart
                    myChart.setOption({
                        baseOption: {
                            title: {
                                text: model.title,
                                subtext: model.subtitle
                            },
                            legend: {
                                type: 'scroll',
                                bottom: 0,
                                data: arrayLegend
                            },
                            tooltip: {
                                trigger: 'item',
                                formatter: model.title + ' <br/>{b} : {c} ({d}%)'
                            },
                            toolbox: {
                                show: true,
                                feature: {
                                    mark: {show: true},
                                    dataView: {show: true, readOnly: false},
                                    restore: {show: true},
                                    saveAsImage: {show: true}
                                }
                            },
                            series: [
                                {
                                   name: '面积模式',
                                   type: 'pie',
                                   radius: [30, 80],
                                   center: ['50%', '50%'],
                                   itemStyle: {
                                       borderRadius: 8
                                   },
                                   data: arraySeries,
                                   emphasis: {
                                       itemStyle: {
                                           shadowBlur: 10,
                                           shadowOffsetX: 0,
                                           shadowColor: 'rgba(0, 0, 0, 0.5)'
                                       }
                                   }
                               }
                            ]
                        },
                        media: []
                    });
                }else{
                    Alert.toast(resp.alert.type, resp.alert.message);
                }

                // set content is loading in div
                targetElement.classList.toggle('content-is-loading', false);
            }, 'GET', true);
        },
        getChart: function(params){
            // get chart order
            this.chartProduksi(params);
            this.chartBestItem(params)
            this.chartBestLokasi(params)
        },
        init: function(){
            // prepare ajax to get content chart
            var params = {};
            this.getChart(params)
        }
    };

    // START SETTING RESIZE CHART
    $(document).on('click', '[data-toggle="minimize"]', function(){
        $(".content-echarts").each(function(){
            var id = $(this).attr('_echarts_instance_');
            if (window.echarts.getInstanceById(id)) {
                window.echarts.getInstanceById(id).resize();
            }
        });
    });

    window.onresize = function() {
        $(".content-echarts").each(function(){
            var id = $(this).attr('_echarts_instance_');
            if (window.echarts.getInstanceById(id)) {
                window.echarts.getInstanceById(id).resize();
            }
        });
    };
    // END SETTING RESIZE CHART

    // intitial
    cvForm.init();

});

</script>
@endpush
