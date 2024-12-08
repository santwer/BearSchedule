<template>
    <ul :style="{'left': xpos + 'px', 'top': ypos + 'px'}"
        v-if="show"
        class="dropdown-menu overflow-auto show shadow-sm"
        ref="context"
        role="menu">
        <BButtonGroup class="px-2" style="width: 100%" v-if="item">
            <BButton
                :variant="isDark ?
                            'secondary' : 'outline-dark'"
                :title="$t('edit item')"
                @click="editItem()">
                <mdicon name="pencil" size="16"/>
            </BButton>
            <BButton
                :variant="isDark ?
                            'secondary' : 'outline-dark'"
                @click="copyItem()"
                :title="$t('copy item')"
            >
                <mdicon name="content-copy" size="16"/>
            </BButton>
            <BButton
                variant="danger"
                :title="$t('delete item')"
                @click="deleteItem"
            >
                <mdicon name="delete" size="16"/>
            </BButton>
        </BButtonGroup>
        <hr v-if="item && hasPaste()">
        <li role="presentation" v-if="hasPaste()">
            <button class="dropdown-item" role="menuitem" type="button" @click="paste()">
                <mdicon name="content-paste" size="20"/>
                <span class="d-none d-lg-inline px-2">{{ $t('paste item') }}</span>
            </button>
        </li>
        <hr v-if="item">
        <li role="presentation">
            <button class="dropdown-item" role="menuitem" type="button" @click="editGroup()" :disabled="!group">
                <mdicon name="folder-edit" size="20"/>
                <span class="d-none d-lg-inline px-2">{{ $t('edit group') }}</span>
            </button>
        </li>
        <hr>
        <li role="presentation">
            <button class="dropdown-item" role="menuitem" type="button" @click="addItem()">
                <mdicon name="plus" size="20"/>
                <span class="d-none d-lg-inline px-2">{{ $t('Add Item') }}</span>
            </button>
        </li>
        <li role="presentation">
            <button class="dropdown-item" role="menuitem" type="button" @click="parentAction('addGroup')">
                <mdicon name="folder-plus" size="20"/>
                <span class="d-none d-lg-inline px-2">{{ $t('Add Group') }}</span>
            </button>
        </li>
        <hr>
        <li role="presentation">
            <button class="dropdown-item" role="menuitem" type="button" @click="parentAction('openShare')">
                <mdicon name="share-variant" size="20"/>
                <span class="d-none d-lg-inline px-2">{{ $t('project_share') }}</span>
            </button>
        </li>
    </ul>
</template>
<script>
import {
    BModal,
    BButton,
    BAlert,
    BButtonGroup,
} from "bootstrap-vue-next";
import ThemeMixin from "@/mixins/ThemeMixin";
import routeMixin from "@/mixins/RouteMixin";
import clipboardMixin from "@/mixins/ClipboardMixin";
import {has} from "immutable";
export default {
    name: "ContextMenuTimeline",
    mixins: [ThemeMixin, routeMixin, clipboardMixin ],
    components: {
        BModal,
        BButton,
        BAlert,
        BButtonGroup,
    },
    data() {
        return {
            show: false,
            xpos: 0,
            ypos: 0,
            time: null,
            group: null,
            item: null,
        }
    },
    methods: {
        copyItem() {
            this.$emit('copy', this.item);
            this.show = false;
        },
        paste() {
            this.$parent.pasteItem(this.group);
            this.show = false;
        },
        open(properties) {
            this.show = true;
            this.xpos = properties.pageX;
            this.ypos = properties.pageY;
            this.time = properties.time;
            this.group = properties.group;
            this.item = properties.item;
            if(this.item === null && this.group) {
                this.getPossibleItems();
            }
            this.$nextTick(() => {
                this.adjustPosition();
                this.setSelectedItem();
            });
        },
        setSelectedItem(){
          if(this.item === null) {
              return;
          }
          this.$parent.setSelection([this.item])
        },
        getPossibleItems() {
            let items = this.$parent.getItemsAtTime(this.time, this.group);
            if (items.length === 0) {
                this.item = null;
            } else if(items.filter(x => x.type === 'background').length > 0) {
                this.item = items.filter(x => x.type === 'background')[0].id;
            }
        },
        adjustPosition() {
            let width = this.$refs.context.offsetWidth;
            let height = this.$refs.context.offsetHeight;

            if (window.innerWidth - this.xpos < width) {
                this.xpos -= width;
            }

            if (window.innerHeight - this.ypos < height) {
                this.ypos -= height;
            }
        },
        editGroup() {
            this.$parent.openGroupById(this.group);
            this.show = false;
        },
        editItem() {
            this.$parent.openItemById(this.item);
            this.show = false;
        },
        addItem() {
            this.$emit('action', 'addItem');
            this.show = false;
            this.$parent.addItemPrefix(this.time, this.group)
        },
        deleteItem() {
          this.$parent.openItemDeleteModal(this.item);
          this.show = false;
        },
        parentAction(action) {
            this.$parent[action]()
            this.$emit('action', action);
            this.show = false;
        }
    },
    mounted() {

        document.addEventListener('click', (e) => {
            if (!this.$el.contains(e.target)) {
                this.show = false;
            }
        });

    }

}
</script>

<style scoped>
.context {
    position: absolute;
    left: 0px;
    top: 0px;
    transform: translate(-110px, 33px);
    max-height: 862.5px;
    max-width: 1393px;
}
</style>
