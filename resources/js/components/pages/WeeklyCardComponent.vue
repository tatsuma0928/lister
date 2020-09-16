<template>
    <div>
        <p class="weekly">
            {{path}}
        </p>
        <div class="edit_container">
            <div class="add_todo_btn_container">
                <button @click="showTodo()">
                    <font-awesome-icon class="plus_icon" icon="plus" />新しくTODOを追加
                </button>
            </div>
            <div class="trash_container" style="height: 20px;">
                <font-awesome-icon class="trash_icon" icon="trash-alt" :style="{transform: scaleUp}" @click="openTrashList()"/>
                <draggable id="trash"
                    @add="addTrash"
                    :group="options"
                >
                </draggable>    
            </div>
        </div>
        <div class="todo_container">
            <draggable class="todo_card_container" 
                :group="weeklyOptions"
                :animation="200"
                >
                <div v-for="(todoTitleObject, index) in localStorageList.todoCardList" :key="index">
                    <todo-card-component
                        ref="todo"
                        :title="todoTitleObject.todoTitle"
                        :weeklyKey="$route.params.path"
                        :inputId="index + '_input'"
                        :todoCardListKey="index"
                        @addTrash="addTrash"
                        @trashScaleDown="trashScaleDown"
                    />
                </div>
            </draggable>
            <template v-if="isAddTodo">
                <div class="add_todo_card_container">
                    <input autofocus type="text"
                        placeholder="リストのタイトルを入力"
                        id="add_list"
                        :class="validAnimation" 
                        v-model="todoValue"
                        @keydown.enter="addTodo($event.keyCode)"
                    >
                    <div class="btn_area">
                        <div>
                            <button class="done_btn" @click="addTodo(13)">
                                追加
                            </button>
                        </div>
                        <div>
                            <button class="cancel_btn" @click="cancelTodo()">
                                <font-awesome-icon class="cancel_icon" icon="times"/>
                            </button>
                        </div>
                    </div>
                </div>
            </template>
        </div>
    </div>
</template>

<script>
import draggable from 'vuedraggable'
import { log } from 'util';
import { setTimeout } from 'timers';
export default {
    components: { draggable },
    data() {
        return {
            scaleUp: null,
            isAddTodo: false,
            todoValue: null,
            validAnimation: null,
            trashArray: [],
            todoTitleObjects: [
                {todoTitle: 'するべきこと'},
                {todoTitle: '作業中'},
                {todoTitle: '完了'},
            ],
            localStorageList: {
                todoCardList: {
                    list_0: { listId: 'list_0', todoTitle: 'するべきこと',  taskList: [] },
                    list_1: { listId: 'list_1', todoTitle: '作業中',  taskList: [] },
                    list_2: { listId: 'list_2', todoTitle: '完了',  taskList: [] },
                }
            },
            options: {
                name: "task",
                animation: 200,
                put: () => {                    
                    return this.trashScaleUp()
                },
            },
            weeklyOptions: {
                name: "weekly",
                delay: 50,
            },

        }
    },
    created() {
        const weeklyKey     = this.$route.params.path
        const weeklyObjects = JSON.parse(this.$localStorage.get(weeklyKey))
        
        // localStorageに、指定した曜日のkeyが登録されていなければweeklyKeyをkeyに登録
        if(weeklyObjects) {
            this.$set(this.localStorageList, "todoCardList", weeklyObjects.todoCardList)
        }
        const jsonList = JSON.stringify(this.localStorageList)
        this.$localStorage.set(weeklyKey, jsonList)
    },
    computed: {
        path() {
            const week = this.$route.params.path;
            switch (week) {
                case 'mon':
                    return '月曜日';
                break;
                case 'tue':
                    return '火曜日';
                break;
                case 'wed':
                    return '水曜日';
                break;
                case 'thu':
                    return '木曜日';
                break;
                case 'fri':
                    return '金曜日';
                break;
                case 'sat':
                    return '土曜日';
                break;
                case 'sun':
                    return '日曜日';
                break;
            }
        }
    },
    methods: {
        trashScaleUp() {
            this.scaleUp = 'scale(1.5)'
        },
        trashScaleDown() {
            this.scaleUp = 'scale(1)'
        },
        openTrashList() {
            console.log('hello');
        },
        addTrash(originalEvent) {            
            // 動かしたタスクのDOMを取得しdisplay: none;を付与
            let addDOM = originalEvent.item.style
            this.trashArray.push(originalEvent.item)
            addDOM['display'] = 'none'  
        },
        showTodo() {
            this.isAddTodo = true
            this.$nextTick(() => document.getElementById('add_list').focus())
        },
        addTodo(keyCode) {
            
            if (keyCode !== 13) return

            const weeklyKey   = this.$route.params.path
            let weeklyObjects = JSON.parse(this.$localStorage.get(weeklyKey))

            let list = this.localStorageList.todoCardList
            const newKey = "list_" + Object.keys(list).length
            weeklyObjects.todoCardList[newKey] = { listId: newKey, todoTitle: this.todoValue,  taskList: [] }
            this.$set(this.localStorageList.todoCardList, newKey, { listId: newKey, todoTitle: this.todoValue,  taskList: [] },)
            
            this.$localStorage.set(weeklyKey, JSON.stringify(weeklyObjects))
            this.isAddTodo = false
            this.todoValueInit()

            // todoを作成した後にaddTaskInputを開く
            const componentLength = this.$refs.todo.length            
            const argumentString  = "list_" + componentLength + "_input"
            const waitDuration = 1

            // 少し時間を開けないとtodo-card-componentが作られない
            setTimeout(() => {
                this.$refs.todo[componentLength].showTaskInput(argumentString)
            }, waitDuration)
        },
        cancelTodo() {
            this.isAddTodo = false
        },
        todoValueInit() {
            this.todoValue = null
        },
    },
    watch: {
        trashArray() {
            this.trashScaleDown()
        }
    }
}
</script>

<style lang="scss" scoped>
@import '../../../sass/variables';
.weekly{
    margin-top: 30px;
    font-size: 3rem;
    font-weight: bold;
    color: $text_color;
}
.edit_container{
    margin-bottom: 50px;
    display: flex;
    align-items: center;
    .add_todo_btn_container{
        margin-right: 20px;
        font-size: 1.6rem;
        button{
            width: 210px;
            color: $white;
            padding: 5px 15px;
            background: $success_btn_bg;
            outline: none;
            border: none;
            border-radius: 10px;
            display: flex;
            align-items: center;
            .plus_icon{
                width: 20px;
                height: 20px;
                margin-right: 10px;
            }
        }
    }
    .trash_container{
        position: relative;
        .trash_icon{
            width: 20px;
            height: 20px;
            transition: .2s all;
            z-index: 999;
        }
        &:hover{
            .trash_icon{
                transform: scale(1.5);
            }
        }
        #trash{
            width: 50px;
            height: 50px;
            opacity: 0.5;
            // background: red;
            position: absolute;
            top: -15px;
            left: -15px;
        }
    }
}
.todo_container{
    display: flex;
    .todo_card_container{
        display: flex;
    }
    .add_todo_card_container{
        width: 280px;
        height: 104px;
        padding: 10px;
        margin-right: 30px;
        border: 1px solid  #707070;
        border-radius: 10px;
        background: $todo_card_bg;
        transition: .5s all;
        input{
            width: 258px;
            margin-bottom: 8px;
            padding: 5px 10px;
            font-size: 1.6rem;
        }
        .btn_area{
            display: flex;
            button{
                width: 70px;
                padding: 0;
                color: $white;
                font-size: 1.6rem;
                border: none;
                border-radius: 50px;
                outline: none;
            }
            .done_btn{
                background: $success_btn_bg;
                margin-right: 10px;
            }
            .cancel_btn{
                background: $cancel_btn_bg;
            }
        }
    }
}
</style>
