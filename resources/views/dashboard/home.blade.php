<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 flex relative">
            <!-- 業界一覧（左側に固定、締切間近と対称） -->
            <div class="w-1/6 p-4 fixed top-16 left-6 md:left-10 lg:left-16
                        md:h-screen md:flex md:flex-col md:justify-between z-10">
                <p class="text-center font-semibold text-gray-900 mb-4">業界</p>

                @if ($industries->isEmpty())
                    <p class="text-gray-600">登録された業界がありません。</p>
                @else
                    <div class="space-y-2 flex-1 overflow-y-auto">
                        @foreach ($industries as $industry)
                            <div class="relative bg-white p-2 rounded-[12px] cursor-pointer hover:bg-gray-100 text-sm"
                                style="min-height: 60px; display: flex; align-items: center; justify-content: center;"
                                onclick="toggleIndustryModal({{ $industry->id }})"
                                onmouseover="switchIndustryModal({{ $industry->id }})">
                                <p class="text-center text-m font-semibold truncate">{{ $industry->name }}</p>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- 企業一覧モーダル -->
            <div id="industry-modal-container" class="fixed top-[13%] left-[20%] p-2 flex items-start hidden opacity-0 transition-opacity duration-300 ease-in-out z-50">
                <div class="w-[57vw] max-w-[90vw] bg-white border-gray-100 rounded-[12px] p-6 relative">
                    <p id="industry-modal-title" class="text-center font-semibold text-gray-800 text-lg mb-4"></p>

                    <button class="text-center absolute top-4 right-4 bg-gray-800 text-white px-3 py-1 rounded-[12px] text-sm hover:bg-gray-600"
                            onclick="closeIndustryModal()">
                        閉じる
                    </button>

                    <div>
                        <ul class="text-sm space-y-2 w-3/4 md:w-1/2 mx-auto ml-4">
                            <li class="px-4 py-2 rounded-[12px] flex items-center justify-start w-full">
                                <div class="w-1/3 text-center flex-grow-0 flex-shrink-0">
                                    <span class="text-gray-900">企業名</span>
                                </div>
                                <div class="w-1/3 text-center flex-grow-0 flex-shrink-0 text-gray-900">
                                    ステータス
                                </div>
                                <div class="w-1/3 text-center flex-grow-0 flex-shrink-0">
                                    <span class="text-gray-900">採用HP</span>
                                </div>
                                <div class="w-1/3 text-center flex-grow-0 flex-shrink-0">
                                    <span class="text-gray-900">MyPage</span>
                                </div>
                                <div class="w-1/3 text-center flex-grow-0 flex-shrink-0 text-gray-900">
                                    <span class="text-gray-900">ID</span>
                                </div>
                            </li>
                        </ul>
                    </div>

                    <!-- スクロール可能なコンテンツエリア -->
                    <div id="industry-modal-content" class="overflow-y-auto max-h-[60vh]"></div>
                </div>
            </div>
            
            <!-- Contents一覧 -->
            <div class="w-4/6 mx-auto p-4 flex flex-col items-center">
                <div class="w-full">
                    @if ($contents->isEmpty())
                        <p class="text-gray-600 text-center">まだコンテンツがありません。</p>
                    @else
                        <div class="bg-gray-100 p-4 rounded-[12px] relative">
                            <ul class="space-y-3">
                                @foreach ($contents as $content)
                                    <li class="bg-white p-4 rounded-[12px] shadow-sm border relative cursor-pointer transition hover:shadow-md"
                                        onclick="location.href='{{ route('entrysheet.show', ['entrysheet' => $content->entrysheet->id]) }}'"
                                        oncontextmenu="showContextMenu(event, '{{ route('content.edit', ['entrysheet' => $content->entrysheet->id, 'content' => $content->id]) }}', '{{ route('content.destroy', ['entrysheet' => $content->entrysheet->id, 'content' => $content->id]) }}')">
                                        <p class="text-base font-semibold text-gray-800 truncate">{{ $content->question }}</p>
                                        <p class="text-sm text-gray-500">
                                            {{ $content->answer }}
                                        </p>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </div>
            </div>

            
            <!-- 締切間近（右側に固定、業界一覧と対称） -->
            <div class="w-1/6 p-4 fixed top-16 right-6 md:right-10 lg:right-16
            md:h-screen md:flex md:flex-col md:justify-between">
                <p class="text-center font-bold mb-4">締切間近</p>

                @if ($entrysheets->isEmpty())
                    <p class="text-gray-600">登録されたエントリーシートがありません。</p>
                @else
                    <div class="space-y-2 flex-1 overflow-y-auto">
                        @foreach ($entrysheets as $entrysheet)
                            @if (!empty($entrysheet->company) && !empty($entrysheet->company->name))
                                <div class="bg-white p-2 border rounded-[12px] cursor-pointer hover:bg-gray-100 text-sm"
                                    style="min-height: 60px; display: flex; flex-direction: column; align-items: center; justify-content: center;"
                                    onclick="location.href='{{ route('entrysheet.show', $entrysheet->id) }}'">
                                    <p class="text-m text-gray-600">
                                        {{ $entrysheet->deadline ? \Carbon\Carbon::parse($entrysheet->deadline)->locale('ja')->isoFormat('M/D(ddd)') : '未設定' }}
                                    </p>
                                    <p class="text-m font-semibold truncate text-center">
                                        {{ $entrysheet->company->name }}：{{ $entrysheet->title }}
                                    </p>
                                </div>
                            @endif
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>

<script>
    let currentIndustryId = null;

    function toggleIndustryModal(industryId) {
        const modal = document.getElementById('industry-modal-container');
        if (currentIndustryId === industryId) {
            closeIndustryModal();
            return;
        }
        modal.classList.remove('hidden', 'opacity-0');
        updateIndustryModal(industryId);
        currentIndustryId = industryId;
    }

    function switchIndustryModal(industryId) {
        const modal = document.getElementById('industry-modal-container');
        if (!modal.classList.contains('hidden') && currentIndustryId !== industryId) {
            updateIndustryModal(industryId);
            currentIndustryId = industryId;
        }
    }

    function copyToClipboard(element) {
        let textToCopy = element.innerText.trim(); // テキストを取得し、余分なスペースを削除
        navigator.clipboard.writeText(textToCopy).then(() => {
            element.innerText = "コピーしました"; // コピー完了後のメッセージ変更
            element.classList.add("bg-green-100", "text-green-600"); // 視覚的に変化を与える

            setTimeout(() => {
                element.innerText = textToCopy; // 3秒後に元のテキストに戻す
                element.classList.remove("bg-green-100", "text-green-600");
            }, 1000);
        }).catch(err => {
            alert("コピーに失敗しました: " + err);
        });
    }
    
    function closeIndustryModal() {
    const modal = document.getElementById('industry-modal-container');
    modal.classList.add('opacity-0');
    
    // 300ms後に `hidden` を追加して完全に非表示
    setTimeout(() => {
        modal.classList.add('hidden');
    }, 300);

    // 現在の業界IDをリセット
    currentIndustryId = null;
}

    // hover時の表示表示内容
    function updateIndustryModal(industryId) {
        const industryName = document.querySelector(`[onclick="toggleIndustryModal(${industryId})"] p`).innerText;
        document.getElementById('industry-modal-title').innerText = industryName + " の企業一覧";

        let companies = {!! $industriesWithCompanies !!};
        let contentDiv = document.getElementById('industry-modal-content');

        if (companies[industryId] && companies[industryId].length > 0) {
            contentDiv.innerHTML = `
                <ul class="text-sm space-y-2 w-3/4 md:w-1/2 mx-auto ml-4">
                    ${companies[industryId].map(company => `
                        <li class="px-4 py-2 rounded-[12px] flex items-center justify-start w-full">
                            <div class="w-1/3 text-center flex-grow-0 flex-shrink-0">
                                ${company.show 
                                    ? `<a href="${company.show}" class="text-blue-500 hover:text-blue-900">${company.name}</a>` 
                                    : `<span class="text-gray-900">${company.name}</span>`}
                            </div>
                            <div class="w-1/3 text-center flex-grow-0 flex-shrink-0 text-gray-900">
                                ${company.status || '------'}
                            </div>
                            <div class="w-1/3 text-center flex-grow-0 flex-shrink-0">
                                ${company.homepage 
                                    ? `<a href="${company.homepage}" target="_blank" class="text-blue-500 hover:text-blue-900">採用HP</a>` 
                                    : `<span class="text-gray-900">------</span>`}
                            </div>
                            <div class="w-1/3 text-center flex-grow-0 flex-shrink-0">
                                ${company.mypage 
                                    ? `<a href="${company.mypage}" target="_blank" class="text-blue-500 hover:text-blue-900">Mypage</a>` 
                                    : `<span class="text-gray-900">------</span>`}
                            </div>
                            <div class="px-3 py-1 text-sm text-center text-gray-900 rounded-full border transition-transform duration-200 hover:scale-105 text-nowrap cursor-pointer"
                                onclick="copyToClipboard(this)">
                                ${company.loginid || '------------'}
                            </div>
                        </li>
                    `).join('')}
                </ul>
            `;
        }
    }
</script>
