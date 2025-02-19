<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\CompanyFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class CompanyFileController extends Controller
{
    // ファイルアップロード
    public function store(Request $request, Company $company)
    {
        $request->validate([
            'file' => 'required|file|mimes:jpg,jpeg,png,gif,pdf,doc,docx,xls,xlsx,ppt,pptx,txt|max:20480',
        ]);

        $file = $request->file('file');
        $filename = $file->getClientOriginalName();
        $path = $file->store('company_files', 'public');

        $company->files()->create([
            'filename' => $filename,
            'path' => $path,
        ]);

        return redirect()->route('company.show', $company->id)->with('success', 'ファイルをアップロードしました');
    }

    // ファイル削除
    public function destroy(CompanyFile $file)
    {
        Storage::delete($file->path);
        $file->delete();

        return back()->with('success', 'ファイルを削除しました');
    }

    //ダウンロード機能
    public function download(CompanyFile $file)
    {
        if (Storage::exists($file->path)) {
            return Storage::download($file->path, $file->filename);
        }
        return redirect()->back()->with('error', 'ファイルが見つかりません');
    }
}
