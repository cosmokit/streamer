import { useParams, useNavigate } from "react-router-dom";
import { CyberpunkNeon } from "@/components/styles/CyberpunkNeon";
import { GamingHUD } from "@/components/styles/GamingHUD";
import { CosmicStream } from "@/components/styles/CosmicStream";
import { TwitchDarkPro } from "@/components/styles/TwitchDarkPro";
import { RGBGamer } from "@/components/styles/RGBGamer";
import { RetroSynthwave } from "@/components/styles/RetroSynthwave";

const styleMap: Record<string, React.FC> = {
  cyberpunk: CyberpunkNeon,
  "gaming-hud": GamingHUD,
  cosmic: CosmicStream,
  twitch: TwitchDarkPro,
  rgb: RGBGamer,
  synthwave: RetroSynthwave,
};

const StylePage = () => {
  const { id } = useParams();
  const navigate = useNavigate();
  const Component = id ? styleMap[id] : null;

  if (!Component) return <div className="min-h-screen bg-black text-white flex items-center justify-center">Стиль не найден</div>;

  return (
    <div className="relative w-full h-screen">
      <Component />
      <button
        onClick={() => navigate("/")}
        className="fixed top-4 right-4 z-50 px-4 py-2 rounded-lg text-sm font-medium transition-colors"
        style={{ background: "rgba(0,0,0,0.7)", color: "#fff", border: "1px solid rgba(255,255,255,0.2)", backdropFilter: "blur(8px)" }}
      >
        ← Назад
      </button>
    </div>
  );
};

export default StylePage;
