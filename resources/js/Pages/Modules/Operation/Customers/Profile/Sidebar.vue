<template>
    <b-col lg="4">
        <b-card>
            <b-card-body style="height: calc(100vh - 320px)">
                <template v-if="wallet">
                    <div class="input-group mb-0">
                        <label class="input-group-text"><i class="ri-wallet-3-fill align-middle me-2"></i> Wallet Balance</label>
                        <input type="text" :value="wallet.available" class="form-control" readonly style="text-align: right;">
                    </div>
                    <p class="text-muted fs-12 mb-n2 mt-3"> Transaction History :</p>
                    <simplebar data-simplebar style="max-height: 400px">
                        <b-list-group class="mt-3">
                            <b-list-group-item class="list-group-item" v-for="(list,index) in wallet.transactions" v-bind:key="index">
                                <div class="d-flex align-items-center">
                                    <div class="flex-grow-1">
                                        <div class="d-flex">
                                            <div class="flex-shrink-0 avatar-xs">
                                                <div class="avatar-xs flex-shrink-0">
                                                    <span class="avatar-title bg-light rounded-circle">
                                                        <svg v-if="list.is_credit" xmlns="http://www.w3.org/2000/svg" width="16px" height="16px" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-up-circle icon-dual-success icon-sm icon-dual-success icon-sm">
                                                            <circle cx="12" cy="12" r="10"></circle>
                                                            <polyline points="16 12 12 8 8 12"></polyline>
                                                            <line x1="12" y1="16" x2="12" y2="8"></line>
                                                        </svg>
                                                        <svg v-else xmlns="http://www.w3.org/2000/svg" width="16px" height="16px" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-down-circle icon-dual-danger icon-sm icon-dual-danger icon-sm">
                                                            <circle cx="12" cy="12" r="10"></circle>
                                                            <polyline points="8 12 12 16 16 12"></polyline>
                                                            <line x1="12" y1="8" x2="12" y2="16"></line>
                                                        </svg>
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="flex-shrink-0 ms-2">
                                                <h6 v-if="list.is_credit" class="fs-14 mb-0">OR# : {{list.transacable.number}}</h6>
                                                <h6 v-else class="fs-14 mb-0">{{list.transacable.code}}</h6>
                                                <small class="text-muted">{{list.created_at}}</small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="flex-shrink-0">
                                        <div v-if="list.is_credit">
                                            <span class="text-success float-end">+ {{list.amount}}</span>
                                            <br/><span class="text-muted float-end fs-11">Balance : {{list.balance}}</span>
                                        </div>
                                        <div v-else>
                                            <span class="text-danger float-end">- {{list.amount}}</span>
                                            <br/><span class="text-muted float-end fs-11">Balance : {{list.balance}}</span>
                                        </div>
                                    </div>
                                </div>
                            </b-list-group-item>
                        </b-list-group>
                    </simplebar>
                    <div class="d-flex mb-n1">
                        <div class="flex-grow-1">
                            <p class="text-muted text-center fs-12 mb-n2 mt-3"> Last Updated : {{wallet.updated_at}}</p>
                        </div>
                    </div>
                </template>
                
            </b-card-body>
        </b-card>
    </b-col>
</template>
<script>
import simplebar from 'simplebar-vue';
export default {
    components : { simplebar },
    props: ['wallet'],
    data(){
        return {
            currentUrl: window.location.origin,
        }
    },
}
</script>