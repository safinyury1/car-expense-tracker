<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Руководство пользователя') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-[#222222] rounded-2xl shadow-sm overflow-hidden">
                <div class="p-6">
                    
                    <div class="prose max-w-none dark:prose-invert">
                        <h1 class="text-2xl font-bold text-gray-800 dark:text-white mb-4">Добро пожаловать в Car Expense Tracker!</h1>
                        <p class="text-gray-600 dark:text-gray-400 mb-6">Это приложение поможет вам отслеживать расходы на автомобиль, заправки и многое другое.</p>
                        
                        <div class="space-y-6">
                            <!-- Раздел 1 -->
                            <div class="border-b border-gray-100 dark:border-gray-700 pb-4">
                                <h2 class="text-xl font-semibold text-gray-800 dark:text-white mb-2">1. Регистрация и вход</h2>
                                <p class="text-gray-600 dark:text-gray-400">Для начала работы необходимо <a href="{{ route('register') }}" class="text-blue-500 hover:underline">зарегистрироваться</a> или <a href="{{ route('login') }}" class="text-blue-500 hover:underline">войти</a> в систему. После входа вы попадёте на страницу добавления автомобиля.</p>
                            </div>
                            
                            <!-- Раздел 2 -->
                            <div class="border-b border-gray-100 dark:border-gray-700 pb-4">
                                <h2 class="text-xl font-semibold text-gray-800 dark:text-white mb-2">2. Добавление автомобиля</h2>
                                <p class="text-gray-600 dark:text-gray-400">Нажмите на кнопку <span class="bg-blue-500 text-white px-2 py-0.5 rounded text-sm">Добавить автомобиль</span> или на круглую синюю кнопку с плюсом в центре шапки. Заполните марку, модель, год и пробег. При необходимости добавьте фото автомобиля.</p>
                                <p class="text-gray-500 dark:text-gray-500 text-sm mt-1">Все добавленные автомобили хранятся в разделе <strong>«Мои автомобили» (Гараж)</strong>.</p>
                            </div>
                            
                            <!-- Раздел 3 -->
                            <div class="border-b border-gray-100 dark:border-gray-700 pb-4">
                                <h2 class="text-xl font-semibold text-gray-800 dark:text-white mb-2">3. Навигация по приложению</h2>
                                <p class="text-gray-600 dark:text-gray-400">В верхней панели находятся две круглые кнопки:</p>
                                <ul class="list-disc list-inside mt-2 space-y-1 text-gray-600 dark:text-gray-400">
                                    <li><span class="bg-blue-500 text-white px-2 py-0.5 rounded text-sm">Синяя кнопка (+)</span> — для быстрого добавления: заправка, расход, автомобиль, обслуживание, доход.</li>
                                    <li><span class="bg-gray-500 text-white px-2 py-0.5 rounded text-sm">Серая кнопка (☰)</span> — для быстрой навигации по страницам: Заправки, Расходы, Доходы.</li>
                                </ul>
                                <p class="text-gray-500 dark:text-gray-500 text-sm mt-2">Остальные разделы доступны в основном меню слева от кнопок.</p>
                            </div>
                            
                            <!-- Раздел 4 -->
                            <div class="border-b border-gray-100 dark:border-gray-700 pb-4">
                                <h2 class="text-xl font-semibold text-gray-800 dark:text-white mb-2">4. Управление категориями</h2>
                                <p class="text-gray-600 dark:text-gray-400">В разделе <strong>«Категории»</strong> вы можете просматривать стандартные категории расходов (Ремонт, Страховка, Налог и др.) и создавать свои собственные категории с любым названием.</p>
                                <p class="text-gray-500 dark:text-gray-500 text-sm mt-1">Созданные категории автоматически появляются в выпадающем списке при добавлении расхода.</p>
                            </div>
                            
                            <!-- Раздел 5 -->
                            <div class="border-b border-gray-100 dark:border-gray-700 pb-4">
                                <h2 class="text-xl font-semibold text-gray-800 dark:text-white mb-2">5. Добавление расходов</h2>
                                <p class="text-gray-600 dark:text-gray-400">Чтобы добавить расход, нажмите на круглую синюю кнопку с плюсом → <span class="text-red-500 font-medium">Добавить расход</span>. Укажите категорию, сумму, пробег и дату. Расходы автоматически появятся в истории и на дашборде.</p>
                            </div>
                            
                            <!-- Раздел 6 -->
                            <div class="border-b border-gray-100 dark:border-gray-700 pb-4">
                                <h2 class="text-xl font-semibold text-gray-800 dark:text-white mb-2">6. Добавление заправок</h2>
                                <p class="text-gray-600 dark:text-gray-400">Нажмите на круглую синюю кнопку с плюсом → <span class="text-green-500 font-medium">Добавить заправку</span>. Укажите количество литров, цену и пробег. Система автоматически рассчитает сумму и расход топлива.</p>
                            </div>
                            
                            <!-- Раздел 7 -->
                            <div class="border-b border-gray-100 dark:border-gray-700 pb-4">
                                <h2 class="text-xl font-semibold text-gray-800 dark:text-white mb-2">7. Добавление дохода</h2>
                                <p class="text-gray-600 dark:text-gray-400">Нажмите на круглую синюю кнопку с плюсом → <span class="text-green-500 font-medium">Добавить доход</span>. Укажите категорию, название, сумму и дату. Доходы отображаются в истории зелёным цветом.</p>
                            </div>
                            
                            <!-- Раздел 8 -->
                            <div class="border-b border-gray-100 dark:border-gray-700 pb-4">
                                <h2 class="text-xl font-semibold text-gray-800 dark:text-white mb-2">8. Добавление обслуживания</h2>
                                <p class="text-gray-600 dark:text-gray-400">Нажмите на круглую синюю кнопку с плюсом → <span class="text-yellow-500 font-medium">Добавить обслуживание</span>. Запишите выполненные работы, укажите пробег, стоимость и при необходимости создайте напоминание о следующем ТО.</p>
                            </div>
                            
                            <!-- Раздел 9 -->
                            <div class="border-b border-gray-100 dark:border-gray-700 pb-4">
                                <h2 class="text-xl font-semibold text-gray-800 dark:text-white mb-2">9. Напоминания о ТО</h2>
                                <p class="text-gray-600 dark:text-gray-400">В разделе <strong>«Напоминания»</strong> вы можете создавать напоминания о техническом обслуживании. При достижении указанного пробега или даты напоминание появится на главной странице. Также можно отмечать напоминания как выполненные.</p>
                            </div>
                            
                            <!-- Раздел 10 -->
                            <div class="border-b border-gray-100 dark:border-gray-700 pb-4">
                                <h2 class="text-xl font-semibold text-gray-800 dark:text-white mb-2">10. Статистика и графики</h2>
                                <p class="text-gray-600 dark:text-gray-400">На странице <strong>«Статистика»</strong> вы можете увидеть графики расходов по категориям, динамику расходов по месяцам и историю расхода топлива. Доступен выбор периода (сегодня, неделя, месяц, свой период) и фильтр по автомобилям. Также есть кнопка <span class="bg-blue-600 text-white px-2 py-0.5 rounded text-sm">Экспорт PDF</span> для сохранения отчёта.</p>
                            </div>
                            
                            <!-- Раздел 11 -->
                            <div class="border-b border-gray-100 dark:border-gray-700 pb-4">
                                <h2 class="text-xl font-semibold text-gray-800 dark:text-white mb-2">11. Сравнение автомобилей</h2>
                                <p class="text-gray-600 dark:text-gray-400">В разделе <strong>«Сравнение»</strong> вы можете выбрать до 4 автомобилей и сравнить их по таким параметрам, как общие расходы, затраты на топливо, средний расход топлива, стоимость 1 км пробега. Результаты отображаются в виде таблицы и наглядных графиков.</p>
                            </div>
                            
                            <!-- Раздел 12 -->
                            <div class="border-b border-gray-100 dark:border-gray-700 pb-4">
                                <h2 class="text-xl font-semibold text-gray-800 dark:text-white mb-2">12. История операций</h2>
                                <p class="text-gray-600 dark:text-gray-400">Страница <strong>«История»</strong> показывает все ваши расходы, заправки и доходы в хронологическом порядке. Вы можете фильтровать по автомобилю, категории и периоду, а также удалять ненужные записи.</p>
                            </div>
                            
                            <!-- Раздел 13 -->
                            <div class="border-b border-gray-100 dark:border-gray-700 pb-4">
                                <h2 class="text-xl font-semibold text-gray-800 dark:text-white mb-2">13. Настройки профиля</h2>
                                <p class="text-gray-600 dark:text-gray-400">В выпадающем меню с вашим именем можно изменить аватар, имя, email и пароль. Также можно удалить аккаунт.</p>
                            </div>
                            
                            <!-- Раздел 14 -->
                            <div class="border-b border-gray-100 dark:border-gray-700 pb-4">
                                <h2 class="text-xl font-semibold text-gray-800 dark:text-white mb-2">14. Настройки автомобиля</h2>
                                <p class="text-gray-600 dark:text-gray-400">В разделе <strong>«Настройки» → «Управление автомобилями» → «Настройки авто»</strong> вы можете изменить единицы измерения (км/мили, литры/галлоны), валюту для каждого автомобиля, а также управлять категориями расходов.</p>
                            </div>
                            
                            <!-- Раздел 15 -->
                            <div class="pb-4">
                                <h2 class="text-xl font-semibold text-gray-800 dark:text-white mb-2">15. Восстановление пароля</h2>
                                <p class="text-gray-600 dark:text-gray-400">Если вы забыли пароль, нажмите на ссылку <strong>«Забыли пароль?»</strong> на странице входа. Введите email, и на почту придёт ссылка для сброса пароля.</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-8 pt-4 border-t border-gray-100 dark:border-gray-700 text-center text-gray-400 dark:text-gray-500 text-sm">
                        <p>© {{ date('Y') }} Car Expense Tracker. Все права защищены.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>