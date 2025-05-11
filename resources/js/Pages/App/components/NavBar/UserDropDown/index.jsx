import React, { useState, useRef, useEffect } from "react";
import { Link, usePage } from "@inertiajs/react";

export default function UserDropdown() {
    const { auth } = usePage().props;
    const [isOpen, setIsOpen] = useState(false);
    const dropdownRef = useRef(null);

    const toggleDropdown = () => {
        setIsOpen(!isOpen);
    };

    const handleClickOutside = (event) => {
        if (dropdownRef.current && !dropdownRef.current.contains(event.target)) {
            setIsOpen(false);
        }
    };

    useEffect(() => {
        document.addEventListener('mousedown', handleClickOutside);
        return () => {
            document.removeEventListener('mousedown', handleClickOutside);
        };
    }, []);

    return (
        <div className="relative">
            <button
                onClick={toggleDropdown}
                className="text-sm text-gray-700 font-semibold focus:outline-none flex items-center"
            >
                {auth?.user?.name}
                <svg className={`w-4 h-4 ml-1 transition-transform ${isOpen ? 'rotate-180' : ''}`} viewBox="0 0 20 20" fill="currentColor">
                    <path fillRule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clipRule="evenodd" />
                </svg>
            </button>

            {isOpen && (
                <div ref={dropdownRef} className="absolute right-0 mt-5 w-60 bg-white rounded-md shadow-xl z-10">
                    <Link href="/profile" className="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                        プロフィール
                    </Link>
                    <Link href={route('bookmark.create')} className="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                        ブックマーク
                    </Link>
                    <a href="https://docs.google.com/forms/d/1sZsxsi5FqdiS35YW2CVzQ4CANyljnbjt2K9urmRomHE/edit" className="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                        お問い合わせ
                    </a>
                    <Link href="/logout" method="post" as="button" className="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 focus:outline-none">
                        ログアウト
                    </Link>
                </div>
            )}
        </div>
    );
}