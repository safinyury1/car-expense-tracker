<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
            <h2 class="font-semibold text-xl sm:text-2xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Руководство пользователя') }}
            </h2>
            <a href="{{ route('settings.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-3 sm:px-4 py-2 rounded-lg text-sm transition w-full sm:w-auto text-center">
                Назад
            </a>
        </div>
    </x-slot>

    <div class="py-4 sm:py-6 md:py-12">
        <div class="max-w-4xl mx-auto px-3 sm:px-4 md:px-6 lg:px-8">
            <div class="bg-white dark:bg-[#222222] overflow-hidden shadow-sm rounded-xl">
                <div class="p-5 sm:p-6 md:p-8">
                    
                    <div class="prose max-w-none dark:prose-invert">
                        <h1 class="text-2xl font-bold text-gray-800 dark:text-white mb-4">Добро пожаловать в AutoCost!</h1>
                        <p class="text-gray-600 dark:text-gray-400 mb-6">Это приложение поможет вам отслеживать расходы на автомобиль, заправки и многое другое.</p>
                        
                        <div class="space-y-6">
                            <!-- Раздел 1 -->
                            <div class="border-b border-gray-100 dark:border-gray-700 pb-4">
                                <h2 class="text-xl font-semibold text-gray-800 dark:text-white mb-2">1. Регистрация и вход</h2>
                                <p class="text-gray-600 dark:text-gray-400">Для начала работы необходимо зарегистрироваться или войти в систему. После входа вы попадёте на страницу добавления автомобиля.</p>
                            </div>
                            
                            <!-- Раздел 2 -->
                            <div class="border-b border-gray-100 dark:border-gray-700 pb-4">
                                <h2 class="text-xl font-semibold text-gray-800 dark:text-white mb-2">2. Добавление автомобиля</h2>
                                <p class="text-gray-600 dark:text-gray-400">Нажмите на кнопку <span class="bg-blue-600 text-white px-2 py-0.5 rounded text-sm">Добавить автомобиль</span> в шапке страницы. Заполните марку, модель, год и пробег. При необходимости добавьте фото автомобиля.</p>
                                <p class="text-gray-500 dark:text-gray-500 text-sm mt-1">Все добавленные автомобили хранятся в разделе <strong>«Мои автомобили» (Гараж)</strong>.</p>
                            </div>
                            
                            <!-- Раздел 3 -->
                            <div class="border-b border-gray-100 dark:border-gray-700 pb-4">
                                <h2 class="text-xl font-semibold text-gray-800 dark:text-white mb-2">3. Навигация по приложению</h2>
                                <p class="text-gray-600 dark:text-gray-400">В верхней панели находится кнопка <span class="bg-blue-600 text-white px-2 py-0.5 rounded text-sm">Действия</span>, которая открывает меню для быстрого доступа:</p>
                                <ul class="list-disc list-inside mt-2 space-y-1 text-gray-600 dark:text-gray-400">
                                    <li><strong>Добавить:</strong> заправку, расход, автомобиль, обслуживание, доход</li>
                                    <li><strong>Перейти к:</strong> автомобилям, заправкам, расходам, доходам</li>
                                </ul>
                                <p class="text-gray-500 dark:text-gray-500 text-sm mt-2">Остальные разделы доступны в основном меню слева (на десктопе) или через бургер-меню (на мобильных).</p>
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
                                <p class="text-gray-600 dark:text-gray-400">Чтобы добавить расход, нажмите на кнопку <span class="bg-blue-600 text-white px-2 py-0.5 rounded text-sm">Действия</span> → <span class="text-red-500 font-medium">Расход</span>. Укажите категорию, сумму, пробег и дату. Расходы автоматически появятся в истории и на дашборде.</p>
                            </div>
                            
                            <!-- Раздел 6 -->
                            <div class="border-b border-gray-100 dark:border-gray-700 pb-4">
                                <h2 class="text-xl font-semibold text-gray-800 dark:text-white mb-2">6. Добавление заправок</h2>
                                <p class="text-gray-600 dark:text-gray-400">Нажмите на кнопку <span class="bg-blue-600 text-white px-2 py-0.5 rounded text-sm">Действия</span> → <span class="text-green-500 font-medium">Заправку</span>. Укажите количество литров, цену и пробег. Система автоматически рассчитает сумму и расход топлива.</p>
                            </div>
                            
                            <!-- Раздел 7 -->
                            <div class="border-b border-gray-100 dark:border-gray-700 pb-4">
                                <h2 class="text-xl font-semibold text-gray-800 dark:text-white mb-2">7. Добавление дохода</h2>
                                <p class="text-gray-600 dark:text-gray-400">Нажмите на кнопку <span class="bg-blue-600 text-white px-2 py-0.5 rounded text-sm">Действия</span> → <span class="text-green-500 font-medium">Доход</span>. Укажите категорию, название, сумму и дату. Доходы отображаются в истории зелёным цветом.</p>
                            </div>
                            
                            <!-- Раздел 8 -->
                            <div class="border-b border-gray-100 dark:border-gray-700 pb-4">
                                <h2 class="text-xl font-semibold text-gray-800 dark:text-white mb-2">8. Добавление обслуживания</h2>
                                <p class="text-gray-600 dark:text-gray-400">Нажмите на кнопку <span class="bg-blue-600 text-white px-2 py-0.5 rounded text-sm">Действия</span> → <span class="text-yellow-500 font-medium">Обслуживание</span>. Запишите выполненные работы, укажите пробег, стоимость и при необходимости создайте напоминание о следующем ТО.</p>
                            </div>
                            
                            <!-- Раздел 9 -->
                            <div class="border-b border-gray-100 dark:border-gray-700 pb-4">
                                <h2 class="text-xl font-semibold text-gray-800 dark:text-white mb-2">9. Напоминания о ТО</h2>
                                <p class="text-gray-600 dark:text-gray-400">В разделе <strong>«Напоминания»</strong> вы можете создавать напоминания о техническом обслуживании. При достижении указанного пробега или даты напоминание появится на главной странице. Также можно отмечать напоминания как выполненные.</p>
                            </div>
                            
                            <!-- Раздел 10 -->
                            <div class="border-b border-gray-100 dark:border-gray-700 pb-4">
                                <h2 class="text-xl font-semibold text-gray-800 dark:text-white mb-2">10. Уведомления</h2>
                                <p class="text-gray-600 dark:text-gray-400">AutoCost может отправлять вам уведомления на email о важных событиях:</p>
                                
                                <div class="mt-3 space-y-2 bg-gray-50 dark:bg-gray-800/30 p-4 rounded-xl">
                                    <div>
                                        <p class="font-medium text-gray-800 dark:text-white">Уведомления о ТО</p>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">Приходит за день до запланированного технического обслуживания</p>
                                    </div>
                                    <div>
                                        <p class="font-medium text-gray-800 dark:text-white">Уведомления о новых расходах</p>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">Приходит сразу после добавления нового расхода</p>
                                    </div>
                                    <div>
                                        <p class="font-medium text-gray-800 dark:text-white">Уведомления о новых заправках</p>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">Приходит сразу после добавления новой заправки</p>
                                    </div>
                                    <div>
                                        <p class="font-medium text-gray-800 dark:text-white">Еженедельный отчёт</p>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">Приходит раз в неделю со сводкой по расходам и пробегу</p>
                                    </div>
                                </div>
                                
                                <div class="mt-3 p-3 bg-blue-50 dark:bg-blue-900/20 rounded-lg border border-blue-200 dark:border-blue-800">
                                    <p class="text-sm text-blue-700 dark:text-blue-300">
                                        Вы можете управлять уведомлениями в разделе <strong>«Настройки» → «Настройки уведомлений»</strong>. Отключите ненужные вам типы уведомлений в любой момент.
                                    </p>
                                </div>
                            </div>
                            
                            <!-- Раздел 11 -->
                            <div class="border-b border-gray-100 dark:border-gray-700 pb-4">
                                <h2 class="text-xl font-semibold text-gray-800 dark:text-white mb-2">11. Статистика и графики</h2>
                                <p class="text-gray-600 dark:text-gray-400">На странице <strong>«Статистика»</strong> вы можете увидеть графики расходов по категориям, динамику расходов по месяцам и историю расхода топлива. Доступен выбор периода (сегодня, неделя, месяц, свой период) и фильтр по автомобилям. Также есть кнопка <span class="bg-blue-600 text-white px-2 py-0.5 rounded text-sm">Экспорт PDF</span> для сохранения отчёта.</p>
                            </div>
                            
                            <!-- Раздел 12 -->
                            <div class="border-b border-gray-100 dark:border-gray-700 pb-4">
                                <h2 class="text-xl font-semibold text-gray-800 dark:text-white mb-2">12. Сравнение автомобилей</h2>
                                <p class="text-gray-600 dark:text-gray-400">В разделе <strong>«Сравнение»</strong> вы можете выбрать до 4 автомобилей и сравнить их по таким параметрам, как общие расходы, затраты на топливо, средний расход топлива, стоимость 1 км пробега. Результаты отображаются в виде таблицы и наглядных графиков.</p>
                            </div>
                            
                            <!-- Раздел 13 -->
                            <div class="border-b border-gray-100 dark:border-gray-700 pb-4">
                                <h2 class="text-xl font-semibold text-gray-800 dark:text-white mb-2">13. История операций</h2>
                                <p class="text-gray-600 dark:text-gray-400">Страница <strong>«История»</strong> показывает все ваши расходы, заправки и доходы в хронологическом порядке. Вы можете фильтровать по автомобилю, категории и периоду, а также удалять ненужные записи.</p>
                            </div>
                            
                            <!-- Раздел 14 -->
                            <div class="border-b border-gray-100 dark:border-gray-700 pb-4">
                                <h2 class="text-xl font-semibold text-gray-800 dark:text-white mb-2">14. Настройки профиля</h2>
                                <p class="text-gray-600 dark:text-gray-400">В выпадающем меню с вашим именем (в правом верхнем углу) можно изменить аватар, имя, email и пароль. Также можно удалить аккаунт.</p>
                            </div>
                            
                            <!-- Раздел 15 -->
                            <div class="border-b border-gray-100 dark:border-gray-700 pb-4">
                                <h2 class="text-xl font-semibold text-gray-800 dark:text-white mb-2">15. Настройки автомобиля</h2>
                                <p class="text-gray-600 dark:text-gray-400">В разделе <strong>«Настройки» → «Управление автомобилями» → «Настройки авто»</strong> вы можете изменить единицы измерения (км/мили, литры/галлоны), валюту для каждого автомобиля, а также управлять категориями расходов.</p>
                            </div>
                            
                            <!-- Раздел 16 -->
                            <div class="border-b border-gray-100 dark:border-gray-700 pb-4">
                                <h2 class="text-xl font-semibold text-gray-800 dark:text-white mb-2">16. Восстановление пароля</h2>
                                <p class="text-gray-600 dark:text-gray-400">Если вы забыли пароль, нажмите на ссылку <strong>«Забыли пароль?»</strong> на странице входа. Введите email, и на почту придёт ссылка для сброса пароля.</p>
                            </div>
                            
                            <!-- ========================================== -->
                            <!-- РАЗДЕЛ 17: ПОДДЕРЖКА (НОВЫЙ) -->
                            <!-- ========================================== -->
                            <div class="border-b border-gray-100 dark:border-gray-700 pb-4">
                                <h2 class="text-xl font-semibold text-gray-800 dark:text-white mb-2">17. Поддержка</h2>
                                <p class="text-gray-600 dark:text-gray-400">Если у вас возникли вопросы или проблемы при использовании AutoCost, вы можете обратиться в службу поддержки:</p>
                                
                                <div class="mt-3 space-y-3 bg-gray-50 dark:bg-gray-800/30 p-4 rounded-xl">
                                    <div class="flex items-center gap-3">
                                        <div>
                                            <p class="text-sm font-medium text-gray-800 dark:text-white">Email</p>
                                            <a href="mailto:autocost774@gmail.com" class="text-sm text-blue-600 dark:text-blue-400 hover:underline">autocost774@gmail.com</a>
                                        </div>
                                    </div>
                                    
                                    <div class="flex items-center gap-3">
                                        <div>
                                            <p class="text-sm font-medium text-gray-800 dark:text-white">Telegram</p>
                                            <a href="https://t.me/autocost_support" target="_blank" class="text-sm text-blue-600 dark:text-blue-400 hover:underline">@autocost_support</a>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="mt-3 p-3 bg-green-50 dark:bg-green-900/20 rounded-lg border border-green-200 dark:border-green-800">
                                    <p class="text-sm text-green-700 dark:text-green-300">
                                        <strong>Как отправить обращение:</strong> В разделе <strong>«Настройки» → «Помощь» → «Связаться с поддержкой»</strong> вы можете заполнить форму с темой и описанием проблемы. Наши специалисты ответят вам в ближайшее время.
                                    </p>
                                </div>
                                
                                <div class="mt-3 p-3 bg-yellow-50 dark:bg-yellow-900/20 rounded-lg border border-yellow-200 dark:border-yellow-800">
                                    <p class="text-sm text-yellow-700 dark:text-yellow-300">
                                        <strong>Совет:</strong> Перед обращением в поддержку ознакомьтесь с данным руководством — возможно, ответ на ваш вопрос уже есть здесь.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-8 pt-4 border-t border-gray-100 dark:border-gray-700 text-center text-gray-400 dark:text-gray-500 text-sm">
                        <p>© {{ date('Y') }} AutoCost. Все права защищены.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>