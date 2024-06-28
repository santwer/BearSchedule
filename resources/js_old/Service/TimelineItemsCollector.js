export default class TimelineItemsCollector {

    set() {
        TimelineItemsCollector.prototype.data.push(['test'])
    }

    get() {
        return TimelineItemsCollector.prototype.data;
    }

}

TimelineItemsCollector.prototype.data = [];
