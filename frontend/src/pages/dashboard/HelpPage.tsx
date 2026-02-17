import { useState } from "react";
import { ChevronDown, HelpCircle, MessageCircle } from "lucide-react";

const faqItems = [
  { badge: "Шаг 1", badgeGradient: "linear-gradient(135deg, hsl(270 75% 50%), hsl(90 85% 40%))", title: "Регистрация на Twitch", description: "Инструкция как зарегистрировать канал на Twitch для новичков", link: "https://telegra.ph/Registraciya-Twitch-10-24" },
  { badge: "ШАГ 2", badgeGradient: "linear-gradient(135deg, hsl(270 75% 50%), hsl(90 85% 40%))", title: "Настройка OBS studio", description: "Инструкция для новичков от скачивания игровых записей до настройки OBS" },
  { badge: "ШАГ 3", badgeGradient: "linear-gradient(135deg, hsl(270 75% 50%), hsl(90 85% 40%))", title: "Оформляем компаньона Twitch", description: "В данной инструкции вы полностью поймёте как правильно заполнять компаньона для получения статуса монетизации в Twitch" },
  { badge: "Полезно", badgeGradient: "linear-gradient(135deg, hsl(90 85% 40%), hsl(90 85% 55%))", title: "Для чего нужны прокси?", description: "Подробное разъяснение как работают прокси и для чего они необходимы." },
  { badge: "Полезно", badgeGradient: "linear-gradient(135deg, hsl(90 85% 40%), hsl(90 85% 55%))", title: "Сколько часов мне стримить?", description: "Ответы на часто задаваемы вопросы" },
  { badge: "Полезно", badgeGradient: "linear-gradient(135deg, hsl(90 85% 40%), hsl(90 85% 55%))", title: "Сколько я могу заработать за 30 дней", description: "Ответы на часто задаваемы вопросы" },
  { badge: "ПОЛЕЗНО", badgeGradient: "linear-gradient(135deg, hsl(90 85% 40%), hsl(90 85% 55%))", title: "Как я могу сделать больше 1 канала", description: "Ответы на часто задаваемы вопросы" },
  { badge: "Полезное", badgeGradient: "linear-gradient(135deg, hsl(90 85% 40%), hsl(90 85% 55%))", title: "Как считается математика рекламы Twitch?", description: "Ответы на часто задаваемы вопросы" },
  { badge: "Полезное", badgeGradient: "linear-gradient(135deg, hsl(90 85% 40%), hsl(90 85% 55%))", title: "Когда мне нужно подключить прокси?", description: "Ответы на часто задаваемы вопросы" },
  { badge: "Полезное", badgeGradient: "linear-gradient(135deg, hsl(90 85% 40%), hsl(90 85% 55%))", title: "Зачем указывать Твиттер (X)", description: "Ответы на часто задаваемы вопросы" },
  { badge: "Основное", badgeGradient: "linear-gradient(135deg, hsl(35 100% 50%), hsl(25 100% 55%))", title: "Ознакомление", description: "Основная инструкция" },
  { badge: "Полезное", badgeGradient: "linear-gradient(135deg, hsl(90 85% 40%), hsl(90 85% 55%))", title: "Почему мы делимся схемой бесплатно и в чём наша выгода?", description: "Ответы на часто задаваемые вопросы" },
];

const HelpPage = () => {
  const [openIndex, setOpenIndex] = useState<number | null>(null);

  return (
    <div className="p-4 md:p-8 max-w-5xl">
      <div className="flex items-center gap-3 mb-6">
        <HelpCircle size={24} style={{ color: "hsl(90 85% 55%)" }} />
        <div>
          <h1 className="text-2xl font-bold glow-text" style={{ color: "hsl(260 20% 93%)" }}>Помощь и Поддержка</h1>
          <p className="text-sm" style={{ color: "hsl(260 15% 50%)" }}>Найдите ответы на часто задаваемые вопросы</p>
        </div>
      </div>

      <div className="flex flex-col gap-3">
        {faqItems.map((item, i) => (
          <div key={i} className="glass-card glass-card-hover rounded-xl overflow-hidden transition-all duration-300">
            <button
              onClick={() => setOpenIndex(openIndex === i ? null : i)}
              className="w-full flex items-center justify-between p-5 text-left"
            >
              <div>
                <span className="text-[10px] px-2.5 py-0.5 rounded font-bold inline-block mb-2 text-white" style={{
                  background: item.badgeGradient,
                }}>{item.badge}</span>
                <h3 className="font-semibold" style={{ color: "hsl(260 20% 90%)" }}>{item.title}</h3>
                <p className="text-sm mt-0.5" style={{ color: "hsl(260 15% 50%)" }}>{item.description}</p>
              </div>
              <ChevronDown
                size={18}
                className="flex-shrink-0 ml-4 transition-transform duration-300"
                style={{ color: "hsl(260 15% 45%)", transform: openIndex === i ? "rotate(180deg)" : "rotate(0)" }}
              />
            </button>
            {openIndex === i && (
              <div className="px-5 pb-5 text-sm" style={{ color: "hsl(260 15% 55%)" }}>
                {item.link ? (
                  <p>Инструкция — <a href={item.link} target="_blank" rel="noopener noreferrer" className="hover:underline" style={{ color: "hsl(90 85% 55%)" }}>{item.link}</a></p>
                ) : (
                  <p>Содержимое раздела будет доступно в ближайшее время.</p>
                )}
              </div>
            )}
          </div>
        ))}
      </div>

      <div className="glass-card rounded-xl p-6 mt-8 relative overflow-hidden">
        <div className="absolute inset-0 pointer-events-none" style={{
          background: "radial-gradient(ellipse at bottom right, hsl(90 85% 45% / 0.06), transparent 60%)"
        }} />
        <div className="flex items-center gap-2 mb-2 relative z-10">
          <MessageCircle size={18} style={{ color: "hsl(195 100% 55%)" }} />
          <h3 className="font-semibold" style={{ color: "hsl(260 20% 90%)" }}>Нужна помощь?</h3>
        </div>
        <p className="text-sm mb-4 relative z-10" style={{ color: "hsl(260 15% 50%)" }}>
          Не можете найти то, что ищете? Свяжитесь с нашей командой поддержки.
        </p>
        <a href="https://t.me/vladis1av_esipenko" target="_blank" rel="noopener noreferrer"
          className="inline-block px-6 py-3 rounded-lg text-sm font-medium text-white glow-btn relative z-10">
          Связаться в Telegram
        </a>
      </div>
    </div>
  );
};

export default HelpPage;
