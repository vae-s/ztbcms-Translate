<extend name="../../Admin/View/Common/base_layout"/>

<block name="content">
    <div id="app" style="padding: 8px;height: 100%;" v-cloak>
        <div class="table_list">
            <div style="position: relative;padding: 4px;height: 60px;">
                <h3 style="display: inline-block;">翻译对照表</h3>
                <button @click="exportConstant" class="btn btn-success"
                        style="float: right;margin-left: 15px;margin-top: 10px;">导出本页翻译
                </button>
                <button @click="addConstant" class="btn btn-primary"
                        style="float: right;margin-left: 15px;margin-top: 10px;">添加翻译
                </button>
            </div>
            <div>
                <input class="form-control" placeholder="请输入名称搜索" type="text" v-model="filter"
                       style="display: inline-block;width: 50%">
                <button class="btn btn-primary" style="margin-left: 20px" @click="search">搜索</button>
            </div>
            <table class="table table-bordered table-hover">
                <thead>
                <tr>
                    <td align="center">Key</td>
                    <td align="center">翻译</td>
                    <td align="center">
                        操作
                    </td>
                </tr>
                </thead>
                <tbody>
                <tr v-for="(item,index) in items">
                    <td align="center">
                        <p><span>key</span><input class="form-control" type="text" v-model="item.key"
                                                  @change="editConstant(index)"></p>
                        <p><span>key名称</span><input class="form-control" type="text" v-model="item.key_name"
                                                    @change="editConstant(index)"></p>
                    </td>
                    <td align="center">
                        <template v-for="dictionary in  item.dictionary">
                            <div style="padding: 10px;">
                                <span style="width: 10%;display: inline-block;text-align: right;padding-right: 8px;">{{ dictionary.lang_name }}  </span>
                                <input class="form-control" type="text" v-model="dictionary.value"
                                       @change="editConstant(index)" style="display: inline-block;width: 80%">
                            </div>
                        </template>
                    </td>
                    <td align="center">
                        <a @click="delConstant(item.id)" class="btn btn-danger">删除</a>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>

    <script>
        $(document).ready(function () {
            new Vue({
                el: '#app',
                data: {
                    id: '{:I("get.category_id")}',
                    items: [],
                    lists: [],
                    filter: ''
                },
                methods: {
                    getList: function () {
                        var that = this;
                        var where = {
                            category_id: that.id
                        };
                        $.ajax({
                            url: "{:U('Translate/Constant/getList')}",
                            data: where,
                            dataType: 'json',
                            type: 'get',
                            success: function (res) {
                                var data = res.data;
                                that.items = data.items;
                                that.lists = data.items;
                            }
                        })
                    },
                    addConstant: function () {
                        var that = this;
                        var data = {
                            category_id: that.id
                        };
                        $.ajax({
                            url: '{:U("Translate/Constant/addConstant")}',
                            data: data,
                            dataType: 'json',
                            type: 'post',
                            success: function (res) {
                                if (res.status) {
                                    that.getList();
                                } else {
                                    layer.msg(res.msg, {time: 1000});
                                }
                            }
                        })
                    },
                    editConstant: function (index) {
                        var that = this;
                        var data = that.items[index];
                        $.ajax({
                            url: '{:U("Translate/Constant/editConstant")}',
                            data: data,
                            dataType: 'json',
                            type: 'post',
                            success: function (res) {
                                if (res.status) {
                                    layer.msg(res.msg, {time: 1000});
                                    that.getList()
                                } else {
                                    layer.msg(res.msg, {time: 1000});
                                }
                            }
                        })
                    },
                    delConstant: function (id) {
                        var that = this;
                        $.ajax({
                            url: '{:U("Translate/Constant/delConstant")}',
                            data: {id: id},
                            dataType: 'json',
                            type: 'post',
                            success: function (res) {
                                if (res.status) {
                                    layer.msg(res.msg, {time: 1000}, function () {
                                        that.getList();
                                    });
                                } else {
                                    layer.msg(res.msg, {time: 1000});
                                }
                            }
                        });
                    },
                    exportConstant: function () {
                        var that = this;
                        var url = '{:U("Translate/Export/exportConstantByCategory")}&category_id=' + that.id;
                        window.open(url);
                    },
                    search: function () {
                        var that = this;
                        if (that.filter.replace(/(^s*)|(s*$)/g, "") == '') {
                            that.items = that.lists;
                            return;
                        }
                        var lists = that.lists;
                        var len = that.lists.length;
                        var arr = [];
                        for (var i = 0; i < len; i++) {
                            //如果字符串中不包含目标字符会返回-1
                            if (lists[i]['key_name'].indexOf(that.filter) >= 0) {
                                arr.push(lists[i]);
                            }
                        }
                        that.items = arr;
                    }
                },
                mounted: function () {
                    this.getList();
                }
            })
        })
    </script>
</block>
