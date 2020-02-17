<template>
<div>
    <input name="coin" v-model="coin" @keypress="isNumber(this)"  type="text" v-on:input="changeValue()" class="form-control" id="amount" placeholder="" />
    <input name="btn_amount" v-model="total_btc"   type="hidden" />
    <input name="total_coin_price_in_dollar" v-model="total_doller"   type="hidden" />
    <ul class="coin_price">
        <li>${{coin_prize}} x {{ coin }} = ${{total_doller}}</li>
        <li>${{total_doller}} = {{total_btc}} BTC</li>
    </ul>
</div>
</template>

<script>
    export default {
        props: ['coin_price'],
        mounted(){
          this.coin_prize = this.coin_price;
        },
        data() {
            return {
                coin: '',
                coin_prize: 0,
                total_doller : 0,
                csfrf_token : '',
                total_btc : 0
            }
        },
        methods :{
            changeValue:function (){
            const vm = this;
                //this.value = this.value.replace(/[^0-9\.]/g,'');
                vm.total_doller = 0;
                vm.total_btc = 0;
             axios.get('get-btc-rate')
                    .then(function (response) {
                        vm.total_doller = vm.coin*vm.coin_prize;
                        vm.total_btc = (vm.total_doller*response.data.btc_rate).toFixed(8);
                    })
                    .catch(function (error) {
                    });
            },
            isNumber: function(evt) {
                evt = (evt) ? evt : window.event;
                var charCode = (evt.which) ? evt.which : evt.keyCode;
                if ((charCode > 31 && (charCode < 48 || charCode > 57)) && charCode !== 46) {
                    evt.preventDefault();;
                } else {
                    return true;
                }
            }
        }
    }
</script>