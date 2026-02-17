import { motion } from "framer-motion";

interface StylePreviewProps {
  title: string;
  emoji: string;
  description: string;
  children: React.ReactNode;
  onSelect: () => void;
}

export const StylePreview = ({ title, emoji, description, children, onSelect }: StylePreviewProps) => (
  <motion.div
    initial={{ opacity: 0, y: 30 }}
    animate={{ opacity: 1, y: 0 }}
    transition={{ duration: 0.5 }}
    className="flex flex-col"
  >
    <div className="relative overflow-hidden rounded-xl border border-white/10 shadow-2xl aspect-[16/10] mb-4">
      {children}
    </div>
    <div className="flex items-start justify-between gap-4">
      <div>
        <h3 className="text-xl font-bold text-white">
          {emoji} {title}
        </h3>
        <p className="text-sm text-gray-400 mt-1">{description}</p>
      </div>
      <button
        onClick={onSelect}
        className="shrink-0 px-4 py-2 rounded-lg bg-white/10 hover:bg-white/20 text-white text-sm font-medium transition-colors border border-white/10"
      >
        Выбрать
      </button>
    </div>
  </motion.div>
);
