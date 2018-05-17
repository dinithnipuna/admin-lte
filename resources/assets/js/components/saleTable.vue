<template>
    <table id="invoiceTable" class="table table-striped table-bordered table-hover dt-responsive" cellspacing="0" width="100%">
        <thead>
            <tr>
                <th>ID</th>
                <th>Product</th>
                <th>Qty</th>
                <th>Rate</th>
                <th>Discount</th>
                <th>Amount</th>
                <th>Actions</th>
            </tr>
        </thead>

        <tbody></tbody>
    </table>
</template>

<script>
    export default {
        mounted() {
            console.log('Sale Table mounted.')
        }
    }
</script>
