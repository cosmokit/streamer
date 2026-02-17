import { useState } from "react";
import { useNavigate } from "react-router-dom";
import { Button } from "@/components/ui/button";
import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";
import { useToast } from "@/hooks/use-toast";
import logo from "@/assets/logo.png";

const Login = () => {
  const [email, setEmail] = useState("user1@streamer.local");
  const [password, setPassword] = useState("password");
  const [isLoading, setIsLoading] = useState(false);
  const navigate = useNavigate();
  const { toast } = useToast();

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    setIsLoading(true);

    // Демо: просто проверяем что поля заполнены
    if (email && password) {
      toast({
        title: "Вход выполнен",
        description: `Добро пожаловать, ${email}`,
      });
      
      // Сохраняем в localStorage для демо
      localStorage.setItem("isAuthenticated", "true");
      localStorage.setItem("userEmail", email);
      
      navigate("/dashboard/progress");
    } else {
      toast({
        title: "Ошибка",
        description: "Заполните все поля",
        variant: "destructive",
      });
    }

    setIsLoading(false);
  };

  return (
    <div className="flex min-h-screen w-full gradient-bg relative overflow-hidden items-center justify-center">
      {/* Background effects */}
      <div className="absolute inset-0 pointer-events-none">
        <div className="absolute inset-0" style={{
          background: "linear-gradient(125deg, transparent 20%, hsl(270 75% 55% / 0.04) 40%, transparent 60%)"
        }} />
        <div className="absolute inset-0" style={{
          background: "linear-gradient(200deg, transparent 30%, hsl(90 85% 45% / 0.03) 50%, transparent 70%)"
        }} />
      </div>

      {/* Login Card */}
      <div className="relative z-10 w-full max-w-md mx-4">
        <div 
          className="p-8 rounded-2xl"
          style={{
            background: "linear-gradient(180deg, hsl(270 45% 8% / 0.9), hsl(270 35% 5% / 0.95))",
            border: "1px solid hsl(270 35% 20% / 0.5)",
            backdropFilter: "blur(16px)",
            boxShadow: "0 0 40px hsl(270 75% 50% / 0.15)",
          }}
        >
          {/* Logo */}
          <div className="flex items-center justify-center gap-3 mb-8">
            <img src={logo} alt="PS Logo" className="w-12 h-12 object-contain" />
            <span className="text-2xl font-bold tracking-wide" style={{ color: "hsl(270 75% 75%)" }}>
              PROFIT<span style={{ color: "hsl(90 85% 55%)" }}>STREAM</span>
            </span>
          </div>

          <h1 className="text-2xl font-bold text-center mb-2" style={{ color: "hsl(270 75% 75%)" }}>
            Вход в систему
          </h1>
          <p className="text-center mb-6" style={{ color: "hsl(260 15% 55%)" }}>
            Введите ваши данные для входа
          </p>

          <form onSubmit={handleSubmit} className="space-y-4">
            <div className="space-y-2">
              <Label htmlFor="email" style={{ color: "hsl(270 75% 75%)" }}>
                Email
              </Label>
              <Input
                id="email"
                type="email"
                placeholder="user1@streamer.local"
                value={email}
                onChange={(e) => setEmail(e.target.value)}
                required
                style={{
                  background: "hsl(270 35% 10% / 0.5)",
                  border: "1px solid hsl(270 35% 20% / 0.5)",
                  color: "hsl(260 15% 75%)",
                }}
              />
            </div>

            <div className="space-y-2">
              <Label htmlFor="password" style={{ color: "hsl(270 75% 75%)" }}>
                Пароль
              </Label>
              <Input
                id="password"
                type="password"
                placeholder="••••••••"
                value={password}
                onChange={(e) => setPassword(e.target.value)}
                required
                style={{
                  background: "hsl(270 35% 10% / 0.5)",
                  border: "1px solid hsl(270 35% 20% / 0.5)",
                  color: "hsl(260 15% 75%)",
                }}
              />
            </div>

            <Button
              type="submit"
              disabled={isLoading}
              className="w-full"
              style={{
                background: "linear-gradient(135deg, hsl(270 75% 50%), hsl(90 85% 40%))",
                color: "white",
                fontWeight: 600,
              }}
            >
              {isLoading ? "Вход..." : "Войти"}
            </Button>
          </form>

          <div className="mt-6 text-center text-sm" style={{ color: "hsl(260 15% 55%)" }}>
            <p>Демо аккаунт:</p>
            <p className="mt-1">
              <span style={{ color: "hsl(90 85% 65%)" }}>user1@streamer.local</span> / password
            </p>
          </div>
        </div>
      </div>
    </div>
  );
};

export default Login;
