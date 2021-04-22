
import { Duration, DateRange, DateMarker, DateProfile, DateEnv, BaseOptionsRefined, DateProfileGenerator, PositionCache, DateComponent, ViewProps, createElement, Seg, Slicer, EventStore, EventUiHash, DateSpan, EventInteractionState, BaseComponent, RefObject, CssDimValue, VNode, ScrollRequest, PluginDef } from '@fullcalendar/common';
import '@fullcalendar/premium-common';

interface TimelineDateProfile {
    labelInterval: Duration;
    slotDuration: Duration;
    slotsPerLabel: number;
    headerFormats: any;
    isTimeScale: boolean;
    largeUnit: string;
    emphasizeWeeks: boolean;
    snapDuration: Duration;
    snapsPerSlot: number;
    normalizedRange: DateRange;
    timeWindowMs: number;
    slotDates: DateMarker[];
    isWeekStarts: boolean[];
    snapDiffToIndex: number[];
    snapIndexToDiff: number[];
    snapCnt: number;
    slotCnt: number;
    cellRows: TimelineHeaderCell[][];
}
interface TimelineHeaderCell {
    date: DateMarker;
    text: string;
    rowUnit: string;
    colspan: number;
    isWeekStart: boolean;
}
declare function buildTimelineDateProfile(dateProfile: DateProfile, dateEnv: DateEnv, allOptions: BaseOptionsRefined, dateProfileGenerator: DateProfileGenerator): TimelineDateProfile;

declare class TimelineCoords {
    slatRootEl: HTMLElement;
    dateProfile: DateProfile;
    private tDateProfile;
    private dateEnv;
    isRtl: boolean;
    outerCoordCache: PositionCache;
    innerCoordCache: PositionCache;
    constructor(slatRootEl: HTMLElement, 
    slatEls: HTMLElement[], dateProfile: DateProfile, tDateProfile: TimelineDateProfile, dateEnv: DateEnv, isRtl: boolean);
    rangeToCoords(range: any): {
        right: any;
        left: any;
    };
    isDateInRange(date: DateMarker): boolean;
    dateToCoord(date: any): any;
    computeDateSnapCoverage(date: DateMarker): number;
    computeDurationLeft(duration: Duration): number;
}

interface TimelineViewState {
    slatCoords: TimelineCoords | null;
    slotCushionMaxWidth: number | null;
}
declare class TimelineView extends DateComponent<ViewProps, TimelineViewState> {
    private buildTimelineDateProfile;
    private scrollGridRef;
    state: {
        slatCoords: any;
        slotCushionMaxWidth: any;
    };
    render(): createElement.JSX.Element;
    handleSlatCoords: (slatCoords: TimelineCoords | null) => void;
    handleScrollLeftRequest: (scrollLeft: number) => void;
    handleMaxCushionWidth: (slotCushionMaxWidth: any) => void;
    computeFallbackSlotMinWidth(tDateProfile: TimelineDateProfile): number;
}
declare function buildSlatCols(tDateProfile: TimelineDateProfile, slotMinWidth?: number): {
    span: number;
    minWidth: number;
}[];

interface TimelineLaneSeg extends Seg {
    start: DateMarker;
    end: DateMarker;
}
declare class TimelineLaneSlicer extends Slicer<TimelineLaneSeg, [DateProfile, DateProfileGenerator, TimelineDateProfile, DateEnv]> {
    sliceRange(origRange: DateRange, dateProfile: DateProfile, dateProfileGenerator: DateProfileGenerator, tDateProfile: TimelineDateProfile, dateEnv: DateEnv): TimelineLaneSeg[];
}


interface TimelineSegDims {
    left: number;
    right: number;
    height: number;
}

interface TimelineLaneProps extends TimelineLaneCoreProps {
    onHeightChange?: (innerEl: HTMLElement, isStable: boolean) => void;
}
interface TimelineLaneCoreProps {
    nowDate: DateMarker;
    todayRange: DateRange;
    dateProfile: DateProfile;
    tDateProfile: TimelineDateProfile;
    nextDayThreshold: Duration;
    businessHours: EventStore | null;
    eventStore: EventStore | null;
    eventUiBases: EventUiHash;
    dateSelection: DateSpan | null;
    eventSelection: string;
    eventDrag: EventInteractionState | null;
    eventResize: EventInteractionState | null;
    timelineCoords?: TimelineCoords;
}
interface TimelineLaneState {
    segDims: {
        [instanceId: string]: TimelineSegDims;
    } | null;
}
declare class TimelineLane extends BaseComponent<TimelineLaneProps, TimelineLaneState> {
    private slicer;
    private computeFgSegHorizontals;
    private computeSegVerticals;
    private harnessElRefs;
    private innerElRef;
    state: {
        segDims: any;
    };
    render(): createElement.JSX.Element;
    componentDidMount(): void;
    componentDidUpdate(prevProps: TimelineLaneProps, prevState: TimelineLaneState): void;
    updateSize(): void;
    renderFgSegs(segs: TimelineLaneSeg[], segHorizontals: any, segTops: any, hiddenSegs: any, isDragging: any, isResizing: any, isDateSelecting: any): createElement.JSX.Element;
}

interface TimelineLaneBgProps {
    businessHourSegs: TimelineLaneSeg[] | null;
    bgEventSegs: TimelineLaneSeg[] | null;
    dateSelectionSegs: TimelineLaneSeg[];
    eventResizeSegs: TimelineLaneSeg[];
    timelineCoords?: TimelineCoords;
    todayRange: DateRange;
    nowDate: DateMarker;
}
declare class TimelineLaneBg extends BaseComponent<TimelineLaneBgProps> {
    render(): createElement.JSX.Element;
    renderSegs(segs: TimelineLaneSeg[], timelineCoords: TimelineCoords, fillType: string): createElement.JSX.Element;
}


interface TimelineHeaderProps {
    dateProfile: DateProfile;
    tDateProfile: TimelineDateProfile;
    clientWidth: number | null;
    clientHeight: number | null;
    tableMinWidth: CssDimValue;
    tableColGroupNode: VNode;
    slatCoords: TimelineCoords;
    rowInnerHeights?: number[];
    onMaxCushionWidth?: (number: any) => void;
}
declare class TimelineHeader extends BaseComponent<TimelineHeaderProps> {
    rootElRef: RefObject<HTMLDivElement>;
    render(): createElement.JSX.Element;
    componentDidMount(): void;
    componentDidUpdate(): void;
    updateSize(): void;
    computeMaxCushionWidth(): number;
}


interface TimelineSlatsProps extends TimelineSlatsContentProps {
    clientWidth: number | null;
    tableMinWidth: CssDimValue;
    tableColGroupNode: VNode;
    onCoords?: (coord: TimelineCoords | null) => void;
    onScrollLeftRequest?: (scrollLeft: number) => void;
}
interface TimelineSlatsContentProps {
    dateProfile: DateProfile;
    tDateProfile: TimelineDateProfile;
    nowDate: DateMarker;
    todayRange: DateRange;
}
declare class TimelineSlats extends BaseComponent<TimelineSlatsProps> {
    private rootElRef;
    private cellElRefs;
    private coords;
    private scrollResponder;
    render(): createElement.JSX.Element;
    componentDidMount(): void;
    componentDidUpdate(prevProps: TimelineSlatsProps): void;
    componentWillUnmount(): void;
    updateSizing(): void;
    handleScrollRequest: (request: ScrollRequest) => boolean;
    positionToHit(leftPosition: any): {
        dateSpan: {
            range: {
                start: Date;
                end: Date;
            };
            allDay: boolean;
        };
        dayEl: HTMLTableCellElement;
        left: any;
        right: any;
    };
}

interface TimelineHeaderRowsProps {
    dateProfile: DateProfile;
    tDateProfile: TimelineDateProfile;
    nowDate: DateMarker;
    todayRange: DateRange;
    rowInnerHeights?: number[];
}
declare class TimelineHeaderRows extends BaseComponent<TimelineHeaderRowsProps> {
    render(): createElement.JSX.Element;
}


declare const _default: PluginDef;

export default _default;
export { TimelineCoords, TimelineDateProfile, TimelineHeader, TimelineHeaderRows, TimelineLane, TimelineLaneBg, TimelineLaneCoreProps, TimelineLaneProps, TimelineLaneSeg, TimelineLaneSlicer, TimelineSlats, TimelineView, buildSlatCols, buildTimelineDateProfile };
