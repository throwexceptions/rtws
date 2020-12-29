@extends('auth.layout.app')

@section('content')
    <!--suppress ALL -->
    <div id="app" class="row">
        <div class="col-md-12">
            <div class="card card-accent-warning">
                <div class="card-header">
                    Request Status
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            @canany(['admin', 'superadmin'])
                                <div class="col-md-auto mb-3">
                                    <form>
                                        <label>Override ID:</label>
                                        <input class="form-control" v-model="fetchid" @keyup="fetch()">
                                    </form>
                                </div>
                            @endcan
                        </div>
                    </div>
                    <div class="list-group">
                        <span class="list-group-item list-group-item-action" v-for="book in books" :key="book._id">
                            <div class="d-flex w-100 justify-content-between">
                                <h5 class="mb-1">#@{{ book.ref_no }}
                                    <span v-if="book.status == 'pending'"
                                          class="badge badge-info">Looking for a Rider</span>
                                    <span v-else-if="book.status == 'accepted'" class="badge badge-success">Rider has been assigned</span>
                                    <span v-else-if="book.status == 'cancelled'"
                                          class="badge btn-square badge-danger">Cancelled</span>
                                    <span v-else class="badge badge-info">@{{ book.status }}</span>
                                </h5>
                                <small>Php @{{ book.amount }}</small>
                            </div>
                            <p class="mb-1"><strong>Schedule</strong> @{{ book.schedule }}</p>
                            <p v-if="book.rider" class="mb-1">
                                <strong>Rider</strong>
                                <a :href="book.rider.msg_link">
                                    @{{ book.rider.name }}
                                </a>
                                <br>
                                (@{{ book.rider.contact }})
                            </p>
                            <p class="mb-1">@{{ book.pick_up }} <strong>to</strong> @{{ book.drop_off }}</p>
                            <form method="POST" action="{{ route('request.cancel') }}">
                                @csrf
                                <p class="mb-1" v-if="book.status == 'pending'">
                                    <input name="book_id" v-bind:value="book.id" hidden>
                                    <button class="btn btn-sm btn-round btn-danger">Cancel Order</button>
                                </p>
                            </form>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        const e = new Vue({
            el: '#app',
            data: {
                fetchid: '{{ auth()->id() }}',
                books: []
            },
            methods: {
                fetch() {
                    var $this = this;
                    $.ajax({
                        url: '{{ route('request.fetch') }}',
                        method: 'POST',
                        data: {
                          id: $this.fetchid
                        },
                        success(value) {
                            $this.books = value.booking.data;
                        }
                    })
                }
            },
            mounted() {
                var $this = this;
                const uuid = PubNub.generateUUID();
                this.fetch();
                Echo.channel('booking-status')
                    .listen('BookingStatusEvent', (e) => {
                        console.log(e.update);
                        $this.fetch();
                    });
            }
        })
    </script>
@endsection
