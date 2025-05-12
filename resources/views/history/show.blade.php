<x-admin-layout>
    @section('scripts')
        @vite('resources/js/history.js')
    @endsection

    <header class="header_post_edit">
        <a href="{{ route('history.index', $id) }}"><i class="fa-solid fa-left-long"></i> Back</a>
        <span class="leave-compare" onclick="leaveCompare();">Leave comparison</span>
        <span class="info">Post History</span>
        <div class="profile">
            <img src="{{ asset(Auth::user()->image_path) }}" alt="" class="profile_img">
            <i class="fa-solid fa-angles-down"></i>
            <span class="notifications_count">{{ $unreadNotifications }}</span>
        </div>
    </header>

    <div class="post__history">
        <div class="compact-history-list">
            <div class="extend-history" onclick="extend_history();">Show compact history</div>
            <div id="history_list" style="height: 0px; visibility: hidden;">
                @php($autoSave = $historyPosts->where('additional_info', 2)->first())
                @php($lastDate = $autoSave ? $autoSave->updated_at->format('Y-m-d') : null)
                @if ($autoSave)
                    <div class="date">
                        Changes from {{ \Carbon\Carbon::parse($lastDate)->translatedFormat('d F, Y') }}
                        <div class="line-v"></div>
                    </div>
                    <div class="history_card h_{{ $autoSave->id }} {{ (int) $history_id === $autoSave->id ? 'active' : '' }}"
                        onclick="show({{ $currentPost->id }}, {{ $autoSave->id }});">
                        <img src="{{ asset($autoSave->image_path) }}" alt="">
                        <div class="body">
                            <div class="top-info">
                                @if ($autoSave->category)
                                    <div class="category"
                                        style="background: {{ $autoSave->category->backgroundColor }}CC; color: {{ $autoSave->category->textColor }}">
                                        {{ $autoSave->category->name }}</div>
                                @endif
                                @if ($autoSave->read_time)
                                    <i class="fa-solid fa-clock"></i>
                                    <p class="reading-time">{{ $autoSave->read_time }} min</p>
                                @endif
                            </div>
                            <span class="title">{{ $autoSave->title }}</span>
                            <div class="bottom-info">
                                <span class="created"><i class="fa-regular fa-clock"></i>
                                    {{ $autoSave->updated_at->diffForHumans() }}, <span
                                        class="time">{{ $autoSave->updated_at->format('H:i') }}</span></span>
                                <span class="additional_info"><i class="fa-solid fa-floppy-disk"></i> Autosave</span>
                            </div>
                            <span onclick="compare(event, {{ $autoSave->id }});"
                                class="compare{{ (int) $history_id === $autoSave->id ? ' hidden' : '' }}">Compare <i
                                    class="fa-solid fa-right-left"></i></span>
                        </div>
                    </div>
                @endif
                @if ($lastDate != $currentPost->updated_at->format('Y-m-d'))
                    @php($lastDate = $currentPost->updated_at->format('Y-m-d'))
                    @if ($autoSave)
                        <div class="date">
                            <div class="line-v"></div>
                            Changes from {{ \Carbon\Carbon::parse($lastDate)->translatedFormat('d F, Y') }}
                            <div class="line-v"></div>
                        </div>
                    @else
                        <div class="date">
                            Changes from
                            {{ \Carbon\Carbon::parse($currentPost->updated_at)->translatedFormat('d F, Y') }}
                            <div class="line-v"></div>
                        </div>
                    @endif
                @else
                    <div class="margin-10"> </div>
                @endif
                <div onclick="show({{ $currentPost->id }}, 'current');"
                    class="history_card{{ $history_id === 'current' ? ' active' : '' }} h_0">
                    <img src="{{ asset($currentPost->image_path) }}" alt="">
                    <div class="body">
                        <div class="top-info">
                            @if ($currentPost->category)
                                <div class="category"
                                    style="background: {{ $currentPost->category->backgroundColor }}CC; color: {{ $currentPost->category->textColor }}">
                                    {{ $currentPost->category->name }}</div>
                            @endif
                            @if ($currentPost->read_time)
                                <i class="fa-solid fa-clock"></i>
                                <p class="reading-time">{{ $currentPost->read_time }} min</p>
                            @endif
                        </div>
                        <span class="title">{{ $currentPost->title }}</span>
                        <div class="bottom-info">
                            <span class="created"><i class="fa-regular fa-clock"></i>
                                {{ $currentPost->updated_at->diffForHumans() }}, <span
                                    class="time">{{ $currentPost->updated_at->format('H:i') }}</span></span>
                            <span class="additional_info"><i class="fa-solid fa-bolt"></i> Current</span>
                            <span class="additional_info">{!! $currentPost->additional_info == 1 ? '<i class="fa-solid fa-clock-rotate-left"></i> Restored' : '' !!}</span>
                        </div>
                        @if ($currentPost->changelog)
                            <div class="changelog-info">
                                <span class="user"><i class="fa-solid fa-user"></i>
                                    {{ $currentPost->changeUser->firstname . ' ' . $currentPost->changeUser->lastname }}</span>
                                <span class="changelog"><i class="fa-solid fa-square-pen"></i> <span
                                        class="text">{{ $currentPost->changelog }}</span></span>
                            </div>
                        @endif
                        <span onclick="compare(event, 'current');"
                            class="compare{{ $history_id === 'current' ? ' hidden' : '' }}">Compare <i
                                class="fa-solid fa-right-left"></i></span>
                    </div>
                </div>
                @foreach ($historyPosts as $historyPost)
                    @php($postDate = $historyPost->updated_at->format('Y-m-d'))
                    @if ($lastDate != $postDate && $historyPost->additional_info != 2)
                        @php($lastDate = $postDate)
                        <div class="date">
                            <div class="line-v"></div>
                            Changes from {{ \Carbon\Carbon::parse($postDate)->translatedFormat('d F, Y') }}
                            <div class="line-v"></div>
                        </div>
                    @elseif($historyPost->additional_info == 2)
                        @continue
                    @else
                        <div class="margin-10"> </div>
                    @endif

                    <div class="history_card h_{{ $historyPost->id }} {{ (int) $history_id === $historyPost->id ? 'active' : '' }}"
                        onclick="show({{ $currentPost->id }}, {{ $historyPost->id }});">
                        <img src="{{ asset($historyPost->image_path) }}" alt="">
                        <div class="body">
                            <div class="top-info">
                                @if ($historyPost->category)
                                    <div class="category"
                                        style="background: {{ $historyPost->category->backgroundColor }}CC; color: {{ $historyPost->category->textColor }}">
                                        {{ $historyPost->category->name }}</div>
                                @endif
                                @if ($historyPost->read_time)
                                    <i class="fa-solid fa-clock"></i>
                                    <p class="reading-time">{{ $historyPost->read_time }} min</p>
                                @endif
                            </div>
                            <span class="title">{{ $historyPost->title }}</span>
                            <div class="bottom-info">
                                <span class="created"><i class="fa-regular fa-clock"></i>
                                    {{ $historyPost->updated_at->diffForHumans() }}, <span
                                        class="time">{{ $historyPost->updated_at->format('H:i') }}</span></span>
                                <span class="additional_info">{!! $historyPost->additional_info == 1 ? '<i class="fa-solid fa-clock-rotate-left"></i> Restored' : '' !!}{!! $historyPost->additional_info == 2 ? '<i class="fa-solid fa-floppy-disk"></i> Autosave' : '' !!}</span>
                            </div>
                            @if ($historyPost->changelog)
                                <div class="changelog-info">
                                    <span class="user"><i class="fa-solid fa-user"></i>
                                        {{ $historyPost->changeUser->firstname . ' ' . $historyPost->changeUser->lastname }}</span>
                                    <span class="changelog"><i class="fa-solid fa-square-pen"></i> <span
                                            class="text">{{ $historyPost->changelog }}</span></span>
                                </div>
                            @endif
                            @if ($historyPost->additional_info !== 2)
                                <span class="actions{{ (int) $history_id === $historyPost->id ? '' : ' hidden' }}">
                                    <span onClick="revert({{ $id }}, {{ $historyPost->id }});">Restore <i
                                            class="fa-solid fa-clock-rotate-left"></i></span>
                                </span>
                            @endif
                            <span onclick="compare(event, {{ $historyPost->id }});"
                                class="compare{{ (int) $history_id === $historyPost->id ? ' hidden' : '' }}">Compare
                                <i class="fa-solid fa-right-left"></i></span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</x-admin-layout>
