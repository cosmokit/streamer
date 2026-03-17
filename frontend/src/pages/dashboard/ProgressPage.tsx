import { useState, useEffect } from "react";
import { Lock, Sparkles, CheckCircle2, ExternalLink } from "lucide-react";
import { toast } from "@/hooks/use-toast";

interface LearningStep {
  id: number;
  title: string;
  description: string;
  external_url: string;
  order: number;
  status: 'new' | 'in_progress' | 'awaiting_confirmation' | 'completed';
  started_at: string | null;
  completed_at: string | null;
}

interface ProgressSummary {
  total: number;
  completed: number;
  progress_percentage: number;
}

const ProgressPage = () => {
  const [steps, setSteps] = useState<LearningStep[]>([]);
  const [summary, setSummary] = useState<ProgressSummary>({ total: 0, completed: 0, progress_percentage: 0 });
  const [loading, setLoading] = useState(true);
  const [loadingAction, setLoadingAction] = useState<number | null>(null);

  const loadProgress = () => {
    fetch('/api/progress', {
      credentials: 'include',
      headers: { 'Accept': 'application/json' }
    })
      .then(res => res.json())
      .then(response => {
        setSteps(response.data);
        setSummary(response.summary);
        setLoading(false);
      })
      .catch(err => {
        console.error('Error loading progress:', err);
        toast({ title: "Ошибка", description: "Не удалось загрузить прогресс", variant: "destructive" });
        setLoading(false);
      });
  };

  useEffect(() => {
    loadProgress();
  }, []);

  const handleStart = (step: LearningStep) => {
    setLoadingAction(step.id);
    fetch(`/api/progress/${step.id}/start`, {
      method: 'POST',
      credentials: 'include',
      headers: { 
        'Accept': 'application/json',
        'Content-Type': 'application/json'
      }
    })
      .then(res => res.json())
      .then(() => {
        window.open(step.external_url, '_blank');
        loadProgress();
        setLoadingAction(null);
      })
      .catch(err => {
        console.error('Error starting step:', err);
        toast({ title: "Ошибка", description: "Не удалось начать обучение", variant: "destructive" });
        setLoadingAction(null);
      });
  };

  const handleComplete = (stepId: number) => {
    setLoadingAction(stepId);
    fetch(`/api/progress/${stepId}/complete`, {
      method: 'POST',
      credentials: 'include',
      headers: { 
        'Accept': 'application/json',
        'Content-Type': 'application/json'
      }
    })
      .then(res => res.json())
      .then(() => {
        toast({ title: "Успех", description: "Отправлено на подтверждение администратору" });
        loadProgress();
        setLoadingAction(null);
      })
      .catch(err => {
        console.error('Error completing step:', err);
        toast({ title: "Ошибка", description: "Не удалось отметить как выполненное", variant: "destructive" });
        setLoadingAction(null);
      });
  };

  const getStepIcon = (order: number) => {
    const icons = ['📋', '🎮', '🖥️', '❓'];
    return icons[order - 1] || '📚';
  };

  const getStatusBadge = (status: string) => {
    switch (status) {
      case 'completed':
        return <span className="text-[10px] px-2 py-0.5 rounded-full font-semibold" style={{ background: "hsl(90 85% 45% / 0.2)", color: "hsl(90 85% 60%)", border: "1px solid hsl(90 85% 45% / 0.3)" }}>ВЫПОЛНЕНО</span>;
      case 'awaiting_confirmation':
        return <span className="text-[10px] px-2 py-0.5 rounded-full font-semibold" style={{ background: "hsl(45 90% 50% / 0.15)", color: "hsl(45 90% 60%)", border: "1px solid hsl(45 90% 50% / 0.2)" }}>ПРОВЕРЯЕТСЯ</span>;
      case 'in_progress':
        return <span className="text-[10px] px-2 py-0.5 rounded-full font-semibold" style={{ background: "hsl(270 75% 50% / 0.15)", color: "hsl(270 75% 70%)", border: "1px solid hsl(270 75% 50% / 0.2)" }}>В ПРОЦЕССЕ</span>;
      default:
        return <span className="text-[10px] px-2 py-0.5 rounded-full font-semibold" style={{ background: "hsl(270 25% 14%)", color: "hsl(260 15% 60%)", border: "1px solid hsl(270 25% 20%)" }}>НОВЫЙ</span>;
    }
  };

  const monetizationUnlocked = summary.progress_percentage === 100;

  if (loading) {
    return (
      <div className="p-4 md:p-8 max-w-5xl flex items-center justify-center min-h-[400px]">
        <div className="text-center">
          <div className="animate-spin rounded-full h-12 w-12 border-b-2 mx-auto mb-4" style={{ borderColor: "hsl(270 75% 50%)" }}></div>
          <p className="text-sm" style={{ color: "hsl(260 15% 50%)" }}>Загрузка...</p>
        </div>
      </div>
    );
  }

  return (
    <div className="p-4 md:p-6 lg:p-8 w-full">
      <div className="flex items-center gap-3 mb-2">
        <Sparkles size={24} style={{ color: "hsl(90 85% 55%)" }} />
        <h1 className="text-xl md:text-2xl font-bold glow-text" style={{ color: "hsl(260 20% 93%)" }}>Мой прогресс</h1>
      </div>

      {/* Phase indicators */}
      <div className="flex items-center justify-between mb-10 max-w-2xl mx-auto mt-10">
        <div className="flex flex-col items-center gap-3">
          <div className="w-20 h-20 rounded-full flex items-center justify-center text-2xl font-bold glow-btn text-white">1</div>
          <span className="text-sm text-center font-medium" style={{ color: "hsl(260 15% 60%)" }}>Станьте частью<br />нашей команды</span>
        </div>
        <div className="flex-1 h-0.5 mx-6" style={{
          background: "linear-gradient(90deg, hsl(90 85% 45% / 0.4), hsl(270 50% 20% / 0.2))"
        }} />
        <div className="flex flex-col items-center gap-3">
          <div className="w-20 h-20 rounded-full flex items-center justify-center text-2xl font-bold" style={{
            background: "hsl(270 18% 12%)",
            color: "hsl(260 15% 45%)",
            border: "2px solid hsl(270 25% 20%)"
          }}>2</div>
          <span className="text-sm font-medium" style={{ color: "hsl(260 15% 35%)" }}>Монетизация</span>
        </div>
      </div>

      {/* Section 1 */}
      <div className="mb-10">
        <div className="flex items-center justify-between mb-4">
          <h2 className="text-xl font-bold" style={{ color: "hsl(260 20% 90%)" }}>Станьте частью нашей команды</h2>
          <span className="text-sm font-medium px-3 py-1 rounded-full glass-card" style={{ color: "hsl(90 85% 60%)" }}>
            {summary.progress_percentage}%
          </span>
        </div>
        <div className="w-full h-2 rounded-full mb-8" style={{ background: "hsl(270 25% 10%)" }}>
          <div className="h-full rounded-full transition-all duration-500" style={{
            width: `${summary.progress_percentage}%`,
            background: "linear-gradient(90deg, hsl(270 75% 50%), hsl(90 85% 45%))",
            boxShadow: "0 0 15px hsl(90 85% 45% / 0.4)",
          }} />
        </div>

        <div className="grid grid-cols-1 lg:grid-cols-2 gap-4">
          {steps.map((step) => (
            <div key={step.id} className="glass-card glass-card-hover rounded-xl p-4 md:p-5 transition-all duration-300 group">
              <div className="flex items-start gap-3 md:gap-4">
                <div className="text-2xl md:text-3xl flex-shrink-0 mt-1">{getStepIcon(step.order)}</div>
                <div className="flex-1 min-w-0">
                  <div className="flex flex-wrap items-start gap-2 mb-2">
                    <h3 className="font-semibold text-sm" style={{ color: "hsl(260 20% 90%)" }}>{step.title}</h3>
                    {getStatusBadge(step.status)}
                  </div>
                  <p className="text-xs mb-4" style={{ color: "hsl(260 15% 50%)" }}>{step.description}</p>
                  <div className="flex flex-wrap gap-2">
                    <button className="px-3 md:px-4 py-1.5 rounded-lg text-xs font-medium transition-all whitespace-nowrap" style={{
                      background: "hsl(270 25% 14%)",
                      color: "hsl(260 15% 75%)",
                      border: "1px solid hsl(270 25% 22%)"
                    }}>Начать обучение</button>
                    <button
                      onClick={() => handleComplete(step.id)}
                      disabled={loadingAction === step.id}
                      className="px-3 md:px-4 py-1.5 rounded-lg text-xs font-medium transition-all flex items-center gap-1.5 whitespace-nowrap"
                      style={{
                        background: step.status === 'completed' ? "hsl(90 85% 35% / 0.2)" : "linear-gradient(135deg, hsl(270 75% 50%), hsl(90 85% 40%))",
                        color: step.status === 'completed' ? "hsl(90 85% 60%)" : "#fff",
                        border: step.status === 'completed' ? "1px solid hsl(90 85% 40% / 0.3)" : "none",
                        boxShadow: step.status === 'completed' ? "none" : "0 0 15px hsl(270 75% 50% / 0.2)",
                      }}
                    >
                      {step.status === 'completed' && <CheckCircle2 size={12} />}
                      {step.status === 'completed' ? "Выполнено" : "Отметить"}
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
          <h2 className="text-xl font-bold" style={{ color: "hsl(260 20% 90%)" }}>Монетизация</h2>
          <span className="text-sm font-medium px-3 py-1 rounded-full glass-card" style={{ color: "hsl(260 15% 60%)" }}>0%</span>
        </div>
        <div className="w-full h-2 rounded-full mb-6" style={{ background: "hsl(270 18% 18%)" }} />

        <div className="glass-card rounded-xl p-7 relative overflow-hidden" style={{
          border: "1px solid hsl(270 75% 50% / 0.35)",
          boxShadow: "0 0 25px hsl(270 75% 50% / 0.1), inset 0 1px 0 hsl(270 75% 60% / 0.1)",
        }}>
          <div className="absolute inset-0 opacity-[0.07]" style={{
            background: "radial-gradient(circle at top left, hsl(270 75% 55%), transparent 60%)",
          }} />
          <div className="flex items-start gap-4 relative">
            <div className="text-4xl flex-shrink-0 mt-1">💰</div>
            <div className="flex-1">
              <h3 className="text-lg font-bold mb-1.5" style={{ color: "hsl(260 20% 93%)" }}>Оформляем компаньона Twitch</h3>
              <p className="text-sm mb-3" style={{ color: "hsl(260 15% 75%)" }}>В данной инструкции вы полностью поймёте как правильно заполнять компаньона для получения статуса монетизации в Twitch.</p>
            </div>
          </div>
          <div className="flex items-center justify-center flex-col gap-4 py-8 px-6 rounded-xl mt-4" style={{
            background: "hsl(270 35% 12% / 0.8)",
            border: "1px solid hsl(270 75% 50% / 0.25)",
            boxShadow: "inset 0 0 20px hsl(270 75% 50% / 0.05)",
          }}>
            <Lock size={44} style={{ color: "hsl(270 75% 70%)", filter: "drop-shadow(0 0 10px hsl(270 75% 55% / 0.4))" }} />
            <p className="text-lg md:text-xl text-center font-bold leading-relaxed" style={{ color: "hsl(260 20% 93%)" }}>
              Доступно после выполнения<br />условий активации (после 4 дней<br />+ активные прокси).
            </p>
          </div>
        </div>
      </div>
    </div>
  );
};

export default ProgressPage;
