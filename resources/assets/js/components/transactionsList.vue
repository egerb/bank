<template>
    <div>
    <div class="form-group">
        <label>Customers</label>
    <select class="form-control" @change="page = 1; getTransactions()" v-model='id'>
        <option value="all" selected="">All</option>
        <option v-for="customer in customers"  :value="customer.id">
            {{customer.name}}
        </option>
    </select>
    </div>
        <div class="form-group">
            <label>Records per page</label>
            <input type="text" v-model="per_page" @keyup.enter="page = 1; getTransactions()"/>
            <button v-if="pages > 1" @click="page -=1; getTransactions()">Prev</button>
            <label>Found pages:{{pages}}</label>
            <label>Cureent:  {{current}}</label>
            <button v-if="pages > 1" @click="page += 1; getTransactions()">Next</button>
        </div>
        <div class="form-group">
            <label>Filter by date</label>
            <input type="text" v-model="date" @keyup.enter="page = 1; getTransactions()"/>
        </div>
        <div class="form-group">
            <label>Filter by amount</label>
            <input type="text" v-model="amount" @keyup.enter="page = 1; getTransactions()"/>
        </div>
        <div class="panel panel-default">
        <div class="panel-heading">Transactions list</div>

        <div class="panel-body">
            <table class="table table-hover ">
                <thead>
                    <th>ID</th>
                    <th>Amount</th>
                    <th>Date</th>
                </thead>
                <tr v-for="transaction in transactions">
                    <td>{{transaction.id}}</td>
                    <td>{{transaction.amount}}</td>
                    <td>{{transaction.date}}</td>
                </tr>
            </table>
        </div>
    </div>

    </div>

</template>

<script>
    export default {
        props : ['bearer', 'host'],
        data() {
            return {
                per_page:10,
                customers: [],
                transactions: [],
                client:null,
                id:'all',
                date:'',
                limit:'',
                offset:'',
                amount:null,
                pages:null,
                page:1,
                current:1
            }
        },
        methods: {
            getCustomers() {
                this.client
                .get('api/customers')
                .then(response => {
                    console.log(response)
                    this.customers = response.data

            })
                .catch(response => {
                    console.log(response)
            })
            },
            getTransactions() {
                var self = this
                let params = {
                    'limit':(this.per_page) ? this.per_page : null,
                    'page':this.page
                }
                if (this.id != 'all') params.customer_id = this.id
                if (this.date.length > 0) params.date = this.date
                if (this.amount != null && this.amount.length > 0) params.amount = this.amount
                if (this.limit && this.limit.length > 0) params.limit = this.limit
                if ( this.offset && this.offset.length > 0) params.offset = this.offset

                this.client
                     .get('api/transactions', {params})
                     .then(response => {
                         console.log(response)
                         self.transactions = response.data.data
                         self.pages = response.data.last_page
                         self.current = response.data.current_page
                     })
                     .catch(response => {
                         console.log(response)
                     })
            }
        },
        mounted() {
            let self = this
            this.client = axios.create({
                baseURL: self.host,
                headers: {
                    "content-type": "application/json",
                    "Accept": "application/json",
                    'Authorization': 'Bearer ' + this.bearer
                }
            })
            this.getCustomers()
            this.getTransactions()
            console.log('Component mounted.')
        }
    }
</script>
