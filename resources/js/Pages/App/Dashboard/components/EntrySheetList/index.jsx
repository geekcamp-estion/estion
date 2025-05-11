import React from "react";
import { router } from "@inertiajs/react";

export default function EntrySheetList({ entrysheets }) {
    return (
        <div className="w-1/5 p-4 fixed top-10 right-6 md:right-10 lg:right-16 
                        md:h-screen md:flex md:flex-col md:justify-between z-10 relative">
            <p className="text-center font-bold mb-4">締切間近</p>

            {entrysheets.length === 0 ? (
                <p className="text-gray-600">登録されたエントリーシートがありません。</p>
            ) : (
                <div className="space-y-2 flex-1 overflow-y-auto flex flex-col items-center">
                    {entrysheets.map((entrysheet) => (
                        <div
                            key={entrysheet.id}
                            className="bg-white p-2 border rounded-[12px] cursor-pointer transition-transform duration-200 hover:scale-105 group"
                            style={{
                                minHeight: "60px",
                                display: "flex",
                                flexDirection: "column",
                                alignItems: "center",
                                justifyContent: "center",
                                width: "200px",  
                            }}
                            onClick={() => router.visit(`/entrysheet/${entrysheet.id}`)}
                        >
                            <p className="text-m text-gray-600">
                                {entrysheet.deadline
                                    ? new Date(entrysheet.deadline).toLocaleDateString("ja-JP", {
                                          month: "numeric",
                                          day: "numeric",
                                          weekday: "short",
                                      })
                                    : "未設定"}
                            </p>

                            {/* 会社名の表示 */}
                            <p className="text-center text-sm text-gray-500 font-semibold truncate">
                                
                            </p>

                            <p className="text-center text-sm text-gray-500 font-semibold truncate">
                            {entrysheet.company?.name || "No Company"}: {entrysheet.title}
                            </p>
                        </div>
                    ))}
                </div>
            )}
        </div>
    );
}