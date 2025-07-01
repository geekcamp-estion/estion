import InputError from '@/Components/InputError';
import InputLabel from '@/Components/InputLabel';
import PrimaryButton from '@/Components/PrimaryButton';
import TextInput from '@/Components/TextInput';
import { Transition } from '@headlessui/react';
import { Link, useForm, usePage } from '@inertiajs/react';

export default function UpdateProfileInformation({ mustVerifyEmail, status, className = '' }) {
  const user = usePage().props.auth.user;

  const { data, setData, patch, errors, processing, recentlySuccessful } = useForm({
    name: user.name,
    email: user.email,
  });

  const submit = (e) => {
    e.preventDefault();

    patch(route('profile.update'));
  };

  return (
    <section className={className}>
      <header className="mb-4">
        <p className="mt-1 text-sm text-gray-600">
          アカウントのプロフィール情報とメールアドレスを更新します。
        </p>
      </header>

      <form onSubmit={submit} className="mt-6 space-y-4">
        <div>
          <InputLabel htmlFor="name" value="お名前" />

          <TextInput
            id="name"
            className="mt-1 block w-full rounded-[12px] border border-gray-300 p-3 focus:border-blue-500 focus:ring-blue-500"
            value={data.name}
            onChange={(e) => setData('name', e.target.value)}
            required
            isFocused
            autoComplete="name"
          />

          <InputError className="mt-2" message={errors.name} />
        </div>

        <div>
          <InputLabel htmlFor="email" value="メールアドレス" />

          <TextInput
            id="email"
            type="email"
            className="mt-1 block w-full rounded-[12px] border border-gray-300 p-3 focus:border-blue-500 focus:ring-blue-500"
            value={data.email}
            onChange={(e) => setData('email', e.target.value)}
            required
            autoComplete="username"
          />

          <InputError className="mt-2" message={errors.email} />
        </div>

        {mustVerifyEmail && user.email_verified_at === null && (
          <div>
            <p className="mt-2 text-sm text-gray-800">
              メールアドレスが認証されていません。
              <Link
                href={route('verification.send')}
                method="post"
                as="button"
                className="rounded-md text-sm text-gray-600 underline hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
              >
                こちらをクリックして、認証メールを再送信してください。
              </Link>
            </p>

            {status === 'verification-link-sent' && (
              <div className="mt-2 text-sm font-medium text-green-600">
                新しい認証リンクがあなたのメールアドレスに送信されました。
              </div>
            )}
          </div>
        )}

        <div className="flex items-center gap-4">
          <PrimaryButton disabled={processing}>保存</PrimaryButton>

          <Transition
            show={recentlySuccessful}
            enter="transition ease-in-out"
            enterFrom="opacity-0"
            leave="transition ease-in-out"
            leaveTo="opacity-0"
          >
            <p className="text-sm text-gray-600">保存しました。</p>
          </Transition>
        </div>
      </form>
    </section>
  );
}
