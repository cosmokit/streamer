import { useState } from "react";
import { Lock, Sparkles, CheckCircle2 } from "lucide-react";

const onboardingSteps = [
  { title: "Шаг 1 - Ознакомление", description: "Изучите краткое сведение, чтобы получить представление о предстоящей деятельности.", emoji: "🎬" },
  { title: "Шаг 2 - Настройка и оформление канала Twitch", description: "Инструкция как зарегистрировать канал на Twitch для новичков.", emoji: "🎮" },
  { title: "Шаг 3 - Настройка OBS studio", description: "Инструкция для новичков от скачивания игровых записей до настройки OBS.", emoji: "🖥️" },
  { title: "Шаг 4 - Ответы на вопросы", description: "В случае возникновения затруднений или вопросов воспользуйтесь разделом «Помощь».", emoji: "❓" },
];

const monetizationSteps = [
  { title: "Оформляем компаньона Twitch", description: "В данной инструкции вы полностью поймёте как правильно заполнять компаньона для получения статуса монетизации в Twitch.", emoji: "💰" },
];

const ProgressPage = () => {
  const [completed, setCompleted] = useState<Record<number, boolean>>({});
  const completedCount = Object.values(completed).filter(Boolean).length;
  const totalSteps = onboardingSteps.length;
  const progressPercent = Math.round((completedCount / totalSteps) * 100);

  return (
    <div className="p-4 md:p-8 max-w-5xl">
      <div className="flex items-center gap-3 mb-2">
        <Sparkles size={24} style={{ color: "hsl(90 85% 55%)" }} />
        <h1 className="text-xl md:text-2xl font-bold glow-text" style={{ color: "hsl(260 20% 93%)" }}>Мой прогресс</h1>
      </div>

      {/* Phase indicators */}
      <div className="flex items-center justify-between mb-8 max-w-md mx-auto mt-8">
        <div className="flex flex-col items-center gap-2">
          <div className="w-10 h-10 rounded-full flex items-center justify-center text-sm font-bold glow-btn text-white">1</div>
          <span className="text-xs text-center" style={{ color: "hsl(260 15% 60%)" }}>Станьте частью<br />нашей команды</span>
        </div>
        <div className="flex-1 h-px mx-4" style={{
          background: "linear-gradient(90deg, hsl(90 85% 45% / 0.4), hsl(270 50% 20% / 0.2))"
        }} />
        <div className="flex flex-col items-center gap-2">
          <div className="w-10 h-10 rounded-full flex items-center justify-center text-sm font-bold" style={{
            background: "hsl(270 18% 12%)",
            color: "hsl(260 15% 45%)",
            border: "1px solid hsl(270 25% 20%)"
          }}>2</div>
          <span className="text-xs" style={{ color: "hsl(260 15% 35%)" }}>Монетизация</span>
        </div>
      </div>

      {/* Section 1 */}
      <div className="mb-10">
        <div className="flex items-center justify-between mb-4">
          <h2 className="text-xl font-bold" style={{ color: "hsl(260 20% 90%)" }}>Станьте частью нашей команды</h2>
          <span className="text-sm font-medium px-3 py-1 rounded-full glass-card" style={{ color: "hsl(90 85% 60%)" }}>
            {progressPercent}%
          </span>
        </div>
        <div className="w-full h-2 rounded-full mb-8" style={{ background: "hsl(270 25% 10%)" }}>
          <div className="h-full rounded-full transition-all duration-500" style={{
            width: `${progressPercent}%`,
            background: "linear-gradient(90deg, hsl(270 75% 50%), hsl(90 85% 45%))",
            boxShadow: "0 0 15px hsl(90 85% 45% / 0.4)",
          }} />
        </div>

        <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
          {onboardingSteps.map((step, i) => (
            <div key={i} className="glass-card glass-card-hover rounded-xl p-5 transition-all duration-300 group">
              <div className="flex items-start gap-4">
                <div className="text-3xl flex-shrink-0 mt-1">{step.emoji}</div>
                <div className="flex-1">
                  <div className="flex items-center justify-between mb-2">
                    <h3 className="font-semibold text-sm" style={{ color: "hsl(260 20% 90%)" }}>{step.title}</h3>
                    <span className="text-[10px] px-2 py-0.5 rounded-full font-semibold" style={{
                      background: "hsl(90 85% 45% / 0.15)",
                      color: "hsl(90 85% 60%)",
                      border: "1px solid hsl(90 85% 45% / 0.2)"
                    }}>В ПРОЦЕССЕ</span>
                  </div>
                  <p className="text-xs mb-4" style={{ color: "hsl(260 15% 50%)" }}>{step.description}</p>
                  <div className="flex gap-2">
                    <button className="px-4 py-1.5 rounded-lg text-xs font-medium transition-all" style={{
                      background: "hsl(270 25% 14%)",
                      color: "hsl(260 15% 75%)",
                      border: "1px solid hsl(270 25% 22%)"
                    }}>Начать обучение</button>
                    <button
                      onClick={() => setCompleted(prev => ({ ...prev, [i]: !prev[i] }))}
                      className="px-4 py-1.5 rounded-lg text-xs font-medium transition-all flex items-center gap-1.5"
                      style={{
                        background: completed[i] ? "hsl(90 85% 35% / 0.2)" : "linear-gradient(135deg, hsl(270 75% 50%), hsl(90 85% 40%))",
                        color: completed[i] ? "hsl(90 85% 60%)" : "#fff",
                        border: completed[i] ? "1px solid hsl(90 85% 40% / 0.3)" : "none",
                        boxShadow: completed[i] ? "none" : "0 0 15px hsl(270 75% 50% / 0.2)",
                      }}
                    >
                      {completed[i] && <CheckCircle2 size={12} />}
                      {completed[i] ? "Выполнено" : "Отметить"}
                    </button>
                  </div>
                </div>
              </div>
            </div>
          ))}
        </div>
      </div>

      {/* Monetization section (locked) */}
      <div>
        <div className="flex items-center justify-between mb-4">
          <h2 className="text-xl font-bold" style={{ color: "hsl(260 15% 35%)" }}>Монетизация</h2>
          <span className="text-sm" style={{ color: "hsl(260 15% 30%)" }}>0%</span>
        </div>
        <div className="w-full h-2 rounded-full mb-6" style={{ background: "hsl(270 18% 10%)" }} />

        {monetizationSteps.map((step, i) => (
          <div key={i} className="glass-card rounded-xl p-6 relative overflow-hidden" style={{ opacity: 0.5 }}>
            <div className="flex items-start gap-4">
              <div className="text-3xl flex-shrink-0 mt-1">{step.emoji}</div>
              <div className="flex-1">
                <h3 className="font-semibold mb-1" style={{ color: "hsl(260 15% 40%)" }}>{step.title}</h3>
                <p className="text-sm mb-4" style={{ color: "hsl(260 15% 35%)" }}>{step.description}</p>
                <div className="flex items-center justify-center flex-col gap-2 py-4">
                  <Lock size={28} style={{ color: "hsl(260 15% 35%)" }} />
                  <p className="text-xs text-center" style={{ color: "hsl(260 15% 35%)" }}>
                    Доступно после выполнения<br />условий активации (после 4 дней<br />+ активные прокси).
                  </p>
                </div>
              </div>
            </div>
          </div>
        ))}
      </div>
    </div>
  );
};

export default ProgressPage;
