@extends('layouts.app')

@section('content')
    <!--suppress ALL -->
    <style>
        .btn-info:not(:disabled):not(.disabled).active {
            background-color: #ca8aea !important;
        }
    </style>
    <div class="content" id="app">
        <form method="POST" action="{{ route('booking.submit') }}">
            @csrf
            <div class="row">
                <div class="col-md-6">
                    <div class="card card-user">
                        <div class="card-header">
                            <h5 class="title">{{ $page_name }}</h5>
                        </div>
                        <div class="card-body">
                            {{--                            VEHICLE--}}
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="btn-group btn-group-toggle btn-block" data-toggle="buttons">
                                        <label class="btn btn-info text-white active">
                                            <input type="radio" name="vehicle" value="motorcycle"
                                                   @click="setVehicle('motorcycle')" checked>
                                            <strong>Motorcycle</strong>
                                        </label>
                                        <label class="btn btn-info text-white">
                                            <input type="radio" name="vehicle" value="car"
                                                   @click="setVehicle('car')">
                                            <strong> Car</strong>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            {{--                            SERVICES--}}
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="btn-group btn-group-toggle btn-block" data-toggle="buttons">
                                        <label class="btn btn-info text-white active">
                                            <input type="radio" name="service" id="padala" value="padala"
                                                   @click="setService('padala')" checked>
                                            <strong>Padala</strong>
                                        </label>
                                        <label class="btn btn-info text-white">
                                            <input type="radio" name="service" id="pabili" value="pabili"
                                                   @click="setService('pabili')">
                                            <strong>Pabili</strong>
                                        </label>
                                        <label class="btn btn-info text-white">
                                            <input type="radio" name="service" id="pa-grocery" value="pa-grocery"
                                                   @click="setService('pa-grocery')">
                                            <strong>Pa-Grocery</strong>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            {{--                            SUB-CATEG--}}
                            <div class="row">
                                <div class="col-md-12" v-if="service == 'padala'">
                                    <div class="btn-group btn-group-toggle btn-block" data-toggle="buttons">
                                        <label class="btn btn-info text-white active">
                                            <input type="radio" name="sub" value="less 10kgs"
                                                   @click="setSub('less 10kgs')" checked>
                                            <strong> less 10kgs</strong>
                                        </label>
                                        <label class="btn btn-info text-white">
                                            <input type="radio" name="sub" value="above 10kgs"
                                                   @click="setSub('above 10kgs')">
                                            <strong> above 10kgs</strong>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-12" v-if="service == 'pabili'">
                                    <div class="btn-group btn-group-toggle btn-block" data-toggle="buttons">
                                        <label class="btn btn-info text-white active">
                                            <input type="radio" name="sub" value="less 1k"
                                                   @click="setSub('lessk 1k')" checked>
                                            <strong> less 1k</strong>
                                        </label>
                                        <label class="btn btn-info text-white">
                                            <input type="radio" name="sub" value="above 1k"
                                                   @click="setSub('above 1k')">
                                            <strong> above 1k</strong>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-12" v-if="service == 'pa-grocery'">
                                    <div class="btn-group btn-group-toggle btn-block" data-toggle="buttons">
                                        <label class="btn btn-info text-white active">
                                            <input type="radio" name="sub" value="less 2k"
                                                   @click="setSub('less 2k')" checked>
                                            <strong> less 2k</strong>
                                        </label>
                                        <label class="btn btn-info text-white">
                                            <input type="radio" name="sub" value="above 2k"
                                                   @click="setSub('above 2k')">
                                            <strong> above 2k</strong>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <label>Schedule Pick-Up</label>
                                    <input type="datetime-local" name="schedule" class="form-control">
                                </div>
                                <div class="col-md-12">
                                    <label>Pick-Up</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend" @click="mapPickUp">
                                            <span class="input-group-text">
                                                <i class="now-ui-icons location_pin"></i>
                                            </span>
                                        </div>
                                        <input type="text" name="pick_up" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <label>Drop-Off</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                                <i class="now-ui-icons location_pin"></i>
                                            </div>
                                        </div>
                                        <input type="text" name="drop_off" class="form-control">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <label>Amount</label>
                                    <input type="number" name="amount" class="form-control">
                                </div>
                            </div>
                            <div class="row mt-2 justify-content-center">
                                <div class="col-md-12">
                                    <button type="submit" class="btn btn-block btn-round btn-success">Book Now!</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection

@section('scripts')
    <script>
        const e = new Vue({
            el: '#app',
            data: {
                service: 'padala',
                vehicle: 'motorcycle',
                sub: '',
                map: null,
                dp: {
                    lat: 0,
                    long: 0,
                },
                pu: {
                    lat: 0,
                    long: 0,
                },
            },
            methods: {
                setService(value) {
                    this.service = value;
                },
                setVehicle(value) {
                    this.vehicle = value;
                },
                setSub(value) {
                    this.sub = value;
                },
                mapMdl() {
                    var $this = this;
                    $('#mapModal').modal('show');
                },
                mapPickUp()
                {
                    window.location='{{ route('manage.map') }}'
                }
            },
            mounted() {
                var $this = this;
            }
        });
    </script>
@endsection
