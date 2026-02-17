import { useState } from "react";
import { useNavigate } from "react-router-dom";
import { motion } from "framer-motion";
import { StylePreview } from "@/components/StylePreview";
import { CyberpunkNeon } from "@/components/styles/CyberpunkNeon";
import { GamingHUD } from "@/components/styles/GamingHUD";
import { CosmicStream } from "@/components/styles/CosmicStream";
import { TwitchDarkPro } from "@/components/styles/TwitchDarkPro";
import { RGBGamer } from "@/components/styles/RGBGamer";
import { RetroSynthwave } from "@/components/styles/RetroSynthwave";

const styles = [
  {
    id: "cyberpunk",
    title: "Cyberpunk Neon",
    emoji: "🌌",
    description: "Яркие неоновые акценты, глитч-эффекты, стиль Cyberpunk 2077",
    component: CyberpunkNeon,
  },
  {
    id: "gaming-hud",
    title: "Gaming HUD",
    emoji: "🎮",
    description: "Игровой интерфейс с HUD-элементами и сканирующими линиями",
    component: GamingHUD,
  },
  {
    id: "cosmic",
    title: "Cosmic Stream",
    emoji: "🔮",
    description: "Космический фиолетовый с частицами и стеклянными карточками",
    component: CosmicStream,
  },
  {
    id: "twitch",
    title: "Twitch Dark Pro",
    emoji: "⚡",
    description: "Минималистичный тёмный дизайн в стиле Twitch",
    component: TwitchDarkPro,
  },
  {
    id: "rgb",
    title: "RGB Gamer",
    emoji: "🔥",
    description: "RGB-градиенты и переливающиеся цвета в стиле Razer/Corsair",
    component: RGBGamer,
  },
  {
    id: "synthwave",
    title: "Retro Synthwave",
    emoji: "🌃",
    description: "Ретро-волна 80-х: розово-голубые градиенты и сетка в перспективе",
    component: RetroSynthwave,
  },
];

const Index = () => {
  const navigate = useNavigate();
  const [selected, setSelected] = useState<string | null>(null);

  return (
    <div className="min-h-screen" style={{ background: "linear-gradient(180deg, #08080c 0%, #0d0d15 100%)" }}>
      <div className="max-w-6xl mx-auto px-6 py-12">
        <motion.div
          initial={{ opacity: 0, y: -20 }}
          animate={{ opacity: 1, y: 0 }}
          className="text-center mb-12"
        >
          <h1 className="text-4xl font-bold text-white mb-3">
            Выбери стиль панели
          </h1>
          <p className="text-lg" style={{ color: "#888" }}>
            6 уникальных дизайнов для стримерской панели. Нажми «Выбрать» для понравившегося.
          </p>
          {selected && (
            <motion.div
              initial={{ opacity: 0, scale: 0.9 }}
              animate={{ opacity: 1, scale: 1 }}
              className="mt-4 inline-block px-4 py-2 rounded-lg text-sm font-medium"
              style={{ background: "#8b5cf622", color: "#a78bfa", border: "1px solid #8b5cf633" }}
            >
              Выбран: {styles.find(s => s.id === selected)?.emoji} {styles.find(s => s.id === selected)?.title}
            </motion.div>
          )}
        </motion.div>

        <div className="grid grid-cols-1 md:grid-cols-2 gap-8">
          {styles.map((style, i) => (
            <motion.div
              key={style.id}
              initial={{ opacity: 0, y: 30 }}
              animate={{ opacity: 1, y: 0 }}
              transition={{ delay: i * 0.1, duration: 0.5 }}
            >
              <StylePreview
                title={style.title}
                emoji={style.emoji}
                description={style.description}
                onSelect={() => { setSelected(style.id); navigate(`/style/${style.id}`); }}
              >
                <style.component />
              </StylePreview>
              {selected === style.id && (
                <motion.div
                  layoutId="selected-border"
                  className="h-0.5 mt-3 rounded-full"
                  style={{ background: "linear-gradient(90deg, #8b5cf6, #a78bfa)" }}
                />
              )}
            </motion.div>
          ))}
        </div>
      </div>
    </div>
  );
};

export default Index;
