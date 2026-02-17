import { useState } from "react";
import { Lock, ArrowLeft, CheckCircle, Eye, EyeOff } from "lucide-react";
import { useNavigate } from "react-router-dom";
import { toast } from "@/hooks/use-toast";

const ResetPasswordPage = () => {
  const navigate = useNavigate();
  const [step, setStep] = useState<"form" | "success">("form");
  const [showOld, setShowOld] = useState(false);
  const [showNew, setShowNew] = useState(false);
  const [showConfirm, setShowConfirm] = useState(false);
  const [oldPw, setOldPw] = useState("");
  const [newPw, setNewPw] = useState("");
  const [confirmPw, setConfirmPw] = useState("");

  const handleSubmit = (e: React.FormEvent) => {
    e.preventDefault();
    if (!oldPw || !newPw || !confirmPw) {
      toast({ title: "Ошибка", description: "Заполните все поля", variant: "destructive" });
      return;
    }
    if (newPw !== confirmPw) {
      toast({ title: "Ошибка", description: "Новые пароли не совпадают", variant: "destructive" });
      return;
    }
    if (newPw.length < 6) {
      toast({ title: "Ошибка", description: "Пароль должен содержать минимум 6 символов", variant: "destructive" });
      return;
    }
    toast({ title: "Пароль изменён", description: "Ваш пароль был успешно обновлён" });
    setStep("success");
  };

  return (
    <div className="p-6 md:p-8 max-w-lg">
      <button
        onClick={() => navigate("/dashboard/profile")}
        className="flex items-center gap-1.5 text-xs mb-6 transition-colors hover:underline"
        style={{ color: "hsl(var(--muted-foreground))" }}
      >
        <ArrowLeft size={14} />
        Назад к профилю
      </button>

      <h1 className="text-2xl font-bold mb-1 glow-text" style={{ color: "hsl(var(--foreground))" }}>
        Смена пароля
      </h1>
      <p className="text-sm mb-8" style={{ color: "hsl(var(--muted-foreground))" }}>
        Введите текущий и новый пароль
      </p>

      {step === "success" ? (
        <div className="glass-card rounded-xl p-8 text-center">
          <div className="w-14 h-14 rounded-full mx-auto mb-4 flex items-center justify-center"
            style={{ background: "hsl(var(--accent) / 0.15)" }}>
            <CheckCircle size={28} style={{ color: "hsl(var(--accent))" }} />
          </div>
          <h2 className="text-lg font-semibold mb-2" style={{ color: "hsl(var(--foreground))" }}>
            Пароль изменён
          </h2>
          <p className="text-sm mb-5" style={{ color: "hsl(var(--muted-foreground))" }}>
            Ваш пароль был успешно обновлён
          </p>
          <button
            onClick={() => navigate("/dashboard/profile")}
            className="glow-btn px-5 py-2.5 rounded-lg text-sm font-medium"
            style={{ color: "hsl(var(--primary-foreground))" }}
          >
            Вернуться в профиль
          </button>
        </div>
      ) : (
        <form onSubmit={handleSubmit} className="glass-card rounded-xl p-6">
          <div className="flex flex-col gap-4">
            {[
              { label: "Текущий пароль", show: showOld, toggle: () => setShowOld(!showOld), value: oldPw, onChange: setOldPw },
              { label: "Новый пароль", show: showNew, toggle: () => setShowNew(!showNew), value: newPw, onChange: setNewPw },
              { label: "Подтвердите новый пароль", show: showConfirm, toggle: () => setShowConfirm(!showConfirm), value: confirmPw, onChange: setConfirmPw },
            ].map((field, i) => (
              <div key={i}>
                <label className="text-[11px] font-medium mb-1.5 block" style={{ color: "hsl(var(--muted-foreground))" }}>
                  {field.label}
                </label>
                <div className="flex items-center gap-3 px-3.5 py-2.5 rounded-lg" style={{
                  background: "hsl(var(--input))",
                  border: "1px solid hsl(var(--border))",
                }}>
                  <Lock size={15} style={{ color: "hsl(var(--muted-foreground))" }} />
                  <input
                    className="bg-transparent text-sm flex-1 outline-none"
                    style={{ color: "hsl(var(--foreground))" }}
                    type={field.show ? "text" : "password"}
                    placeholder="••••••••"
                    value={field.value}
                    onChange={e => field.onChange(e.target.value)}
                    required
                  />
                  <button type="button" onClick={field.toggle}
                    style={{ color: "hsl(var(--muted-foreground))" }}>
                    {field.show ? <EyeOff size={15} /> : <Eye size={15} />}
                  </button>
                </div>
              </div>
            ))}
          </div>
          <button type="submit" className="glow-btn mt-6 w-full py-2.5 rounded-lg text-sm font-medium"
            style={{ color: "hsl(var(--primary-foreground))" }}>
            Сменить пароль
          </button>
        </form>
      )}
    </div>
  );
};

export default ResetPasswordPage;
