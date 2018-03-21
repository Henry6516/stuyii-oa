<?php
/**
 * @desc PhpStorm.
 * @author: turpure
 * @since: 2018-03-21 14:52
 */


?>
<!--element-ui-->
<link rel="stylesheet" href="https://unpkg.com/element-ui/lib/theme-chalk/index.css">
<script src="https://unpkg.com/vue/dist/vue.js"></script>
<script src="https://unpkg.com/element-ui/lib/index.js"></script>
<script src="https://cdn.bootcss.com/vue-resource/1.5.0/vue-resource.min.js"></script>
<div id="app">
    <template>
        <el-table :data="tableData" style="width: 100%">
            <el-table-column prop="date" label="日期" width="180">
            </el-table-column>
            <el-table-column prop="name" label="姓名" width="180">
            </el-table-column>
            <el-table-column prop="address" label="地址" width="180">
            </el-table-column>
        </el-table>
    </template>
</div>

<script>
    new Vue({
        el: '#app',
        data: function() {
            return {
                tableData: [],
                loading: true
            }
        },
        created: function () {
            this.$http({url:'data-detail'}).then(function (response) {
                var ret =  response.body;
                this.tableData = ret;
                this.loading = false;
            })
        }
    })
</script>